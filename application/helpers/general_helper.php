<?php


function truncate($string, $length){
	$x = strlen($string) < $length ? strlen($string) : $length;
	for ($i = 0; $i < $x; $i++){
		$new_string .= $string[$i];
	}
	return $new_string;
}
