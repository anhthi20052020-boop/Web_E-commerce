<?php
$posts = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC")->fetchAll();

$id = $_GET['id'] ?? 0;
$post = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$post->execute([$id]);
$post = $post->fetch();

if (!$post) {
    echo '<script>window.location.href="admin.php?section=posts";</script>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE posts SET title=?, content=?, author=?, category=?, image=? WHERE id=?");
    $stmt->execute([
        $_POST['title'],
        $_POST['content'],
        $_POST['author'],
        $_POST['category'],
        $_POST['image'],
        $id
    ]);
    echo '<script>window.location.href="admin.php?section=posts";</script>';
    exit;
}
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Post: <?= htmlspecialchars($post['title']) ?></h1>
    
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($post['title']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Content</label>
                    <textarea name="content" id="editor" class="form-control" rows="10" required><?= htmlspecialchars($post['content']) ?></textarea>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Author</label>
                            <input type="text" name="author" class="form-control" value="<?= htmlspecialchars($post['author']) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category" class="form-control" required>
                                <option value="hot-news" <?= $post['category'] === 'hot-news' ? 'selected' : '' ?>>Hot News</option>
                                <option value="tips-and-trends" <?= $post['category'] === 'tips-and-trends' ? 'selected' : '' ?>>Tips & Trends</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Featured Image URL</label>
                            <input type="text" name="image" class="form-control" value="<?= htmlspecialchars($post['image']) ?>" required>
                            <?php if ($post['image']): ?>
                            <img src="<?= htmlspecialchars($post['image']) ?>" style="max-height: 100px; margin-top: 10px;">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Update Post</button>
                <a href="admin.php?section=posts" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('editor', {
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