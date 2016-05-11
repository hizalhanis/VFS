<?php

class Video extends Model {
	
	function Video(){
		parent::Model();

		$this->name				= 'Video';
		$this->namespace		= 'video';
		$this->css_namespace	= 'lcms-video';
				
		$this->js_includes		= 'lcms.video.js';
		$this->css_includes 	= 'lcms.video.css';
		
		$this->js_resources 	= '';
		$this->css_resources	= '';
		
	}
	
	function Hooks(){

	}
	
	
	function PreHTML(){
		$prehtml = $this->load->view('modules/video/form-new', $data, true);
		$prehtml .= $this->load->view('modules/video/form-edit', $data, true);
		return $prehtml;
	}
	
	
	function HTML($content, $author_mode){
		
		$data['content'] 	= $content;
		$data['url'] 		= site_url()  . 'p/' . $content->content . '/do_search';

		return $this->load->view('modules/video/html',$data,true);
		
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
	
	
}