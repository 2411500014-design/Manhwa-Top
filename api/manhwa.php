<?php
session_start();
require "../includes/config.php";

function response($status, $msg, $data = null)
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        "status" => $status,
        "message" => $msg,
        "data" => $data
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== "GET") {
    response("error", "Gunakan metode GET.");
}

// Get base URL dynamically
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$base_path = dirname(dirname($_SERVER['SCRIPT_NAME']));
$base_url = $protocol . '://' . $host . $base_path;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Gunakan prepared statement untuk keamanan
    $stmt = $mysqli->prepare("SELECT * FROM manhwa WHERE id_manhwa = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $manhwa = $result->fetch_assoc();
    $stmt->close();

    if (!$manhwa) {
        response("error", "Manhwa tidak ditemukan");
    }

    $genre = [];
    $stmt_genre = $mysqli->prepare("
        SELECT g.nama_genre 
        FROM manhwa_genre mg
        JOIN genre g ON mg.id_genre = g.id_genre 
        WHERE mg.id_manhwa = ?
    ");
    $stmt_genre->bind_param("i", $id);
    $stmt_genre->execute();
    $resGenre = $stmt_genre->get_result();
    while ($row = $resGenre->fetch_assoc()) {
        $genre[] = $row['nama_genre'];
    }
    $stmt_genre->close();

    $data = [
        "id_manhwa" => $manhwa['id_manhwa'],
        "nama_manhwa" => $manhwa['nama_manhwa'],
        "penulis" => $manhwa['penulis'],
        "penerbit" => $manhwa['penerbit'],
        "tahun_terbit" => $manhwa['tahun_terbit'],
        "genre" => $genre,
        "cover_manhwa" => $manhwa['cover_manhwa']
            ? $base_url . "/uploads/manhwa/" . $manhwa['cover_manhwa']
            : null
    ];

    response("success", "Detail manhwa ditemukan", $data);

} else {
    $result = $mysqli->query("SELECT * FROM manhwa ORDER BY id_manhwa DESC");
    $list = [];

    while ($manhwa = $result->fetch_assoc()) {
        $list[] = [
            "id_manhwa" => $manhwa['id_manhwa'],
            "nama_manhwa" => $manhwa['nama_manhwa'],
            "penulis" => $manhwa['penulis'],
            "penerbit" => $manhwa['penerbit'],
            "tahun_terbit" => $manhwa['tahun_terbit'],
            "cover_manhwa" => $manhwa['cover_manhwa']
                ? $base_url . "/uploads/manhwa/" . $manhwa['cover_manhwa']
                : null
        ];
    }

    response("success", "Daftar semua manhwa ditemukan", $list);
}
?>