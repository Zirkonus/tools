<?php
/**
 * A method of byte info displaying in an easy-to-read form
 * 
 * @author Oleksii Dovzhenko <zirkonus@gmail.com>
 * @copyright 2010
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
function get_byte_size($size) {
	if ($size < 1024) {
		return intval($size) .' B';
	} elseif ($size < 1048576) {
		return round($size / 1024, 1) .' KB';
	} elseif ($size < 1073741824) {
		return round($size / 1048576, 1) . ' MB';
	} elseif ($size < 1099511627776) {
		return round($size / 1073741824, 1) . ' GB';
	} else {
		return round($size / 1099511627776, 1) .' TB';
	}
	return $size;
}
?>