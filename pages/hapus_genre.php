<?php
$id_genre = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_genre == 0) {
    $_SESSION['pesan'] = "Genre tidak ditemukan!";
    $_SESSION['pesan_type'] = "danger";
    ob_clean();
    header("Location: index.php?hal=daftar_genre");
    exit;
}

// Cek apakah genre sedang digunakan oleh manhwa
$stmt_check = $mysqli->prepare("SELECT COUNT(*) as total FROM manhwa_genre WHERE id_genre = ?");
$stmt_check->bind_param("i", $id_genre);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$check = $result_check->fetch_assoc();
$stmt_check->close();

if ($check['total'] > 0) {
    $_SESSION['pesan'] = "Genre tidak bisa dihapus karena sedang digunakan oleh " . $check['total'] . " manhwa!";
    $_SESSION['pesan_type'] = "warning";
    ob_clean();
    header("Location: index.php?hal=daftar_genre");
    exit;
}

// Hapus genre
$stmt = $mysqli->prepare("DELETE FROM genre WHERE id_genre = ?");
$stmt->bind_param("i", $id_genre);

if ($stmt->execute()) {
    $stmt->close();
    $_SESSION['pesan'] = "Genre berhasil dihapus!";
    $_SESSION['pesan_type'] = "success";
} else {
    $_SESSION['pesan'] = "Gagal menghapus genre: " . $mysqli->error;
    $_SESSION['pesan_type'] = "danger";
    $stmt->close();
}

ob_clean();
header("Location: index.php?hal=daftar_genre");
exit;
?>

