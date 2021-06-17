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
            Contact US
            <small>Get in touch</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Contact Us</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <!-- Your Page Content Here -->

          <div class="row">
            <div class="col-md-6">
              <!-- Horizontal Form -->
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title text-info"><i class="fa fa-home"></i> Address</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                <div class="box-body">
                  <h4>
                    Angamaly Broadband Communications<br />
                    Palamattom building <br />
                    Near Cherkottu Furnishing & ACTV Channel<br />
                    East Angadi angamaly<br />
                    Ernakulam<br />
                    Kerala<br />
                    Pincode: 683572<br />
                  </h4>
                </div>
                <!-- /.box-body -->

              </div>
            </div>


            <div class="col-md-6">
              <!-- Horizontal Form -->
              <div class="box box-success">
                <div class="box-header with-border">
                  <h3 class="box-title text-success"><i class="fa fa-envelope-square"></i> Email</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                <div class="box-body">
                  <a href="mailto:angamalybroadband@gmail.com" class="btn btn-block btn-success">angamalybroadband@gmail.com</a>
                </div>
                <!-- /.box-body -->
              </div>


              <!-- Horizontal Form -->
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title text-danger"><i class="fa  fa-phone-square"></i> Phone</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                <div class="box-body">
                  <a href="tel:+919061004545" class="btn btn-block btn-danger">9061004545</a>
                </div>
                <!-- /.box-body -->
              </div>

            </div>
          </div>


          <div class="row">
            <div class="col-md-12">
              <!-- Horizontal Form -->
              <div class="box box-warning">
                <div class="box-header with-border">
                  <h3 class="box-title text-warning"><i class="fa fa-map-marker"></i> Map</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                <div class="box-body">
                  <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
                  <script type="text/javascript">
                    function init_map() {
                      var myOptions = {
                        zoom: 18,
                        center: new google.maps.LatLng(10.188698156171489, 76.39134488046125),
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                      };
                      map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);
                      marker = new google.maps.Marker({
                        map: map,
                        position: new google.maps.LatLng(10.188698156171489, 76.39134488046125)
                      });
                      infowindow = new google.maps.InfoWindow({
                        content: "<b>Angamaly Broadband Communications</b><br/>Near Cherkottu Furnishing and ACTV Channel, East Angadi<br/>683572 Angamaly"
                      });
                      google.maps.event.addListener(marker, "click", function() {
                        infowindow.open(map, marker);
                      });
                      infowindow.open(map, marker);
                    }
                    google.maps.event.addDomListener(window, 'load', init_map);
                  </script>
                  <style>
                    #gmap_canvas img {
                      max-width: none !important;
                      background: none !important
                    }
                  </style>

                  <div style="overflow:hidden;height:300px;width:100%;">
                    <div id="gmap_canvas" style="height:300px;width:100%;"></div>
                  </div>

                </div>
                <!-- /.box-body -->
              </div>
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
} else {
  header('Location: /login.php?err=ltc');
  exit;
}
?>