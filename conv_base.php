<?php
/**
 * A method of number conversion between random scales of notation
 * 
 * @author Oleksii Dovzhenko <zirkonus@gmail.com>
 * @copyright 2011
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
function conv_base($number_input, $from_base_input, $to_base_input) {
	if ($from_base_input == $to_base_input) return $number_input;
	$from_base  = str_split($from_base_input, 1);
	$to_base    = str_split($to_base_input, 1);
	$number     = str_split($number_input, 1);
	$from_len   = strlen($from_base_input);
	$to_len     = strlen($to_base_input);
	$number_len = strlen($number_input);
	$retval     = '';
	if ($to_base_input == '0123456789') {
		$retval = 0;
		for ($i=1; $i<=$number_len; $i++)
			$retval = bcadd($retval, bcmul(array_search($number[$i-1], $from_base), bcpow($from_len, $number_len-$i)));
		return $retval;
	}
	if ($from_base_input != '0123456789') $base10 = conv_base($number_input, $from_base_input, '0123456789');
	else $base10 = $number_input;
	if ($base10 < strlen($to_base_input)) return $to_base[$base10];
	while ($base10 != '0') {
		$retval = $to_base[bcmod($base10, $to_len)].$retval;
		$base10 = bcdiv($base10, $to_len, 0);
	}
	return $retval;
}
?>