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
  include 'table_retriever.php';
  if (isset($_POST['frmDay']) & isset($_POST['frmMonth']) & isset($_POST['frmYear']) & isset($_POST['toDay']) & isset($_POST['toMonth']) & isset($_POST['toYear'])) {
    $frmArr[0] = $_POST['frmDay'];
    $frmArr[1] = $_POST['frmMonth'];
    $frmArr[2] = $_POST['frmYear'];
    $toArr[0] = $_POST['toDay'];
    $toArr[1] = $_POST['toMonth'];
    $toArr[2] = $_POST['toYear'];
    $json = usage($_SESSION['usr'], $_SESSION['pwd'], $_SESSION['isp'], $frmArr, $toArr);
    if ($json == '1') {
      header('Location: /login.php?err=sd');
      exit;
    } else if ($json == '2') {
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
        <script src="./plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
        <link href="./plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
      </head>

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

      <body class="hold-transition skin-blue sidebar-mini sidebar-collapse fixed">
        <div class="wrapper">

          <!-- Main Header -->
          <?php include 'jose_header.php'; ?>


          <!-- Left side column. contains the logo and sidebar -->
          <?php include 'jose_sidebar.php'; ?>

          <!-- Content Wrapper. Contains page content -->
          <div class="content-wrapper">
            <section class="content-header">
              <h1>
                Usage Report
                <small>Usage report for the active/last recharge</small>
              </h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Usage Report</li>
              </ol>
            </section>

            <!-- Main content -->
            <section class="content">
              <!-- Page Content -->

              <?php
              if ($json == 3) {
              ?>
                <div class="alert alert-danger">
                  <h4><i class="icon fa fa-ban"></i>Error, No Reports Found!</h4>
                </div>
              <?php
              } else {
              ?>

                <div class="box box-info" style="overflow-y: hidden;">
                  <div class="box-header">
                    <h3 class="box-title">Showing Reports from <?php echo $frmArr[0] . "/" . $frmArr[1] . "/" . $frmArr[2]  ?> to <?php echo $toArr[0] . "/" . $toArr[1] . "/" . $toArr[2]  ?></h3>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body no-padding" style="overflow-x:scroll;">
                    <?php
                    echo $json;
                    ?>
                  </div>
                </div>

              <?php
              }
              ?>


            </section><!-- /.content -->
          </div><!-- /.content-wrapper -->



          <!-- Main Footer -->
          <?php include 'jose_footer.php'; ?>
          <!-- REQUIRED JS SCRIPTS -->

          <!-- jQuery 2.1.4 -->
          <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
          <!-- Bootstrap 3.3.5 -->
          <script src="bootstrap/js/bootstrap.min.js"></script>
          <!-- AdminLTE App -->
          <script src="dist/js/app.min.js"></script>

          <!-- Optionally, you can add Slimscroll and FastClick plugins.
         Both of these plugins are recommended to enhance the
         user experience. Slimscroll is required when using the
         fixed layout. -->
      </body>

      </html>

    <?php
    }
  } //if ends is set post
  else {
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
      <script src="./plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
      <link href="./plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
      <link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">

      <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    </head>

    <body class="hold-transition skin-blue sidebar-mini sidebar-collapse fixed">
      <div class="wrapper">

        <!-- Main Header -->
        <?php include 'jose_header.php'; ?>
        <!-- Left side column. contains the logo and sidebar -->
        <?php include 'jose_sidebar.php'; ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
          <section class="content-header">
            <h1>
              Usage Report
              <small>Usage report for the active/last recharge</small>
            </h1>
            <ol class="breadcrumb">
              <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
              <li class="active">Usage Report</li>
            </ol>
          </section>

          <!-- Main content -->
          <section class="content">
            <!-- Page Content -->
            <div class="col-md-6">
              <!-- Horizontal Form -->
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Select Date Range</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" action="<?php echo $path; ?>usage.php" method="post">
                  <div class="box-body">


                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">From</label>

                      <div class="col-sm-2">

                        <select class="form-control" name="frmDay">
                          <?php
                          for ($i = 1; $i <= 31; $i++) {
                            if ($i < 10)
                              $i = "0" . $i;

                            echo '<option value="' . $i . '">' . $i . '</option>';
                          }

                          ?>
                        </select>

                      </div>
                      <div class="col-sm-3">
                        <select class="form-control" name="frmMonth">
                          <?php
                          $m = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
                          $date = getdate();

                          for ($i = 1; $i <= 12; $i++) {
                            $sel = "";
                            if ($i == $date['mon'])
                              $sel = 'selected';
                            if ($i < 10)
                              $i = "0" . $i;
                            echo '<option ' . $sel . ' value="' . $i . '">' . $m[$i - 1] . '</option>';
                          }

                          ?>
                        </select>
                      </div>
                      <div class="col-sm-3">
                        <select class="form-control" name="frmYear">
                          <?php


                          for ($i = 2011; $i <= $date['year']; $i++) {
                            $sel = "";
                            if ($date['mon'] != 1) {
                              if ($i == $date['year'])
                                $sel = 'selected';
                            } else {
                              if ($i == $date['year'] - 1)
                                $sel = 'selected';
                            }



                            echo '<option ' . $sel . ' value="' . $i . '">' . $i . '</option>';
                          }

                          ?>
                        </select>
                      </div>
                    </div>


                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-2 control-label">To</label>

                      <div class="col-sm-2">

                        <select class="form-control" name="toDay">
                          <?php

                          for ($i = 1; $i <= 31; $i++) {
                            $sel = "";
                            if ($i == $date['mday'])
                              $sel = 'selected';
                            if ($i < 10)
                              $i = "0" . $i;

                            echo '<option ' . $sel . ' value="' . $i . '">' . $i . '</option>';
                          }

                          ?>
                        </select>

                      </div>
                      <div class="col-sm-3">
                        <select class="form-control" name="toMonth">
                          <?php
                          $m = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");


                          for ($i = 1; $i <= 12; $i++) {
                            $sel = "";
                            if ($i == $date['mon'])
                              $sel = 'selected';
                            if ($i < 10)
                              $i = "0" . $i;


                            echo '<option ' . $sel . ' value="' . $i . '">' . $m[$i - 1] . '</option>';
                          }

                          ?>
                        </select>
                      </div>
                      <div class="col-sm-3">
                        <select class="form-control" name="toYear">
                          <?php


                          for ($i = 2011; $i <= $date['year']; $i++) {
                            $sel = "";

                            if ($i == $date['year'])
                              $sel = 'selected';

                            echo '<option ' . $sel . ' value="' . $i . '">' . $i . '</option>';
                          }

                          ?>
                        </select>
                      </div>
                    </div>

                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" class="btn btn-info pull-right">Submit</button>
                  </div>
                  <!-- /.box-footer -->
                </form>
              </div>
              <!-- /.box -->

          </section><!-- /.content -->
        </div><!-- /.content-wrapper -->

        <!-- Main Footer -->
        <?php include 'jose_footer.php'; ?>

        <!-- REQUIRED JS SCRIPTS -->

        <!-- jQuery 2.1.4 -->
        <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <!-- AdminLTE App -->
        <script src="dist/js/app.min.js"></script>

        <!-- Optionally, you can add Slimscroll and FastClick plugins.
         Both of these plugins are recommended to enhance the
         user experience. Slimscroll is required when using the
         fixed layout. -->
    </body>

    </html>

<?php

  }
} else {
  header('Location: /login.php?err=ltc');
  exit;
}
?>