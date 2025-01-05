<?php
include_once  "../../init.php";
include "../../genral/config.php";

// Menggunakan VIEW untuk mendapatkan data produk
$slecet = "SELECT * FROM view_product_details";
$s = mysqli_query($connectSQL, $slecet);

// Penerapan Commit & Rollback
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Mulai transaksi
    mysqli_begin_transaction($connectSQL);

    try {
        // Hapus data produk
        $delete = "DELETE FROM product WHERE id = ?";
        $stmt = mysqli_prepare($connectSQL, $delete);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);

        // Jika penghapusan berhasil, commit transaksi
        mysqli_commit($connectSQL);

        // Redirect setelah penghapusan
        header("Location: $root_path/dashboard/product/index.php");
        exit;
    } catch (Exception $e) {
        // Jika terjadi kesalahan, rollback transaksi
        mysqli_rollback($connectSQL);

        // Log error atau tampilkan pesan
        echo "Error: " . $e->getMessage();
    }
}

include "../../genral/functions.php";
include "../layouts/header.php";
include "../layouts/sidebar.php";
?>

<main class="app-content">
    <div class="col-md-12">
        <div class="tile">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="tile-title">Display Products</h3>
                <!-- Tombol Tambah Produk -->
                <a href="<?php echo $root_path ?>/dashboard/product/add.php" class="btn btn-primary">Tambah Produk</a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">descriptions</th>
                        <th scope="col">price(Rp)</th>
                        <th scope="col">category</th>
                        <th scope="col">image</th>
                        <th scope="col" colspan="2">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($s as $data){ ?>
                        <tr>
                            <td><?php echo $data['title']; ?></td>
                            <td><?php echo $data['descriptions']; ?></td>
                            <td><?php echo number_format($data['price'], 0, ',', '.'); ?></td>
                            <td><?php echo $data['categoryName']; ?></td>
                            <td><img style="width:50px;" src="./upload/<?php echo $data['image']; ?>" alt=""></td>
                            <td>
                                <a href="<?php echo $root_path ?>/dashboard/product/add.php?edit=<?php echo $data['id']; ?>" class="btn btn-info">Edit</a>
                            </td>
                            <td>
                                <a href="<?php echo $root_path ?>/dashboard/product/index.php?delete=<?php echo $data['id']; ?>" class="btn btn-danger">remove</a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<?php
include "../layouts/footer.php";
