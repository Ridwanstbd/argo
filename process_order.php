<?php
// process_order.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'controllers/db.php';
require 'controllers/csrf_handler.php';

function formatCurrency($amount) {
    return number_format($amount, 0, ',', '.');
}

function getOrderDetails($conn,$id_pesanan) {
    $detail = [];
    $query_customer = "SELECT k.* FROM tb_klien k
                        JOIN tb_pesanan p ON k.id_pemesan = p.id_pemesan 
                        WHERE p.id_pesanan = ?
    ";
    $stmt = mysqli_prepare($conn,$query_customer);
    mysqli_stmt_bind_param($stmt,"i", $id_pesanan);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $customer = mysqli_fetch_assoc($result);
    
    $query_items = "SELECT b.nama_barang, b.layanan, b.harga, d.jumlah, d.subtotal, d.catatan 
                   FROM tb_detailpesanan d
                   JOIN tb_barang b ON d.id_barang = b.id_barang
                   WHERE d.id_pesanan = ?";
    $stmt = mysqli_prepare($conn,$query_items);
    mysqli_stmt_bind_param($stmt,"i", $id_pesanan);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $message = "🛒 *HALO KAK MAU PESAN JASA*\n\n";
    $message .= "*Data Pelanggan:*\n";
    $message .= "Nama: {$customer['nama']}\n";
    $message .= "No HP: {$customer['no_hp']}\n";
    $message .= "Alamat: {$customer['alamat']}\n\n";
    $message .= "*Detail Pesanan:*\n";
    $total = 0;
    while($item = mysqli_fetch_assoc($result)) {
        $message .= "- {$item['nama_barang']} ({$item['layanan']})\n";
        $message .= "  {$item['jumlah']} x Rp " . formatCurrency($item['harga']) . "\n";
        $message .= "  Subtotal: Rp " . formatCurrency($item['subtotal']) . "\n";
        if (!empty($item['catatan'])) {
            $message .= "  Catatan: {$item['catatan']}\n";
        }
        $message .= "\n";
        $total += $item['subtotal'];
    }
    $message .= "*Total Pesanan: Rp " . formatCurrency($total) . "*\n";
    return urlencode($message);
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Validasi CSRF token
    if (!validateCSRFToken($_POST['csrf_token'])) {
        die('CSRF token validation failed');
    }
    
    $nama = sanitizeInput($_POST['nama']);
    $no_hp = sanitizeInput($_POST['no_hp']);
    $alamat = sanitizeInput($_POST['alamat']);
    
    // Escape string untuk database
    $nama = mysqli_real_escape_string($conn, $nama);
    $no_hp = mysqli_real_escape_string($conn, $no_hp);
    $alamat = mysqli_real_escape_string($conn, $alamat);
    
    // Simpan data klien
    $query_klien = "INSERT INTO tb_klien (nama, no_hp, alamat) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query_klien);
    mysqli_stmt_bind_param($stmt, "sss", $nama, $no_hp, $alamat);
    mysqli_stmt_execute($stmt);
    $id_pemesan = mysqli_insert_id($conn);
    
    // Simpan data pesanan
    $waktu_pesan = date('Y-m-d H:i:s');
    $kstatus = 'Baru';
    
    $query_pesanan = "INSERT INTO tb_pesanan (id_pemesan, waktu_pesan, kstatus) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query_pesanan);
    mysqli_stmt_bind_param($stmt, "iss", $id_pemesan, $waktu_pesan, $kstatus);
    mysqli_stmt_execute($stmt);
    $id_pesanan = mysqli_insert_id($conn);
    
    // Simpan detail pesanan
    $barang = $_POST['barang'];
    $jumlah = $_POST['jumlah'];
    $catatan = $_POST['catatan'];
    
    $query_detail = "INSERT INTO tb_detailpesanan (id_pesanan, id_barang, jumlah, subtotal, catatan) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query_detail);
    
    for($i = 0; $i < count($barang); $i++) {
        $id_barang = (int)sanitizeInput($barang[$i]);
        $jml = (int)sanitizeInput($jumlah[$i]);
        $cat = sanitizeInput($catatan[$i]);
        
        // Ambil harga barang
        $query_harga = "SELECT harga FROM tb_barang WHERE id_barang = ?";
        $stmt_harga = mysqli_prepare($conn, $query_harga);
        // Ambil harga barang
        $query_harga = "SELECT harga FROM tb_barang WHERE id_barang = ?";
        $stmt_harga = mysqli_prepare($conn, $query_harga);
        mysqli_stmt_bind_param($stmt_harga, "i", $id_barang);
        mysqli_stmt_execute($stmt_harga);
        $result = mysqli_stmt_get_result($stmt_harga);
        $row = mysqli_fetch_assoc($result);
        $harga = $row['harga'];
        
        $subtotal = $harga * $jml;
        
        mysqli_stmt_bind_param($stmt, "iiids", $id_pesanan, $id_barang, $jml, $subtotal, $cat);
        mysqli_stmt_execute($stmt);
    }
    
    // Refresh CSRF token
    refreshCSRFToken();
    $whatsapp_number = "6285704412510";
    $message = getOrderDetails($conn, $id_pesanan);
    $whatsapp_url = "https://api.whatsapp.com/send?phone={$whatsapp_number}&text={$message}";

    header("Location: $whatsapp_url");
    exit();
}