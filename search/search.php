<?php
include_once  "../init.php";
include "../genral/config.php";
include "../genral/functions.php";

if(isset($_GET['search'])){
  $search_term = mysqli_real_escape_string($connectSQL, $_GET['search_term'] );
  $slecet_search = "SELECT * FROM product WHERE title LIKE '%$search_term%'";
  $sS = mysqli_query($connectSQL ,$slecet_search );
  // $fetchSearch= mysqli_fetch_assoc($sS);
  $numRowSearch = mysqli_num_rows($sS);
}else{
  $numRowSearch = 0;
}

// Check if add to cart is triggered
if (isset($_POST['add_to_cart'])) {
  if (isset($_SESSION['customer'])) {
      $quantity = $_POST['quantity'];
      $productId = $_POST['product_id'];
      $customerId = $_SESSION['id'];
      $insert = "INSERT INTO orders VALUES (NULL, $quantity, $customerId, $productId)";
      $i = mysqli_query($connectSQL, $insert);
      $message = testMessage($i, "insert order");
      header("location: $root_path/index.php#content_product");
      exit;
  } else {
      header("location: $root_path/user/login.php");
      exit;
  }
}

include "../shared/header.php" ;
include "../shared/nav.php" ;
 ?>
<section id="home">
    <div class="product_overview">
        <div class="container">
        <div class="Advertisement">
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
  </ol>
  <div class="carousel-inner">
    <div class="carousel-item active">
    <div class="overlay"></div>
    <img src="<?php echo $root_path ?>/image/image_show2.png" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
    <div class="overlay"></div>
    <img src="<?php echo $root_path ?>/image/image_show4.png" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
    <div class="overlay"></div>
    <img src="<?php echo $root_path ?>/image/image_show3.png" class="d-block w-100" alt="...">
    </div>
  </div>
</div>
    </div>
            <div class="landing pb-2 mt-5">
                <div class="row">
                    <div class="col-6">
                        <h2>research results : <span class="text-danger"><?php if(isset($_GET['search'])){echo $numRowSearch ;}?></span></h2>
                    </div>
                         <!-- filter product -->
          <div class="col-6">
          </div>
        </div>
        <div id="content_product" class="content_product mt-4">
                <?php if($numRowSearch > 0  ): ?>
                  <div id="content-filter" class="row">
                        <?php foreach($sS as $data){ ?>
                            <div class="col-lg-3 col-md-4 col-12 mb-3">
                                <div class="card wow animate__bounceInUp">
                                        <div class="card-head">
                                            <img src="../dashboard/product/upload/<?php echo $data['image'] ; ?>" class="card-img-top" alt="...">
                                            <a href="<?php echo $root_path ?>/product_profile/profile.php?pId=<?php echo $data['id'];?>" class="btn btn-light quick_view rounded-pill">Quick View</a>
                                        </div>        
                                    <div class="card-body p-2" style="display: flex; justify-content: space-between; align-items: center;">
                                      <div>
                                      <h5 class='card-title'><?php echo $data['title'] ; ?></h5>
                                      <p class=""><?php echo 'Rp. ' . number_format($data['price'], 0, ',', '.');?></p>
                                      </div>
                                        <form method="POST" class="ml-auto">
                                            <input type="hidden" name="product_id" value="<?php echo $data['id']; ?>">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" name="add_to_cart" class="btn btn-warning" style="margin-left: 15px;"><ion-icon name="cart-outline" style="font-size: 1.5rem; margin-top: 5px;"></ion-icon></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php }?>  
                </div>
                <?php else : ?>
                    <div class="not_found">
                    </div>
                    <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<?php include "../shared/footer.php" ?>