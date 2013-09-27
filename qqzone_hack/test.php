<?php
error_reporting(E_ALL);
//ob_start();
//header('Content-Type: image/png');

define('IMG_NO', "no.png");
define('IMG_BACKGROUND', "background.png");
define('IMG_WIDTH', 400);
define('IMG_HEIGHT', 128);
define('FONT_NAME', "AdobeHeitiStd-Regular.otf"); 

/*
function load_from_cache($remote, $local, $expire = CACHE_EXPIRE, $as_path = false) {
	$local = preg_replace("/[.\/\\\?\*\'\"\|\:\<\>]/", "_", $local);
	$cache = CACHE_PATH.$local;
	//filemtime : get the file modify time
	//time      : return the current UNIX timestamp
	if(file_exists($cache) && (-1 == $expire || filemtime($cache) - time() < $expire)) {
		return $as_path ? $cache : file_get_contents($cache);
	}
	else {
		$content = file_get_contents($remote);
		//write content to cache
		file_put_contents($cache, $content);
		return $as_path ? $cache : $content;
	}
}
*/

$referer = $_SERVER['HTTP_REFERER'];
//var_dump($referer);
//$referer = "http://user.qzone.qq.com/929174377/infocenter";

$pattern = "/http:\/\/user.qzone.qq.com\/(\d+)\/infocenter/";

if(preg_match($pattern, $referer, $matches)) {
	$uin = $matches[1];
	$qq_interface = "http://base.qzone.qq.com/fcg-bin/cgi_get_portrait.fcg?uins=";
	$info = explode('"', file_get_contents($qq_interface.$uin));
	$avatar = $info[3];
	$nickname = iconv("GBK", "UTF-8//IGNORE", $info[5]);
	try{
		$im = imagecreatefrompng(IMG_BACKGROUND);
	/*
	    $avatar_file = load_from_cache($avatar, $uin.".jpg", 60*60*24, true);
	    $im_avatar = imagecreatefromjpeg($avatar_file);
		imagecopymerge($im, $im_avatar, 14, 14, 0, 0, 100, 100, 100);
		imagedestroy($im_avatar);
	*/
		
		$blue = imagecolorallocate($im, 0, 0x99, 0xFF);
		$white = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);
		
	
		$texts = array(
			array(18, 125, 70, $blue, $nickname)
		);
	/*
		foreach($texts as $key=>$value) {
			if(function_exists(imagettftext)) {
				imagettftext($im, $value[0], 0, $value[1], $value[2], $value[3], FONT_NAME, 
					mb_convert_encoding($value[4], "html-entities", "utf-8")); 
			}
		}
	*/
		
		imagestring($im, 5, 50, 50, $nickname, $white);
		
		imagepng($im);//Output a PNG image to either the browser or a file
		imagedestroy($im);

		header("Content-Length: ".ob_get_length());
        ob_end_flush();
	} catch (Exception $e) {

	    //die($e->getMessage());
	    $error = true;
	}	
} else {
	$error = true;
}
 
if($error){
	header('Content-Length: '.filesize(IMG_NO));
    echo file_get_contents(IMG_NO);
}