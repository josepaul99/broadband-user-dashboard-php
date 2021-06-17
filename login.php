<!--  Redirecting to dashboard if logged in -->
<?php session_start();
if(isset($_SESSION['usr']) & isset($_SESSION['pwd']) & isset($_SESSION['isp']) & isset($_SESSION['name']))
{
	header('Location: /myaccount/dashboard.php');	
	exit;	
}
else{
if(!(isset($_POST['username']) & isset($_POST['password']) & isset($_POST['isp'])))
{

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Angamaly Broadband Communications | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="myaccount/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="myaccount/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="myaccount/plugins/iCheck/square/blue.css">
	
	<!-- input validation -->
	<script language="JavaScript">
	function chkLogin()
	{
		doc = document.frmLogin;
		if (doc.isp.value == "" )
		{
			alert("Select ISP");
			
			return false;
		}
		if (doc.username.value == "" )
		{
			alert("Enter User Name");
			doc.username.focus();
			return false;
		}
		if (doc.password.value == "" )
		{
			alert("Enter Password");
			doc.password.focus();
			return false;
		}
		
		
	}
	</script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body   
  class="hold-transition login-page">
  
  
    <div class="login-box">
      <div class="login-logo">
	  
	  
        <img src="./myaccount/dist/img/logo.svg" />
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Sign in to manage your account</p>
        <form name="frmLogin" onsubmit="javascript: return chkLogin()" action="login.php" method="post">
		
		
				  
		<div align="center" style="padding:4px;margin-bottom:7px;">
            <label style="padding-bottom:15px;" class="radio-inline"><input type="radio" class="form-control" label="Dwan" name="isp" value="1"> Dwan</label>
			<label style="padding-bottom:15px;"  class="radio-inline"><input type="radio" class="form-control"  name="isp" value="2"> Kings</label>
	    </div>
		<!-- Displaying error -->
	 <?php
		           if(isset($_GET['err']))
				   {
					   $err = $_GET['err'];
					   if($err == 'los')
						   $color = 'text-green';
					   else
						   $color = 'text-red';
		            echo '<p class="'.$color.'" align="center">';
                  
                    if($err == 'iup')
					echo 'Invalid username / password';
				    else if($err == 'sd')
				    echo 'Sorry, server is down';
				    else if($err == 'ltc' )
				    echo 'Please login to view dashboard';
				    else if($err == 'los')
					echo 'Logged out successfully';
                    echo '</p>';
				   }
		?>	
		 
		  <div class="form-group has-feedback">
			<input type="text" class="form-control" placeholder="Username" name="username" size="12">
			<span class="fa fa-user form-control-feedback"></span>
		  </div>
		  
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Password" name="password" size="12">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
		 
          <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox" name="rem" value="1"> Remember Me
                </label>
              </div>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div><!-- /.col -->
          </div>
        </form>

       

      

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    <script src="myaccount/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="myaccount/bootstrap/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="myaccount/plugins/iCheck/icheck.min.js"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
  </body>

  </html>
  
<?php
}
else{
     
	$lgn_username= $_POST['username'];
	$lgn_password= $_POST['password'];
	$lgn_isp= $_POST['isp'];
	// setting cookie to last longer on remember me
	if(isset($_POST['rem']))
	{
	$params = session_get_cookie_params();
    setcookie(session_name(), $_COOKIE[session_name()], time() + 60*60*24*30, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
	}
    else
	{
	$params = session_get_cookie_params();
    setcookie(session_name(), $_COOKIE[session_name()], time() + 60*60*24*365, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
	}
	if($lgn_isp == 1)
		$lgn_isp = "unify.abcangamaly.in";
	else
		$lgn_isp = "login.abcangamaly.in";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://'.$lgn_isp.'/cgi/login.php');
	curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "txtLogin=".$lgn_username."&txtLoginPass=".$lgn_password."&Submit=Login");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_COOKIESESSION, true);
	curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie-name');//could be empty, but cause problems on some hosts
	curl_setopt($ch, CURLOPT_COOKIEFILE, '/var/www/ip4.x/file/tmp');//could be empty, but cause problems on some hosts
	$answer = curl_exec($ch);
	
	// Checking login details and redirecting to dashboard or throwing error
	if (curl_error($ch)) 
		{
			//server down error
			header('Location: /login.php?err=sd');
			exit;
		 }
	else 
	{
		 //another request preserving the session
		 curl_setopt($ch, CURLOPT_URL, 'http://'.$lgn_isp.'/cgi/index.php');
		 curl_setopt($ch, CURLOPT_POST, false);
		 curl_setopt($ch, CURLOPT_POSTFIELDS, "");
		 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 		 $string = curl_exec($ch);
		 
		
		 if (curl_error($ch)) 
		 {
			 curl_close($ch);
			 //server down error
		     header('Location: /login.php?err=sd');
			 exit;
		 }
		else 
		 {
			curl_close($ch);
			if ((strpos($string, "Personal Account Information") == false) || (strpos($string, "Admin Disabled") != false)) 
			{
				//Invalid username or password error
				header('Location: /login.php?err=iup');
				exit;
			}
			 else
			{
				//Setting session
				$string=strip_tags($string);
				$name = name($string);
				$_SESSION[usr] = $lgn_username;
				$_SESSION[pwd] = $lgn_password;
				$_SESSION[isp] = $lgn_isp;
				$_SESSION[name] = $name;
				
			    //Redirecting to dashboard
				header('Location: /myaccount/dashboard.php');
				exit;
			
			}

		 }

	} 
	 
}
	 
} 
	 
//Retrieving the user's name
function name($string){
		$string = strstr($string, 'Name:');
		$pos = strpos($string, "Created");
		$string = substr($string, 0, $pos);
		$string = str_replace('Name:','',$string);
		$pos = strpos($string, "Hr");
		$string = substr($string,26);
		$string = substr($string,0,-26);
		$string = preg_replace('/\b(\w)/e', 'strtoupper("$1")', $string); 
		$string = str_replace('  ',' ',$string);
		return $string;
	}
	
?>
