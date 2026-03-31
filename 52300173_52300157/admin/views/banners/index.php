<?php
// Lay danh sach banner
$banners = $pdo->query("SELECT * FROM banners ORDER BY id ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_banner'])) {
        $description = str_replace(['<p>', '</p>','<strong>','</strong>'], '', $_POST['description']);

        $stmt = $pdo->prepare("INSERT INTO banners (title, subtitle, description, video_url, product_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['title'],
            $_POST['subtitle'],
            $description,
            $_POST['video_url'],
            $_POST['product_id']
        ]);
        echo '<script>window.location.href="admin.php?section=home";</script>';
        exit;
    }
    
    if (isset($_POST['delete_banner'])) {
        $pdo->prepare("DELETE FROM banners WHERE id = ?")->execute([$_POST['banner_id']]);
        echo '<script>window.location.href="admin.php?section=home";</script>';
        exit;
    }
}

// Lay danh sach banner
$banners = $pdo->query("SELECT * FROM banners ORDER BY id ASC")->fetchAll();
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Homepage Banners</h1>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Current Banners</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product ID</th>
                                    <th>Title</th>
                                    <th>Video URL</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($banners as $banner): ?>
                                <tr>
                                    <td><?= htmlspecialchars($banner['product_id']) ?></td>
                                    <td><?= htmlspecialchars($banner['title']) ?></td>
                                    <td><?= htmlspecialchars($banner['video_url']) ?></td>
                                    <td>
                                        <a href="admin.php?section=home&action=edit_banner&id=<?= $banner['id'] ?>" class="btn btn-sm btn-primary">
    <i class="fas fa-edit"></i> Edit
</a>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="banner_id" value="<?= $banner['id'] ?>">
                                            <button type="submit" name="delete_banner" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Add New Banner</h6>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Subtitle</label>
                            <input type="text" name="subtitle" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Video URL</label>
                            <input type="text" name="video_url" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Linked Product ID</label>
                            <input type="number" name="product_id" class="form-control">
                        </div>

                        <button type="submit" name="add_banner" class="btn btn-primary">Add Banner</button>
                    </form>
                </div>
            </div>
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