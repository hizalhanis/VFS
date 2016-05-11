<?php

class Admin extends Controller {
	function Admin(){
		parent::Controller();
		$this->load->model('application/models/user');
		$this->load->helper('form');
		
		if ($this->uri->segment(2) != 'login' && $this->uri->segment(2) != 'auth'){
			if (!$this->user->is_alive()){
				redirect('admin');
			}
		}
	}
	
	function index(){
		$this->login();
	}
	
	function login($err){
		$trial = $this->session->userdata('trial');
		if ($trial >= 3){
			$this->load->view('interface/locked');
		} else {
			if ($err) $data['err'] = true;
			$this->load->view('interface/login',$data);
		}
	}

	
	function auth(){
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		
		$trial = $this->session->userdata('trial');
		if ($trial >= 5){
			$this->load->view('interface/locked');
			return;
		}
		
		if ($this->user->login($username, $password)){
			$this->session->set_userdata(array('trial'=>0));
			redirect('page');
		} else {
			$trial++;
			$this->session->set_userdata(array('trial'=>$trial));
			
			redirect('admin/login/err');			
		}
		
	}
	
	function logout(){
		$this->user->logout();
		redirect('page');
	}
	
	function users($alert,$error){
	
		$query = $this->db->query("SELECT * FROM `users`");
		$data['users'] = $query->result();
		$data['me'] = $this->user->data();
		$data['alert'] = $alert;
		$data['error'] = $error;
		$this->load->view('interface/header');
		$this->load->view('interface/users/users',$data);
		$this->load->view('interface/footer');
	}
	
	function add_user($error){
		$data['me'] = $this->user->data();
		$data['error'] = $error;
		$form->username = $this->input->post('username');
		$form->firstname= $this->input->post('firstname');
		$form->lastname = $this->input->post('lastname');
		$form->email    = $this->input->post('email');
		$data['form'] = $form;

		$this->load->view('interface/header');
		$this->load->view('interface/users/users_add',$data);
		$this->load->view('interface/footer');

	}
	
	function create_user(){
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$rpassword= $this->input->post('repeat_password');
		$firstname= $this->input->post('firstname');
		$lastname = $this->input->post('lastname');
		$email    = $this->input->post('email');
		$type     = $this->input->post('type');
		
		$query = $this->db->query("SELECT * FROM `users` WHERE `username` = '$username'");
		if ($query->num_rows() > 0) $user_exists = true;
		
		if ($user_exists){
			$error = true;
			$error_msg[] = "Username already taken.";
		}
		
		if (!$username || strlen($username) < 4){
			$error = true;
			$error_msg[] = "Username must be at least 4 characters long.";
		}
		
		if (strlen($password) < 6){
			$error = true;
			$error_msg[] = "Password must be at least 6 characters long.";
		}
		
		if ($password != $rpassword){
			$error = true;
			$error_msg[] = "Password supplied does not match.";
		}
		
		if ($error){
			$this->add_user(implode('<br />',$error_msg));
			return;
		}
	
		$sql = $this->db->insert_string('users',array(
			'username' => $username,
			'password' => md5($password),
			'firstname'=> $firstname,
			'lastname' => $lastname,
			'email'	   => $email,
			'type'	   => $type
		));
		$this->db->query($sql);
		
		$this->users('User &quot;'.$username.'&quot has been created.');
		
		
	}
	

	function edit_user($id,$error){
		$me = $this->user->data();
		$query = $this->db->query("SELECT * FROM `users` WHERE `id` = '$id'");
		$user = $query->row();
		if ($user->type == 'Super Admin' && $user->id != $me->id){
			$this->users('','You cannot edit this user.');
			return;
		}
		if ($me->type == 'Admin' && $user->id != $me->id){
			$this->users('','You cannot edit this user.');
			return;
		}
		$data['error'] = $error;
		
		if ($error){
			$firstname= $this->input->post('firstname');
			$lastname = $this->input->post('lastname');
			$email    = $this->input->post('email');
			$user->firstname = $firstname;
			$user->lastname = $lastname;
			$user->email = $email;
		} else {
			$data['user'] = $user;
		}
		
		$this->load->view('interface/header');
		$this->load->view('interface/users/users_edit',$data);
		$this->load->view('interface/footer');

		
	}
	
	function update_user($id){
		$password = $this->input->post('password');
		$rpassword= $this->input->post('repeat_password');
		$firstname= $this->input->post('firstname');
		$lastname = $this->input->post('lastname');
		$email    = $this->input->post('email');
		$type     = $this->input->post('type');
		
	
		if ($password){
		
			if (strlen($password) < 6){
				$error = true;
				$error_msg[] = "Password must be at least 6 characters long.";
			}
			
			if ($password != $rpassword){
				$error = true;
				$error_msg[] = "Password supplied does not match.";
			}
		}
		
		if ($error){
			$this->edit_user($id,implode('<br />',$error_msg));
			return;
		}
		
		
		$sql = $this->db->update_string('users',array(
			'password' => md5($password),
			'firstname'=> $firstname,
			'lastname' => $lastname,
			'email'	   => $email,
		),"`id` = '$id'");
		
		$this->db->query($sql);
		
		$this->users('User has been updated.');

	}
	
	function delete_user($id){
		$me = $this->user->data();
		$query = $this->db->query("SELECT * FROM `users` WHERE `id` = '$id'");
		$user = $query->row();
		if ($id == $me->id){
			$this->users('','You cannot delete yourself.');
			return;
		}
		
		if ($user->type == 'Super Admin'){
			$this->users('','Super Admin cannot be deleted.');
			return;
		}
		
		$this->db->query("DELETE FROM `users` WHERE `id` = '$id'");
		
		$this->users('User <strong>'.$user->username.'</strong> has been deleted.');

	}
	
	function forgot(){
		$this->load->view('interface/forgot');
	}
	
	function reset(){
		$username = $this->input->post('username');
		$email = $this->input->post('email');
		
		$query = $this->db->query("SELECT * FROM `users` WHERE `username` = '$username' AND `email` = '$email'");
		if ($query->num_rows()){
			$user = $query->row();
			$new_pass = mt_rand(100000, 999999);
			$enc_pass = md5($new_pass);
			$this->db->query("UPDATE `users` SET `password` = '$enc_pass' WHERE `id` = '{$user->id}'");
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: Live CMS <shafiq@swiftlabs.com>' . "\r\n";

			$message = "Hello {$user->username},<br /><br />You have requested to reset your password. Your new password is <b>$new_pass</b>";
						
		//	mail('shafiq@swiftlabs.com', "Password Reset", $message, $headers);
			$data['new_pass'] = $new_pass;
			$this->load->view('interface/reset_pass',$data);
		} else {
			$this->load->view('interface/reset_failed');
		}
	}
	
	function themes(){
		$query = $this->db->query("SELECT * FROM `themes`");
		$data['themes'] = $query->result();
		$this->load->view('interface/header');
		$this->load->view('interface/themes/themes',$data);
		$this->load->view('interface/footer');

	}
	
	function save_theme(){
		$name = $this->input->post('name');
		$directory = $this->input->post('directory');
		$author = $this->input->post('author');
		$description = $this->input->post('description');
		
		$sql = $this->db->insert_string('themes',array(
			'name' => $name,
			'directory' => $directory,
			'author' => $author,
			'description' => $description
		));
		
		$this->db->query($sql);
		$id = $this->db->insert_id();
		
		$style = 'div.lcms-image-form {
	padding: 10px;
}

a.lcms-content-image {
	background-image: url(image.png);
}

div.lcms-gallery-item img {
	width: auto;
	height: 100%;
}

div.lcms-gallery-item {
	width: 80px;
	height: 60px;
	float: left;
	margin: 5px;
	padding: 10px;
}

br.lcms-gallery-clear {
	clear:both;
}';

		

		mkdir('themes/'.$directory.'/');
		$res = fopen('themes/'.$directory.'/style.css','w+');
		fwrite($res, $style);
		fclose($res);

		
		redirect('admin/new_layout/'.$id.'/main');

	}

	function view_theme($id){
		$query = $this->db->query("SELECT * FROM `theme_layout` WHERE `theme_id` = '$id'");
		$data['layouts'] = $query->result();
		$data['theme_id'] = $id;
		
		$this->load->view('interface/header');
		$this->load->view('interface/themes/view_theme',$data);
		$this->load->view('interface/footer');


		
	}
	
	function new_layout($id,$layout_name){
		$data['layout_name'] = $layout_name;
		$data['theme_id'] = $id;
		$query = $this->db->query("SELECT * FROM `media` WHERE `type` = 'Images'");
		$data['images'] = $query->result();
		$this->load->view('interface/header');
		$this->load->view('interface/themes/theme_editor',$data);
		$this->load->view('interface/footer');

	}

	function save_layout(){
		$layout_name = $this->input->post('layout_name');
		$theme_id = $this->input->post('theme_id');
		$css = $this->input->post('css');
		$html = $this->input->post('html');	
		$layout_id = $this->input->post('layout_id');
		
		$query = $this->db->query("SELECT * FROM `themes` WHERE `id` = '$theme_id'");
		$theme = $query->row();
		
		if ($layout_id){
			$sql = $this->db->update_string('theme_layout',array(
				'name' => $layout_name,
				'css' => $css,
				'html' => $html
			),"`id` = '$layout_id'");	
		} else {
			$sql = $this->db->insert_string('theme_layout',array(
				'name' => $layout_name,
				'theme_id' => $theme_id,
				'css' => $css,
				'html' => $html
			));
		}
		
		$this->db->query($sql);
		
		mkdir('themes/'.$theme->directory.'/');
		
		$res = fopen('themes/'.$theme->directory.'/'.$layout_name.'.php','w+');
		
		$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<head>
		<title><?=$title?></title>
		<style>
		
#navigation {
	padding-top: 0;
	height: 35px;
}

#navigation ul {
	padding: 0 10px;
	margin: 0;
	list-style: none;
}

#navigation ul li {
	position: relative;
    display: block;
    float: left; 
	width: auto;
}

#navigation ul li a, #navigation ul li a:visited {
	display: block;
	float: left;
	color: #656565;
	padding: 9px 10px 9px 10px;
	font-size: 12px;
	font-weight: bold;
	text-transform: uppercase;
}

#navigation ul li.current a {
	color: #FFF;
	background: #656565
}

		'.$css.'
		</style>
		<?=$headers?>
	</head>
	<body>
	<div id="container">
		'.$html.'
	</div>
	</body>
</html>';
		
		fwrite($res, $html);
		fclose($res);
		

			
		redirect('admin/view_theme/'.$theme_id);
	}
	
	function set_active_theme($id){
		$this->db->query("UPDATE `themes` SET `active` = 0");
		$this->db->query("UPDATE `themes` SET `active` = 1 WHERE `id` = '$id'");
		redirect('admin/themes');
	}
	
	
	function new_theme(){
		$this->load->view('interface/header');
		$this->load->view('interface/themes/theme_new',$data);
		$this->load->view('interface/footer');

	}
	
	function delete_layout($id){
		$query = $this->db->query("SELECT * FROM `theme_layout` WHERE `id` = '$id'");
		$layout = $query->row();
		
		$query = $this->db->query("SELECT * FROM `themes` WHERE `id` = '{$layout->theme_id}'");
		$theme = $query->row();
		
		$this->db->query("DELETE FROM `theme_layout` WHERE `id` = '$id'");
		
		unlink('themes/'.$theme->directory.'/'.$layout->name.'.php');
		
		redirect('admin/view_theme/'.$theme->id);
	}
	
	function edit_layout($id){
		$query = $this->db->query("SELECT * FROM `theme_layout` WHERE `id` = '$id'");
		$layout = $query->row();
		$data['css'] = $layout->css;
		$data['html'] = $layout->html;
		$data['layout_id'] = $layout->id;
		$data['theme_id'] = $layout->theme_id;
		$data['layout_name'] = $layout->name;
		
		$query = $this->db->query("SELECT * FROM `media` WHERE `type` = 'Images'");
		$data['images'] = $query->result();
		$this->load->view('interface/theme_editor',$data);
	}
	
	function delete_theme($id){
		$query = $this->db->query("SELECT * FROM `themes` WHERE `id` = '$id'");
		$theme = $query->row();
		
		rmdir('themes/'.$theme->directory);
		$this->db->query("DELETE FROM `themes` WHERE `id` = '$id'");
		$this->db->query("DELETE FROM `theme_layout` WHERE `theme_id` = '$id'");
		
		redirect('admin/themes');
	}
	
}

