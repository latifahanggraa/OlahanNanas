<?php
include_once "../init.php";
include "../genral/config.php";
include "../genral/functions.php";

if (isset($_GET['pId']) && !empty($_GET['pId'])) {
    $id = intval($_GET['pId']);
    $select = "SELECT * FROM product WHERE id = $id";
    $sr = mysqli_query($connectSQL, $select);

    if ($row = mysqli_fetch_assoc($sr)) {
        $namePro = $row['title'];
        $descriptionsyR = $row['descriptions'];
        $imageR = $row['image'];
        $imageR2 = $row['image2'];
        $imageR3 = $row['image3'];
        $priceR = number_format($row['price'], 0, ',', '.');
    } else {
        die("Product not found");
    }
} else {
    die("Product ID not provided");
}

if (isset($_POST['send'])) {
    if (isset($_SESSION['customer'])) {
        $quantity = intval($_POST['quantity'] ?? 1);
        $productId = intval($_POST['productId'] ?? 0); // Gunakan field hidden `productId`
        $customerId = intval($_SESSION['id']);

        if ($productId > 0) {
            $checkQuery = "SELECT id, quantity FROM orders WHERE customerId = $customerId AND productId = $productId";
            $checkResult = mysqli_query($connectSQL, $checkQuery);

            if (mysqli_num_rows($checkResult) > 0) {
                $existingOrder = mysqli_fetch_assoc($checkResult);
                $newQuantity = $existingOrder['quantity'] + $quantity;

                $updateQuery = "UPDATE orders SET quantity = $newQuantity WHERE id = {$existingOrder['id']}";
                $updateResult = mysqli_query($connectSQL, $updateQuery);
                $message = testMessage($updateResult, "update quantity in order");
            } else {
                $insert = "INSERT INTO orders (id, quantity, customerId, productId) VALUES (NULL, $quantity, $customerId, $productId)";
                $insertResult = mysqli_query($connectSQL, $insert);
                $message = testMessage($insertResult, "insert order");
            }

            header("location: $root_path/index.php#content_product");
            exit;
        } else {
            die("Invalid product ID");
        }
    } else {
        header("location: $root_path/user/login.php");
        exit;
    }
}

include "../shared/header.php"; 
include "../shared/nav.php";
?>

<!-- Tampilan Produk -->
<div class="Product_profile">
    <div class="container py-3 border mb-4">
        <div class="row">
            <div class="col-md-6 p-md-5">
                <div class="collection-image gallery">
                    <div class="row">
                        <div class="col-12 ">
                            <img class="image_top" src="../dashboard/product/upload/<?php echo $imageR ?>" alt="image">
                        </div>
                        <div class="col-4 py-2">
                            <img src="../dashboard/product/upload/<?php echo $imageR ?>" alt="image">
                        </div>
                        <div class="col-4 py-2">
                            <img src="../dashboard/product/upload/<?php echo $imageR2 ?>" alt="image">
                        </div>
                        <div class="col-4 py-2">
                            <img src="../dashboard/product/upload/<?php echo $imageR3 ?>" alt="image">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 p-md-5">
                <h3 class='mt-5 border-bottom pb-1'><?php echo $namePro; ?></h3>
                <h4 class='my-3 border-bottom pb-1'><?php echo "Rp. " . $priceR; ?></h4>
                <p class='my-5 border-bottom pb-1'><?php echo $descriptionsyR; ?></p>
                <form method="POST">
                    <input type="hidden" name="productId" value="<?php echo $id; ?>">
                    <div class="form-group col-md-4">
                        <label for="inputcategory">Quantity</label>
                        <div style="display: flex; align-items: center;">
                            <button type="button" class="btn btn-outline-secondary btn-sm quantity-decrease" style="margin-right: 5px;">-</button>
                            <input type="number" class="form-control text-center quantity-input" name="quantity" value="1" min="1" max="10000" style="width: 100px;">
                            <button type="button" class="btn btn-outline-secondary btn-sm quantity-increase" style="margin-left: 5px;">+</button>
                        </div>
                    </div>
                    <div class="text-center form-group mt-5">
                        <button type="submit" name="send" class="btn btn-warning">Add To Cart</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const decreaseButton = document.querySelector('.quantity-decrease');
        const increaseButton = document.querySelector('.quantity-increase');
        const quantityInput = document.querySelector('.quantity-input');

        decreaseButton.addEventListener('click', () => {
            let quantity = parseInt(quantityInput.value) || 1;
            if (quantity > 1) {
                quantityInput.value = quantity - 1;
            }
        });

        increaseButton.addEventListener('click', () => {
            let quantity = parseInt(quantityInput.value) || 1;
            if (quantity < 10000) {
                quantityInput.value = quantity + 1;
            }
        });
    });
</script>
<?php include "../shared/footer.php"; ?>
