
<?php
$url = "http://photo.hupu.com/nba";
$content=file_get_contents($url);
$reg="/<img.*?src=\"(.*?)\".*?>/";

preg_match_all($reg,$content,$matches);

$path = './imgDownload';
if(!file_exists($path)){
	mkdir($path, 0777);
}

for($i = 0;$i < count($matches[1]);$i ++){
	
	/*explode
	$url_arr[$i] = explode('/', $matches[1][$i]);
	$last = count($url_arr[$i])-1;
	*/
	
	//strrchr	
	$filename = strrchr($matches[1][$i], '/');
	
	downImage($matches[1][$i],$path.$filename);
	//downImage($matches[1][$i],$path.'/'.$url_arr[$i][$last]);
}

function downImage($url,$filename="") {
	if($url=="") {
		return false;
	}

	if($filename=="") {
		$ext=strrchr($url,".");
		if($ext!=".gif" && $ext!=".jpg" && $ext!=".png" && $ext!="jpeg") return false;
		$filename=date("YmdHis").$ext;
	}

	ob_start();
	//make file that output from url goes to buffer
	readfile($url);
	//file_get_contents($url);  这个方法不行的！！！只能用readfile
	$img = ob_get_contents();
	ob_end_clean();

	$fp=@fopen($filename, "a");//append
	fwrite($fp,$img);
	
	fclose($fp);

	return $filename;
}