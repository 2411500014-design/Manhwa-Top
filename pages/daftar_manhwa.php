<?php
// Get all manhwa with their genres
$sql = "SELECT m.*, GROUP_CONCAT(g.nama_genre SEPARATOR ', ') as genres 
        FROM manhwa m 
        LEFT JOIN manhwa_genre mg ON m.id_manhwa = mg.id_manhwa 
        LEFT JOIN genre g ON mg.id_genre = g.id_genre 
        GROUP BY m.id_manhwa 
        ORDER BY m.id_manhwa DESC";
$result = $mysqli->query($sql);
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Daftar Manhwa</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Daftar Manhwa</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Data Manhwa
            <a href="index.php?hal=tambah_manhwa" class="btn btn-primary btn-sm float-end">
                <i class="fas fa-plus"></i> Tambah Manhwa
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
                        <th>Cover</th>
                        <th>Nama Manhwa</th>
                        <th>Penulis</th>
                        <th>Penerbit</th>
                        <th>Tahun Terbit</th>
                        <th>Genre</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while ($row = $result->fetch_assoc()): 
                        $cover_path = !empty($row['cover_manhwa']) 
                            ? "uploads/manhwa/" . $row['cover_manhwa'] 
                            : "assets/img/no-image.png";
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>
                                <img src="<?php echo $cover_path; ?>" 
                                     alt="Cover" 
                                     style="width: 60px; height: 80px; object-fit: cover;"
                                     onerror="this.src='assets/img/no-image.png'">
                            </td>
                            <td><?php echo htmlspecialchars($row['nama_manhwa']); ?></td>
                            <td><?php echo htmlspecialchars($row['penulis']); ?></td>
                            <td><?php echo htmlspecialchars($row['penerbit']); ?></td>
                            <td><?php echo date('Y', strtotime($row['tahun_terbit'])); ?></td>
                            <td><?php echo htmlspecialchars($row['genres'] ?? '-'); ?></td>
                            <td>
                                <a href="index.php?hal=ubah_manhwa&id=<?php echo $row['id_manhwa']; ?>" 
                                   class="btn btn-sm btn-warning" title="Ubah">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="index.php?hal=hapus_manhwa&id=<?php echo $row['id_manhwa']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus manhwa ini?');" 
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

