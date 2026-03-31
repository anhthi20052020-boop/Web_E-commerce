<?php
// Lay danh sach order
$status = $_GET['status'] ?? 'all';
$page = max(1, $_GET['page'] ?? 1);
$limit = 10;
$offset = ($page - 1) * $limit;

$where = '';
$params = [];

if ($status !== 'all') {
    $where = "WHERE o.status = ?";
    $params[] = $status;
}

try {
    $orders = $pdo->prepare("
        SELECT o.*, u.username
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        $where
        ORDER BY o.created_at DESC
        LIMIT $limit OFFSET $offset
    ");
    $orders->execute($params);
    $total_orders = $pdo->prepare("SELECT COUNT(*) FROM orders o $where");
    $total_orders->execute($params);
    $total = $total_orders->fetchColumn();
    $total_pages = ceil($total / $limit);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Order Management</h1>
    
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="btn-group" role="group">
                <a href="?section=orders&status=all" class="btn btn-<?= $status === 'all' ? 'primary' : 'secondary' ?>">All</a>
                <a href="?section=orders&status=pending" class="btn btn-<?= $status === 'pending' ? 'primary' : 'secondary' ?>">Pending</a>
                <a href="?section=orders&status=processing" class="btn btn-<?= $status === 'processing' ? 'primary' : 'secondary' ?>">Processing</a>
                <a href="?section=orders&status=shipped" class="btn btn-<?= $status === 'shipped' ? 'primary' : 'secondary' ?>">Shipped</a>
                <a href="?section=orders&status=delivered" class="btn btn-<?= $status === 'delivered' ? 'primary' : 'secondary' ?>">Delivered</a>
                <a href="?section=orders&status=cancelled" class="btn btn-<?= $status === 'cancelled' ? 'primary' : 'secondary' ?>">Cancelled</a>
            </div>
        </div>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Orders List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?= $order['id'] ?></td>
                            <td>
                                <?= htmlspecialchars($order['username'] ?? 'Guest') ?>
                            </td>
                            <td><?= date('M d, Y H:i', strtotime($order['created_at'])) ?></td>
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
                            <td>
                                <?= htmlspecialchars($order['payment_method'] ?? 'N/A') ?>
                            </td>
                            <td>
                                <a href="?section=orders&action=view&id=<?= $order['id'] ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?section=orders&status=<?= $status ?>&page=<?= $page-1 ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?section=orders&status=<?= $status ?>&page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?section=orders&status=<?= $status ?>&page=<?= $page+1 ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div> 