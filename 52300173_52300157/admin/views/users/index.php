<?php
// Lấy danh sách người dùng
$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();

// Xóa người dùng nếu được yêu cầu
if (isset($_GET['delete_user'])) {
    $userId = $_GET['delete_user'];

    // Kiểm tra số lượng đơn hàng của người dùng
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
    $stmt->execute([$userId]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo "<script>alert('Không thể xóa người dùng này vì đã có đơn hàng.'); window.location.href='admin.php?section=users';</script>";
    } else {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        header("Location: admin.php?section=users");
        exit;
    }
}
?>


<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">User Management</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Users</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Full Name</th>
                            <th>Phone</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['full_name']) ?></td>
                            <td><?= htmlspecialchars($user['phone']) ?></td>
                            <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                            <td>
                                <a href="admin.php?section=users&action=view&id=<?= $user['id'] ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="admin.php?section=users&delete_user=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
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