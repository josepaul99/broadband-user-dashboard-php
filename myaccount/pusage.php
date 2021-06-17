<?php
session_start();
if (isset($_SESSION['usr']) & isset($_SESSION['pwd']) & isset($_SESSION['isp']) & isset($_SESSION['name'])) {

  if (isset($_COOKIE[session_name()])) {
    $params = session_get_cookie_params();
    setcookie(session_name(), $_COOKIE[session_name()], time() + 60 * 60 * 24 * 365, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
  }
  // either new or old, it should live at most for another hour
  $now = time();
  $_SESSION['discard_after'] = $now + 31536000;

  include 'pusage_retriever.php';
  $pusage_table = prevusage($_SESSION['usr'], $_SESSION['pwd'], $_SESSION['isp']);
  if ($pusage_table == '1') {
    header('Location: /login.php?err=sd');
    exit;
  } else if ($pusage_table == '2') {
    header('Location: /login.php?err=iup');
    exit;
  } else {

?>
    <!DOCTYPE html>
    <html>

    <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>Angamaly Broadband Communication</title>
      <!-- Tell the browser to be responsive to screen width -->
      <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
      <!-- Bootstrap 3.3.5 -->
      <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
      <!-- Ionicons -->
      <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
      <!-- Theme style -->
      <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
      <link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">

      <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->




      <style type="text/css">
        .table>thead:first-child>tr:first-child>th:first-child {
          position: absolute;
          display: inline-block;
          background-color: #f9f9f9;
          height: 100%;
          width: 40px;
        }

        .table>tbody>tr>td:first-child {
          position: absolute;
          display: inline-block;
          background-color: #f9f9f9;
          height: 100%;
          margin-left: 0px !important;
          width: 40px;

        }

        .table>thead:first-child>tr:first-child>th:nth-child(2) {
          padding-left: 50px;
        }

        .table>tbody>tr>td:nth-child(2) {
          padding-left: 50px !important;

        }
      </style>
    </head>

    <body class="hold-transition skin-blue sidebar-mini sidebar-collapse fixed">
      <div class="wrapper">

        <!-- Main Header -->
        <?php include 'jose_header.php'; ?>


        <!-- Left side column. contains the logo and sidebar -->
        <?php include 'jose_sidebar.php'; ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
          <!-- Content Header (Page header) -->
          <section class="content-header">
            <h1>
              Previous Usage
              <small>Old recharges and data usages</small>
            </h1>
            <ol class="breadcrumb">
              <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
              <li class="active">Previous Usage</li>
            </ol>
          </section>

          <!-- Main content -->
          <section class="content">
            <!-- Page Content -->
            <div class="box box-info" style="overflow-y: hidden;">
              <div class="box-header">
                <h3 class="box-title">Previous Usage Details</h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body no-padding" style="overflow-x:scroll;">
                <?php
                echo $pusage_table;
                ?>
              </div>
            </div>

          </section><!-- /.content -->
        </div><!-- /.content-wrapper -->



        <!-- Main Footer -->
        <?php include 'jose_footer.php'; ?>


        <!-- Create the tabs -->



        <!-- REQUIRED JS SCRIPTS -->

        <!-- jQuery 2.1.4 -->
        <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <!-- AdminLTE App -->
        <script src="dist/js/app.min.js"></script>
    </body>

    </html>

<?php
  }
} else {
  header('Location: /login.php?err=ltc');
  exit;
}
?>