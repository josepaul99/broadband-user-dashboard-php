<?php

function usage($usr, $pass, $isp, $from, $to)
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
		 curl_setopt($ch, CURLOPT_URL, 'http://'.$isp.'/cgi/indiusage.php?DayFrom='.$from[0].'&MonthFrom='.$from[1].'&YearFrom='.$from[2].'&DayTo='.$to[0].'&MonthTo='.$to[1].'&YearTo='.$to[2].'&usage=Check+Usage&UsageReport=1');
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
		    else if(strpos($string, "No records found"))
			{
				$ans = "3";
			}
			else
			{
				$pos = strpos($string, "<tr align=\"center\">");
				$string = substr($string,$pos);
				$pos = strpos($string, "<td colspan=10 class=\"menuLink\" align=left>");
				$string = substr($string,0,$pos);
				$pos = strpos($string, "Session Time</a></th>");
				$string = substr($string,$pos);
				$string = str_replace("Session Time</a></th>",'',$string);
				$string = str_replace_first('</tr>', '', $string); 
				$string = str_lreplace('<tr align="center">','', $string); 
				$string = preg_replace("/[^A-Za-z0-9 <>\/\"\'\.=:]/", "", $string);
				$string = str_replace("nbsp",'',$string);
				$string = str_replace('right','left',$string);
				$string='<table class="table table-striped">
                <tr>
                  <td><b>#</b></td>
                  <td><b>IP Address</b></td>
                  <td><b>Login Time</b></td>
                  <td><b>Logout time</b></td>
				  <td><b>Download [MB]</b></td>
				  <td><b>Upload [MB]</b></td>
				  <td><b>Total [MB]</b></td>
				  <td><b>Session Time</b></td>
                </tr>'.$string.'</table>';
                $string = str_replace_first('<strong>', '<span class="badge bg-aqua">', $string);
 				$string = str_replace_first('</strong>', '</span>', $string);
				 $string = str_replace_first('<strong>', '<span class="badge bg-green">', $string);
 				$string = str_replace_first('</strong>', '</span>', $string);
				 $string = str_replace_first('<strong>', '<span class="badge bg-orange">', $string);
 				$string = str_replace_first('</strong>', '</span>', $string);
				 $string = str_replace_first('<strong>', '<span class="badge bg-red">', $string);
 				$string = str_replace_first('</strong>', '</span>', $string);
				$string = str_replace_first('<td colspan=4>', '<td>Tot.</td><td></td><td></td><td>', $string);
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
	
	
	function format_json($json, $html = false, $tabspaces = null)
    {
        $tabcount = 0;
        $result = '';
        $inquote = false;
        $ignorenext = false;

        if ($html) {
            $tab = str_repeat("&nbsp;", ($tabspaces == null ? 4 : $tabspaces));
            $newline = "<br/>";
        } else {
            $tab = ($tabspaces == null ? "\t" : str_repeat(" ", $tabspaces));
            $newline = "\n";
        }

        for($i = 0; $i < strlen($json); $i++) {
            $char = $json[$i];
            if ($ignorenext) {
                $result .= $char;
                $ignorenext = false;
            } else {
                switch($char) {
                    case ':':
                        $result .= $char . (!$inquote ? " " : "");
                        break;
                    case '{':
                        if (!$inquote) {
                            $tabcount++;
                            $result .= $char . $newline . str_repeat($tab, $tabcount);
                        }
                        else {
                            $result .= $char;
                        }
                        break;
                    case '}':
                        if (!$inquote) {
                            $tabcount--;
                            $result = trim($result) . $newline . str_repeat($tab, $tabcount) . $char;
                        }
                        else {
                            $result .= $char;
                        }
                        break;
                    case ',':
                        if (!$inquote) {
                            $result .= $char . $newline . str_repeat($tab, $tabcount);
                        }
                        else {
                            $result .= $char;
                        }
                        break;
                    case '"':
                        $inquote = !$inquote;
                        $result .= $char;
                        break;
                    case '\\':
                        if ($inquote) $ignorenext = true;
                        $result .= $char;
                        break;
                    default:
                        $result .= $char;
                }
            }
        }

        return $result;
    }