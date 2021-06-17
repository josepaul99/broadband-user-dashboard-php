<?php
function retrieve($user, $pass, $isp)
{
	//initial request with login data
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://'.$isp.'/cgi/login.php');
	curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "txtLogin=".$user."&txtLoginPass=".$pass."&Submit=Login");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_COOKIESESSION, true);
	curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie-name');//could be empty, but cause problems on some hosts
	curl_setopt($ch, CURLOPT_COOKIEFILE, '/var/www/ip4.x/file/tmp');//could be empty, but cause problems on some hosts
	$answer = curl_exec($ch);
	
	if (curl_error($ch)) 
		$ans = 1;
	else 
	{
		 //another request preserving the session
		 curl_setopt($ch, CURLOPT_URL, 'http://'.$isp.'/cgi/index.php');
		 curl_setopt($ch, CURLOPT_POST, false);
		 curl_setopt($ch, CURLOPT_POSTFIELDS, "");
		 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 		 $string = curl_exec($ch);
		
		 if (curl_error($ch)) 
		 {
			 curl_close($ch);
		    	$ans = '1';
		 }
		else 
		 {
			curl_close($ch);
			if (strpos($string, "Reset") != false) 
			  $ans = '2';
			else
			{
				$temp = $string;
				$string=strip_tags($string);
				
				$name = name($string);
                $ron = ron($string);
				$roff = roff($string);
				$plname = plname($string);
				$pldata = pldata($plname);
				$acc_stat = acc_stat($string);
				$log_stat = log_stat($string);
				$data = get_usage_array(get_usage_table($temp));
				$altd = $data[0];	
                $usd = $data[1];
                $rem = $data[2];
				$used_time=$data[7];
				$pos= strpos($used_time,":");
				$used_time = substr($used_time, 0,$pos);
				$used_time = $used_time." Hours";
				$usage = round(($data[4]/1024),1);
		
				$arr = array('name' => $name, 'ron' => $ron, 'roff' => $roff, 'plname' => $plname, 'pldata' => $pldata, 'acc_stat' => $acc_stat, 'log_stat' => $log_stat, 'altd' => $altd, 'usd' => $usd, 'rem' => $rem, 'usage' => $usage, 'used_time' => $used_time);
				$ans = json_encode($arr);
				return $ans;
			}

		 }

	}

}



function get_usage_table($string)
	{
		
		
		$pos = strposX($string,"<table",22);
		
		$string = substr($string, $pos,-1);
		
		$pos = strpos($string,"</table>");
		$pos=$pos+8;
		$string = substr($string, 0,$pos);
		$string = str_replace("&nbsp;","#",$string);
		return $string;
	}
	
	
	function strposX($haystack, $needle, $number){
    if($number == '1'){
        return strpos($haystack, $needle);
    }elseif($number > '1'){
        return strpos($haystack, $needle, strposX($haystack, $needle, $number - 1) + strlen($needle));
    }else{
        return error_log('Error: Value for parameter $number is out of range');
    }
    }





function get_usage_array($string)
{
    $contents = $string;
    $DOM = new DOMDocument;
    $DOM->loadHTML($contents);

    $items = $DOM->getElementsByTagName('tr');
    $length = $items->length;
	for($i=0;$i<$length;$i++)
	{
		$element = $items[$i]->childNodes;		  
		$j=0;
		foreach($element as $element)
		{
		  $arr2[$i][$j]=$element->nodeValue;					
		  $j++;
		}
	}
	
	 $c1 = sizeof($arr2);
	 $c2 = sizeof($arr2,1);
	 $c=($c2-$c1)/$c1;
	
					for($m=0;$m<$c1;$m++)
					{
					for($k=1;$k<$c2;$k++)
					{
						if($k&1)
						{
							unset($arr2[$m][$k]); //remove null value from array
						}
					}
					$arr2 = array_values($arr2);
					}
					
					
					 
	
					
    $arr2=array_map(null, ...$arr2); //transpose the multidimensional array and reindex
	 $c1 = sizeof($arr2);
	 $c2 = sizeof($arr2,1);
	 $c=($c2-$c1)/$c1;
						
	for($i=0;$i<$c1;$i++)
	{
		for($j=0;$j<$c;$j++)
		{
			if(strpos($arr2[$i][$j], 'Alloted') & !strpos($arr2[$i][$j], 'Extra') !== false){$data[0]=$arr2[$i][3];$data[3]=$arr2[$i][1];$data[6]=$arr2[$i][2];}
			if(strpos($arr2[$i][$j], 'Used') !== false)                                     {$data[1]=$arr2[$i][3];$data[4]=$arr2[$i][1];$data[7]=$arr2[$i][2];}
			if(strpos($arr2[$i][$j], 'Balanced') !== false)                                 {$data[2]=$arr2[$i][3];$data[5]=$arr2[$i][1];$data[8]=$arr2[$i][2];}
		}
		
	}
	 $data[2] = str_replace('Expired','0',$data[2]);
	//data 0 = alloted days | 1 = used days | 2 = balance days	
	//data 3 = alloted MB   | 4 = used MB   | 5 = balance MB
	//data 6 = alloted time   | 7 = used time   | 8 = balance time
	return $data;
					 
  
}

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

	
	function ron($string){
		$string = strstr($string, 'Renewed On:');
		$pos = strpos($string, "Pin");
		$string = substr($string, 0, $pos);
		$string = str_replace('Renewed On:','',$string);
		$string = preg_replace('/\s+/', '', $string);
		$string = str_replace(',','/',$string);
	    $m = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
		for($i=0;$i < 12 ;$i++)
		{
			$pos = strpos($string,$m[$i]);
			if($pos != 1 | 2)
				$string = str_replace($m[$i],"/".($i+1),$string);
		}
		return $string;
	}

	
	function roff($string){
		$string = strstr($string, 'Expiry Date:');
		$pos = strpos($string, "Planname:");
		$string = substr($string, 0, $pos);
		$string = str_replace('Expiry Date:','',$string);
		$string = preg_replace('/\s+/', '', $string);
		$string = str_replace(',','/',$string);
	    $m = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
		for($i=0;$i < 12 ;$i++)
		{
			$pos = strpos($string,$m[$i]);
			if($pos != 1 | 2)
				$string = str_replace($m[$i],"/".($i+1),$string);
		}
				return $string;
	}

	
	function plname($string){
		$string = strstr($string, 'Planname:');
		$pos = strpos($string, "Account");
		$string = substr($string, 0, $pos);
		$string = str_replace('Planname:','',$string);
		$string = preg_replace('/\s+/', '', $string);
		return $string;
	}
	
	function pldata($string)
	{
               
		if ($string == 'ABCFP')
			$data = 5;
        else if($string == 'UDFUP2Y35GB')
			$data = 35;
		else if($string == 'UDFUP2MBUL30GB')
			$data = 30;
		else if($string == 'UDNIGHT2MBPS')
			$data = 0;
		else if($string == 'UDFUP10GB')
			$data = 10;
		else if($string == 'UDFUP16GB')
			$data = 16;
		else if($string == 'UDFUP1MBUL10GB')
			$data = 10;
		else if($string == 'UDFUP20GB')
			$data = 20;
		else if($string == 'UDFUP25GB')
			$data = 25;
		else if($string == 'UDFUP2Y20GB')
			$data = 20;
		else if($string == 'UDFUP2Y50GB')
			$data = 50;
		else if($string == 'UDFUP30GB')
			$data = 30;
		else if($string == 'UDFUP50GB')
			$data = 50;
		else if($string == 'UDFUP5GB')
			$data = 5;
		else
			$data = 'NA';
		
		return $data;
	}

	
	function acc_stat($string){
		$string = strstr($string, 'Account Status:');
		$pos = strpos($string, "Login");
		$string = substr($string, 0, $pos);
		$string = str_replace('Account Status:','',$string);
		$string = preg_replace('/\s+/', '', $string);
		if($string == "Active")
			$string = "1";
		else
			$string = "0";
		return $string;
	}

	
	function log_stat($string){
		$string = strstr($string, 'Login Status:');
		$pos = strpos($string, "Disable");
		$string = substr($string, 0, $pos);
		$string = str_replace('Login Status:','',$string);
		$string = preg_replace('/\s+/', '', $string);
		if($string == "IN")
			$string = "1";
		else
			$string = "0";
		return $string;
	}
	
	?>
	