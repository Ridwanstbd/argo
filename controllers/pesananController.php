<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db.php';
require_once 'csrf_handler.php';

function getOrderDetails($id_pesanan) {
    global $conn;
    
    // Get order details with customer info
    $query = "SELECT p.*, k.nama, k.no_hp, k.alamat FROM tb_pesanan p 
              JOIN tb_klien k ON p.id_pemesan = k.id_pemesan 
              WHERE p.id_pesanan = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_pesanan);
    mysqli_stmt_execute($stmt);
    $order = mysqli_stmt_get_result($stmt)->fetch_assoc();
    
    // Get ordered items
    $query_items = "SELECT d.*, b.nama_barang, b.layanan, b.harga 
                   FROM tb_detailpesanan d
                   JOIN tb_barang b ON d.id_barang = b.id_barang
                   WHERE d.id_pesanan = ?";
    $stmt = mysqli_prepare($conn, $query_items);
    mysqli_stmt_bind_param($stmt, "i", $id_pesanan);
    mysqli_stmt_execute($stmt);
    $items = mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);
    
    return ['order' => $order, 'items' => $items];
}

function pesanan() {
    global $conn;
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['ubahpesanan'])) {
            if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
                return "Invalid request";
            }
            
            $id_pesanan = filter_var($_POST['idpesanan'], FILTER_SANITIZE_NUMBER_INT);
            $status = sanitizeInput($_POST['tstatus']);
            $waktu = sanitizeInput($_POST['twaktu']);
            
            $stmt = mysqli_prepare($conn, "UPDATE tb_pesanan SET kstatus=?, waktu_pesan=? WHERE id_pesanan=?");
            mysqli_stmt_bind_param($stmt, "ssi", $status, $waktu, $id_pesanan);
            
            if (mysqli_stmt_execute($stmt)) {
                header("Location: pesanan.php");
                exit();
            }
            mysqli_stmt_close($stmt);
        }
        
        // Handle delete
        elseif (isset($_POST['hapuspesanan'])) {
            if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
                return "Invalid request";
            }
            
            $id_pesanan = filter_var($_POST['idpesanan'], FILTER_SANITIZE_NUMBER_INT);
            
            $stmt = mysqli_prepare($conn, "DELETE FROM tb_pesanan WHERE id_pesanan = ?");
            mysqli_stmt_bind_param($stmt, "i", $id_pesanan);
            
            if (mysqli_stmt_execute($stmt)) {
                header("Location: pesanan.php");
                exit();
            }
            mysqli_stmt_close($stmt);
        }
        
        elseif (isset($_POST['getorderdetails'])) {
            $id_pesanan = filter_var($_POST['id_pesanan'], FILTER_SANITIZE_NUMBER_INT);
            $details = getOrderDetails($id_pesanan);
            header('Content-Type: application/json');
            echo json_encode($details);
            exit();
        }
        
        // Handle search
        elseif (isset($_POST['bcari'])) {
            $search = sanitizeInput($_POST['tcari']);
            return "SELECT p.*, k.nama, k.alamat FROM tb_pesanan p 
                    JOIN tb_klien k ON p.id_pemesan=k.id_pemesan 
                    WHERE k.nama LIKE '%$search%' OR p.kstatus LIKE '%$search%'";
        }
    }
    
    return "SELECT p.*, k.nama, k.alamat FROM tb_pesanan p 
            JOIN tb_klien k ON p.id_pemesan=k.id_pemesan ORDER BY p.waktu_pesan DESC";
}