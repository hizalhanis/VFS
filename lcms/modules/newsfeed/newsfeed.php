<?php

class newsfeed extends Model {
	
	function newsfeed(){
		parent::Model();

		$this->name				= 'News Feed';
		$this->namespace		= 'newsfeed';
		$this->css_namespace	= 'lcms-newsfeed';
				
		$this->js_includes		= 'lcms.newsfeed.js';
		$this->css_includes 	= 'lcms.newsfeed.css';
		
		$this->js_resources 	= '';
		$this->css_resources	= '';
		
	}
	
	function Hooks(){

	}
	
	
	function PreHTML(){
		$prehtml = $this->load->view('modules/newsfeed/form-new', $data, true);
		$prehtml .= $this->load->view('modules/newsfeed/form-edit', $data, true);
		return $prehtml;
	}
	
	
	function HTML($content, $author_mode){
		
		$data['content'] 	= $content;
		
		$query = $this->db->query("SELECT * FROM `contents` WHERE `id` = ? ", array($content->id));
		$data['feed_data'] = $feed_data = $query->row();
		
		$option = json_decode($feed_data->options);
		
		$query = $this->db->query("SELECT * FROM `news` WHERE `name` = ? AND `status` = 'Published' ORDER BY `date` DESC LIMIT {$option->limit}", array($feed_data->content));
		$data['feeds'] = $query->result();
		
		
		$query = $this->db->query("SELECT * FROM `contents` WHERE `type` = 'news' AND `content` = ?", array($feed_data->content));
		$data['news_content'] = $query->row();
				

		return $this->load->view('modules/newsfeed/html',$data,true);
		
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
	
	
	
	
	function News_list(){
		$query = $this->db->query("SELECT * FROM `news` GROUP BY `name` ORDER BY `name` ASC");
		foreach ($query->result() as $news_item){
			$output .= "<option value=\"{$news_item->name}\">{$news_item->name}</option>";
		}
		echo $output;
	}	
	
}