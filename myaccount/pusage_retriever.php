<!-- Retrieve the previous usage data -->
<?php
function prevusage($usr, $pass, $isp)
{
	//initial request with login data
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://'.$isp.'/cgi/login.php');
	curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "txtLogin=".$usr."&txtLoginPass=".$pass."&Submit=Login");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_COOKIESESSION, true);
	curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie-name');//could be empty, but cause problems on some hosts
	curl_setopt($ch, CURLOPT_COOKIEFILE, '/var/www/ip4.x/file/tmp');//could be empty, but cause problems on some hosts
	$answer = curl_exec($ch);
	
	if (curl_error($ch)) 
		$ans = "1";
	else 
	{
		 //another request preserving the session
		 curl_setopt($ch, CURLOPT_URL, 'http://'.$isp.'/cgi/prevusage.php');
		 curl_setopt($ch, CURLOPT_POST, false);
		 curl_setopt($ch, CURLOPT_POSTFIELDS, "");
		 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 		 $string = curl_exec($ch);
		
		 if (curl_error($ch)) 
		    	$ans = "1";
		else 
		 {
			curl_close($ch);
			if (strpos($string, "Reset") != false) 
			  $ans = "2";
			else
			{

				$pos = strpos($string, "CLASS=\"tblData\">");
				$string = substr($string,$pos);
				$string = str_replace("CLASS=\"tblData\">",'',$string);
				$pos = strpos($string, "</table>");
				$string = substr($string,0,$pos);
				$pos = strpos($string, "</tr>");
				$string = substr($string,$pos,-1);
				$string = str_replace_first('</tr>', '', $string); 
				$string = str_replace('align="center"> <A class=menuLink href=javascript:userlink("udetails.php?serial=','alt="',$string);
				$string = str_replace('")','"',$string);
				$string = str_replace('div','font',$string);
				
				
				
				if($isp == "unify.abcangamaly.in")
				{
				$string='<table class="table table-striped">
                <tr>
                  <td><b>#</b></td>
                  <td><b>Expired PIN Serial</b></td>
				  <td><b>Plan Name</b></td>
                  <td><b>Start Period</b></td>
                  <td><b>End Period</b></td>
				  <td><b>Total MB</b></td>
				  <td><b>Total Hours</b></td>
                </tr>'.$string.'</table>';
				}
				else if($isp == "login.abcangamaly.in")
				{
					$string='<table class="table table-striped">
                <tr>
                  <td><b>#</b></td>
                  <td><b>Expired PIN Serial</b></td>
				  <td><b>Plan Name</b></td>
                  <td><b>Start Period</b></td>
                  <td><b>End Period</b></td>
				  <td><b>Total MB</b></td>
				  <td><b>Total Hours</b></td>
                </tr>'.$string.'</table>';					
				}

				$ans = $string;	
			}

		 }

	}

	return $ans;
}
	
	
	
function str_replace_first($from, $to, $subject)
{
$from = '/'.preg_quote($from, '/').'/';
return preg_replace($from, $to, $subject, 1);
}

function str_lreplace($search, $replace, $subject)
{
    $pos = strrpos($subject, $search);
    if($pos !== false)
    {
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
    }
    return $subject;
}
