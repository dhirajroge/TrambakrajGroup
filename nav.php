<?php
// Ensure the active page is highlighted
$current_page = htmlspecialchars(basename($_SERVER['PHP_SELF']));
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" aria-label="Main navigation">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="images/logo.png" alt="Trambakraj Logo" height="40" class="d-inline-block align-top me-2">
            Trambakraj
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'index.php' ? 'active' : '' ?>" href="index.php" aria-current="<?= $current_page == 'index.php' ? 'page' : '' ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'about.php' ? 'active' : '' ?>" href="about.php" aria-current="<?= $current_page == 'about.php' ? 'page' : '' ?>">About</a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link  <?= $current_page == 'services.php' ? 'active' : '' ?>" href="services.php#construction" id="services" role="button"  aria-expanded="false">
                        Services
                    </a>
                 
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'gallery.php' ? 'active' : '' ?>" href="gallery.php" aria-current="<?= $current_page == 'gallery.php' ? 'page' : '' ?>">Gallery</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'blog.php' ? 'active' : '' ?>" href="blog.php" aria-current="<?= $current_page == 'blog.php' ? 'page' : '' ?>">Blog</a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'contact.php' ? 'active' : '' ?>" href="contact.php" aria-current="<?= $current_page == 'contact.php' ? 'page' : '' ?>">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-primary text-white ms-2" href="book_appointment.php">Book Now</a>
                </li>
            </ul>
            <!-- <form class="d-flex ms-3" action="search.php" method="GET" role="search">
                <input class="form-control me-2" type="search" name="query" placeholder="Search services..." aria-label="Search services">
                <button class="btn btn-outline-light" type="submit" aria-label="Search"><i class="fas fa-search"></i></button>
            </form> -->
        </div>
    </div>
</nav>