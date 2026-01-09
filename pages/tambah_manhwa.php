<?php
$pesan = '';
$pesan_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_manhwa = $_POST['nama_manhwa'] ?? '';
    $penulis = $_POST['penulis'] ?? '';
    $penerbit = $_POST['penerbit'] ?? '';
    $tahun_terbit = $_POST['tahun_terbit'] ?? '';
    $genres = $_POST['genre'] ?? [];

    if (empty($nama_manhwa) || empty($penulis) || empty($penerbit) || empty($tahun_terbit)) {
        $pesan = "Semua field wajib diisi!";
        $pesan_type = "danger";
    } else {
        // Handle file upload
        $cover_filename = '';
        if (isset($_FILES['cover_manhwa']) && $_FILES['cover_manhwa']['error'] == 0) {
            $upload_dir = "uploads/manhwa/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file = $_FILES['cover_manhwa'];
            $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($file_ext, $allowed_ext)) {
                $cover_filename = uniqid() . '_' . time() . '.' . $file_ext;
                $upload_path = $upload_dir . $cover_filename;
                
                if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
                    $pesan = "Gagal mengupload cover!";
                    $pesan_type = "danger";
                }
            } else {
                $pesan = "Format file tidak didukung! Gunakan JPG, PNG, GIF, atau WEBP.";
                $pesan_type = "danger";
            }
        }
        
        if (empty($pesan)) {
            $stmt = $mysqli->prepare("INSERT INTO manhwa (nama_manhwa, penulis, penerbit, tahun_terbit, cover_manhwa) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $nama_manhwa, $penulis, $penerbit, $tahun_terbit, $cover_filename);
            
            if ($stmt->execute()) {
                $id_manhwa = $mysqli->insert_id;
                
                // Insert genres
                if (!empty($genres) && is_array($genres)) {
                    $stmt_genre = $mysqli->prepare("INSERT INTO manhwa_genre (id_manhwa, id_genre) VALUES (?, ?)");
                    foreach ($genres as $id_genre) {
                        $id_genre = intval($id_genre);
                        if ($id_genre > 0) {
                            $stmt_genre->bind_param("ii", $id_manhwa, $id_genre);
                            $stmt_genre->execute();
                        }
                    }
                    $stmt_genre->close();
                }
                
                $stmt->close();
                $_SESSION['pesan'] = "Manhwa berhasil ditambahkan!";
                $_SESSION['pesan_type'] = "success";
                ob_clean();
                header("Location: index.php?hal=daftar_manhwa");
                exit;
            } else {
                $pesan = "Gagal menambahkan manhwa: " . $mysqli->error;
                $pesan_type = "danger";
                $stmt->close();
            }
        }
    }
}

// Get all genres for the form
$genres_result = $mysqli->query("SELECT * FROM genre ORDER BY nama_genre");
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Tambah Manhwa</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="index.php?hal=daftar_manhwa">Daftar Manhwa</a></li>
        <li class="breadcrumb-item active">Tambah Manhwa</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus me-1"></i>
            Form Tambah Manhwa
        </div>
        <div class="card-body">
            <?php if ($pesan): ?>
                <div class="alert alert-<?php echo $pesan_type; ?> alert-dismissible fade show" role="alert">
                    <?php echo $pesan; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nama_manhwa" class="form-label">Nama Manhwa <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nama_manhwa" name="nama_manhwa" 
                           value="<?php echo isset($_POST['nama_manhwa']) ? htmlspecialchars($_POST['nama_manhwa']) : ''; ?>" 
                           required>
                </div>
                
                <div class="mb-3">
                    <label for="penulis" class="form-label">Penulis <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="penulis" name="penulis" 
                           value="<?php echo isset($_POST['penulis']) ? htmlspecialchars($_POST['penulis']) : ''; ?>" 
                           required>
                </div>
                
                <div class="mb-3">
                    <label for="penerbit" class="form-label">Penerbit <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="penerbit" name="penerbit" 
                           value="<?php echo isset($_POST['penerbit']) ? htmlspecialchars($_POST['penerbit']) : ''; ?>" 
                           required>
                </div>
                
                <div class="mb-3">
                    <label for="tahun_terbit" class="form-label">Tahun Terbit <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="tahun_terbit" name="tahun_terbit" 
                           value="<?php echo isset($_POST['tahun_terbit']) ? htmlspecialchars($_POST['tahun_terbit']) : ''; ?>" 
                           required>
                </div>
                
                <div class="mb-3">
                    <label for="genre" class="form-label">Genre</label>
                    <select class="form-select" id="genre" name="genre[]" multiple size="5">
                        <?php while ($genre = $genres_result->fetch_assoc()): ?>
                            <option value="<?php echo $genre['id_genre']; ?>" 
                                    <?php echo (isset($_POST['genre']) && in_array($genre['id_genre'], $_POST['genre'])) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($genre['nama_genre']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <small class="form-text text-muted">Tekan Ctrl (atau Cmd di Mac) untuk memilih multiple genre</small>
                </div>
                
                <div class="mb-3">
                    <label for="cover_manhwa" class="form-label">Cover Manhwa</label>
                    <input type="file" class="form-control" id="cover_manhwa" name="cover_manhwa" 
                           accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                           onchange="previewCover(this)">
                    <small class="form-text text-muted">Format: JPG, PNG, GIF, atau WEBP</small>
                    <div id="cover_preview" class="mt-3" style="display: none;">
                        <p class="mb-2"><strong>Preview Cover:</strong></p>
                        <img id="preview_image" src="" alt="Preview Cover" 
                             style="max-width: 200px; max-height: 300px; object-fit: cover; border: 1px solid #ddd; border-radius: 4px; padding: 5px;">
                    </div>
                </div>
                
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="index.php?hal=daftar_manhwa" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewCover(input) {
    const preview = document.getElementById('cover_preview');
    const previewImage = document.getElementById('preview_image');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            preview.style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}
</script>

