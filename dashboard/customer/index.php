<?php
include_once  "../../init.php";
include "../../genral/config.php";

// Query to select all customers
$select = "SELECT * FROM customers"; // Replace 'admins' with 'customers'
$s = mysqli_query($connectSQL, $select);

// Deleting a customer based on ID
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete = "DELETE FROM customers WHERE id = $id";
    mysqli_query($connectSQL, $delete);
    header("location: $root_path/dashboard/customer/index.php");
}

include "../../genral/functions.php";
include "../layouts/header.php";
include "../layouts/sidebar.php";
?>
<main class="app-content">
    <div class="col-md-12">
        <div class="tile">
            <h3 class="tile-title">Display Customers</h3>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Address</th>
                            <th scope="col">Image</th>
                            <th scope="col" colspan="2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($s as $data) { ?>
                        <tr>
                            <th scope="row"><?php echo $data['id']; ?></th>
                            <td><?php echo $data['name']; ?></td>
                            <td><?php echo $data['email']; ?></td>
                            <td><?php echo $data['phone']; ?></td>
                            <td><?php echo $data['address']; ?></td>
                            <td><img src="<?php echo $root_path . '/user/upload_user_image/' . $data['image_user']; ?>" alt="Profile" style="width: 50px; height: 50px;"></td>
                            <td>
                                <a href="<?php echo $root_path; ?>/dashboard/customer/index.php?delete=<?php echo $data['id']; ?>" class="btn btn-danger">Remove</a>
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
