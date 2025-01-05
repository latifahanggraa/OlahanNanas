<?php
include_once "../../init.php";
include "../../genral/functions.php";
include "../../genral/config.php";

// Query to get blog data along with customer names
$select = "SELECT blog.blogId, blog.message, customers.name FROM blog 
           JOIN customers ON blog.customerId = customers.id";
$s = mysqli_query($connectSQL, $select);

// Handle delete action for blog
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete = "DELETE FROM blog WHERE blogId = $id";
    mysqli_query($connectSQL, $delete);
    header("location: $root_path/dashboard/blog/index.php");
}

include "../layouts/header.php";
include "../layouts/sidebar.php";
?>
<main class="app-content">
    <div class="col-md-12">
        <div class="tile">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="tile-title">Display Blog Comments</h3>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Blog ID</th>
                            <th scope="col">Customer Name</th>
                            <th scope="col">Message</th>
                            <th scope="col" colspan="2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($s as $data) { ?>
                        <tr>
                            <th scope="row"><?php echo $data['blogId']; ?></th>
                            <td><?php echo htmlspecialchars($data['name']); ?></td>
                            <td><?php echo htmlspecialchars($data['message']); ?></td>
                            <td>
                                <a href="<?php echo $root_path ?>/dashboard/blog/index.php?delete=<?php echo $data['blogId']; ?>" class="btn btn-danger">Remove</a>
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
?>
