<?php
// Inisialisasi variabel untuk menyimpan nilai input dan error
$nama = $email = $nomor = $mobil = $alamat = "";
$namaErr = $emailErr = $nomorErr = $alamatErr = "";

include_once("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi Nama
    $nama = $_POST["nama"];
    if (empty($nama)) {
        $namaErr = "Nama wajib diisi";
    }

    // Validasi Email
    $email = $_POST["email"];
    if (empty($email)) {
        $emailErr = "Email wajib diisi";
    }

    // Validasi Nomor Telepon
    $nomor = $_POST["nomor"];
    if (empty($nomor)) {
        $nomorErr = "Nomor Telepon wajib diisi";
    } elseif (!ctype_digit($nomor)) {
        $nomorErr = "Nomor Telepon harus berupa angka";
    }

    // Validasi Alamat
    $alamat = $_POST["alamat"];
    if (empty($alamat)) {
        $alamatErr = "Alamat wajib diisi";
    }

    // Menyimpan pilihan mobil tanpa validasi khusus
    $mobil = $_POST["mobil"];

    // Jika tidak ada error, simpan data ke database
    if (!$namaErr && !$emailErr && !$nomorErr && !$alamatErr) {
        $sql = "INSERT INTO users (nama, email, nomor, mobil, alamat) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($mysqli, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssss", $nama, $email, $nomor, $mobil, $alamat);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            echo "Error: " . mysqli_error($mysqli);
        }
    }
}

$result = mysqli_query($mysqli, "SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pembelian Mobil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Form Pembelian Mobil</h2>
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div class="form-group">
                <label for="nama">Nama:</label>
                <input type="text" id="nama" name="nama" value="<?php echo $nama; ?>">
                <span class="error"><?php echo $namaErr ? "* $namaErr" : ""; ?></span>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" value="<?php echo $email; ?>">
                <span class="error"><?php echo $emailErr ? "* $emailErr" : ""; ?></span>
            </div>

            <div class="form-group">
                <label for="nomor">Nomor Telepon:</label>
                <input type="text" id="nomor" name="nomor" value="<?php echo $nomor; ?>">
                <span class="error"><?php echo $nomorErr ? "* $nomorErr" : ""; ?></span>
            </div>

            <div class="form-group">
                <label for="mobil">Pilih Mobil:</label>
                <select id="mobil" name="mobil">
                    <option value="Sedan" <?php echo ($mobil == "Sedan") ? "selected" : ""; ?>>Sedan</option>
                    <option value="SUV" <?php echo ($mobil == "SUV") ? "selected" : ""; ?>>SUV</option>
                    <option value="Hatchback" <?php echo ($mobil == "Hatchback") ? "selected" : ""; ?>>Hatchback</option>
                </select>
            </div>

            <div class="form-group">
                <label for="alamat">Alamat Pengiriman:</label>
                <textarea id="alamat" name="alamat"><?php echo $alamat; ?></textarea>
                <span class="error"><?php echo $alamatErr ? "* $alamatErr" : ""; ?></span>
            </div>

            <div class="button-container">
                <button type="submit">Beli Mobil</button>
            </div>
        </form>
    </div>

    <div class="container">
        <h3>Data Pembelian</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th width="10%">ID</th>
                        <th width="18%">Nama</th>
                        <th width="18%">Email</th>
                        <th width="14%">Nomor Telepon</th>
                        <th width="14%">Mobil</th>
                        <th width="26%">Alamat Pengiriman</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nama']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['nomor']; ?></td>
                        <td><?php echo $row['mobil']; ?></td>
                        <td><?php echo $row['alamat']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>