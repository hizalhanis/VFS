<?php

// Logout controller

class Logout extends CI_Controller {
	
	function Index(){
		$this->user->logout();
		redirect('login');
	}
	
}