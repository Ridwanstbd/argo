<?php
// pesanan.php
    require 'controllers/loginController.php';
    require 'controllers/pesananController.php';
    $query = pesanan();
    requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Racing+Sans+One&display=swap" rel="stylesheet">

    <title>Argo Blast Coating</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/modal.css">
    <script src="assets/js/modal.js" defer></script>
    <script src="assets/js/pesanan.js" defer></script>
</head>

<body>    
    <!-- Navbar Start -->
    <nav class="navbar">
    <div class="nav-container">
            <a href="index.php"><img src="assets/img/argo-jaya.png" alt="Argo Jaya" class="ikon"></a>
            
            <button class="nav-toggle" type="button" id="navToggle">
                <span class="nav-icon"></span>
            </button>

            <div class="nav-menu" id="navbarTogglerDemo01">
                <ul class="nav-list">
                    <li class="nav-item">
                        <a class="nav-link" href="pesanan.php">Pesanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="barang.php">Barang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="klien.php">Klien</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1 class="page-title">Daftar Pesanan</h1>
        
        <div class="summary-card">
            <div class="card">
                <div class="card-content">
                    <div class="card-info">
                        <div class="card-label">Jumlah Pesanan</div>
                        <div class="card-value">
                            <?php
                                $count_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM tb_pesanan");
                                $count_result = mysqli_fetch_assoc($count_query);
                                echo $count_result['count'];
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="data-card">
            <div class="search-section">
                <form method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <div class="search-group">
                        <input type="text" name="tcari" class="search-input" placeholder="Cari pesanan...">
                        <button class="search-btn" type="submit" name="bcari">Cari</button>
                        <button class="reset-btn" type="submit" name="breset">Reset</button>
                    </div>
                </form>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Waktu</th>
                            <th>Nama Klien</th>
                            <th>Status</th>
                            <th>Aksi</th>                                            
                        </tr>
                    </thead>                                
                    <tbody>
                        <?php 
                        $getpesanan = mysqli_query($conn, $query);
                        while($p = mysqli_fetch_array($getpesanan)){
                            $idPesanan = $p['id_pesanan'];
                            $waktuPesan = $p['waktu_pesan'];
                            $namaKlien = $p['nama'];
                            $alamat = $p['alamat'];
                            $status = $p['kstatus'];
                        ?>
                        <tr>
                            <td><?=$idPesanan?></td>
                            <td><?=$waktuPesan?></td>
                            <td><?=$namaKlien?></td>
                            <td><?=$status?></td>
                            <td class="action-cell">
                                <a class="view-btn" href="#" onclick="openEditModal('<?=$idPesanan?>', '<?=$status?>', '<?=$waktuPesan?>')">Ubah</a>
                                <a class="delete-btn" href="#" onclick="confirmDelete('<?=$idPesanan?>')">Hapus</a>
                            </td>
                        </tr>
                        <?php }; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-content">
            <span class="copyright">Copyright &copy; Argo Blast Coating 2023</span>
        </div>
    </footer>


    <!-- Edit Modal -->
    <div class="modal" id="editPesanan">
        <div class="modal-wrapper">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ubah Pesanan</h4>
                    <button type="button" class="modal-close" data-modal-close>&times;</button>
                </div>

                <form method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <div class="modal-body">
                        <input type="hidden" name="idpesanan" id="edit_idpesanan">
                        
                        <select name="tstatus" id="edit_status" class="form-input" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="Baru">Baru</option>
                            <option value="Dalam Antrian">Dalam Antrian</option>
                            <option value="Sedang Diproses">Sedang Diproses</option>
                            <option value="Sedang Dikerjakan">Sedang Dikerjakan</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                        
                        <input type="datetime-local" name="twaktu" id="edit_waktu" class="form-input" required>
                    </div>
                    
                    <div id="order_details" class="order-details-section">
                        <!-- Content will be populated by JavaScript -->
                    </div>                    
                    <div class="modal-footer">
                        <button type="submit" name="ubahpesanan" class="submit-btn">Simpan</button>
                        <button type="button" class="cancel-btn" data-modal-close>Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>