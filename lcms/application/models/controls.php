<?php 

class Controls extends Model {

	function Controller_bar($current_page){

		$data['main_page']		= $main = $this->cms->main_page();
		$data['jump_nav']		= $this->page_list($current_page, true, false);
		$data['current_page']	= $current_page;

		$controller_bar = $this->load->view('interface/controller-bar', $data, true);
		
		echo $controller_bar;
	}
	
	function Forms($current_page, $tools){
		$data['controls'] = $this->core_controls();
		$data['layouts'] = $this->layouts($current_page, true);		
		$data['pages'] = $this->page_list('',true, true);
		$new_content_form = $this->load->view('interface/content-add', $data, true);
		echo str_replace("\n", "", $new_content_form);
		
		$new_page_form = $this->load->view('interface/page-add', $data, true);
		echo str_replace("\n", "", $new_page_form);
		
		$data['page'] = $this->cms->page($current_page);

		$data['parents'] = $this->page_list($page->parent, true, true);
		
		$edit_page_form = $this->load->view('interface/page-edit', $data, true);
			
		echo str_replace("\n", "", $edit_page_form);
	}
	
	function Layouts($current_page = null, $return = false){

		$theme = $this->cms->current_theme();

		$theme_path = FCPATH . 'themes/' . $theme->directory . '/';
		$theme_url = base_url() . 'themes/' . $theme->directory . '/';

		$d = opendir($theme_path);
		
		while (($entry = readdir($d)) !== false) {
			if ($entry != '.' && $entry != '..' && !is_dir($dir.$entry)) $files[] = $entry;

		}
		
		$query = $this->db->query("SELECT * FROM `pages` WHERE `name` = ?", array($current_page));
		$current = $query->row();
		
		
		
		closedir($d);
		foreach ($files as $file){
			$path_info = pathinfo($file);
			
			if ($path_info['extension'] == 'php'){
				if ($current->layout == $path_info['filename']){
					$check = 'checked="checked" ';
				}
				else $check = null;
				if (file_exists($theme_path . $path_info['filename'] . '.png')) $output .= '<div><img src="'.$theme_url.$path_info['filename'].'.png" alt="" /> <input '.$check.' type="radio" name="layout" value="'.$path_info['filename'].'" /> '.$path_info['filename'] .'</div>';
				else if (file_exists($theme_path . $path_info['filename'] . '.jpg')) $output .= '<div><img src="'.$theme_url.$path_info['filename'].'.png" alt="" /> <input '.$check.' type="radio" name="layout" value="'.$path_info['filename'].'" /> '.$path_info['filename'] .'</div>';
				else if (file_exists($theme_path . $path_info['filename'] . '.png')) $output .= '<div><img src="'.$theme_url.$path_info['filename'].'.png" alt="" /> <input '.$check.' type="radio" name="layout" value="'.$path_info['filename'].'" /> '.$path_info['filename'] .'</div>';
				else $output .= '<div><input '.$check.' type="radio" name="layout" value="'.$path_info['filename'].'" /> '.$path_info['filename'].'</div>';
			}
		}
		
		$output .= "<hr />";
		if ($return) return $output;
		echo $output;
	}
	

	function Page_list($current, $return = false, $parent_selection = false, $self){
		$query = $this->db->query("SELECT * FROM `pages` WHERE `parent` = '' ORDER BY `name` DESC");
		
		if ($query->num_rows() > 0){
			
			$pages = $query->result();
			
			if ($parent_selection) $output .= '<option value="">No Parent</option>';
			
			foreach ($pages as $page){
				if ($current){
					if ($current == $page->name) $cur = 'selected="selected"';
					else $cur = '';
				}
				if ($parent_selection){
					if ($self != $page->name){
						$output .= "<option {$cur} value=\"{$page->name}\">{$page->title}</option>";
					}
				} else {
					$output .= "<option {$cur} value=\"{$page->name}\">{$page->title}</option>";
				}
				$output .= $this->display_children($current, $page->name, 1, $parent_selection, $self);
			}
			
		} 
		if ($return) return $output;
		else echo $output;

	}
	
	function Display_children($current, $parent, $level, $parent_selection, $self) { 
		$children = $this->page_children($parent);

  		foreach ($children as $child) { 
		    // indent and display the title of this child 
		    $more_child = $this->display_children($child->name, $level+1); 
		    if ($current == $child->name) $cur = ' selected="selected"';
		    else $cur = '';

		    if ($parent_selection){
			    if ($self != $child->name){
				    $child_text .= "<option{$cur} value=\"{$child->name}\">".str_repeat("&nbsp;&nbsp;",$level).$child->title."</option>" . $more_child;
				}
		    } else {
			    $child_text .= "<option{$cur} value=\"{$child->name}\">".str_repeat("&nbsp;&nbsp;",$level).$child->title."</option>" . $more_child;
		    }
		
		    // call this function again to display this 
		    // child's children 
		}
		
		return $child_text;
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
	
	function Core_controls(){

		$controls_path = FCPATH . 'modules/';
		$d = opendir($controls_path);

		while (($entry = readdir($d)) !== false) {
			if (is_dir($controls_path.$entry)){
				if ($entry != '.' && $entry != '..' && $entry[0] != '_') $modules[] = $entry;
			}
		}
		
		closedir($d);
		
		foreach ($modules as $module){
			$this->load->model('modules/'.$module.'/'.$module);
			$controls[] = $module;
		}

		return $controls;
	}
	
	function Country_list($select = true){
		$query = $this->db->query("SELECT * FROM `countries`");
		if ($select) $countries[] = "Select Country";
		foreach ($query->result() as $country){
			$countries[$country->name] = $country->name;
		}
		
		return $countries;
	}

	
	function State_list($country){
		if ($country){
			$query = $this->db->query("SELECT * FROM `states` WHERE `country` = ? ORDER BY `state` ASC",array($country));
			foreach ($query->result() as $state){
				$states[$state->state] = $state->state;
			}
			return $states;
		}
		return false;		
	}
}
