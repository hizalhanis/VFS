<?php

class Users extends CI_Controller {
	
	function Index(){
		if ($this->user->data('type') == 'Superadmin'){
			$data['users'] = $this->user->get_users();			
		} else {
			$data['users'] = $this->user->get_users(" ");
		}
		
		$data['tab'] = 'users';
	
		$this->load->view('header', $data);
		$this->load->view('users/index', $data);
		$this->load->view('footer', $data);
	}
	
	function Add($do){
	
		$data['tab'] = 'new-user';
		
		
		if ($do == 'do'){
			
			$this->db->insert('users', array(
				'username'		=> $this->input->post('username'),
				'password'		=> md5($this->input->post('password')),
				'firstname'		=> $this->input->post('firstname'),
				'type'			=> $this->input->post('type'),
				'added_on'		=> date('Y-m-d H:i:s'),
				'added_by'		=> $this->user->data('username')
			));
			
			redirect('users');
			
		} else {
		
			
			$this->load->view('header', $data);
			$this->load->view('users/add', $data);
			$this->load->view('footer', $data);

		}
	}
	
	function Edit($id, $do){
	
		
		if ($do == 'do'){
		
			$user = $this->user->get_user_by_id($id);
			
			if ($this->input->post('password')){
				$password = md5($this->input->post('password'));
			} else {
				$password = $user->password;
			}
			
			$data = array(
				'password'		=> $password,
				'firstname'		=> $this->input->post('firstname'),
				'modified_on'	=> date('Y-m-d H:i:s'),
				'modified_by'	=> $this->user->data('username')
			);
			
			if ($this->input->post('type')){
				$data['type'] = $this->input->post('type');
			}
		
			$this->db->update('users', $data, "`id` = '$id'");
			
			redirect('users');
			
		} else {
		
			$data['user'] = $this->user->get_user_by_id($id);
		
			$this->load->view('header', $data);
			$this->load->view('users/edit', $data);
			$this->load->view('footer', $data);
			
		}
		
	}
	
	function View(){
		$data['user'] = $this->user->get_user_by_id($id);
		
		$this->load->view('header', $data);
		$this->load->view('users/view', $data);
		$this->load->view('footer', $data);
	}
	
	function Delete($id, $do){
		if ($do == 'do'){
			$this->db->query("DELETE FROM `users` WHERE `id` = ?", array($id));
			echo 'ok';
		}
	}
	
	
	function Search(){
		$search = $this->input->post('q');
		$query = $this->db->query("SELECT * FROM `users` WHERE `firstname` LIKE '$search%' ORDER BY `firstname` ASC");
		$items = $query->result();
				
		foreach ($items as $item){
			$response[] = array($item->id, $item->firstname);
		}
		
		header('Content-type: application/json');
		echo json_encode($response);
	}
	
	function SearchById($id){
		$search = $this->input->post('q');
		$query = $this->db->query("SELECT * FROM `users` WHERE `id` = ?", array($id));
		$items = $query->result();
				
		foreach ($items as $item){
			$response[] = array($item->firstname, $item->id);
		}
		
		header('Content-type: application/json');
		echo json_encode($response);
	}
	
	
}