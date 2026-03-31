<?php
// Lấy danh sách sản phẩm
$products = $pdo->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll();

// Xử lý xóa sản phẩm
if (isset($_GET['delete_product'])) {
    $id = $_GET['delete_product'];
    $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
    header("Location: admin.php?section=products");
    exit;
}
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Product Management</h1>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Products</h6>
            <a href="admin.php?section=products&action=create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New Product
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><img src="<?= htmlspecialchars($product['image']) ?>" width="50"></td>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td><?= number_format($product['price'], 0) ?>đ</td>
                            <td><?= $product['stock'] ?></td>
                            <td>
                                <?php if ($product['is_bestseller']): ?>
                                    <span class="badge badge-success">Bestseller</span>
                                <?php endif; ?>
                                <?php if ($product['is_hot']): ?>
                                    <span class="badge badge-danger">Hot</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="admin.php?section=products&action=edit&id=<?= $product['id'] ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="admin.php?section=products&delete_product=<?= $product['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
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