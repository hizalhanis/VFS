<?php

class subpages extends Model {
	
	function subpages(){
		parent::Model();

		$this->name				= 'Subpages List';
		$this->namespace		= 'subpages';
		$this->css_namespace	= 'lcms-subpages';
				
		$this->js_includes		= 'lcms.subpages.js';
		$this->css_includes 	= 'lcms.subpages.css';
		
		$this->js_resources 	= '';
		$this->css_resources	= '';
		
	}
	
	function Hooks(){

	}
	
	
	function PreHTML(){
		$prehtml = $this->load->view('modules/subpages/form-new', $data, true);
		$prehtml .= $this->load->view('modules/subpages/form-edit', $data, true);
		return $prehtml;
	}
	
	
	function HTML($content, $author_mode){
		
		$data['content'] 	= $content;
		$data['subpages']	= $this->tree($content);

		return $this->load->view('modules/subpages/html',$data,true);
		
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
		    $cur_parent = '';
		    if ($_SESSION['page'] == $child->name){
		    	$cur = ' class="current"';
		    	$cur_parent = ' lcms-subpage-subchild-current ';
		    } else {
			    $cur = '';
		    }

		    if ($more_child) $more_child = "<ul class=\"lcms-subpage-subchild lcms-subpage-level-{$level2} {$cur_parent}\">{$more_child}</ul>";
		    
		    $url = site_url() . '' . $child->parent .  '/'. $child->name;
		    
		    $child_text .= "<li {$cur} value=\"{$child->name}\"><a href=\"{$url}\">".$child->title."</a>". $more_child ."</li>" ;

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