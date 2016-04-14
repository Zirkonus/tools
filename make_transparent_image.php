<?php
/**
 * It's required to replace white and nearly white backgrounds with transparent ones and remove the empty space.
 *
 * Code for testing class:
 * <?php
 * require_once "make_transparent_image.php";
 * 
 * try {
 *   $m = new makeTransparentImage();
 *
 *   $m->setColor('red', 240, 255);
 *   $m->setColor('green', 240, 255);
 *   $m->setColor('blue', 240, 255);
 *
 *   header('Content-type: image/png');
 *   echo $m->make(__DIR__."/image.jpg");
 * } catch (Exception $e) {
 *   echo $e->getMessage();
 * }
 * ?>
 *
 * @author Oleksii Dovzhenko <zirkonus@gmail.com>
 * @copyright 2016
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class makeTransparentImage {
	private $start_red   = 240;
	private $end_red     = 255;
	private $start_green = 240;
	private $end_green   = 255;
	private $start_blue  = 240;
	private $end_blue    = 255;
	
	public function setColor($color, $start=0, $end=0) {
		if (!in_array($color, array('red','green','blue'))) {
			throw new InvalidArgumentException('Invalid color name');
		}
		if ($start > $end) list($start, $end) = array($end, $start);
		if ($start < 0) $start = 0;
		if ($end > 255) $end = 255;
		$this->{'start_'.$color} = $start;
		$this->{'end_'.$color} = $end;
	}

	public function make($image) {
		switch (exif_imagetype($image)) {
			case IMAGETYPE_GIF:
				$src = imagecreatefromgif($image);
				break;
			case IMAGETYPE_JPEG:
				$src = imagecreatefromjpeg($image);
				break;
			case IMAGETYPE_PNG:
				$src = imagecreatefrompng($image);
				break;
			default:
				throw new InvalidArgumentException('Invalid image type');
		}
		
		$startX = $sizeX = imagesx($src);
		$startY = $sizeY = imagesy($src);
		$dst = imagecreatetruecolor($sizeX, $sizeY);
		imagesavealpha($dst, TRUE);
		imagefill($dst, 0, 0, imagecolorallocatealpha($dst, 0, 0, 0, 127));
		
		// find non-white pixels on $src and copy them to $dst
		$finishX = $finishY = 0;
		for ($x=0; $x<$sizeX-1; $x++) {
			for ($y=0; $y<$sizeY-1; $y++) {
				$rgb = imagecolorat($src, $x, $y);
				$colors = imagecolorsforindex($src, $rgb);
				$r = $colors['red'];
				$g = $colors['green'];
				$b = $colors['blue'];
				if (($r < $this->start_red || $r > $this->end_red) && ($g < $this->start_green || $g > $this->end_green) && ($b < $this->start_blue || $b > $this->end_blue)) {
					imagesetpixel($dst, $x, $y, $rgb);
					if ($startX >= $x) $startX = $x;
					if ($startY >= $y) $startY = $y;
					if ($finishX <= $x) $finishX = $x;
					if ($finishY <= $y) $finishY = $y;
				}
			}
		}
		
		// final resize - remove empty space round the image
		$width = $newWidth - $startX + ($finishX - $newWidth);
		$height = $newHeight - $startY + ($finishY - $newHeight);
		$newWidth = $finishX - $startX;
		$newHeight = $finishY - $startY;
		$ret = imagecreatetruecolor($width, $height);
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