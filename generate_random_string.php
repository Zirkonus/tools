<?php
/**
 * Generate random string.
 * 
 * @author Oleksii Dovzhenko <zirkonus@gmail.com>
 * @copyright 2016
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

function generateRandomString($length = 10, $type = 'number char', $return_md5 = false) {
	$random_string = $characters = '';
	if ($type) {
		$types = explode(' ', preg_replace("/\s{2,}/",' ',trim($type)));
		$types = array_flip($types);
	}
	$characters_types = array (
		'number'	=> '0123456789',
		'char'		=> 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
		'special'	=> '`~!@#$%^&*()_-+={}[]\|:;"'."'".'<>,.? /'
	);
	
	if (isset($types['all'])) {
		foreach ($characters_types as $k=>$v) {
			$characters .= $characters_types[$k];
		}
	} elseif (isset($types)) {
		foreach ($types as $k=>$v) {
			if (!$k || !isset($characters_types[$k])) continue; 
			$characters .= $characters_types[$k];
		}
	}
	if (!$characters) $characters = $characters_types['number'].$characters_types['char'];
	
	$characters_length = strlen($characters);
	$random_string = '';
	for ($i = 0; $i < $length; $i++) {
		$random_string .= $characters[rand(0, $characters_length - 1)];
	}
	return $return_md5 ? md5($random_string) : $random_string;
}
?>