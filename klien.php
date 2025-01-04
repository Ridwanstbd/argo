<?php
    require 'controllers/loginController.php';
    require 'controllers/klienController.php';
    $query = klien();
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
    <script src="assets/js/klien.js" defer></script>
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
        <h1 class="page-title">Daftar Data Klien</h1>
        
        <div class="summary-card">
            <div class="card">
                <div class="card-content">
                    <div class="card-info">
                        <div class="card-label">Jumlah Klien</div>
                        <div class="card-value">
                            <?php
                                $count_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM tb_klien");
                                $count_result = mysqli_fetch_assoc($count_query);
                                echo $count_result['count'];
                            ?>
                        </div>
                    </div>
                    <button type="button" class="add-button" data-modal-target="#pemesan">Tambah Data Klien</button>
                </div>
            </div>
        </div>

        <div class="data-card">
            <div class="search-section">
                <form method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <div class="search-group">
                        <input type="text" name="tcari" class="search-input" placeholder="Masukan Nama Klien !!">
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
                            <th>Nama Klien</th>
                            <th>Nomor Telepon</th>
                            <th>Alamat</th>
                            <th>Aksi</th>                                            
                        </tr>
                    </thead>                                
                    <tbody>
                        <?php
                        $getklien = mysqli_query($conn, $query);
                        $i = 1;
                        
                        while($k = mysqli_fetch_array($getklien)){
                            $idklien = $k['id_pemesan'];
                            $namaklien = $k['nama'];
                            $nomortelepon = $k['no_hp'];
                            $alamat = $k['alamat'];
                        ?>
                        <tr>
                            <td><?=$i++?></td>
                            <td><?=$namaklien?></td>
                            <td><?=$nomortelepon?></td>
                            <td><?=$alamat?></td>                                           
                            <td class="action-cell">
                                <a class="view-btn" href="#" onclick="openEditModal('<?=$idklien?>', '<?=$namaklien?>', '<?=$nomortelepon?>', '<?=$alamat?>')">Ubah</a>
                                <a class="delete-btn" href="#" onclick="confirmDelete('<?=$idklien?>')">Hapus</a>
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

    <!-- Modal Add -->
    <div class="modal" id="pemesan">
        <div class="modal-wrapper">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Data Klien</h4>
                    <button type="button" class="modal-close" data-modal-close>&times;</button>
                </div>

                <form method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <div class="modal-body">
                        <input type="text" name="namaKlien" placeholder="Nama Klien" class="form-input" required>
                        <input type="text" name="noTelepon" placeholder="Nomor Telepon" class="form-input" required>
                        <input type="text" name="alamat" placeholder="Alamat" class="form-input" required>                    
                    </div>
                    
                    <div class="modal-footer">
                        <button type="submit" name="tambahklien" class="submit-btn">Tambah</button>
                        <button type="button" class="cancel-btn" data-modal-close>Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal" id="editKlien">
        <div class="modal-wrapper">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ubah Data Klien</h4>
                    <button type="button" class="modal-close" data-modal-close>&times;</button>
                </div>

                <form method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <div class="modal-body">
                        <input type="hidden" name="idklien" id="edit_idklien">
                        <input type="text" name="namaKlien" id="edit_nama_klien" placeholder="Nama Klien" class="form-input" required>
                        <input type="text" name="noTelepon" id="edit_no_telepon" placeholder="Nomor Telepon" class="form-input" required>
                        <input type="text" name="alamat" id="edit_alamat" placeholder="Alamat" class="form-input" required>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="submit" name="ubahklien" class="submit-btn">Simpan</button>
                        <button type="button" class="cancel-btn" data-modal-close>Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>