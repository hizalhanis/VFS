<?php

class User extends CI_Model {
	var $session_id;
	var $username;
	var $data;
	var $hash;
	
	var $allowed_uri = array('login', 'mobile','api');
	
	function User_list($filter = null){
		
		$query = $this->db->query("SELECT * FROM `users`");
		return $query->result();
	}
	
	function User(){
		parent::__construct();
		
		session_start();
		$this->session_id = session_id();
		$this->ip_address = $_SERVER['REMOTE_ADDR'];
		$this->timeout 	  = time() + (3600 * 24);
		$this->username	  = $_SESSION['admin_user'];
				
		if (!$this->is_alive() && !in_array($this->uri->segment(1), $this->allowed_uri)) redirect('login');
	}
	
	function Get_acname_by_username($username){
		$query = $this->db->query("SELECT * FROM `users` WHERE `username` = ?", array($username));
		$user = $query->row();
		
		$ac->plain	= $user->firstname . ' ' . $user->lastname . ' (' . $user->division . ')';
		$ac->id		= $user->username;
		$ac->html	= $user->firstname . ' ' . $user->lastname . ' (' . $user->division . ')';
		
		return $ac;
		
	}
	
	function Get_user_by_id($id){
		$query = $this->db->query("SELECT * FROM `users` WHERE `id` = ?", array($id));
		return $query->row();
	}
	
	
	function Get_fullname($username){
		$query = $this->db->query("SELECT * FROM `users` WHERE `username` = '$username'");
		$user = $query->row();
		
		if ($user->lastname){
			return $user->firstname . ' ' . $user->lastname;
		}
		return $user->firstname;
	}

	
	function Login($username, $password){

		if ($query = $this->db->query("SELECT * FROM `users` WHERE `username` = '$username'")){
			$user_data = $query->row();
			if (md5($password) == $user_data->password && $user_data->flag != '2'){
				$_SESSION['admin_user'] = $this->username = $username;
				$_SESSION['hash'] = $this->hash = md5(mt_rand(100000,999999));
				$_SESSION['name'] = $this->data('firstname');
				$this->db->query("UPDATE `users` SET `session` = '{$this->session_id}', `ip` = '{$this->ip_address}', `flag` = '1', `timeout` = '{$this->timeout}', `hash` = '{$this->hash}' WHERE `username` = '$username'");
				$mpk_long = md5(base64_encode($password));
				$_SESSION['mpk'] = $mpk[0] . $mpk[2] . $mpk[5] . $mpk[1] . $mpk[0] . $mpk[3] . $mpk[4];
				
				$session_module_data = json_decode($user_data->data);
				foreach ($session_module_data as $module => $data){
					foreach ($data as $key => $value){
						$this->session->set_userdata(array(strtolower($module.'_'.$key) => $value));
					}
				}
				
				return true;
			} else {
				return false;
			}			
		} else {
			return false;
		}
	}
	
	function Is_alive(){
		$query = $this->db->query("SELECT * FROM `users` WHERE `username` = '{$this->username}'");
		$result = $query->result();
		$user_data = $result[0];
		if ($user_data->timeout > time()){
			if (time() > ($user_data->timeout - 1800)) $this->update_hash();
			$this->db->query("UPDATE `users` SET `timeout` = '{$this->timeout}' WHERE `username` = '{$this->username}'", FALSE, TRUE, FALSE);
			return true;
		} else {
			$this->logout();
			return false;
		}
	}
	
	function Update_hash(){
		$_SESSION['hash'] = $this->hash = md5(mt_rand(100000,999999));
		$this->db->query("UPDATE `users` SET `hash` = '{$this->hash}' WHERE `username` = '{$this->username}'", FALSE, TRUE, FALSE);
	}
	
	function Data($var = null){
		if (!$this->data){
			$query = $this->db->query("SELECT * FROM `users` WHERE `username` = '{$this->username}'");
			$this->data = $query->row();
		}
		if ($var) return $this->data->$var;
		else return $this->data;
	}
	
	function Update_user_data($user_data){
		$sql = $this->db->update_string('users', $user_data, "`username` = '{$this->username}'");
		$this->db->query($sql);
	}
	
	function Logout(){
		$_SESSION['hash'] = '';
		$_SESSION['admin_user'] = '';
		$_SESSION['name'] = '';
	}
	
	function group_list(){
		$query = $this->db->query("SELECT * FROM `groups` ORDER BY `name` ASC");
		$groups = $query->result();
		foreach($groups as $group){
			$group_list[$group->name] = $group->name;
		}

		return $group_list;
	}
	
	function Auth_pass($pass = null){
		if (!$pass) $pass = $this->input->post('pass');
		$query = $this->db->query("SELECT * FROM `users` WHERE `role` = 'Superadmin'");
		$superadmin = $query->row();
		
		if ($superadmin->password == md5($pass)) return true;
		
		return false;
	}
	
	function Get_users($sql_filter){
		$query = $this->db->query("SELECT * FROM `users` {$sql_filter}");
		return $query->result();
	}
	
	function Has_permission($action_type, $module){
		if ($this->is_super_admin()) return true;
		
		$action_type = ucfirst($action_type);
		
		$user_data = $this->data();
		$query = $this->db->query("SELECT * FROM `permissions` WHERE `user_type` = '{$user_data->type}'");
		$permission = $query->row();

		if ($permission->super) return true;
		
		if ($permission->module == '*') return true;
		$modules = explode(',', $permission->module);
		if (!in_array($module,$modules)) return false;		

		$actions = json_decode($permission->action);
		$p_action = $actions->{$module};
		
		if ($p_action->{$action_type}) return true;
		return false;		
	}
	
	function Is_super_admin(){
		$user_data = $this->data();
		$query = $this->db->query("SELECT * FROM `permissions` WHERE `user_type` = '{$user_data->type}'");
		$permission = $query->row();
		
		if ($permission->super) return true;
		else return false;
	}
	
	function Has_access($controller){
		if ($this->is_super_admin()) return true;
		
		$query = $this->db->query("SELECT * FROM `modules` WHERE `controller_name` = '$controller'");
		$module = $query->row();
		
		if ($module->common) return true;
		
		$user_data = $this->data();
		$query = $this->db->query("SELECT * FROM `permissions` WHERE `user_type` = '{$user_data->type}'");
		$permission = $query->row();
		
		if ($permission->super) return true;
		
		if ($permission->module == '*') return true;
		$modules = explode(',', $permission->module);
		
		if (in_array($module->name,$modules)) return true;
		return false;
	}
	
	function Check_superadmin($id){
		$query = $this->db->query("SELECT * FROM `users` WHERE `id` = ?", array($id));
		$user = $query->row();
		
		$query = $this->db->query("SELECT * FROM `permissions` WHERE `user_type` = ?", array($user->type));
		$permission = $query->row();
		
		if ($permission->super) return true;
		else return false;
	}
		
	
	function Has_case_access($case){
		$user = $this->user->data();
		
		if ($user->type == 'Superadmin'){
			return true;
		}
		
		if (in_array($user->id, $members)) return true;
		
		return false;
	}
}

