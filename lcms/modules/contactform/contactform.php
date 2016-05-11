<?php

class Contactform extends Model {
	
	function Contactform(){
		parent::Model();

		$this->name				= 'Contact Form';
		$this->namespace		= 'contactform';
		$this->css_namespace	= 'lcms-contactform';
				
		$this->js_includes		= 'lcms.contactform.js';
		$this->css_includes 	= 'lcms.contactform.css';
		
		$this->js_resources 	= '';
		$this->css_resources	= '';
		
	}
	
	function Hooks(){

	}
	
	
	function PreHTML(){
		$prehtml = $this->load->view('modules/contactform/form-new', $data, true);
		$prehtml .= $this->load->view('modules/contactform/form-edit', $data, true);
		return $prehtml;
	}
	
	
	// Output on page load / on request load
	function HTML($content, $author_mode){
		
		$data['content'] = $content;
		$data['author_mode'] = $author_mode;
		return $this->load->view('modules/contactform/html',$data,true);
		
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