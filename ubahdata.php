<?php
include ("koneksi.php");

if (isset($_POST['aksi'])) {
    if ($_POST['aksi'] == 'tambah') {
        tambahData();
    } elseif ($_POST['aksi'] == 'edit') {
        editData();
    } elseif ($_POST['aksi'] == 'hapus') {
        hapusData();
    }
}

function tambahData() {
    global $conn;
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $program_studi = $_POST['program_studi'];

    $sql = "INSERT INTO mahasiswa (nim, nama, program_studi) VALUES ('$nim', '$nama', '$program_studi')";
    if ($conn->query($sql) === TRUE) {
        echo "Data mahasiswa berhasil disimpan.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

function editData() {
    global $conn;
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $program_studi = $_POST['program_studi'];

    $sql = "UPDATE mahasiswa SET nama='$nama', program_studi='$program_studi' WHERE nim='$nim'";
    if ($conn->query($sql) === TRUE) {
        echo "Data mahasiswa berhasil diperbarui.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

function hapusData() {
    global $conn;
    $nim = $_POST['nim'];

    $sql = "DELETE FROM mahasiswa WHERE nim='$nim'";
    if ($conn->query($sql) === TRUE) {
        echo "Data mahasiswa berhasil dihapus.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>