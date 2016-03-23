<?php
/**
 * GeoIP with http://tabgeo.com/
 *
 * 
 * @author Oleksii Dovzhenko <zirkonus@gmail.com>
 * @copyright 2015
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

$ip = $_GET['ip'];
require_once __DIR__."/tabgeo/tabgeo_country_v4.php";
if (empty($ip)) {
	$ip = $_SERVER['REMOTE_ADDR'];
	$country_code = tabgeo_country_v4($ip);
	echo $_SERVER['REMOTE_ADDR'].'<br>'.$country_code;
} else {
	$country_code = tabgeo_country_v4($ip);
	echo $country_code;
}
?>