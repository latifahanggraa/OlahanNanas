<?php
include_once "../init.php";
include "../genral/config.php";
include "../genral/functions.php";

if (isset($_POST['send'])) {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    $address = htmlspecialchars(trim($_POST['address']));
    $phone = trim($_POST['phone']);

    $errorName = $errorEmail = $errorPassword = $errorAddress = $errorPhone = [];

    if (empty($name)) {
        $errorName[] = "The name field is empty!";
    } elseif (strlen($name) > 25) {
        $errorName[] = "(20) max for this field!";
    }

    if (empty($email)) {
        $errorEmail[] = "The email field is empty!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorEmail[] = "Invalid email format!";
    } elseif (strlen($email) > 50) {
        $errorEmail[] = "(50) max for this field!";
    }

    if (empty($password)) {
        $errorPassword[] = "The Password field is empty!";
    } elseif (strlen($password) > 12) {
        $errorPassword[] = "(12) max for this field!";
    } elseif (strlen($password) < 4) {
        $errorPassword[] = "(4) minimum for this field!";
    }

    if (empty($address)) {
        $errorAddress[] = "The address field is empty!";
    } elseif (strlen($address) > 25) {
        $errorAddress[] = "(25) max for this field!";
    }

    if (empty($phone)) {
        $errorPhone[] = "The phone field is empty!";
    } elseif (!preg_match('/^[0-9]{10,13}$/', $phone)) {
        $errorPhone[] = "Enter between 10 and 13 numbers!";
    }
    

    $stmt = $connectSQL->prepare("SELECT * FROM `customers` WHERE `email` = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $numRow = $stmt->num_rows;
    $stmt->close();

    if ($numRow > 0) {
        $errorEmail[] = "This email is already in use: enter a new email!";
    }

    if (empty($errorName) && empty($errorEmail) && empty($errorPassword) && empty($errorAddress) && empty($errorPhone)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        $insert_stmt = $connectSQL->prepare("INSERT INTO customers (`name`, `email`, `password`, `phone`, `address`) VALUES (?, ?, ?, ?, ?)");
        $insert_stmt->bind_param("sssss", $name, $email, $password, $phone, $address);
        
        if ($insert_stmt->execute()) {
            header("Location: $root_path/index.php");
            exit;
        } else {
            echo "Error: " . $connectSQL->error;
        }
        
        $insert_stmt->close();
    }
}
include "../shared/header.php"; 
include "../shared/nav.php"; 
?>

<section class='Sign_page pt-5'>
    <div class="Sign">
        <div class="row mr-0 ml-0">
            <div class="col-md-6 px-md-0 pt-5">
                <div class="text-center">
                    <img style="width:200px" src="" alt="">
                </div>
                <div class="form">
                    <h2 class="text-center">Sign Up</h2>
                    <form method="POST">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputemail4">Email</label>
                                <input value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" name='email' type="text" class="form-control" id="inputemail4" placeholder="Email">
                                <?php if (!empty($errorEmail)): ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo $errorEmail[0]; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputPassword">Password</label>
                                <div class="content_password">
                                    <input value="<?php echo htmlspecialchars($_POST['password'] ?? ''); ?>" name='password' type="password" class="form-control" id="inputPassword" placeholder="Password">
                                    <i class="fas fa-eye showPass" onclick="showpas()" id="showPassword"></i>
                                </div>
                                <?php if (!empty($errorPassword)): ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo $errorPassword[0]; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputname4">Name</label>
                            <input value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" name='name' type="text" class="form-control" id="inputname4" placeholder="Name">
                            <?php if (!empty($errorName)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $errorName[0]; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="inputphone">Phone</label>
                            <input value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" name='phone' type="text" class="form-control" id="inputphone" placeholder="Phone">
                            <?php if (!empty($errorPhone)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $errorPhone[0]; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Address</label>
                            <input value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>" name='address' type="text" class="form-control" id="inputAddress" placeholder="Address">
                            <?php if (!empty($errorAddress)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $errorAddress[0]; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="text-center form-group">
                            <button type="submit" name="send" class="btn btn-primary">Sign Up</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-6 p-md-0 image-Sign d-md-block d-none">
                <img src="<?php echo $root_path ?>/image/login-image.jpg" alt="">
            </div>
        </div>
</section>
<?php include "../shared/footer.php"; ?>
