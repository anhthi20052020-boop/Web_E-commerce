<?php
$banners = $pdo->query("SELECT * FROM banners ORDER BY id ASC")->fetchAll();

// Lay ID banner tu URL
$id = $_GET['id'] ?? 0;

// Lay thong tin banner
$stmt = $pdo->prepare("SELECT * FROM banners WHERE id = ?");
$stmt->execute([$id]);
$banner = $stmt->fetch();

if (!$banner) {
    echo '<script>window.location.href="admin.php?section=home";</script>';
    exit;
}

// Xu ly cap nhat banner
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE banners SET title=?, subtitle=?, description=?, video_url=?, product_id=? WHERE id=?");
    $description = str_replace(['<p>', '</p>','<strong>','</strong>'], '', $_POST['description']);
    $stmt->execute([
        $_POST['title'],
        $_POST['subtitle'],
        $description,
        $_POST['video_url'],
        $_POST['product_id'],
        $id
    ]);
    echo '<script>window.location.href="admin.php?section=home";</script>';
    exit;
}
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Banner #<?= $banner['id'] ?></h1>
    
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($banner['title']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Subtitle</label>
                    <input type="text" name="subtitle" class="form-control" value="<?= htmlspecialchars($banner['subtitle']) ?>">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="3" required><?= htmlspecialchars($banner['description']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Video URL</label>
                    <input type="text" name="video_url" class="form-control" value="<?= htmlspecialchars($banner['video_url']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Linked Product ID</label>
                    <input type="number" name="product_id" class="form-control" value="<?= htmlspecialchars($banner['product_id']) ?>">
                </div>
                <button type="submit" class="btn btn-primary">Update Banner</button>
                <a href="admin.php?section=home" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('description', {
        toolbar: [
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList', 'Blockquote'] },
            { name: 'links', items: ['Link', 'Unlink'] },
            { name: 'insert', items: ['Image', 'Table'] },
            { name: 'tools', items: ['Maximize'] },
            { name: 'document', items: ['Source'] }
        ],
        height: 300
    });
</script>
</div>