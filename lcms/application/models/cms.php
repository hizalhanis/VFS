<?php

class Cms extends Model {

	var $author_mode;
	var $plugin_controls;
	
	function Cms(){
		parent::Model();
		if ($this->user->is_alive()) $this->author_mode = true;

	}
		
	function Current_theme($info = null){
		$query = $this->db->query("SELECT * FROM `themes` WHERE `active` = '1'");
		if ($query->num_rows() > 0){
			$theme = $query->row();
			if ($info) return $theme->{$info};
			else return $theme;
		} else return false;

	}
	
	function Theme_link(){
		$theme = $this->current_theme('directory');
		return '<link rel="stylesheet" href="'.base_url().'themes/'.$theme.'/style.css" />';
	}
	
	function Navigation($page){

		$query = $this->db->query("SELECT * FROM `pages` WHERE `in_nav` = 1 ORDER BY `order` ASC");

		if ($query->num_rows() > 0){
			$links = $query->result();
			$output = '<ul class="lcms-navigation">';
			foreach ($links as $link){
				$query = $this->db->query("SELECT * FROM `pages` WHERE `parent` = ?", array($link->name));
				$children = $query->result();
		
				if (count($children)){
					$suboutput = '<span class="lcms-subnavigation" style="display:none">';
					foreach ($children as $child){
						if ($child->redirect_url) $url = $child->redirect_url;
						else $url = site_url() . '' . $child->name;
						
						if ($child->name == $page) $suboutput .= '<span class="current"><a name="'.$child->name.'" href="'.$url.'">'.$child->title.'</a></span>';
						else $suboutput .= '<span><a name="'.$child->name.'" href="'.$url.'">'.$child->title.'</a></span>';
					}
					$suboutput .= '</span>';
				} else {
					$suboutput = '';
				}

				if ($link->redirect_url) $url = $link->redirect_url;
				else $url = site_url() . '' . $link->name;

				if ($link->name == $page || $link->name == $this->get_parent($page)) $output .= '<li class="current"><a name="'.$link->name.'" href="'.$url.'">'.$link->title.'</a>'.$suboutput.'</li>';
				else $output .= '<li><a name="'.$link->name.'"href="'.$url.'">'.$link->title.'</a>'.$suboutput.'</li>';
			}
			$output .= '</ul>';
			return $output;
		} else return false;
	}
	
	function Main_page(){
		$query = $this->db->query("SELECT * FROM `pages` WHERE `main` = 1");
		if ($query->num_rows() > 0){
			$page = $query->row();
			return $page->name;
		} else return false;
	}
	
	function Page($page){
		$query = $this->db->query("SELECT * FROM `pages` WHERE `name` = ?", array($page));
		if ($query->num_rows() > 0){
			$page = $query->row();
			if ($info) return $page->{$info};
			return $page;
		} else return false;
	}
	
	function Check_pseudo_page($page){
		$query = $this->db->query("SELECT * FROM `pages` WHERE `name` = ?", array($page));
		if ($query->num_rows()) return true;
		return false;
	}
	
	function Load_contents($page, $author_mode){
		if (is_object($page)) $page = $page->name;
		$query = $this->db->query("SELECT `location` FROM `contents` WHERE (`page` = ? OR `in_all` = 1) GROUP BY `location`", array($page));
		if ($query->num_rows() > 0){
			$rows = $query->result();
			foreach ($rows as $row){
				$locations[] = $row->location;
			}

			foreach ($locations as $location){
				if ($author_mode){
					$query = $this->db->query("SELECT * FROM `contents` WHERE `location` = ? AND (`page` = ? OR `in_all` = 1) AND `current` = 1 ORDER BY `order` ASC", array($location, $page));
				} else {
					$query = $this->db->query("SELECT * FROM `contents` WHERE `location` = ? AND (`page` = ? OR `in_all` = 1) AND `published` = 1 AND `current` = 1 ORDER BY `order` ASC", array($location, $page));
				}
				$contents[$location] = $query->result();
			}

			if ($this->author_mode) $contents['LCMS_AUTHOR'] = 1;
			return $contents;
		} else {
			if ($this->author_mode) $contents['LCMS_AUTHOR'] = 1;
			return $contents;
		}		
	}
	
	function Get_parent($name){
		$query = $this->db->query("SELECT * FROM `pages` WHERE `name` = ?", array($name));
		$page = $query->row();
		if ($page->parent){
			return $this->get_parent($page->parent);
		} else {
			return $page->name;
		}
		
	}
	
	function Get_content_by_id($id){
		$query = $this->db->query("SELECT * FROM `contents` WHERE `id` = ?", array($id));
		return $query->row();
	}
	
	function Essential_js_link(){
		$output = '<script type="text/javascript" src="'.base_url().'js/jquery.js"></script>';
		return $output;
	}
	
	
	function Essential_css_link(){
		$output = '<link rel="stylesheet" href="'.base_url().'css/style.css" />';
		return $output;
	}
	
	function Controller_css_link(){
		$output = '<link rel="stylesheet" href="'.base_url().'css/controller.css" />';
		$output .= '<link rel="stylesheet" href="'.base_url().'css/ui-lightness/jquery-ui-1.7.2.custom.css" />';

		
		$controls = $this->core_controls();
		
		foreach ($controls as $control){

		
			if (is_array($this->{$control}->css_includes)){
				foreach ($this->{$control}->css_includes as $css_include){
					$src = base_url() . 'css/' . $css_include;
					$output .= '<link rel="stylesheet" href="'.$src.'" />';	
				}
			} elseif ($this->{$control}->css_includes) {
			
				$src = base_url() . 'css/' . $this->{$control}->css_includes;
				$output .= '<link rel="stylesheet" href="'.$src.'" />';	
			}
		}

		return $output;
	}
	
	function Page_list($current, $return, $parent = false){
		$query = $this->db->query("SELECT * FROM `pages` WHERE `parent` = '' ORDER BY `name` DESC");

		if ($query->num_rows() > 0){

			$pages = $query->result();
			if ($parent) $output .= '<option value="">No Parent</option>';
			foreach ($pages as $page){
				if ($current == $page->name) $cur = ' selected="selected"';
				else $cur = '';
				$output .= "<option{$cur} value=\"{$page->name}\">{$page->title}</option>";
				$output .= $this->display_children($current, $page->name,1);
			}
			
			if ($return) return $output;
			else echo $output;
		}
	}
	
	function Display_children($current, $parent, $level) { 
		$children = $this->page_children($parent);

  		foreach($children as $child) { 
		    $more_child = $this->display_children($current, $child->name, $level+1); 
		    if ($current == $child->name) $cur = ' selected="selected"';
		    else $cur = '';
		    $child_text .= "<option{$cur} value=\"{$child->name}\">".str_repeat("&nbsp;&nbsp;",$level).$child->title."</option>" . $more_child;
		
		}
		return $child_text;
	} 
	
	function Has_children($name){
		$query = $this->db->query("SELECT * FROM `pages` WHERE `parent` = ?", array($name));
		if ($query->num_rows() > 0) return true;
		return false;
	}
	
	function Page_children($name){
		$query = $this->db->query("SELECT * FROM `pages` WHERE `parent` = ?", array($name));
		if ($query->num_rows() > 0){
			$pages = $query->result();
			return $pages;
		} else {
			return false;
		}
	}
	
	function New_page(){
		$title = htmlentities($this->input->post('title'), ENT_QUOTES);
		$name = $this->input->post('name');
		$name = preg_replace("/[^A-Za-z0-9-]+/",'_',$name);
		
		$layout 	= $this->input->post('layout');
		$in_nav 	= $this->input->post('in_nav');
		$parent 	= $this->input->post('parent');
		$searchable	= $this->input->post('searchable');
		$redirect	= $this->input->post('redirect');
		
		$query = $this->db->query("SELECT * FROM `pages` ORDER BY `order` DESC");
		
		if ($query->num_rows() > 0){
			$page = $query->row();
			$order = $page->order + 1;
		} else {
			$order = 1;
		}
		
		$sql = $this->db->insert_string('pages',array(
			'title'		=> $title,
			'name'		=> str_replace('_2F','/',$name),
			'layout'	=> $layout,
			'in_nav'	=> $in_nav,
			'parent'	=> $parent,
			'order'		=> $order,
			'redirect_url'	=> $redirect,
			'searchable'=> $searchable
		));
		$this->db->query($sql);
		echo 'success';
	}
	
	function Edit_page(){
		$page = $this->session->userdata('page');
		$title = htmlentities($this->input->post('title'), ENT_QUOTES);
		$name = $this->input->post('name');
		$name = preg_replace("/[^A-Za-z0-9-]+/",'_',$name);
		
		if ($page != $name){
			$query = $this->db->query("SELECT * FROM `pages` WHERE `name` = ?", array($name));
			if ($query->num_rows() > 0) echo '1'; die();
		}
		
		$layout 	= $this->input->post('layout');
		$in_nav 	= $this->input->post('in_nav');
		$parent 	= $this->input->post('parent');
		$searchable	= $this->input->post('searchable');
		$redirect	= $this->input->post('redirect');
				
		$sql = $this->db->update_string('pages',array(
			'title'		=> $title,
			'name'		=> str_replace('_2F','/',$name),
			'layout'	=> $layout,
			'in_nav'	=> $in_nav,
			'parent'	=> $parent,
			'redirect_url'	=> $redirect,
			'searchable'=> $searchable
		), "`name` = '$page'");
		
		$this->db->query($sql);
		$this->db->query("UPDATE `contents` SET `page` = ? WHERE `page` = ?", array($name, $page));
		echo 'success';
	}
	
	function Save_content($type, $content, $options, $class, $location, $page, $publish){
	
		if (strpos($page,'/')){
			$segments = explode('/',$page);
			$query = $this->db->query("SELECT * FROM `pages` WHERE `name` = ?", array($segments[0]));
			if ($query->num_rows()){
				$page = $segments[0];
			}
		}
		
		$query = $this->db->query("SELECT * FROM `contents` WHERE `location` = ? AND `page` = ? ORDER BY `order` DESC", array($location, $page));
		
		$bottom_most = $query->row();
		$order = $bottom_most->order + 1;
		
		$sql = $this->db->insert_string('contents', array(
			'page' 		=> $page,
			'location' 	=> $location,
			'type' 		=> $type,
			'content' 	=> $content,
			'options'	=> $options,
			'order' 	=> $order,
			'class'		=> $class,
			'current'	=> 1,
			'datetime'	=> date('Y-m-d H:i:s'),
			'published'	=> $publish
		));
		
		$this->db->query($sql);
		
		$id = $this->db->insert_id();
		
		$sql = $this->db->update_string('contents', array(
			'vid'		=> $id
		), "`id` = '{$id}'");
		
		$this->db->query($sql);
		
		return $id;
		
	}
	
	function Update_content($id, $type, $content, $options, $class, $location, $page, $publish){
		
		$query = $this->db->query("SELECT * FROM `contents` WHERE `id` = ?", array($id));
		$old_content = $query->row();
		
		if ($publish){
			$old_published = 0;
		} else {
			$old_published = $old_content->published;
		}
		
		if ($old_content->content == $content && $old_content->options == $options && $old_content->class == $class){
			$sql = $this->db->update_string('contents', array(
				'published'	=> $publish
			),"`id` = '{$id}'");
			$this->db->query($sql);
			return;
		}
		
		$sql = $this->db->insert_string('contents', array(
			'page'		=> $old_content->page,
			'type'		=> $old_content->type,
			'content'	=> $old_content->content,
			'options'	=> $old_content->options,
			'location'	=> $old_content->location,
			'order'		=> $old_content->order,
			'class'		=> $old_content->class,
			'current'	=> 0,
			'datetime'	=> $old_content->datetime,
			'published'	=> $old_published,
			'archived'	=> $old_content->archived,
			'in_all'	=> $old_content->in_all,
			'vid'		=> $id
		));
		$this->db->query($sql);
		
		$sql = $this->db->update_string('contents', array(
			'content'	=> $content,
			'options'	=> $options,
			'class'		=> $class,
			'current'	=> 1,
			'datetime'	=> date('Y-m-d H:i:s'),
			'published'	=> $publish
		),"`id` = '{$id}'");
		
		$this->db->query($sql);

		return $id;
	}
	
	function Save_order($order){
		if ($order){
			$pages = explode(',',$order);
			$i = 1;
			foreach ($pages as $page){

				$this->db->query("UPDATE `pages` SET `order` = ? WHERE `name` = ?", array($i, $page));
				$i++;
			}
			echo 'success';
		} else return false;
	}
	
	function Save_content_order($order){
		if ($order){
			$ids = explode(',',$order);
			$i = 1;
			foreach ($ids as $id){

				$this->db->query("UPDATE `contents` SET `order` = ? WHERE `id` = ?", array($i, $id));
				$i++;
			}
			echo 'success';
		} else return false;
	}
	
	function Delete_content($id){
		$query = $this->db->query("SELECT * FROM `contents` WHERE `id` = ?", array($id));
		$content = $query->row();
		
		$this->db->query("DELETE FROM `contents` WHERE `vid` = ?", array($content->vid));
		echo 'success';		
	}
	
	function Delete_page($page){

		$this->db->query("DELETE FROM `pages` WHERE `name` = ?", array($page));
		$this->db->query("UPDATE `pages` SET `parent` = '' WHERE `parent` = ?", array($page));
		
		echo 'success';
	}
	
	function Published($id, $published){
		$sql = $this->db->update_string('contents', array(
			'published'	=> $published
		),"`id` = '{$id}'");
		
		$this->db->query($sql);
		
		$sql = $this->db->update_string('contents', array(
			'published'	=> !$published
		),"`vid` = '{$id}' AND `id` <> '{$id}'");
				
		if ($this->db->query($sql)) echo 'success';
		else return 'failed';
	}
	
	function Common($id, $common){
		$query = $this->db->query("SELECT * FROM `contents` WHERE `id` = ?", array($id));
		$content = $query->row();
	
		$sql = $this->db->update_string('contents', array(
			'in_all'	=> $common
		),"`vid` = '{$content->vid}'");
		
		
		if ($this->db->query($sql)) echo 'success';
		else return 'failed';
	}
	
	function Get_content_revisions($id){
		$query = $this->db->query("SELECT * FROM `contents` WHERE `id` = ?", array($id));
		$content = $query->row();
		
		$query = $this->db->query("SELECT * FROM `contents` WHERE `vid` = ? ORDER BY `datetime` DESC", array($content->vid));
		return $query->result();
	}
	
	function Commit_revision($current, $published){
		$query = $this->db->query("SELECT * FROM `contents` WHERE `id` = ?", array($current));
		$new_current_content = $query->row();
		
		$query = $this->db->query("SELECT * FROM `contents` WHERE `vid` = ? AND `current` = 1", array($new_current_content->vid));
		$current_content = $query->row();
		
		$this->db->query("UPDATE `contents` SET `current` = 0 WHERE `vid` = ?", array($current_content->vid));
		$this->db->query("UPDATE `contents` SET `current` = 1, `order` = ? WHERE `id` = ?", array($current_content->order, $new_current_content->id));
		$this->db->query("UPDATE `contents` SET `published` = 0 WHERE `vid` = ?", array($current_content->vid));
		$this->db->query("UPDATE `contents` SET `published` = 1 WHERE `id` = ?", array($published));
	}

	function Subpage_of($parent, $child){
		$query = $this->db->query("SELECT * FROM `pages` WHERE `name` = ?", array($child));
		$child = $query->row();
		if ($child->parent == $parent) return true;
		
		return false;
	}
}

