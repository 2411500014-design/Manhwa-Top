<?php
$id_manhwa = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_manhwa == 0) {
    $_SESSION['pesan'] = "Manhwa tidak ditemukan!";
    $_SESSION['pesan_type'] = "danger";
    ob_clean();
    header("Location: index.php?hal=daftar_manhwa");
    exit;
}

// Get manhwa data untuk mendapatkan nama file cover
$stmt_get = $mysqli->prepare("SELECT cover_manhwa FROM manhwa WHERE id_manhwa = ?");
$stmt_get->bind_param("i", $id_manhwa);
$stmt_get->execute();
$result_get = $stmt_get->get_result();
$manhwa = $result_get->fetch_assoc();
$stmt_get->close();

if (!$manhwa) {
    $_SESSION['pesan'] = "Manhwa tidak ditemukan!";
    $_SESSION['pesan_type'] = "danger";
    ob_clean();
    header("Location: index.php?hal=daftar_manhwa");
    exit;
}

// Hapus relasi genre terlebih dahulu (akan terhapus otomatis karena CASCADE, tapi lebih aman hapus manual)
$stmt_genre = $mysqli->prepare("DELETE FROM manhwa_genre WHERE id_manhwa = ?");
$stmt_genre->bind_param("i", $id_manhwa);
$stmt_genre->execute();
$stmt_genre->close();

// Hapus manhwa
$stmt = $mysqli->prepare("DELETE FROM manhwa WHERE id_manhwa = ?");
$stmt->bind_param("i", $id_manhwa);

if ($stmt->execute()) {
    // Hapus file cover jika ada
    if (!empty($manhwa['cover_manhwa'])) {
        $cover_path = "uploads/manhwa/" . $manhwa['cover_manhwa'];
        if (file_exists($cover_path)) {
            unlink($cover_path);
        }
    }
    
    $stmt->close();
    $_SESSION['pesan'] = "Manhwa berhasil dihapus!";
    $_SESSION['pesan_type'] = "success";
} else {
    $_SESSION['pesan'] = "Gagal menghapus manhwa: " . $mysqli->error;
    $_SESSION['pesan_type'] = "danger";
    $stmt->close();
}

ob_clean();
header("Location: index.php?hal=daftar_manhwa");
exit;
?>

