<?php
/**
 * Transliterate Cyrillic alphabet to Latin alphabet
 * 
 * @author Oleksii Dovzhenko <zirkonus@gmail.com>
 * @copyright 2008
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

function translit($str, $replace_space=false) {
	$tr = array (
		"А" => "A",   "Б" => "B",   "В" => "V",   "Г" => "G",   "Д" => "D",
		"Е" => "E",   "Ё" => "YO",  "Ж" => "J",   "З" => "Z",   "И" => "I",
		"Й" => "Y",   "К" => "K",   "Л" => "L",   "М" => "M",   "Н" => "N",
		"О" => "O",   "П" => "P",   "Р" => "R",   "С" => "S",   "Т" => "T",
		"У" => "U",   "Ф" => "F",   "Х" => "H",   "Ц" => "TS",  "Ч" => "CH",
		"Ш" => "SH",  "Щ" => "SCH", "Ъ" => "",    "Ы" => "YI",  "Ь" => "",
		"Э" => "E",   "Ю" => "YU",  "Я" => "YA",  "а" => "a",   "б" => "b",
		"в" => "v",   "г" => "g",   "д" => "d",   "е" => "e",   "ё" => "yo",
		"ж" => "j",   "з" => "z",   "и" => "i",   "й" => "y",   "к" => "k",
		"л" => "l",   "м" => "m",   "н" => "n",   "о" => "o",   "п" => "p",
		"р" => "r",   "с" => "s",   "т" => "t",   "у" => "u",   "ф" => "f",
		"х" => "h",   "ц" => "ts",  "ч" => "ch",  "ш" => "sh",  "щ" => "sch",
		"ъ" => "y",   "ы" => "yi",  "ь" => "",    "э" => "e",   "ю" => "yu",
		"я" => "ya",  "«" => '"',   "»" => '"'
	);
	if ($replace_space) {
		$tr[' ']  = '_';
		$tr["\\"] = '_';
		$tr['/']  = '_';
		$tr['&']  = '_';
		$tr['?']  = '_';
	}
	return strtr($str, $tr);
}
?>