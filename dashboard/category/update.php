<?php 
include_once "../../init.php";
include "../../genral/functions.php";
include "../../genral/config.php";

$success = ""; // Untuk pesan sukses
$error = "";   // Untuk pesan gagal
$name = "";    // Placeholder untuk nama kategori

// Cek apakah parameter `edit` ada di URL
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    // Query untuk mendapatkan data kategori berdasarkan ID
    $select = "SELECT * FROM category WHERE id = $id";
    $ss = mysqli_query($connectSQL, $select);

    // Jika data ditemukan
    if ($ss && mysqli_num_rows($ss) > 0) {
        $row = mysqli_fetch_assoc($ss);
        $name = $row['name'];
    } else {
        $error = "Category not found or invalid ID.";
    }
} else {
    $error = "No category ID specified for update.";
}

// Proses Update Data
if (isset($_POST['update'])) {
    $id = $_POST['id']; // Ambil ID kategori dari hidden input
    $name = $_POST['name'];

    if (empty($name)) {
        $error = "The name field is empty!";
    } elseif (strlen($name) > 25) {
        $error = "The name exceeds the maximum length of 25 characters!";
    } else {
        $updateQ = "UPDATE category SET name = '$name' WHERE id = $id";
        $u = mysqli_query($connectSQL, $updateQ);
        if ($u) {
            $success = "Category successfully updated!";
            header("Location: $root_path/dashboard/category/index.php");
            exit; // Pastikan berhenti setelah redirect
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
                <h3 class="tile-title">Update Category</h3>

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
                                <!-- Hidden input untuk mengirimkan ID kategori yang sedang diupdate -->
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                            </div>
                        </div>
                        <div class="tile-footer">
                            <div class="row">
                                <div class="col-md-8 col-md-offset-3">
                                    <button class="btn btn-primary" name="update" type="submit">
                                        <i class="fa fa-fw fa-lg fa-check-circle"></i> Update Data
                                    </button>
                                    <a href="<?php echo $root_path ?>/dashboard/category/index.php" class="btn btn-secondary">
                                        <i class="fa fa-fw fa-lg fa-arrow-left"></i> Back
                                    </a>
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
