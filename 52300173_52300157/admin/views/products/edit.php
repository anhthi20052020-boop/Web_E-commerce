<?php

ob_start();
$is_edit = isset($_GET['id']);
$title = $is_edit ? "Edit Product" : "Add New Product";

// Lấy thông tin sản phẩm nếu là edit
if ($is_edit) {
    $id = $_GET['id'];
    $product = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $product->execute([$id]);
    $product = $product->fetch();
    if (!$product) {
        header("Location: admin.php?section=products");
        exit;
    }
}

// Xử lý form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $original_price = $_POST['original_price'];
    $image = $_POST['image'];
    $category = $_POST['category'];
    $brand = $_POST['brand'];
    $stock = $_POST['stock'];
    $is_bestseller = isset($_POST['is_bestseller']) ? 1 : 0;
    $is_hot = isset($_POST['is_hot']) ? 1 : 0;
    
    if ($is_edit) {
        $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, original_price = ?, image = ?, category = ?, brand = ?, stock = ?, is_bestseller = ?, is_hot = ? WHERE id = ?");
        $stmt->execute([$name, $description, $price, $original_price, $image, $category, $brand, $stock, $is_bestseller, $is_hot, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, original_price, image, category, brand, stock, is_bestseller, is_hot) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $original_price, $image, $category, $brand, $stock, $is_bestseller, $is_hot]);
    }
    
    echo "<script>window.location.href='admin.php?section=products';</script>";

    exit;
}
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Product Details</h6>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="editor" rows="3" class="form-control" required><?= $product['description'] ?? '' ?></textarea>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Price (VND)</label>
                            <input type="number" name="price" class="form-control" value="<?= $product['price'] ?? '' ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Original Price (VND)</label>
                            <input type="number" name="original_price" class="form-control" value="<?= $product['original_price'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Stock Quantity</label>
                            <input type="number" name="stock" class="form-control" value="<?= $product['stock'] ?? 0 ?>" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category" class="form-control" required>
                                <option value="Smartphones" <?= ($product['category'] ?? '') === 'Smartphones' ? 'selected' : '' ?>>Smartphones</option>
                                <option value="Laptops" <?= ($product['category'] ?? '') === 'Laptops' ? 'selected' : '' ?>>Laptops</option>
                                <option value="PCs" <?= ($product['category'] ?? '') === 'PCs' ? 'selected' : '' ?>>PCs</option>
                                <option value="Tablets" <?= ($product['category'] ?? '') === 'Tablets' ? 'selected' : '' ?>>Tablets</option>
                                <option value="Accessories" <?= ($product['category'] ?? '') === 'Accessories' ? 'selected' : '' ?>>Accessories</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Brand</label>
                            <input type="text" name="brand" class="form-control" value="<?= htmlspecialchars($product['brand'] ?? '') ?>" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Image URL</label>
                    <input type="text" name="image" class="form-control" value="<?= htmlspecialchars($product['image'] ?? '') ?>" required>
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" name="is_bestseller" class="form-check-input" id="is_bestseller" <?= ($product['is_bestseller'] ?? 0) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="is_bestseller">Bestseller Product</label>
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" name="is_hot" class="form-check-input" id="is_hot" <?= ($product['is_hot'] ?? 0) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="is_hot">Hot Product</label>
                </div>
                <button type="submit" class="btn btn-primary">Save Product</button>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
     });
</script>