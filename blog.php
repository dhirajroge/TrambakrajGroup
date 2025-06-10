<?php
include 'db_connect.php';
$stmt = $conn->prepare("SELECT * FROM posts ORDER BY created_at DESC");
$stmt->execute();
$posts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Read the latest news and updates from Trambakraj Enterprises, including construction and car washing tips.">
    <meta name="keywords" content="blog, news, construction tips, car washing tips, Trambakraj, Nagpur">
    <meta name="author" content="Trambakraj Enterprises">
    <title>Blog - Trambakraj Enterprises</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-XXXXXXXXXX');
    </script>
</head>
<body>
    <?php include 'nav.php'; ?>

    <section class="blog-section py-5" aria-label="Blog Posts">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2>Our Blog</h2>
                <p class="lead">Stay updated with our latest news and tips</p>
            </div>
            <div class="row">
                <?php if (empty($posts)): ?>
                    <p class="text-center">No blog posts available yet.</p>
                <?php else: ?>
                    <?php foreach ($posts as $post): ?>
                    <div class="col-md-4 col-12 mb-4">
                        <div class="card h-100">
                            <img src="<?= $post['image'] ? 'uploads/' . htmlspecialchars($post['image']) : 'images/default-blog.jpg' ?>" 
                                 srcset="<?= $post['image'] ? 'uploads/' . htmlspecialchars($post['image']) . ' 800w, uploads/' . htmlspecialchars($post['image']) . '-mobile.jpg 576w' : 'images/default-blog.jpg 800w' ?>"
                                 sizes="(max-width: 576px) 576px, 800px"
                                 class="card-img-top" alt="<?= htmlspecialchars($post['title']) ?>" loading="lazy">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars(substr($post['content'], 0, 100)) ?>...</p>
                                <a href="#post-<?= $post['id'] ?>" class="btn btn-primary">Read More</a>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted"><?= date('F d, Y', strtotime($post['created_at'])) ?></small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>