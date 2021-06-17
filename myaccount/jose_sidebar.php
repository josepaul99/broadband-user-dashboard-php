 <aside class="main-sidebar">
   <section class="sidebar">
     <div class="user-panel">
       <div class="pull-left image">
         <img src="dist/img/acc.png" class="img-circle" alt="User Image">
       </div>
       <div class="pull-left info">
         <!-- Print user's name -->
         <p>Hi, <?php echo $_SESSION['name']; ?></p>
         <!-- Print username -->
         <font size="2px" color="#aeaeae"> <?php echo $_SESSION['usr']; ?> </font>
       </div>
     </div>


     <!-- Sidebar Menu with current page list active-->
     <ul class="sidebar-menu">
       <li class="header">MENU</li>
       <li <?php if (basename($_SERVER['PHP_SELF']) == 'dashboard.php') echo 'class="active"' ?>><a href="<?php echo $path; ?>dashboard.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
       <li <?php if (basename($_SERVER['PHP_SELF']) == 'usage.php') echo 'class="active"' ?>><a href="<?php echo $path; ?>usage.php"><i class="fa fa-area-chart"></i> <span>Usage Report</span></a></li>
       <li <?php if (basename($_SERVER['PHP_SELF']) == 'pusage.php') echo 'class="active"' ?>><a href="<?php echo $path; ?>pusage.php"><i class="fa fa-history"></i> <span>Previous Usage</span></a></li>
       <li <?php if (basename($_SERVER['PHP_SELF']) == 'contact.php') echo 'class="active"' ?>><a href="<?php echo $path; ?>contact.php"><i class="fa fa-envelope"></i> <span>Contact Us</span></a></li>
     </ul><!-- /.sidebar-menu -->
   </section>
   <!-- /.sidebar -->
 </aside