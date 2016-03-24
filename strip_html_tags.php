<?php
/**
 * Strip html tags from text
 * 
 * @author Oleksii Dovzhenko <zirkonus@gmail.com>
 * @copyright 2012
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

function strip_html_tags($str, $tags, $strip_content=false) {
	$content = '';
	if (!is_array($tags)) {
		$tags = (strpos($str, '>') !== false ? explode('>', str_replace('<', '', $tags)) : array($tags));
		if (end($tags) == '') array_pop($tags);
	}
	$pattern = array();
	foreach ($tags as $tag) {
		if ($strip_content) $content = '(.+?</'.$tag.'>)';
		$pattern[] = '#<'.$tag.'([^>]*>)'.$content.'#is';
	}
	$str = preg_replace($pattern, '', $str);
	return $str;
}
?>