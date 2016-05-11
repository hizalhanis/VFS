<?php

class File extends Controller {
	var $author_mode;
	
	function File(){
		parent::Controller();	
		$this->load->model('application/models/user');
		$this->load->model('application/models/cms');
		$this->load->model('application/models/controls');
		$this->load->model('application/models/media');

		$this->load->helper('content_helper');
		
		if (!$this->user->is_alive()) redirect(base_url());
	}
	
	function index(){
		$data['category_list'] = $this->media->category_list();
		$this->load->view('interface/file', $data);
	}
	
	function select($cat){
		$data['file_list'] = $this->media->file_list($cat);
		$this->load->view('interface/file_select', $data);	
	}
	
	function categories(){
		echo $this->media->category_list();		
	}
	
	function upload(){
		$success = $this->media->upload();
		echo '<script language="javascript" type="text/javascript">window.top.window.stopUpload('.$success.');</script>';
	}
	
	function files($cat){
		echo $this->media->file_list($cat);
	}
	
	function info($id){
		echo $this->media->file_preview($id);
	}
	
	function delete($id){
		$this->db->query("DELETE FROM `media` WHERE `id` = '$id'");
	}
	

	

}
