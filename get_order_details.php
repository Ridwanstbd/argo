<?php
ob_end_clean();
header('Content-Type: application/json');

error_reporting(0);
ini_set('display_errors', 0);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    require_once 'controllers/db.php';
    
    // Validasi input
    if (!isset($_POST['csrf_token']) || !isset($_POST['id_pesanan'])) {
        throw new Exception('Missing required parameters');
    }

    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        throw new Exception('Invalid CSRF token');
    }

    $id_pesanan = filter_var($_POST['id_pesanan'], FILTER_SANITIZE_NUMBER_INT);
    
    // Get order and customer details
    $query = "SELECT p.*, k.nama, k.no_hp, k.alamat 
             FROM tb_pesanan p 
             JOIN tb_klien k ON p.id_pemesan = k.id_pemesan 
             WHERE p.id_pesanan = ?";
             
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_pesanan);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $order = mysqli_fetch_assoc($result);
    
    if (!$order) {
        throw new Exception('Order not found');
    }
    
    // Get ordered items
    $query_items = "SELECT d.*, b.nama_barang, b.layanan, b.harga,
                    (d.jumlah * b.harga) as subtotal
                   FROM tb_detailpesanan d
                   JOIN tb_barang b ON d.id_barang = b.id_barang
                   WHERE d.id_pesanan = ?";
                   
    $stmt = mysqli_prepare($conn, $query_items);
    mysqli_stmt_bind_param($stmt, "i", $id_pesanan);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $items = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }

    echo json_encode([
        'success' => true,
        'order' => $order,
        'items' => $items
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

// Pastikan tidak ada output lain setelah JSON
exit();