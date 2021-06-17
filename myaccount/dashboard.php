<?php
//checks login status
session_start();
if (isset($_SESSION['usr']) & isset($_SESSION['pwd']) & isset($_SESSION['isp']) & isset($_SESSION['name'])) {
  if (isset($_COOKIE[session_name()])) {
    $params = session_get_cookie_params();
    setcookie(session_name(), $_COOKIE[session_name()], time() + 60 * 60 * 24 * 365, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
  }
  // either new or old, it should live at most for another hour
  $now = time();
  $_SESSION['discard_after'] = $now + 31536000;

  //Retrieve the data to be displayed in JSON format from retrieve method
  include 'retriever.php';
  $json = retrieve($_SESSION['usr'], $_SESSION['pwd'], $_SESSION['isp']);
  if ($json == '1') {
    header('Location: /login.php?err=sd');
    exit;
  } else if ($json == '2') {
    header('Location: /login.php?err=iup');
    exit;
  } else {
    $json = json_decode($json, true);
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
      <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
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
          <!-- Content Header (Page header) -->
          <section class="content-header">
            <h1>
              Dashboard
              <small>View your account</small>
            </h1>
            <ol class="breadcrumb">
              <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
              <li class="active">Dashboard</li>
            </ol>
          </section>

          <!-- Main content -->
          <section class="content">
            <!--  Page Content  -->
            <?php
            //Display plan validity / FUP expiry notification
            if ($json['acc_stat'] == 0) {
            ?>
              <div class="callout callout-danger">
                <h4><i class="fa fa-ban"></i> Your data plan is expired. Please recharge to continue enjoying our service.</h4>
              </div>
              <?php
            } else {
              if (($json['usage'] > $json['pldata']) && $json['plname'] != 'UDNIGHT2MBPS') {
              ?>
                <div class="callout callout-warning">
                  <h4><i class="fa fa-warning"></i> Your high speed data is exhausted, recharge to continue browsing at high speed.</h4>
                </div>
              <?php
              } else if (($json['usage'] < $json['pldata']) && $json['plname'] != 'UDNIGHT2MBPS') {
              ?>
                <div class="callout callout-success">
                  <h4><i class="fa fa-check"></i> Your are now browsing at high speed.</h4>
                </div>
            <?php
              }
            }
            ?>

            <div class="row">
              <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                  <div class="inner">
                    <h3><?php echo $json['usage']; ?><sup style="font-size: 20px"> GB</sup></h3>

                    <p>Used</p>
                  </div>
                  <div class="icon">
                    <i class="fa fa-download"></i>
                  </div>
                  <span class="small-box-footer">
                    Data Usage
                  </span>
                </div>
              </div>
              <!-- ./col -->
              <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                  <div class="inner">
                    <h3><?php echo $json['pldata']; ?><sup style="font-size: 20px"> GB</sup></h3>

                    <p>Alloted</p>
                  </div>
                  <div class="icon">
                    <i class="fa  fa-globe"></i>
                  </div>
                  <span class="small-box-footer">
                    High-Speed Data
                  </span>
                </div>
              </div>
              <!-- ./col -->
              <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                  <div class="inner">
                    <h3><?php echo $json['usd']; ?></h3>

                    <p>Days</p>
                  </div>
                  <div class="icon">
                    <i class="fa fa-hourglass-3"></i>
                  </div>
                  <span class="small-box-footer">
                    Days Used
                  </span>
                </div>
              </div>
              <!-- ./col -->
              <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                  <div class="inner">
                    <h3><?php echo $json['rem']; ?></h3>

                    <p>Days</p>
                  </div>
                  <div class="icon">
                    <i class="fa fa-hourglass-1"></i>
                  </div>
                  <span class="small-box-footer">
                    Days Left
                  </span>
                </div>
              </div>
              <!-- ./col -->
            </div>


            <div class="col-md-16">
              <!-- Widget: user widget style 1 -->
              <div class="box box-widget widget-user">
                <div class="widget-user-header bg-aqua-active">
                  <h3 class="widget-user-username"><?php echo $_SESSION['name']; ?></h3>
                  <h5 class="widget-user-desc"><?php echo $_SESSION['usr']; ?></h5>
                </div>
                <div class="widget-user-image">
                  <img class="img-circle" src="./dist/img/acc.png" alt="User Avatar">
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-4 border-right">
                      <div class="description-block">
                        <h5 class="description-header">LOGIN STATUS</h5>
                        <span class="description-text">
                          <p class="text-aqua"><b><?php if ($json['log_stat'] == 1) echo "IN";
                                                  else echo "OUT"; ?></b></p>
                        </span>
                      </div>
                      <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-4 border-right">
                      <div class="description-block">
                        <h5 class="description-header">ACCOUNT STATUS</h5>
                        <span class="description-text"><?php if ($json['acc_stat'] == 1) echo '<p class="text-green"><b><i class="fa fa-check-circle"></i> ACTIVE</b></p>';
                                                        else echo '<p class="text-red"><b><i class="fa fa-ban"></i> DISABLED</b></p>'; ?></span>
                      </div>
                      <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-4">
                      <div class="description-block">
                        <h5 class="description-header">PLAN NAME</h5>
                        <span class="description-text">
                          <p class="text-aqua"><b><?php echo $json['plname']; ?></b></p>
                        </span>
                      </div>
                      <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                  </div>
                  <!-- /.row -->
                  </ul>
                </div>
              </div>
            </div>
            <!-- /.widget-user -->





            <div class="row">
              <div class="col-lg-3 col-xs-6">
                <!-- small box -->

                <div class="small-box bg-aqua">
                  <div class="inner">
                    <h3 style="font-size:25px!important;"><?php echo $json['ron']; ?></h3>
                  </div>

                  <span class="small-box-footer">
                    Renewed
                  </span>
                </div>
              </div><!-- ./col -->


              <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                  <div class="inner">
                    <h3 style="font-size:25px!important;"><?php echo $json['roff']; ?></h3>
                  </div>
                  <span class="small-box-footer">
                    Expires
                  </span>
                </div>
              </div>


              <!-- ./col -->
              <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                  <div class="inner">
                    <h3 style="font-size:25px!important;"><?php echo $json['altd']; ?></h3>
                  </div>
                  <span class="small-box-footer">
                    Validity
                  </span>
                </div>
              </div>
              <!-- ./col -->


              <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                  <div class="inner">
                    <h3 style="font-size:25px!important;"><?php if($_SESSION['isp'] == 'unify.abcangamaly.in') echo "Dwan"; else echo "Kings"; ?></h3>
                  </div>
                  <span class="small-box-footer">
                    ISP
                  </span>
                </div>
              </div>
              <!-- ./col -->
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