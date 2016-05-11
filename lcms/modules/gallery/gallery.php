<?php

class gallery extends Model {
	
	function gallery(){
		parent::Model();

		$this->name				= 'Photo Gallery';
		$this->namespace		= 'gallery';
		$this->css_namespace	= 'lcms-gallery';
				
		$this->js_includes		= 'lcms.gallery.js';
		$this->css_includes 	= 'lcms.gallery.css';
		
		$this->js_resources 	= array('jquery.fancybox.js', 'jquery.fancybox-buttons.js', 'jquery.fancybox-media.js', 'jquery.fancybox-thumbs.js');
		$this->css_resources	= array('jquery.fancybox.css', 'jquery.fancybox-buttons.css', 'jquery.fancybox-thumbs.css');
		
	}
	
	function Hooks(){

	}
	
	
	function PreHTML(){
		$prehtml = $this->load->view('modules/gallery/form-new', $data, true);
		$prehtml .= $this->load->view('modules/gallery/form-edit', $data, true);
		return $prehtml;
	}
	
	
	function HTML($content, $author_mode){
		
		$data['content'] 	= $content;
		$data['images']		= json_decode($content->content);
		$data['options']	= json_decode($content->options);

		return $this->load->view('modules/gallery/html',$data,true);
		
	}
	
	function Presave($content, $options){		
		$data->content = $content;
		$data->options = $options;
		return $data;
	}
	
	function Preupdate($id, $content, $options){
		$data->content = $content;
		$data->options = $options;
		return $data;		
	}
	
	function Saved($id, $published){
		
	}
	
	function Updated($id, $published){
		
	}
	
	
	
	
	function Tree($content){

		$page = $this->get_main_parent($content->page);
		
		$output .= "<ul class=\"lcms-subpage {$content->class}\">";
		$output .= $this->display_children($page,1);
		$output .= "</ul>";
		
		return $output;
	}
	
	
	function Display_children($parent, $level) {
		$children = $this->page_children($parent);

  		foreach($children as $child) { 

		    $more_child = $this->display_children($child->name, $level+1); 
		    $level2 = $level+1;
		    if ($more_child) $more_child = "<ul class=\"lcms-subpage-subchild lcms-subpage-level-{$level2}\">{$more_child}</ul>";
		    if ($_SESSION['page'] == $child->name) $cur = ' class="current"';
		    else $cur = '';
		    
		    $url = site_url() . 'p/' . $child->name;
		    
		    $child_text .= "<li {$cur} value=\"{$child->name}\"><a href=\"{$url}\">".$child->title."</a></li>" . $more_child;

		}
		return $child_text;
	}
	
	function Get_main_parent($child){
		$query = $this->db->query("SELECT * FROM `pages` WHERE `name` = ?", array($child));
		if ($query->num_rows()){
			$child = $query->row();
			if (!$child->parent) return $child->name;
			return $this->get_main_parent($child->parent);
		} else return $child;
	}
	
	
	function Has_children($name){
		$query = $this->db->query("SELECT * FROM `pages` WHERE `parent` = '$name'");
		if ($query->num_rows() > 0) return true;
		return false;
	}
	
	function Page_children($name){
		$query = $this->db->query("SELECT * FROM `pages` WHERE `parent` = '$name'");
		if ($query->num_rows() > 0){
			$pages = $query->result();
			return $pages;
		} else {
			return false;
		}
	}
	
}