<?php

class User extends Model {
	var $session_id;
	var $username;
	var $data;
	var $hash;
	
	function User(){
		parent::Model();
		session_start();
		$this->session_id = session_id();
		$this->ip_address = $_SERVER['REMOTE_ADDR'];
		$this->timeout 	  = time() + 99999999;
		$this->username	  = $_SESSION['plexcms_admin_user'];
	}
	
	function login($username, $password){
		if ($query = $this->db->query("SELECT * FROM `users` WHERE `username` = '$username'")){
		 	$result = $query->result();
			$user_data = $result[0];
			if (md5($password) == $user_data->password && $user_data->flag != '2'){
				$_SESSION['plexcms_admin_user'] = $this->username = $username;
				$_SESSION['hash'] = $this->hash = md5(mt_rand(100000,999999));
				$_SESSION['name'] = $this->data('firstname');
				$this->db->query("UPDATE `users` SET `session` = '{$this->session_id}', `ip` = '{$this->ip_address}', `flag` = '1', `timeout` = '{$this->timeout}', `hash` = '{$this->hash}' WHERE `username` = '$username'");
				return true;
			} else {
				return false;
			}			
		} else {
			return false;
		}
	}
	
	function is_alive(){
		$query = $this->db->query("SELECT * FROM `users` WHERE `username` = '{$this->username}'");
		$result = $query->result();
		$user_data = $result[0];
		if ($user_data->timeout > time()){
			if (time() > ($user_data->timeout)) $this->update_hash();
			$this->db->query("UPDATE `users` SET `timeout` = '{$this->timeout}' WHERE `username` = '{$this->username}'");
			return true;
		} else {
			$this->logout();
			return false;
		}
	}
	
	function update_hash(){
		$_SESSION['hash'] = $this->hash = md5(mt_rand(100000,999999));
		$this->db->query("UPDATE `users` SET `hash` = '{$this->hash}' WHERE `username` = '{$this->username}'");
	}
	
	function data($var){
		$query = $this->db->query("SELECT * FROM `users` WHERE `username` = '{$this->username}'");
		$result = $query->result();
		$this->data = $result[0];
		if (!$var) return $this->data;
		return $this->data->$var;
	}
	
	function logout(){
		$_SESSION['hash'] = '';
		$_SESSION['plexcms_admin_user'] = '';
		$_SESSION['name'] = '';
	}
	
	
}

?>