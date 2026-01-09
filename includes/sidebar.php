<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link <?php echo ($page == "dashboard")? 'active' : '';  ?>" href="/">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <div class="sb-sidenav-menu-heading">Manajemen</div>
            <a class="nav-link <?php echo ($page == "daftar_manhwa" || $page == "tambah_manhwa" || $page == "ubah_manhwa") ? "active" : "collapsed"; ?>" href="#" data-bs-toggle="collapse" data-bs-target="#collapseManhwa" aria-expanded="false" aria-controls="collapseManhwa">
                <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                Data Manhwa
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php echo ($page == "daftar_manhwa" || $page == "tambah_manhwa" || $page == "ubah_manhwa") ? "show" : ""; ?>" id="collapseManhwa" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link <?php echo ($page == "daftar_manhwa") ? 'active' : ''; ?>" href="index.php?hal=daftar_manhwa">Daftar Manhwa</a>
                    <a class="nav-link <?php echo ($page == "tambah_manhwa") ? 'active' : ''; ?>" href="index.php?hal=tambah_manhwa">Tambah Manhwa</a>
                </nav>
            </div>
                <a class="nav-link <?php echo ($page == "daftar_genre" || $page == "tambah_genre" || $page == "ubah_genre") ? "active" : "collapsed"; ?>" href="#" data-bs-toggle="collapse" data-bs-target="#collapseGenre" aria-expanded="false" aria-controls="collapseGenre">
                    <div class="sb-nav-link-icon"><i class="fas fa-tags"></i></div>
                    Genre
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse <?php echo ($page == "daftar_genre" || $page == "tambah_genre" || $page == "ubah_genre") ? "show" : ""; ?>" id="collapseGenre" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link <?php echo ($page == "daftar_genre") ? 'active' : ''; ?>" href="index.php?hal=daftar_genre">Daftar Genre</a>
                        <a class="nav-link <?php echo ($page == "tambah_genre") ? 'active' : ''; ?>" href="index.php?hal=tambah_genre">Tambah Genre</a>
                    </nav>
                </div>
                
                <a class="nav-link" href="logout.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-sign-out"></i></div>
                    Logout
                </a>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <?php echo $_SESSION['admin_nama_lengkap'] ?>
        </div>
    </nav>
</div>
