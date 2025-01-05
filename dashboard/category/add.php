<?php 
include_once "../../init.php";
include "../../genral/functions.php";
include "../../genral/config.php";

$success = ""; // Untuk pesan sukses
$error = "";   // Untuk pesan gagal

// Proses Menambah Kategori
if (isset($_POST['send'])) {
    $name = $_POST['name'];
    if (empty($name)) {
        $error = "The name field is empty!";
    } elseif (strlen($name) > 25) {
        $error = "The name exceeds the maximum length of 25 characters!";
    } else {
        $insert = "INSERT INTO `category` VALUES (NULL, '$name')";
        $i = mysqli_query($connectSQL, $insert);
        if ($i) {
            $success = "Category successfully added!";
        } else {
            $error = "Failed to add category.";
        }
    }
}

// Untuk form update
$name = "";
$update = false;

// Proses Menampilkan Data untuk Diedit
if (isset($_GET['edit'])) {
    $update = true;
    $id = $_GET['edit'];
    $select = "SELECT * FROM category WHERE id = $id";
    $ss = mysqli_query($connectSQL, $select);
    $row = mysqli_fetch_assoc($ss);
    $name = $row['name']; // Mengisi data lama untuk ditampilkan di form
}

// Proses Update Data
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $id = $_POST['id']; // Mendapatkan ID kategori dari hidden input
    if (empty($name)) {
        $error = "The name field is empty!";
    } elseif (strlen($name) > 25) {
        $error = "The name exceeds the maximum length of 25 characters!";
    } else {
        $updateQ = "UPDATE category SET name = '$name' WHERE id = $id";
        $u = mysqli_query($connectSQL, $updateQ);
        if ($u) {
            $success = "Category successfully updated!";
        } else {
            $error = "Failed to update category.";
        }
    }
}

include "../layouts/header.php";
include "../layouts/sidebar.php";
?>

<main class="app-content">
    <div class="row">
        <div class="col-md-6">
            <div class="tile">
                <?php if ($update): ?>
                    <h3 class="tile-title">Update Category</h3>
                <?php else: ?>
                    <h3 class="tile-title">Add Category</h3>
                <?php endif; ?>

                <!-- Alert Success -->
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Alert Error -->
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="tile-body">
                    <form class="form-horizontal" method="POST" autocomplete="off">
                        <div class="form-group row">
                            <label class="control-label col-md-3">Name Category:</label>
                            <div class="col-md-8">
                                <input class="form-control" value="<?php echo $name; ?>" name="name" type="text" placeholder="Name Category">
                                <?php if ($update): ?>
                                    <!-- Hidden input untuk mengirimkan ID kategori yang sedang diupdate -->
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="tile-footer">
                            <div class="row">
                                <div class="col-md-8 col-md-offset-3">
                                    <?php if ($update): ?>
                                        <button class="btn btn-primary" name="update" type="submit">
                                            <i class="fa fa-fw fa-lg fa-check-circle"></i> Update Data
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-primary" name="send" type="submit">
                                            <i class="fa fa-fw fa-lg fa-check-circle"></i> Add Data
                                        </button>
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
