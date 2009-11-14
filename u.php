<?php

	/* Configuration Variables */

	$localBaseURL = '';

	$twitterUsername = '';
	$twitterPassword = '';

	$licenseText = "CC BY-SA";

	$lessnEndpoint  = '';
	$lessnAPI = '';





	/* Do not modify below this line */

	define('DEBUG_MODE', false);
	$out = array('response' => '', 'post' => array(), 'get' => array(), 'files' => array());

	if($_SERVER['REQUEST_METHOD'] == 'POST') {

		$username = '';
		$password = '';
		$source = '';
		$message = '';
		$media = '';

		if(isset($_POST['username'])) $username = $_POST['username'];
		if(isset($_POST['password'])) $password = $_POST['password'];
		if(isset($_POST['source'])) $source = $_POST['source'];
		if(isset($_POST['message'])) $message = $_POST['message'];

		if($username != $twitterUsername) $out['response'] = 'Authentication failed; bad username.';
		if(!$out['response'] && $password != $twitterPassword) $out['response'] = 'Authentication failed; bad password.';

		if(!$out['response'] && isset($_FILES['media'])) {
			$file = time();
			$file = $file . '.jpg';

			if(@move_uploaded_file($_FILES['media']['tmp_name'], $file)) {
				@chmod($file, 0777);

				if(extension_loaded('gd') && function_exists('gd_info')) {
					$imgOriginal = imagecreatefromjpeg($file);
					$ox = imagesx($imgOriginal);
					$oy = imagesy($imgOriginal);

					if(file_exists("./watermark.png")) {

						$imgOriginal = imagecreatefromjpeg($file);
						$imgWatermark = imagecreatefrompng('./watermark.png');

						$merge_right = 10;
						$merge_bottom = 10;
						$sx = imagesx($imgWatermark);
						$sy = imagesy($imgWatermark);

						imagecopy($imgOriginal, $imgWatermark, $ox - $sx - $merge_right, $oy - $sy - $merge_bottom, 0, 0, $sx, $sy);
						imagejpeg($imgOriginal, $file, 85);

					} else {

						$colorText = imagecolorallocatealpha($imgOriginal, 255, 255, 255, 63.5);
						$colorShadow = imagecolorallocatealpha($imgOriginal, 0, 0, 0, 63.5);

						$text = '';
						if(strlen($message)) $text = wordwrap($message) . "\n";
						if(strlen($licenseText)) $licenseText = "   {$licenseText}";
						$dated = date("m/d/y H:i");
						$text .= "@{$username}   {$dated}{$licenseText}";

						$box = imagettfbbox(6, 0, "./silkscreen.ttf", $text);
						$boxHeight = $box[3] - $box[5];
						$bX = 10;
						$bY = $oy - $boxHeight - 6;
						imagettftext($imgOriginal, 6, 0, $bX + 1, $bY + 1, $colorShadow, "./silkscreen.ttf", $text);
						imagettftext($imgOriginal, 6, 0, $bX, $bY, $colorText, "./silkscreen.ttf", $text);

						imagejpeg($imgOriginal, $file, 85);

					}
				}

				$out['response'] = 'Success!';
				$url = "{$localBaseURL}{$file}";

				if($lessnAPI && $lessnEndpoint) $url = LessnURL($url);
				echo "<mediaurl>{$url}</mediaurl>";

			} else{
				echo "There was an error uploading the file, please try again!";
			}
		}

	} else {

		header("Location: http://twitter.com/{$twitterUsername}");

	}

	if(DEBUG_MODE) {
		$out['post'] = $_POST;
		$out['get'] = $_GET;
		$out['files'] = $_FILES;

		unset($out['post']['password']);

		ob_start();
		var_dump($out);
		$out = ob_get_clean();

		file_put_contents("dump.txt", $out);
		chmod("dump.txt", 0777);
	}

	exit;

	function LessnURL($url) {

		global $lessnEndpoint, $lessnAPI;

		$url = urlencode($url);
		return file_get_contents("{$lessnEndpoint}?url={$url}&api={$lessnAPI}");

	}


?>
