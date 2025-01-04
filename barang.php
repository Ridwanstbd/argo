<!-- barang.php -->
<?php
    require 'controllers/loginController.php';
    require 'controllers/barangController.php';
    barang();
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
    <script src="assets/js/barang.js" defer></script>
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
                    </li <li class="nav-item">
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
        <h1 class="page-title">Daftar Barang</h1>
        
        <div class="summary-card">
            <div class="card">
                <div class="card-content">
                    <div class="card-info">
                        <div class="card-label">Jumlah Barang</div>
                        <div class="card-value">
                            <?php
                                $count_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM tb_barang");
                                $count_result = mysqli_fetch_assoc($count_query);
                                echo $count_result['count'];
                            ?>
                        </div>
                    </div>
                    <button type="button" class="add-button" data-modal-target="#barang">Tambah Barang</button>
                </div>
            </div>
        </div>

        <div class="data-card">
            <div class="search-section">
                <form method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <div class="search-group">
                        <input type="text" name="tcari" class="search-input" placeholder="Masukan Barang !!">
                        <button class="search-btn" type="submit" name="bcari">Cari</button>
                        <button class="reset-btn" type="submit" name="breset">Reset</button>
                    </div>
                </form>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Layanan</th>
                            <th>Harga</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>                                            
                        </tr>
                    </thead>                                
                    <tbody>
                        <?php 
                        $query = barang();
                        $getbarang = mysqli_query($conn,"SELECT * FROM tb_barang");
                        $i = 1;
                        
                        while($p=mysqli_fetch_array($getbarang)){
                            $idbarang = $p['id_barang']; 
                            $namabarang = $p['nama_barang'];
                            $layanan = $p['layanan'];
                            $harga = $p['harga'];
                            $keterangan = $p['keterangan'];
                        ?>
                        <tr>
                            <td><?=$i++?></td>
                            <td><?=$namabarang?></td>
                            <td><?=$layanan?></td>
                            <td><?=$harga?></td>
                            <td><?=$keterangan?></td>
                            <td class="action-cell">
                                <a class="view-btn" href="#" onclick="openEditModal('<?=$idbarang?>', '<?=$namabarang?>', '<?=$layanan?>', '<?=$harga?>', '<?=$keterangan?>')">Ubah</a>
                                <a class="delete-btn" href="#" onclick="confirmDelete('<?=$idbarang?>')">Hapus</a>
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

    <!-- Modal -->
    <div class="modal" id="barang">
        <div class="modal-wrapper">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Barang</h4>
                    <button type="button" class="modal-close" data-modal-close>&times;</button>
                </div>

                <form method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <div class="modal-body">
                        <input type="text" name="nama_barang" placeholder="Nama Barang" class="form-input" required>
                        <input type="text" name="layanan" placeholder="Layanan" class="form-input" required>
                        <input type="number" name="harga" placeholder="Harga" class="form-input" required>
                        <input type="text" name="keterangan" placeholder="Isi Keterangan" class="form-input">
                    </div>
                    
                    <div class="modal-footer">
                        <button type="submit" name="tambahbarang" class="submit-btn">Tambah</button>
                        <button type="button" class="cancel-btn" data-modal-close>Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="editBarang">
        <div class="modal-wrapper">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ubah Barang</ <button type="button" class="modal-close" data-modal-close>&times;</button>
                </div>

                <form method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <div class="modal-body">
                        <input type="hidden" name="idbarang" id="edit_idbarang">
                        <input type="text" name="nama_barang" id="edit_nama_barang" placeholder="Nama Barang" class="form-input" required>
                        <input type="text" name="layanan" id="edit_layanan" placeholder="Layanan" class="form-input" required>
                        <input type="number" name="harga" id="edit_harga" placeholder="Harga" class="form-input" required>
                        <input type="text" name="keterangan" id="edit_keterangan" placeholder="Isi Keterangan" class="form-input">
                    </div>
                    
                    <div class="modal-footer">
                        <button type="submit" name="ubahbarang" class="submit-btn">Simpan</button>
                        <button type="button" class="cancel-btn" data-modal-close>Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>