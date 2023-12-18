<?php
include ('koneksi.php');

$prodi = isset($_GET['prodi']) ? $_GET['prodi'] : '';
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

$sql = "SELECT * FROM mahasiswa";

if (!empty($prodi) && $prodi != 'Semua Program Studi') {
    $sql .= " WHERE LOWER(program_studi) = LOWER(?)";
}

$sql .= " ORDER BY program_studi ASC"; // Urutkan berdasarkan alfabet

$stmt = $conn->prepare($sql);

if (!empty($prodi) && $prodi != 'Semua Program Studi') {
    $param_prodi = strtolower($prodi);
    $stmt->bind_param('s', $param_prodi);
}

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["nim"] . "</td>";
        echo "<td>" . $row["nama"] . "</td>";
        echo "<td>" . $row["program_studi"] . "</td>";
        echo "<td>
                <button onclick='editData(\"" . $row["nim"] . "\")'>Edit</button>
                <button onclick='hapusData(\"" . $row["nim"] . "\")'>Hapus</button>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>Data mahasiswa tidak ditemukan.</td></tr>";
}

$stmt->close();
$conn->close();
?>
