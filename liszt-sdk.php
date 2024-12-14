<?php
class liszt{

    public function setHT($date = "NOW")
	{
		setlocale(LC_ALL, 'en_US.UTF8');
		setlocale(LC_MONETARY, 'en_US');
		date_default_timezone_set('UTC');

		$this->now = new DateTime($date);
		$this->ht = [
			'timestamp' => $this->now->getTimestamp(),
			'sqldate' => $this->now->format('Y-m-d'),
			'sqlts' => $this->now->format('Y-m-d H:i:s'),
			'year' => $this->now->format('Y'),
			'year2' => $this->now->format('y'),
			'weekday' => $this->now->format('w'),
			'month' => $this->now->format('m'),
			'roman' => $this->romanic_number($this->now->format('Y'))
		];
	}

	public function externals(){
		include "externals.php";
	}

	public function head($title,$insert="",$desc="",$light="",$img="https://kylevanderburg.com/assets/KV17-Composer.svg"){
		$this->tzscript();
		echo "<head>
			<meta charset=\"utf-8\">
			<!--<meta name=\"viewport\" content=\"width=device-width, minimum-scale=1.0, maximum-scale=1.0, initial-scale=1.0\" />-->
			<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
			<meta name=\"description\" content=\"";if($desc){echo $desc;}else{echo "NoteForge Liszt is a full-featured Musician Management System";}echo "\" />
			<meta name=\"author\" content=\"NoteForge Hammer\" />
			<meta name=\"generator\" content=\"NoteForge Liszt\" />
			<title>".$title."</title>";
			echo "			<meta property=\"og:title\" content=\"".$title."\"/>\n";
    		echo "			<meta property=\"og:description\" content=\"".$title."\"/>\n";
			echo "			<meta property=\"og:type\" content=\"website\"/>\n";
    		echo "			<meta property=\"og:url\" content=\"".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."\"/>\n";
    		echo "			<meta property=\"og:image\" content=\"".$img."\"/>\n";
			// $this->kmail($_SERVER['REQUEST_URI']);
			//if($_SERVER['HTTP_HOST']=="noteforge.com"||$_SERVER['HTTP_HOST']=="xkylevanderburg.com"){$light=1;}else{$light=0;}
			$this->externals($light);
			
			echo $insert;
			// echo "<link rel=\"shortcut icon\" href=\"/assets/lisztfav.png\"/>";
			if($this->modal===1){
				echo "<style>body{padding:5px;}</style>";}
			echo "</head>";
	}

    //Convert from local TZ to UTC SQL
	public function l2u($time)
	{
		$time = new DateTime($time, new DateTimeZone($this->htz));
		$time->setTimezone(new DateTimeZone("UTC"));
		$return = $time->format('Y-m-d H:i:s');
		return $return;
	}

	//Convert from UTC to local TZ
	public function u2l($time)
	{
		if($this->htz==""){$this->htz="America/Chicago";}
		$time = new DateTime($time, new DateTimeZone("UTC"));
		$time->setTimezone(new DateTimeZone($this->htz));
		//$return = $time->format('Y-m-d H:i:s');
		return $time;
	}

    public function stop($message){die("<center><img src=\"//liszt.app/assets/lisztlogo-X.png\" alt=\"Restricted\" style=\"width:100%;max-width:200px;\" /><br /><h1>".$message."</h1></center>");}
	
	public function redirect($url){echo "<script>window.location = '".$url."';</script>";}
	
    public function clientUrlParse()
	{
		$url = $_SERVER['REQUEST_URI'];
		$this->location2['fullurl']=$url;
		$this->location2['get']=$_GET;
		//Test if Error is set
		if(strpos($url,'/e/') !== false) {
			$url = str_replace('/e/','/',$url);
			$this->debug();
		}
		$parts = explode('/',$url);
		array_shift($parts);
		array_pop($parts);
		
		$this->location = $parts;	
	}

    //Write timezone capture script
	public function tzScript(){
		//Hammer UTC Offset Cookie
		if(!isset($_COOKIE["HammerLocalTZ"])){
			?>
			<script>
			zone = Intl.DateTimeFormat().resolvedOptions().timeZone;
			document.cookie = 'HammerLocalTZ=' + zone + ';domain=.<?php echo $_SERVER['SERVER_NAME'];?>;path=/';
			location.reload();
			</script>
	
			<?php
		}
	}
}

?>