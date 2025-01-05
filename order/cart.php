<?php
include_once  "../init.php";
include "../genral/config.php";
include "../genral/functions.php";
userPermissions();

if(isset($_GET['pId'])){
    $pId = $_GET['pId']; 
}

if(isset($_SESSION['id'])){
    $idUser = $_SESSION['id'];
    $slecet = "SELECT *,orders.id AS orderID ,orders.quantity * product.price AS totalQuantity FROM orders JOIN product , customers WHERE customers.id = $idUser AND orders.productId = product.id AND customerId = customers.id;";
    $s = mysqli_query($connectSQL ,$slecet );
    $numRow = mysqli_num_rows($s);
    $total = "SELECT * ,orders.quantity * product.price AS totalQuantity , SUM(orders.quantity * product.price) AS sumTotal FROM orders JOIN product , customers WHERE customers.id = $idUser AND orders.productId = product.id AND customerId = customers.id;";
    $t = mysqli_query($connectSQL ,$total );
    $sumt = mysqli_fetch_assoc($t);
    $sumTotal  =$sumt['sumTotal'];

    $idp = $_SESSION['id'];
    $select = "SELECT * FROM customers where id = $idp";
    $sr = mysqli_query($connectSQL , $select);
    $row = mysqli_fetch_assoc($sr);
    $nameR = $row['name'];
    $phoneR = $row['phone'];
    $addressR = $row['address'];
    $image = $row['image_user'];
}

if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $delete = "DELETE FROM orders WHERE id = $id";
    mysqli_query($connectSQL, $delete);
    header("location: $root_path/order/cart.php");
}

if(isset($_POST['update_quantity'])){
    $orderID = $_POST['order_id'];
    $newQuantity = $_POST['quantity'];
    $updateQuantity = "UPDATE orders SET quantity = $newQuantity WHERE id = $orderID";
    mysqli_query($connectSQL, $updateQuantity);
    header("Location: $root_path/order/cart.php");
}

$select = "SELECT * FROM customers where id = $idUser";
$sr = mysqli_query($connectSQL , $select);
$row = mysqli_fetch_assoc($sr);
$nameR = $row['name'];
$phoneR = $row['phone'];
$addressR = $row['address'];

$pro = '';
$orderId = '';
if(isset($_POST['chekOut'])){
    $payment_method = $_POST['payment_method'];
    $insertCheck = "INSERT INTO order_manager VALUES (NULL ,'$nameR','$phoneR' ,'$addressR',$sumTotal,'$payment_method',1,current_timestamp())";
    $iCheck = mysqli_query($connectSQL ,$insertCheck);

    if($iCheck){
        $selectCheck = "SELECT * FROM `order_manager`";
        $sCheck = mysqli_query($connectSQL ,$selectCheck);

        foreach($sCheck as $sCheckId){
            $orderId = $sCheckId['order_Id'];
        }

        foreach($s as $allProduct){
            $pro = mysqli_real_escape_string($connectSQL, $allProduct['title']);
            $pri = $allProduct['price']; 
            $qu = $allProduct['quantity'];
            $insertCheckO = "INSERT INTO `chekout` (order_Id, product_name, price, quantity) VALUES ($orderId ,'$pro','$pri' ,'$qu')";
            $iCheckO = mysqli_query($connectSQL ,$insertCheckO);
        }
    }
    $deleteO = "DELETE FROM `orders` WHERE customerId = $idUser";
    mysqli_query($connectSQL, $deleteO);
    if ($payment_method == 'transfer bank') {
        header("Location: $root_path/order/transfer_bank.php");
        exit();
    } else {
        header("location: $root_path/order/cart.php");
        exit();
    }
}

include "../shared/header.php"; 
include "../shared/nav.php"; 
?>

<div class="cart pt-5">
<div class="container pt-5">
<?php if($numRow > 0): ?>
<div class="row">
  <div class="col-md-8">
  <div class="card">
  <table class="table">
  <thead>
    <tr>
      <th scope="col">PRODUCT</th>
      <th scope="col">PRICE</th>
      <th scope="col">QUANTITY</th>
      <th scope="col">TOTAL</th>
      <th scope="col">ACTION</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($s as $data){ ?>
    <tr>
      <td class="product_list" scope="row">
        <img class='img_cart' src="../dashboard/product/upload/<?php echo $data['image']; ?>" alt="">
        <h6 class="cart_title pl-3"><?php echo $data['title'] ;?></h6>
      </td>
      <td>Rp. <?php echo number_format($data['price'], 0, ',', '.'); ?></td>
      <td>
      <form action="" method="POST">
    <div class="d-flex align-items-center">
        <button type="submit" name="update_quantity" class="btn btn-sm btn-white border" style="width: 30px; margin-right: 4px;" value="decrement" onclick="updateQuantity(<?php echo $data['orderID']; ?>, 'decrement')">-</button>
        <input type="number" name="quantity" value="<?php echo $data['quantity']; ?>" min="1" class="form-control text-center" style="width: 60px; display: inline-block; margin: 0 10px;" id="quantity_<?php echo $data['orderID']; ?>" />
        <button type="submit" name="update_quantity" class="btn btn-sm btn-white border" style="width: 30px; margin-left: 4px;" value="increment" onclick="updateQuantity(<?php echo $data['orderID']; ?>, 'increment')">+</button>
    </div>
    <input type="hidden" name="order_id" value="<?php echo $data['orderID']; ?>">
</form>

      </td>
      <td>Rp. <?php echo number_format($data['totalQuantity'], 0, ',', '.'); ?></td>
      <td><a href="<?php echo $root_path ?>/order/cart.php?delete=<?php echo $data['orderID']; ?>" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
</div>
</div>

<div class="col-md-4">
  <div class="card p-4">
    <h5>CART TOTALS</h5>
    <div class="row mt-3">
      <div class="col-4">
        <span>Subtotal:</span>
      </div>
      <div class="col-8">
        <span>RP. <?php echo $sumTotal ;?></span>
      </div>
    </div>
    <span class="line"></span>
    <div class="row">
      <div class="col-4">
        <span>Name:</span>
      </div>
      <div class="col-8">
        <p><?php echo $nameR; ?></p>
      </div>
    </div>
    <div class="row">
      <div class="col-4">
        <span>Address:</span>
      </div>
      <div class="col-8">
        <p><?php echo $addressR; ?></p>
      </div>
    </div>
    <span class="line"></span>
    <div class="text-center form-group">
      <form action="" method="post">
        <label for="inputcategory">Payment method</label>
        <select class="form-control" name="payment_method">
          <option value="transfer bank">Transfer Bank</option>
          <option value="pay cash">COD</option>
          <option value="PayPal">PayPal</option>
        </select>
        <!-- <button type="submit" name="chekOut" class="btn btn-primary mt-4">Checkout</button> -->
        <a href="checkout.php" class="btn btn-primary mt-4">Checkout</a>

      </form>
    </div>
  </div>
</div>
</div>

<?php else: ?>
  <div class="text-center ">
    <a href="<?php echo $root_path ?>/index.php#content_product" class="btn btn-warning">Shopping</a>
    <div class="cart-empty mt-5">
      <img class='img-fluid' src="https://image.freepik.com/free-vector/no-data-concept-illustration_114360-626.jpg" alt="">
    </div>
  </div>
<?php endif; ?>


</div>
</div>

<?php include "../shared/footer.php" ?>

<script>
// JavaScript to handle the quantity increment/decrement
function updateQuantity(orderId, action) {
    var quantityInput = document.getElementById('quantity_' + orderId);
    var currentQuantity = parseInt(quantityInput.value);

    if(action === 'increment') {
        quantityInput.value = currentQuantity + 1;
    } else if(action === 'decrement' && currentQuantity > 1) {
        quantityInput.value = currentQuantity - 1;
    }
}
</script>

