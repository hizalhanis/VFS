<?php

class Searchresult extends Model {
	
	function Searchresult(){
		parent::Model();

		$this->name				= 'Search Result';
		$this->namespace		= 'searchresult';
		$this->css_namespace	= 'lcms-searchresult';
				
		$this->js_includes		= 'lcms.searchresult.js';
		$this->css_includes 	= 'lcms.searchresult.css';
		
		$this->js_resources 	= '';
		$this->css_resources	= '';
		
	}
	
	function Hooks(){

	}
	
	
	function PreHTML(){
		$prehtml = $this->load->view('modules/searchresult/form-new', $data, true);
		$prehtml .= $this->load->view('modules/searchresult/form-edit', $data, true);
		return $prehtml;
	}
	
	
	function HTML($content, $author_mode){
		
		$data['content'] 	= $content;
		$data['url'] 		= base_url()  . 'p/' . $content->page . '/do_search';
		
		
		switch ($_SESSION['param1']){
			case "do_search":
				$search_term = $this->input->post('q');
				if (trim($search_term)) $do_search = true;
				break;
		}
		
		if ($do_search){
			$query = $this->db->query("SELECT * FROM `pages`");
			foreach ($query->result() as $page){
				$page_title[$page->name] = $page->title;
			}
				
			$query = $this->db->query("SELECT * FROM `contents` WHERE `content` LIKE ? GROUP BY `page` ",array('%'.$search_term.'%'));
			$data['results'] 	= $query->result();
			$data['do_search'] 	= true;
			$data['search_term']= $search_term;
			$data['page_title'] = $page_title;
			
		}	
			
		return $this->load->view('modules/searchresult/html',$data,true);
		
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
	
	
	
	
	function Results_list(){
		$query = $this->db->query("SELECT * FROM `pages`");
		foreach ($query->result() as $page){
			$page_title[$page->name] = $page->title;
		}
		$query = $this->db->query("SELECT * FROM `contents` WHERE `type` = 'searchresult' ORDER BY `page` ASC");
		foreach ($query->result() as $result_item){
			$page = $page_title[$result_item->page];
			$output .= "<option value=\"{$result_item->page}\">{$page}</option>";
		}
		echo $output;
	}

	
}