<?php
/**
 * It's required to replace white and nearly white backgrounds with transparent ones and  remove the empty space.
 *
 * @author Oleksii Dovzhenko <zirkonus@gmail.com>
 * @copyright 2016
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class makeTransparentImage {
	private $tmp_dir 		= "./tmp/";
	private $percent_rate 	= 270;
	private $max_val 		= 1000;
	private $min_val 		= 0;
	private $rotate			= -90;
	private $red			= 240;
	private $green			= 240;
	private $blue			= 240;
	
	public function make($data, $isFileString=FALSE) {
		if ($isFileString) {
			$src = imagecreatefromstring($data);
		} else {
			$src = imagecreatefromjpeg($data);
		}
		$tmp_file = $this->tmp_dir . md5(time() . mt_rand());
		imagepng($src, $tmp_file);
		imagedestroy($src);
		
		// rotate, pre-resize and resamle the source image
		$src = imagecreatefrompng($tmp_file);
		unlink($tmp_file);
		$src = imagerotate($src, $this->rotate, 0);
		$width = imagesx($src);
		$height = imagesy($src);
		$percent = ($this->percent_rate * 2) / $width;
		$newWidth = $width * $percent;
		$newHeight = $height * $percent;
		$im = imagecreatetruecolor($newWidth, $newHeight);
		imagecopyresampled($im, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
		
		// create a transparent image with a size like $im image
		$dst = imagecreatetruecolor($newWidth, $newHeight);
		imagesavealpha($dst, TRUE);
		imagefill($dst, 0, 0, imagecolorallocatealpha($dst, 0, 0, 0, 127));
		
		// find non-white pixels on $im and copy them to $dst
		$sizeX = $newWidth;
		$sizeY = $newHeight;
		$startX = $startY = $this->max_val;
		$finishX = $finishY = $this->min_val;
		for ($x=0; $x<$sizeX-1; $x++) {
			for ($y=0; $y<$sizeY-1; $y++) {
				$rgb = imagecolorat($im, $x, $y);
				$colors = imagecolorsforindex($im, $rgb);
				$r = $colors['red'];
				$g = $colors['green'];
				$b = $colors['blue'];
				if ($r < $this->red && $g < $this->green && $b < $this->blue) {
					imagesetpixel($dst, $x, $y, $rgb);
					if ($startX >= $x) $startX = $x;
					if ($startY >= $y) $startY = $y;
					if ($finishX <= $x) $finishX = $x;
					if ($finishY <= $y) $finishY = $y;
				}
			}
		}
		
		// final resize
		$width = $newWidth - $startX + ($finishX - $newWidth);
		$height = $newHeight - $startY + ($finishY - $newHeight);
		$percent = $this->percent_rate / $width;
		$newWidth = $width * $percent;
		$newHeight = $height * $percent;
		$ret = imagecreatetruecolor($newWidth, $newHeight);
		imagesavealpha($ret, TRUE);
		imagefill($ret, 0, 0, imagecolorallocatealpha($ret, 0, 0, 0, 127));
		imagecopyresampled($ret, $dst, 0, 0, $startX, $startY, $newWidth, $newHeight, $width, $height);
		
		// returning image
		ob_start();
		imagepng($ret);
		$image_data = ob_get_contents();
		ob_end_clean();
		return $image_data;
	}
}