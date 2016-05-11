<?php

if (!defined('BASEPATH')) exit('No direct script access allowed'); 

$CI =& get_instance();
$CI->load->database();

function content($contents, $location){
	global $CI;
	$author_mode = $contents['LCMS_AUTHOR'];
	$contents = $contents[$location];

	$base_url = base_url();
	if (!$contents){
		if ($author_mode){
			
			// Generate random ID for container
			$rand_id = mt_rand(00,99);
			$rand_id .= mt_rand(00,99);
			$rand_id .= mt_rand(0,9);
			$rand_id .= mt_rand(0,9);
			echo "<ul class=\"lcms-content\" rel=\"$location\">";
			echo "<li class=\"lcms-empty-author\">This section is empty. You can add new contents here. <a href=\"#\" class=\"lcms-add-content\" rel=\"$location\">Add New Content</a></li>";
			echo "</ul>";
		} else {
			// echo "<div class=\"lcms-empty\">There are currently no contents.</div>";
		}
	} else {
		if ($author_mode){
			echo "<div class=\"lcms-content-toolbar\" rel=\"$location\"><a href=\"#\" class=\"lcms-btn lcms-add-content\" rel=\"$location\">+</a> </div>";
			echo "<ul class=\"lcms-content\" rel=\"$location\">";
			foreach ($contents as $content){
				$CI->load->model('modules/'.$content->type.'/'.$content->type);
				
				if (!$content->in_all) $common = 'lcms-uncommon-handle';
				else $common = '';
				
				if ($content->published) $published = 'lcms-published-handle-on';
				else $published = '';
				
				echo "<li class=\"lcms-editable-object\" type=\"{$content->type}\" rel=\"{$content->id}\" published=\"{$content->published}\" common=\"{$content->in_all}\">";
				echo "<div class=\"lcms-content-controls\">
					<a class=\"lcms-drag-handle\"><span>Drag</span></a>
					<a class=\"lcms-edit-handle lcms-content-edit\"><span>Edit</span></a>
					<a class=\"lcms-versions-handle\"><span>Edit</span></a>
					<a class=\"lcms-delete-handle lcms-content-delete\"><span>Delete</span></a>
					<a class=\"lcms-common-handle {$common}\"><span>Common</span></a>
					<a class=\"lcms-published-handle {$published}\"><span>Published</span></a>
				</div>";
				echo $CI->{$content->type}->html($content,$author_mode);
				echo "</li>";
			}
			echo "</ul>";

		} else {
			foreach ($contents as $content){
				$CI->load->model('modules/'.$content->type.'/'.$content->type);
				echo $CI->{$content->type}->html($content,$author_mode);
			}
		}
	}
}


function clean_url($string){
	$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $string);
	$clean = strtolower(trim($clean, '-'));
	$clean = preg_replace("/[\/_|+ -]+/", '-', $clean);
	
	return $clean;
}

function reverse_clean_url($string){
	return str_replace('-',' ', $string);
}

function page_url(){
	global $CI;
	
	return site_url() . 'p/' . ($CI->uri->segment(2) ? $CI->uri->segment(2) : 'home') . '/';
}

function truncate($string, $length){
	$x = strlen($string) < $length ? strlen($string) : $length;
	for ($i = 0; $i < $x; $i++){
		$new_string .= $string[$i];
	}
	return $new_string;
}

function search_excerpt($string, $term, $length){
	$x = strpos(strtoupper($string), strtoupper($term));
	if ($x > 30){
		$max = $x + 60 > strlen($string) ? strlen($string) : $x + 60;
		$excerpt = substr($string, $x - 20, $max);
	} else {
		$excerpt = substr($string, 0, 80);
	}
	return truncate(str_replace($term, '<b>' . $term . '</b>', $excerpt),$length);
}

function strip_only($str, $tags, $stripContent = false) {
    $content = '';
    if(!is_array($tags)) {
        $tags = (strpos($str, '>') !== false ? explode('>', str_replace('<', '', $tags)) : array($tags));
        if(end($tags) == '') array_pop($tags);
    }
    foreach($tags as $tag) {
        if ($stripContent)
             $content = '(.+</'.$tag.'[^>]*>|)';
         $str = preg_replace('#</?'.$tag.'[^>]*>'.$content.'#is', '', $str);
    }
    return $str;
}

function number_range($from, $to){
	for ($i = $from; $i <= $to; $i++){
		$numbers[$i] = $i;
	}
	return $numbers;
}

function imgsrc($file, $type){

	$ext = substr(strrchr($file,'.'),1);
	$bn = basename($file, '.'.$ext);
	
	if ($type == 'standard'){
		return $bn . '_plex_standard300.' . $ext;
	} elseif ($type == 'thumbnail') {
		return $bn . '_plex_thumb50.' . $ext;		
	}

}