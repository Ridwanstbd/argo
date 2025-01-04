<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require 'controllers/db.php';
    require 'controllers/csrf_handler.php';
    
    $query_barang = "SELECT * FROM tb_barang";
    $result_barang = mysqli_query($conn, $query_barang);
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

    <title>Buat Pesanan</title>
    <link rel="stylesheet" href="assets/css/auth.css">

    <style>
        .pesanan-container {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-pesanan select, .form-pesanan input, .form-pesanan textarea {
            margin-bottom: 10px;
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .btn-hapus {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }
        .btn-tambah {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 15px;
        }
        .total-harga {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
            margin-top: 15px;
            border-top: 2px solid #ddd;
            padding-top: 10px;
        }
        .subtotal-display {
            font-weight: bold;
            margin-top: 5px;
            color: #28a745;
        }
    </style>
</head>

<body class="bg-dark">
    <div class="container">
        <div class="content-center">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="mb-4">Buat Pesanan Baru</h1>
                                </div>
                                
                                <form method="POST" action="process_order.php" class="user form-pesanan">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                    
                                    <h4 class="mb-3">Data Diri</h4>
                                    <div class="form-group mb-3">
                                        <input type="text" name="nama" class="form-control form-control-user"
                                            placeholder="Nama Lengkap" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <input type="text" name="no_hp" class="form-control form-control-user"
                                            placeholder="Nomor HP" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <textarea name="alamat" class="form-control form-control-user"
                                            placeholder="Alamat Lengkap" required rows="3"></textarea>
                                    </div>
                                    
                                    <h4 class="mb-3">Detail Pesanan</h4>
                                    <div id="container-barang">
                                        <div class="pesanan-container">
                                            <select name="barang[]" class="form-control" required onchange="hitungTotal()">
                                                <option value="">Pilih Barang</option>
                                                <?php 
                                                mysqli_data_seek($result_barang, 0);
                                                while($row = mysqli_fetch_assoc($result_barang)) { 
                                                ?>
                                                    <option value="<?php echo $row['id_barang']; ?>" 
                                                            data-harga="<?php echo $row['harga']; ?>">
                                                        <?php echo $row['nama_barang']; ?> - <?php echo $row['layanan']; ?> 
                                                        (Rp <?php echo number_format($row['harga'],0,',','.'); ?>)
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <input type="number" name="jumlah[]" class="form-control" 
                                                placeholder="Jumlah" required min="1" 
                                                onchange="hitungSubtotal(this.parentElement)" 
                                                onkeyup="hitungSubtotal(this.parentElement)">
                                            <textarea name="catatan[]" class="form-control" 
                                                placeholder="Catatan untuk pesanan (opsional)"></textarea>
                                            <div class="subtotal-display">Subtotal: Rp 0</div>
                                        </div>
                                    </div>
                                    
                                    <button type="button" onclick="tambahBarang()" class="btn-tambah">
                                        + Tambah Barang</button>

                                    <div class="total-harga">
                                        Total: <span id="total">Rp 0</span>
                                    </div>
                                    
                                    <button type="submit" name="submit" class="btn btn-primary btn-user btn-block mt-4">
                                        KIRIM PESANAN JASA</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function tambahBarang() {
        var container = document.getElementById('container-barang');
        var div = document.createElement('div');
        div.className = 'pesanan-container';
        div.innerHTML = `
            <select name="barang[]" class="form-control" required onchange="hitungTotal()">
                <option value="">Pilih Barang</option>
                <?php 
                mysqli_data_seek($result_barang, 0);
                while($row = mysqli_fetch_assoc($result_barang)) { 
                ?>
                    <option value="<?php echo $row['id_barang']; ?>"
                            data-harga="<?php echo $row['harga']; ?>">
                        <?php echo $row['nama_barang']; ?> - <?php echo $row['layanan']; ?> 
                        (Rp <?php echo number_format($row['harga'],0,',','.'); ?>)
                    </option>
                <?php } ?>
            </select>
            <input type="number" name="jumlah[]" class="form-control" 
                placeholder="Jumlah" required min="1" 
                onchange="hitungSubtotal(this.parentElement)" 
                onkeyup="hitungSubtotal(this.parentElement)">
            <textarea name="catatan[]" class="form-control" 
                placeholder="Catatan untuk pesanan (opsional)"></textarea>
            <div class="subtotal-display">Subtotal: Rp 0</div>
            <button type="button" class="btn-hapus" onclick="this.parentElement.remove(); hitungTotal()">
                Hapus</button>
        `;
        container.appendChild(div);
    }

    function hitungSubtotal(container) {
        const select = container.querySelector('select');
        const jumlah = container.querySelector('input[type="number"]').value || 0;
        const option = select.options[select.selectedIndex];
        let subtotal = 0;
        
        if(option && option.dataset.harga) {
            subtotal = option.dataset.harga * jumlah;
        }
        
        container.querySelector('.subtotal-display').textContent = 
            'Subtotal: Rp ' + subtotal.toLocaleString('id-ID');
        hitungTotal();
    }

    function hitungTotal() {
        let total = 0;
        const containers = document.querySelectorAll('.pesanan-container');
        
        containers.forEach(container => {
            const select = container.querySelector('select');
            const jumlah = container.querySelector('input[type="number"]').value || 0;
            const option = select.options[select.selectedIndex];
            
            if(option && option.dataset.harga) {
                total += option.dataset.harga * jumlah;
            }
        });

        document.getElementById('total').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
    </script>
</body>
</html>