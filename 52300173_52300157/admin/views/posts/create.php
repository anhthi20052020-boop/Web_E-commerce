<?php
$is_edit = isset($_GET['id']);
$title = $is_edit ? "Edit Post" : "Add New Post";

// Xu ly form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $image = $_POST['image'];
    
    if ($is_edit) {
        $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ?, author = ?, category = ?, image = ? WHERE id = ?");
        $stmt->execute([$title, $content, $author, $category, $image, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO posts (title, content, author, category, image, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$title, $content, $author, $category, $image]);
    }

    echo '<script>window.location.href = "admin.php?section=posts";</script>';
    header("Location: admin.php?section=posts");
    exit;
}

// Lay thong tin bai viet neu la edit
if ($is_edit) {
    $id = $_GET['id'];
    $post = $pdo->prepare("SELECT * FROM posts WHERE id = ?")->execute([$id])->fetch();
    if (!$post) {
        header("Location: admin.php?section=posts");
        exit;
    }
}
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Post Details</h6>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($post['title'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Content</label>
                    <textarea name="content" id="content" rows="5" class="form-control" required><?= htmlspecialchars($post['content'] ?? '') ?></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Author</label>
                            <input type="text" name="author" class="form-control" value="<?= htmlspecialchars($post['author'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category" class="form-control" required>
                                <option value="hot-news" <?= ($post['category'] ?? '') === 'hot-news' ? 'selected' : '' ?>>Hot News</option>
                                <option value="tips-and-trends" <?= ($post['category'] ?? '') === 'tips-and-trends' ? 'selected' : '' ?>>Tips and Trends</option>

                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Image URL</label>
                    <input type="text" name="image" class="form-control" value="<?= htmlspecialchars($post['image'] ?? '') ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Save Post</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('content', {
        toolbar: [
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Blockquote'] },
            { name: 'links', items: ['Link', 'Unlink'] },
            { name: 'insert', items: ['Image', 'Table', 'HorizontalRule'] },
            { name: 'styles', items: ['Styles', 'Format'] },
            { name: 'document', items: ['Source'] }
        ],
        height: 300
    });
</script>