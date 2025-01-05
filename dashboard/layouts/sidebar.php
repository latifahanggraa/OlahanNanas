<!-- Sidebar menu -->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
  <div class="app-sidebar__user">
    <img class="app-sidebar__user-avatar" style="width: 60px;" src="../image/user.png" alt="User Image">
    <div>
      <p class="app-sidebar__user-name"><?php echo $_SESSION['adminName']; ?></p>
      <p class="app-sidebar__user-designation"><?php echo $_SESSION['admin']; ?></p>
    </div>
  </div>
  <ul class="app-menu">
    <li>
      <a class="app-menu__item active" href="<?php echo $root_path ?>/dashboard/">
        <i class="app-menu__icon fa fa-dashboard"></i>
        <span class="app-menu__label">Dashboard</span>
      </a>
    </li>
    <li>
      <a class="app-menu__item" href="<?php echo $root_path ?>/dashboard/admin/">
        <i class="app-menu__icon fa fa-laptop"></i>
        <span class="app-menu__label">Admins</span>
      </a>
    </li>
    <li>
      <a class="app-menu__item" href="<?php echo $root_path ?>/dashboard/orderCustomer/">
        <i class="app-menu__icon fa fa-pie-chart"></i>
        <span class="app-menu__label">Orders</span>
      </a>
    </li>
    <li>
      <a class="app-menu__item" href="<?php echo $root_path ?>/dashboard/category/">
        <i class="app-menu__icon fa fa-folder"></i>
        <span class="app-menu__label">Category</span>
      </a>
    </li>
    <li>
      <a class="app-menu__item" href="<?php echo $root_path ?>/dashboard/product/">
        <i class="app-menu__icon fa fa-shopping-cart"></i>
        <span class="app-menu__label">Product</span>
      </a>
    </li>
    <li>
      <a class="app-menu__item" href="<?php echo $root_path ?>/dashboard/customer/">
        <i class="app-menu__icon fa fa-users"></i>
        <span class="app-menu__label">Customers</span>
      </a>
    </li>
    <li>
    <a class="app-menu__item" href="<?php echo $root_path ?>/dashboard/blogs/">
      <i class="app-menu__icon fa fa-comment"></i>
      <span class="app-menu__label">Blog</span>
    </a>
    </li>
    <li>
      <a class="app-menu__item" href="<?php echo $root_path ?>/dashboard/salesReport/">
        <i class="app-menu__icon fa fa-file"></i>
        <span class="app-menu__label">Report</span>
      </a>
    </li>
  </ul>
</aside>
