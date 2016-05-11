<?php

class Richtext extends Model {
	
	function Richtext(){
		parent::Model();

		$this->name				= 'Rich Text';
		$this->namespace		= 'richtext';
		$this->css_namespace	= 'lcms-richtext';
				
		$this->js_includes		= 'lcms.richtext.js';
		$this->css_includes 	= 'lcms.richtext.css';
		
		$this->js_resources 	= '';
		$this->css_resources	= '';
		
	}
	
	function Hooks(){

	}
	
	
	function PreHTML(){
		$prehtml = $this->load->view('modules/richtext/form-new', $data, true);
		$prehtml .= $this->load->view('modules/richtext/form-edit', $data, true);
		return $prehtml;
	}
	
	
	function HTML($content, $author_mode){
		
		$data['content'] = $content;
		return $this->load->view('modules/richtext/html',$data,true);
		
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