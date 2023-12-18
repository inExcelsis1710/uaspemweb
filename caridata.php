<?php
include('koneksi.php');

$prodi = isset($_GET['prodi']) ? $_GET['prodi'] : '';
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

if (!empty($prodi) && $prodi !== 'Semua Program Studi') {
    $sql = "SELECT * FROM mahasiswa WHERE LOWER(program_studi) = LOWER(?) ORDER BY program_studi ASC";
    $stmt = $conn->prepare($sql);
    $param_prodi = strtolower($prodi);
    $stmt->bind_param('s', $param_prodi);
} else {
    // Jika prodi kosong atau "Semua Program Studi" dipilih, tampilkan semua data
    header("Location: index.php");
    exit();
}

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["nim"] . "</td><td>" . $row["nama"] . "</td><td>" . $row["program_studi"] . "</td></tr>";
    }
} else {
    echo "<tr><td colspan='3'>Data mahasiswa tidak ditemukan.</td></tr>";
}

$stmt->close();
$conn->close();
?>
