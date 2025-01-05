<?php 

include_once  "../../init.php";
include "../../genral/config.php";

$errorName = [];
$errorEmail = [];
$errorPassword = [];
$success = null;

if (isset($_POST['sginUp'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (empty($name)) {
        $errorName[] = "The name field is empty!";
    } elseif (strlen($name) > 25) {
        $errorName[] = "(20) max for this field!";
    }

    if (empty($password)) {
        $errorPassword[] = "The Password field is empty!";
    } elseif (strlen($password) > 12) {
        $errorPassword[] = "(12) max for this field!";
    } elseif (strlen($password) < 4) {
        $errorPassword[] = "(4) minimum for this field!";
    }

    $select = "SELECT * FROM `admins` WHERE name = '$name'";
    $s = mysqli_query($connectSQL, $select);
    $numRow = mysqli_num_rows($s);

    if ($numRow > 0) {
        $errorName[] = "This name is already in use: enter a new name!";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorEmail[] = "Please enter an email containing @";
    }

    $checkEmail = "SELECT * FROM `admins` WHERE `email` = '$email'";
    $checkRow = mysqli_query($connectSQL, $checkEmail);
    if (mysqli_fetch_assoc($checkRow)) {
        $errorEmail[] = "This email already exists";
    }

    if (empty($errorName) && empty($errorPassword) && empty($errorEmail)) {
        $insert = "INSERT INTO `admins` VALUES (NULL, '$name', '$email', '$password', $role)";
        $i = mysqli_query($connectSQL, $insert);
        $success = $i ? "success" : "error";
    }
}

$name = "";
$password = "";
$update = false;

if (isset($_GET['edit'])) {
    $update = true;
    $id = $_GET['edit'];
    $select = "SELECT * FROM admins WHERE id = $id";
    $ss = mysqli_query($connectSQL, $select);
    $row = mysqli_fetch_assoc($ss);
    $name = $row['name'];

    if (isset($_POST['update'])) {
        $name = $_POST['name'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        if (empty($name)) {
            $errorName[] = "The name field is empty!";
        } elseif (strlen($name) > 25) {
            $errorName[] = "(20) max for this field!";
        }

        if (empty($password)) {
            $errorPassword[] = "The Password field is empty!";
        } elseif (strlen($password) > 12) {
            $errorPassword[] = "(12) max for this field!";
        } elseif (strlen($password) < 4) {
            $errorPassword[] = "(4) minimum for this field!";
        }

        if (empty($errorName)) {
            $updateQ = "UPDATE admins SET name='$name', password='$password', `roles`=$role WHERE id = $id";
            $u = mysqli_query($connectSQL, $updateQ);
            header("location: $root_path/dashboard/admin/index.php");
            exit();
        }
    }
}

include "../../genral/functions.php";
include "../layouts/header.php";
include "../layouts/sidebar.php";
?>

<main class="app-content">
    <!-- Popup Notifikasi -->
    <?php if ($success == "success"): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Admin berhasil ditambahkan!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif ($success == "error"): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Gagal menambahkan admin. Silakan coba lagi!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-6">
            <div class="tile">
                <?php if ($update): ?>
                    <h3 class="tile-title">Update Admin</h3>
                <?php else: ?>
                    <h3 class="tile-title">Register Admin</h3>
                <?php endif; ?>
                <div class="tile-body">
                    <form method="POST">
                        <div class="form-group row">
                            <label class="control-label col-md-3" for="inputname4">Name</label>
                            <div class="col-md-8">
                                <input value="<?php echo $name ?>" name="name" type="text" class="form-control" id="inputname4" placeholder="name">
                                <?php if (!empty($errorName)): ?>
                                    <div class="alert alert-danger" role="alert"><?php echo $errorName[0]; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if (!$update): ?>
                            <div class="form-group row">
                                <label class="control-label col-md-3" for="inputemail4">Email</label>
                                <div class="col-md-8">
                                    <input name="email" type="text" class="form-control" id="inputemail4" placeholder="email">
                                    <?php if (!empty($errorEmail)): ?>
                                        <div class="alert alert-danger" role="alert"><?php echo $errorEmail[0]; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="form-group row">
                            <label class="control-label col-md-3" for="inputPassword">Password</label>
                            <div class="col-md-8">
                                <input name="password" type="password" class="form-control" id="inputPassword" placeholder="Password">
                                <?php if (!empty($errorPassword)): ?>
                                    <div class="alert alert-danger" role="alert"><?php echo $errorPassword[0]; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group row" style="display: none;">
                            <label class="control-label col-md-3">Role</label>
                            <div class="col-md-8">
                                <select class="form-control" name="role">
                                    <option value="0">All Access</option>
                                    <option value="1">Semi Access</option>
                                </select>
                            </div>
                        </div>

                        <div class="tile-footer">
                            <div class="row">
                                <div class="col-md-8 col-md-offset-3">
                                    <?php if ($update): ?>
                                        <button type="submit" name="update" class="btn btn-info">Update Data</button>
                                    <?php else: ?>
                                        <button type="submit" name="sginUp" class="btn btn-primary">Register</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include "../layouts/footer.php"; ?>
