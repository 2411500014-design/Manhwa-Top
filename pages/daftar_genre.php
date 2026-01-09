<?php
// Get all genres
$sql = "SELECT * FROM genre ORDER BY nama_genre";
$result = $mysqli->query($sql);
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Daftar Genre</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Daftar Genre</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Data Genre
            <a href="index.php?hal=tambah_genre" class="btn btn-primary btn-sm float-end">
                <i class="fas fa-plus"></i> Tambah Genre
            </a>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['pesan'])): ?>
                <div class="alert alert-<?php echo $_SESSION['pesan_type']; ?> alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['pesan']; 
                    unset($_SESSION['pesan']);
                    unset($_SESSION['pesan_type']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <table id="datatablesSimple" class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Genre</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while ($row = $result->fetch_assoc()): 
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row['nama_genre']); ?></td>
                            <td>
                                <a href="index.php?hal=ubah_genre&id=<?php echo $row['id_genre']; ?>" 
                                   class="btn btn-sm btn-warning" title="Ubah">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="index.php?hal=hapus_genre&id=<?php echo $row['id_genre']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus genre ini? Genre yang sudah digunakan oleh manhwa tidak bisa dihapus.');" 
                                   title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

