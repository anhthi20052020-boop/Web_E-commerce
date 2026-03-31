<?php
if (!isset($_GET['id'])) {
    header('Location: admin.php?section=users');
    exit;
}

$user_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: admin.php?section=users');
    exit;
}

// Lay lich su don hang cua nguoi dung
$stmt = $pdo->prepare("
    SELECT * FROM orders 
    WHERE user_id = ?

");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">User Details: <?= htmlspecialchars($user['username']) ?></h1>
    
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profile Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Username:</strong> <?= htmlspecialchars($user['username']) ?>
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong> <?= htmlspecialchars($user['email']) ?>
                    </div>
                    <div class="mb-3">
                        <strong>Full Name:</strong> <?= htmlspecialchars($user['full_name']) ?>
                    </div>
                    <div class="mb-3">
                        <strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?>
                    </div>
                    <div class="mb-3">
                        <strong>Address:</strong> 
                        <p><?= nl2br(htmlspecialchars($user['address'])) ?></p>
                    </div>
                    <div class="mb-3">
                        <strong>Registered:</strong> <?= date('M d, Y H:i', strtotime($user['created_at'])) ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order History</h6>
                </div>
                <div class="card-body">
                    <?php if (count($orders) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><a href="admin.php?section=orders&action=view&id=<?= $order['id'] ?>">#<?= $order['id'] ?></a></td>
                                        <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>  <!-- Sửa thành tên cột đúng -->
                                        <td><?= number_format($order['total_amount'], 2) ?>đ</td>
                                        <td>
                                            <span class="badge badge-<?= 
                                                $order['status'] === 'pending' ? 'warning' : 
                                                ($order['status'] === 'processing' ? 'info' : 
                                                ($order['status'] === 'shipped' ? 'primary' : 
                                                ($order['status'] === 'delivered' ? 'success' : 'danger')))
                                            ?>">
                                                <?= ucfirst($order['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p>No orders found for this user.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <a href="admin.php?section=users" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Users
    </a>
</div>