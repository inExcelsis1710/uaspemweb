<?php
include ('koneksi.php');

if (isset($_POST['nim']) && isset($_POST['nama']) && isset($_POST['program_studi'])) {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $program_studi = $_POST['program_studi'];

    $sql = "INSERT INTO mahasiswa (nim, nama, program_studi) VALUES ('$nim', '$nama', '$program_studi')";

    if ($conn->query($sql) === TRUE) {
        echo "Data mahasiswa berhasil disimpan.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Invalid parameters.";
}

$conn->close();
?>