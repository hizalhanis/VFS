<?php

// Login Controller

class Login extends CI_Controller {
	
	function Index(){
		$this->load->view('login');
	}
	
	function Auth(){
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		
		if ($this->user->login($username, $password)){
			redirect('main');
		} else {
			redirect('login/err');
		}
		
	}
	
	function Err(){
		$data['error'] = 'Incorrect username or password';
		$this->load->view('login', $data);
	}
	
}