<?php
include_once "./init.php";
include "./genral/config.php";
include "./genral/functions.php";

// Query semua produk
$slecet = "SELECT * FROM product";
$s = mysqli_query($connectSQL, $slecet);

// Query produk kategori 
$slecetMen = "SELECT category.name AS categoryName, product.id, product.title, product.descriptions, 
              product.price, product.image 
              FROM category 
              JOIN product ON categoryId = category.id AND product.categoryId = 1";
$sMen = mysqli_query($connectSQL, $slecetMen);

// Query produk kategori 
$slecetFmale = "SELECT category.name AS categoryName, product.id, product.title, product.descriptions, 
                product.price, product.image 
                FROM category 
                JOIN product ON categoryId = category.id AND product.categoryId = 2";
$sFmale = mysqli_query($connectSQL, $slecetFmale);

// Handle pencarian produk
if (isset($_GET['search'])) {
    $search = $_GET['search_term'];
    header("location: $root_path/search/search.php?search=$search");
}

// Tambahkan produk ke keranjang
if (isset($_POST['add_to_cart'])) {
  if (isset($_SESSION['customer'])) {
      $quantity = $_POST['quantity'];
      $productId = $_POST['product_id'];
      $customerId = $_SESSION['id'];

      // Cek apakah produk sudah ada di keranjang
      $checkQuery = "SELECT id, quantity FROM orders WHERE customerId = $customerId AND productId = $productId";
      $checkResult = mysqli_query($connectSQL, $checkQuery);

      if (mysqli_num_rows($checkResult) > 0) {
          // Jika produk sudah ada, update jumlahnya
          $existingOrder = mysqli_fetch_assoc($checkResult);
          $newQuantity = $existingOrder['quantity'] + $quantity;

          $updateQuery = "UPDATE orders SET quantity = $newQuantity WHERE id = {$existingOrder['id']}";
          $updateResult = mysqli_query($connectSQL, $updateQuery);
          $message = testMessage($updateResult, "update quantity in order");
      } else {
          // Jika produk belum ada, tambahkan produk ke keranjang
          $insertQuery = "INSERT INTO orders (id, quantity, customerId, productId) VALUES (NULL, $quantity, $customerId, $productId)";
          $insertResult = mysqli_query($connectSQL, $insertQuery);
          $message = testMessage($insertResult, "insert order");
      }

      // Redirect kembali ke halaman dengan notifikasi
      header("location: $root_path/index.php#content_product");
      exit;
  } else {
      // Jika pengguna belum login, arahkan ke halaman login
      header("location: $root_path/user/login.php");
      exit;
  }
}


// Include header dan navigasi
include "shared/header.php";
include "shared/nav.php";
?>

<section id="home">
  <div class="home">
    <div id="carouselExampleCaptions" class="carousel slide carousel-fade" data-ride="carousel">
      <ol class="carousel-indicators">
        <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
        <li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner">
        <!-- Carousel Item 1 -->
        <div class="carousel-item active">
          <div class="overlay"></div>
          <img src="<?php echo $root_path ?>/image/image_show1.png" class="d-block w-100" alt="...">
          <div class="carousel-caption d-none d-md-block">
            <h5 class="display-4">Produk Olahan Nanas Asli</h5>
            <h2 class="display-2 pb-4 mb-3">NANAS PINNE</h2>
            <a href="<?php echo $root_path ?>/collection/collection.php?category=1" class="btn btn-outline-info rounded-pill">SHOP NOW</a>
          </div>
        </div>
        <!-- Carousel Item 2 -->
        <div class="carousel-item">
          <div class="overlay"></div>
          <img src="<?php echo $root_path ?>/image/image_show2.png" class="d-block w-100" alt="...">
          <div class="carousel-caption d-none d-md-block">
            <h5 class="display-4">New Product</h5>
            <h2 class="display-2 pb-4 mb-3">PINNE NANAS</h2>
            <a href="<?php echo $root_path ?>/collection/collection.php?category=1" class="btn btn-outline-info rounded-pill">SHOP NOW</a>
          </div>
        </div>
        <!-- Carousel Item 3 -->
        <div class="carousel-item">
          <div class="overlay"></div>
          <img src="<?php echo $root_path ?>/image/image_show3.png" class="d-block w-100" alt="...">
          <div class="carousel-caption d-none d-md-block">
            <h5 class="display-4">Fresh Ingredients</h5>
            <h2 class="display-2 pb-4 mb-3">FOODS AND DRINKS</h2>
            <a href="<?php echo $root_path ?>/collection/collection.php?category=1" class="btn btn-outline-info rounded-pill">SHOP NOW</a>
          </div>
        </div>
      </div>
      <!-- Carousel Controls -->
      <button class="carousel-control-prev" type="button" data-target="#carouselExampleCaptions" data-slide="prev">
        <i class="fas fa-caret-left fa-4x" aria-hidden="true"></i>
      </button>
      <button class="carousel-control-next" type="button" data-target="#carouselExampleCaptions" data-slide="next">
        <i class="fas fa-caret-right fa-4x" aria-hidden="true"></i>
      </button>
    </div>
  </div>

  <!-- Product Section -->
  <div class="product">
    <div class="container">
      <div class="row">
        <!-- Product 1 -->
        <div class="col-md-6">
          <a href="<?php echo $root_path ?>/collection/collection.php?category=1">
            <div class="card">
              <img src="<?php echo $root_path ?>/image/image_show5.png" alt="">
              <div class="overlay"></div>
              <div class="card-body">
                <h5>Makanan</h5>
                <span>New</span>
              </div>
              <h4>Shop Now</h4>
            </div>
          </a>
        </div>
        <!-- Product 2 -->
        <div class="col-md-6">
          <a href="<?php echo $root_path ?>/collection/collection.php?category=2">
            <div class="card">
              <img src="<?php echo $root_path ?>/image/image_show6.png" alt="">
              <div class="overlay"></div>
              <div class="card-body">
                <h5>Minuman</h5>
                <span>New</span>
              </div>
              <h4>Shop Now</h4>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Product Overview -->
  <div class="product_overview">
    <div class="container">
      <div class="landing">
        <h2>PRODUCT</h2>
        <div class="row">
          <div class="col-6"></div>
        </div>
        <!-- Filter Product -->
        <div id="content_product" class="content_product mt-4">
          <div id="content-filter" class="row">
            <?php foreach ($s as $data) { ?>
              <?php $price = number_format($data['price'], 0, ',', '.'); ?>
              <div class="col-lg-3 col-md-4 col-12 mb-3">
                <div class="card wow animate__bounceInUp">
                  <div class="card-head">
                    <img src="./dashboard/product/upload/<?php echo $data['image']; ?>" class="card-img-top" alt="...">
                    <a href="<?php echo $root_path ?>/product_profile/profile.php?pId=<?php echo $data['id']; ?>" class="btn btn-light quick_view rounded-pill">Quick View</a>
                  </div>
                  <div class="card-body p-2" style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                      <h5 class="card-title mb-1"><?php echo $data['title']; ?></h5>
                      <p class="mb-1"><?php echo 'Rp. ' . $price; ?></p>
                    </div>
                    <form method="POST" class="ml-auto">
                      <input type="hidden" name="product_id" value="<?php echo $data['id']; ?>">
                      <input type="hidden" name="quantity" value="1">
                      <button type="submit" name="add_to_cart" class="btn btn-warning" style="margin-left: 15px;">
                        <ion-icon name="cart-outline" style="font-size: 1.5rem; margin-top: 5px;"></ion-icon>
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
        </div> 
      </div>
    </div>
  </div>
</section>

<?php 
$whatsappNumber = "6285725400399";  // Gunakan kode negara untuk nomor
$whatsappLink = "https://wa.me/$whatsappNumber?text=" . urlencode("Hello, admin");
?>

<!-- WhatsApp Icon -->
<a href="<?php echo $whatsappLink; ?>" target="_blank" class="whatsapp-icon">
    <ion-icon name="logo-whatsapp"></ion-icon>
</a>


<!-- Include CSS for WhatsApp Icon -->
<style>
    .whatsapp-icon {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #25D366; /* WhatsApp green */
        color: white;
        border-radius: 50%;
        padding: 15px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        font-size: 2rem; /* Adjust icon size */
        z-index: 1000; /* Ensure it's above other elements */
        transition: transform 0.3s ease, opacity 0.3s ease;
    }

    .whatsapp-icon:hover {
        transform: scale(1.1); /* Slight zoom effect on hover */
        opacity: 0.9;
    }

    .whatsapp-icon ion-icon {
        display: block; /* Ensures the icon is centered inside the circle */
    }
</style>


<!-- Script Ionicons -->
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

<?php if (isset($_SESSION['customer'])): ?>
    <?php include "shared/contact.php"; ?>
<?php endif; ?>

<?php include "shared/footer.php"; ?>
