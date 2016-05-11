<?php

class Searchbox extends Model {
	
	function Searchbox(){
		parent::Model();

		$this->name				= 'Search Box';
		$this->namespace		= 'searchbox';
		$this->css_namespace	= 'lcms-searchbox';
				
		$this->js_includes		= 'lcms.searchbox.js';
		$this->css_includes 	= 'lcms.searchbox.css';
		
		$this->js_resources 	= '';
		$this->css_resources	= '';
		
	}
	
	function Hooks(){

	}
	
	
	function PreHTML(){
		$prehtml = $this->load->view('modules/searchbox/form-new', $data, true);
		$prehtml .= $this->load->view('modules/searchbox/form-edit', $data, true);
		return $prehtml;
	}
	
	
	function HTML($content, $author_mode){
		
		$data['content'] 	= $content;
		$data['url'] 		= site_url()  . 'p/' . $content->content . '/do_search';

		return $this->load->view('modules/searchbox/html',$data,true);
		
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