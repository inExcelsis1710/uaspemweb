<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa Universitas Fontaine</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            height: 100vh;
        }

        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px;
            width: 100%;
        }

        h2 {
            margin-bottom: 0;
        }

        form {
            margin-top: 20px;
            text-align: center;
        }

        table {
            margin-top: 20px;
            border-collapse: collapse;
            width: 80%;
            text-align: center;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #333;
            color: #fff;
        }

        input[type="text"] {
            padding: 8px;
        }

        select, button {
            padding: 10px;
            margin-right: 10px;
            font-size: 16px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        button:hover {
        background-color: #555; 
        color: #fff;
    }

    button[type="button"] {
        background-color: #4CAF50; 
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button[type="button"]:hover {
        background-color: #45a049;
    }

    footer {
        background-color: #002147;
        color: #fff;
        text-align: center;
        padding: 10px;
        margin: 20px;
        width: 100%;
    }

    </style>
</head>
<body>

<h2>DATA MAHASISWA BOOTKEMP PEMROGRAMAN WEB</h2><br>

<div style="margin-bottom: 20px;">
    <label for="prodi">Pilih Program Studi:</label>
    <select id="prodi">
        <option value="">Semua Program Studi</option>
        <?php
        include('koneksi.php');
        $sql = "SELECT DISTINCT program_studi FROM mahasiswa ORDER BY program_studi ASC";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['program_studi'] . "'>" . $row['program_studi'] . "</option>";
        }
        ?>
    </select>

    <button onclick="cariData()">Cari</button>
</div>

<form id="form-mahasiswa">
    NIM: <input type="text" name="nim" id="nim" required>
    Nama: <input type="text" name="nama" id="nama" required>
    Program Studi: <input type="text" name="program_studi" id="program_studi" required>
    <button type="button" onclick="simpanData()">Simpan</button>
</form>


<table border="1">
    <thead>
        <tr>
            <th>NIM</th>
            <th>Nama</th>
            <th>Program Studi</th>
            <th>Opsi</th>
        </tr>
    </thead>
    <tbody id="tabel_mahasiswa">
    </tbody>
</table>

<script>
function editData(nim) {
    // Untuk ngambil data mahasiswa yang akan diedit
    var dataMahasiswa = getDataMahasiswaByNim(nim);

    // Untuk menampilkan prompt untuk input data baru
    var namaBaru = prompt("Masukkan nama baru:", dataMahasiswa.nama);
    var programStudiBaru = prompt("Masukkan program studi baru:", dataMahasiswa.program_studi);

    // Untuk Periksa apakah pengguna memasukkan data baru
    if (namaBaru !== null || programStudiBaru !== null) {
        // Untuk Mengubah data hanya jika ada input baru
        namaBaru = namaBaru !== null ? namaBaru : dataMahasiswa.nama;
        programStudiBaru = programStudiBaru !== null ? programStudiBaru : dataMahasiswa.program_studi;

        $.ajax({
            type: "POST",
            url: "ubahdata.php",
            data: { aksi: "edit", nim: nim, nama: namaBaru, program_studi: programStudiBaru },
            success: function(response) {
                alert(response);
                tampilkanDataMahasiswa();
            }
        });
    } else {
        alert("Edit dibatalkan karena tidak ada data baru dimasukkan.");
    }
}

function getDataMahasiswaByNim(nim) {
    var dataMahasiswa = {};

    $("#tabel_mahasiswa tr").each(function() {
        var rowNim = $(this).find("td:first-child").text();
        if (rowNim === nim) {
            dataMahasiswa.nama = $(this).find("td:nth-child(2)").text();
            dataMahasiswa.program_studi = $(this).find("td:nth-child(3)").text();
            return false;
        }
    });

    return dataMahasiswa;
}

function hapusData(nim) {
    var konfirmasi = confirm("Anda yakin ingin menghapus data?");
    if (konfirmasi) {
        $.ajax({
            type: "POST",
            url: "hapusdata.php",
            data: { aksi: "hapus", nim: nim },
            success: function(response) {
                alert(response);
                tampilkanDataMahasiswa();
            }
        });
    }
}

function simpanData() {
    var nim = $("#nim").val();
    var nama = $("#nama").val();
    var program_studi = $("#program_studi").val();

    $.ajax({
        type: "POST",
        url: "simpandata.php",
        data: { nim: nim, nama: nama, program_studi: program_studi },
        success: function(response) {
            alert(response);
            tampilkanDataMahasiswa();
        }
    });
}

function tampilkanDataMahasiswa() {
    var selectedProdi = $("#prodi").val();
    var keyword = $("#keyword").val();

    $.ajax({
        type: "GET",
        url: "tampildata.php",
        data: { prodi: selectedProdi, keyword: keyword },
        success: function(response) {
            $("#tabel_mahasiswa").html(response);
            // Memastikan tombol Edit dan Hapus tetap dapat digunakan
            tambahkanEventEditHapus();
        }
    });
}

function tambahkanEventEditHapus() {
    $("#tabel_mahasiswa button").on("click", function() {
        var nim = $(this).closest("tr").find("td:first-child").text();
        var aksi = $(this).text().toLowerCase(); // "Edit" atau "Hapus"

        if (aksi === "edit") {
            editData(nim);
        } else if (aksi === "hapus") {
            hapusData(nim);
        }
    });
}

function cariData() {
            var selectedProdi = $("#prodi").val();
            var keyword = $("#keyword").val();

            $.ajax({
                type: "GET",
                url: "caridata.php",
                data: { prodi: selectedProdi, keyword: keyword },
                success: function (response) {
                    $("#tabel_mahasiswa").html(response);
                }
            });
        }

$(document).ready(function() {
    tampilkanDataMahasiswa();
});

function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + value + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function eraseCookie(name) {
    document.cookie = name + '=; Max-Age=-99999999;';
}

function setLocalStorage(key, value) {
    localStorage.setItem(key, value);
}

function getLocalStorage(key) {
    return localStorage.getItem(key);
}

function removeLocalStorage(key) {
    localStorage.removeItem(key);
}


</script>

</body>

<footer>
    <p>&copy; UTS 1 PEMROGRAMAN WEB - IGNATIUS KRISNA </p>
</footer>

</html>
