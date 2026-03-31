<?php
ob_start();

if (!isset($_GET['id'])) {
    header('Location: admin.php?section=orders');
    ob_end_flush();
    exit;
}

$order_id = $_GET['id'];

try {
    $order = $pdo->prepare("
        SELECT o.*, u.username
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        WHERE o.id = ?
    ");
    $order->execute([$order_id]);
    $order = $order->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        header('Location: admin.php?section=orders');
        ob_end_flush();
        exit;
    }

     $order_items = $pdo->prepare("
        SELECT 
            oi.*, 
            p.image,
            COALESCE(p.name, oi.product_name) as product_name
        FROM order_items oi
        LEFT JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = ?
    ");
    $order_items->execute([$order_id]);
    $order_items = $order_items->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
        $new_status = $_POST['status'];
        $update = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $update->execute([$new_status, $order_id]);
        header("Location: admin.php?section=orders&action=view&id=".$order_id);
        ob_end_flush();
        exit;
    }
} catch (PDOException $e) {
    ob_end_clean();
    die("Database error: " . $e->getMessage());
}
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Order Details #<?= $order['id'] ?></h1>
    <div class="row">
        <div class="col-lg-8">
            <!-- Order item -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Items</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order_items as $item): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($item['image'])): ?>
                                            <img src="<?= htmlspecialchars($item['image']) ?>" width="50" class="mr-2">
                                        <?php endif; ?>
                                        <?= htmlspecialchars($item['product_name'] ?? '') ?>
                                    </td>
                                    <td><?= number_format($item['price'] ?? 0, 2) ?>đ</td>
                                    <td><?= $item['quantity'] ?? 0 ?></td>
                                    <td><?= number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 2) ?>đ</td>
                                </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="3" class="text-right font-weight-bold">Total</td>
                                    <td class="font-weight-bold"><?= number_format($order['total_amount'] ?? 0, 2) ?>đ</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Order note -->
            <?php if (!empty($order['notes'])): ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Notes</h6>
                </div>
                <div class="card-body">
                    <?= nl2br(htmlspecialchars($order['notes'])) ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Summary</h6>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="form-group">
                            <label>Order Status</label>
                            <select name="status" class="form-control">
                                <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                                <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </div>
                        
                        <button type="submit" name="update_status" class="btn btn-primary btn-block">Update Status</button>
                    </form>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <strong>Order Date:</strong> <?= date('M d, Y H:i', strtotime($order['created_at'] ?? 'now')) ?>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_method'] ?? 'N/A') ?>
                    </div>
                    
                    <hr>
                    
                    <h5 class="font-weight-bold">Customer</h5>
                    <div class="mb-2">
                        <strong>Username:</strong> <?= htmlspecialchars($order['username'] ?? 'Guest') ?>
                    </div>
                    
                    <hr>
                    
                    <h5 class="font-weight-bold">Shipping Address</h5>
                    <p><?= nl2br(htmlspecialchars($order['shipping_address'] ?? 'Not provided')) ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <a href="admin.php?section=orders" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Orders
    </a>
</div>