<?php
// Lay danh sach bai viet
$posts = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC")->fetchAll();

// Xoa bai viet
if (isset($_GET['delete_post'])) {
    $id = $_GET['delete_post'];
    $pdo->prepare("DELETE FROM posts WHERE id = ?")->execute([$id]);
    echo '<script>window.location.href="admin.php?section=posts";</script>';
    exit;
}
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Posts Management</h1>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Posts</h6>
            <a href="admin.php?section=posts&action=create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New Post
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><?= htmlspecialchars($post['title']) ?></td>
                            <td><?= htmlspecialchars($post['author']) ?></td>
                            <td><?= htmlspecialchars($post['category']) ?></td>
                            <td><?= date('M d, Y', strtotime($post['created_at'])) ?></td>
                            <td>
                                <a href="admin.php?section=posts&action=edit&id=<?= $post['id'] ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="admin.php?section=posts&delete_post=<?= $post['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>