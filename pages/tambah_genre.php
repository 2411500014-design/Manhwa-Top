<?php
$pesan = '';
$pesan_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_genre = trim($_POST['nama_genre'] ?? '');
    
    if (empty($nama_genre)) {
        $pesan = "Nama genre wajib diisi!";
        $pesan_type = "danger";
    } else {
        // Cek apakah genre sudah ada
        $stmt_check = $mysqli->prepare("SELECT id_genre FROM genre WHERE nama_genre = ?");
        $stmt_check->bind_param("s", $nama_genre);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        
        if ($result_check->num_rows > 0) {
            $pesan = "Genre dengan nama tersebut sudah ada!";
            $pesan_type = "warning";
            $stmt_check->close();
        } else {
            $stmt_check->close();
            
            // Insert genre baru
            $stmt = $mysqli->prepare("INSERT INTO genre (nama_genre) VALUES (?)");
            $stmt->bind_param("s", $nama_genre);
            
            if ($stmt->execute()) {
                $stmt->close();
                $_SESSION['pesan'] = "Genre berhasil ditambahkan!";
                $_SESSION['pesan_type'] = "success";
                ob_clean();
                header("Location: index.php?hal=daftar_genre");
                exit;
            } else {
                $pesan = "Gagal menambahkan genre: " . $mysqli->error;
                $pesan_type = "danger";
                $stmt->close();
            }
        }
    }
}
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Tambah Genre</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="index.php?hal=daftar_genre">Daftar Genre</a></li>
        <li class="breadcrumb-item active">Tambah Genre</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus me-1"></i>
            Form Tambah Genre
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
                           value="<?php echo isset($_POST['nama_genre']) ? htmlspecialchars($_POST['nama_genre']) : ''; ?>" 
                           placeholder="Contoh: Action, Romance, Comedy, dll"
                           required>
                    <small class="form-text text-muted">Masukkan nama genre yang ingin ditambahkan</small>
                </div>
                
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="index.php?hal=daftar_genre" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

