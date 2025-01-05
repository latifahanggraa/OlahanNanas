<?php 
if(isset($_GET['logout'])){
  session_unset();
  session_destroy();
  header("location: $root_path/index.php");
}
$host = "localhost";
$user = "root";
$password= "";
$dbName = "penjualan";

$connectSQL = mysqli_connect($host , $user , $password , $dbName);

$slecet_category = "SELECT * FROM `category`";
$s_category = mysqli_query($connectSQL ,$slecet_category );


if(isset($_SESSION['id'])){
$ccId = $_SESSION['id'];
$slecetOrder = "SELECT * FROM `orders` JOIN `product` , `customers` WHERE customers.id = $ccId AND orders.productId = product.id AND customerId = customers.id";
$sc = mysqli_query($connectSQL ,$slecetOrder);
$numRowOrder = mysqli_num_rows($sc);
}
if(isset($_SESSION['id'])){
$cId = $_SESSION['id'];
$slecetCstum = "SELECT * FROM `customers` WHERE customers.id = $cId ";
$sCstum= mysqli_query($connectSQL ,$slecetCstum);
$rowCstum = mysqli_fetch_assoc($sCstum);
    $nameR = $rowCstum['name'];
    $email = $rowCstum['email'];
    $addressR = $rowCstum['address'];
    $image = $rowCstum['image_user'];
    $phoneU = $rowCstum['phone'];
}


?>
<nav class="navbar navbar-expand-lg navbar-dark bg-light fixed-top" style="border-bottom: 2px solid #26965A;">
  <a class="navbar-brand" href="<?php echo $root_path ?>/index.php"><img src="<?php echo $root_path ?>/image/logo-pinne.png" style="width: 100px;" alt=""></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="<?php echo $root_path ?>/index.php" style="color: black;" >Home<span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo $root_path ?>/blog/blog.php" style="color: black;" >Blog</a>
      </li>
      <?php if(isset($_SESSION['customer'] )) :?>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo $root_path ?>/order/cart.php" style="color: black;" >Cart
        ( <span class='count_cart' style="color: #26965A;"> <?php echo $numRowOrder ;?></span> )</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo $root_path ?>/customer_requests/customer_requests.php" style="color:black;" >requests</a>
      </li>
      <?php endif; ?>
    </ul>
    <div class="box_srarch">
    <form class="form-inline my-2 my-lg-0"  autocomplete="off" method="GET">
      <input id="search_text" name="search_term" class="form-control w-100" type="text" placeholder="Search" aria-label="Search" style="border-radius: 50px;" >
      <button name="search" class="btn btn-outline-primary my-2 my-sm-0" type="submit" style="border-radius: 50%; border: 1px solid #26965A; color: #26965A; background-color: transparent; transition: background-color 0.3s, color 0.3s, border-color 0.3s;" onmouseover="this.style.backgroundColor='#26965A'; this.style.color='#fff'; this.style.borderColor='#fff';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#26965A'; this.style.borderColor='#26965A';">
        <i class="fas fa-search"></i>
      </button>
    </form>
    <div class="search-box" id="search-box"></div>
      </div>
    <?php if(isset($_SESSION['customer'] )) :?>
      <div class="form-inline my-2 my-lg-0 ml-auto dropdown ">
        <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-expanded="false" style="border: 1px solid black; border-radius: 50%;" >
          <img class='img-logo' src="<?php echo $root_path ?>/user/upload_user_image/<?php echo $image ?>" alt=""></a>
        <div class="dropdown-menu custm-drop" aria-labelledby="dropdownMenuLink">
          <div class="main-header p-3 border-bottom border-primary bg-primary text-white">
            <div class="row no-gutters">
              <div class="col-md-4">
                <img class='img-fluid rounded-circle border border-light' src="<?php echo $root_path ?>/user/upload_user_image/<?php echo $image ?>" alt="...">
              </div>
              <div class="col-md-8 pl-2">
                <h6><?php echo $nameR ?></h6>
                <p><small class="text-white-50"><?php echo $phoneU ?></small></p>
              </div>
            </div>
          </div>
          <a class="dropdown-item border-bottom" href="<?php echo $root_path ?>/user/user_profile.php"><i class="fas fa-user"></i> Profile</a>
          <a class="dropdown-item border-bottom" href="<?php echo $root_path ?>/order/cart.php"><i class="fas fa-shopping-cart"></i> cart</a>
          <a class="dropdown-item text-danger" href="<?php echo $root_path ?>/index.php?logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
      </div>
    <?php else : ?>
    <form class="form-inline my-2 my-lg-0 ml-auto">
      <a href="<?php echo $root_path ?>/user/signUp.php" class="btn btn-outline-info my-2 my-sm-0 " >Sign Up</a>
      <a href="<?php echo $root_path ?>/user/login.php" class="btn btn-outline-success my-2 my-sm-0 ml-3" >Login</a>
    </form>
    <?php endif; ?>
  </div>
</nav>