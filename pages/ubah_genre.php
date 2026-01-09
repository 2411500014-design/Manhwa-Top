<?php
$pesan = '';
$pesan_type = '';

// Get genre ID
$id_genre = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_genre == 0) {
    $_SESSION['pesan'] = "Genre tidak ditemukan!";
    $_SESSION['pesan_type'] = "danger";
    ob_clean();
    header("Location: index.php?hal=daftar_genre");
    exit;
}

// Get genre data
$stmt = $mysqli->prepare("SELECT * FROM genre WHERE id_genre = ?");
$stmt->bind_param("i", $id_genre);
$stmt->execute();
$result = $stmt->get_result();
$genre = $result->fetch_assoc();
$stmt->close();

if (!$genre) {
    $_SESSION['pesan'] = "Genre tidak ditemukan!";
    $_SESSION['pesan_type'] = "danger";
    ob_clean();
    header("Location: index.php?hal=daftar_genre");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_genre = trim($_POST['nama_genre'] ?? '');
    
    if (empty($nama_genre)) {
        $pesan = "Nama genre wajib diisi!";
        $pesan_type = "danger";
    } else {
        // Cek apakah genre dengan nama tersebut sudah ada (selain yang sedang diubah)
        $stmt_check = $mysqli->prepare("SELECT id_genre FROM genre WHERE nama_genre = ? AND id_genre != ?");
        $stmt_check->bind_param("si", $nama_genre, $id_genre);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        
        if ($result_check->num_rows > 0) {
            $pesan = "Genre dengan nama tersebut sudah ada!";
            $pesan_type = "warning";
            $stmt_check->close();
        } else {
            $stmt_check->close();
            
            // Update genre
            $stmt = $mysqli->prepare("UPDATE genre SET nama_genre = ? WHERE id_genre = ?");
            $stmt->bind_param("si", $nama_genre, $id_genre);
            
            if ($stmt->execute()) {
                $stmt->close();
                $_SESSION['pesan'] = "Genre berhasil diupdate!";
                $_SESSION['pesan_type'] = "success";
                ob_clean();
                header("Location: index.php?hal=daftar_genre");
                exit;
            } else {
                $pesan = "Gagal mengupdate genre: " . $mysqli->error;
                $pesan_type = "danger";
                $stmt->close();
            }
        }
    }
}
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Ubah Genre</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="index.php?hal=daftar_genre">Daftar Genre</a></li>
        <li class="breadcrumb-item active">Ubah Genre</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Form Ubah Genre
        </div>
        <div class="card-body">
            <?php if ($pesan): ?>
                <div class="alert alert-<?php echo $pesan_type; ?> alert-dismissible fade show" role="alert">
                    <?php echo $pesan; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label for="nama_genre" class="form-label">Nama Genre <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nama_genre" name="nama_genre" 
                           value="<?php echo isset($_POST['nama_genre']) ? htmlspecialchars($_POST['nama_genre']) : htmlspecialchars($genre['nama_genre']); ?>" 
                           required>
                </div>
                
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="index.php?hal=daftar_genre" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

