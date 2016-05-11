<?php

class News extends Model {
	
	function News(){
		parent::Model();

		$this->name				= 'News';
		$this->namespace		= 'news';
		$this->css_namespace	= 'lcms-news';
				
		$this->js_includes		= 'lcms.news.js';
		$this->css_includes 	= 'lcms.news.css';
		
		$this->js_resources 	= '';
		$this->css_resources	= '';
		
	}
	
	function Hooks(){
		switch ($_SESSION['param1']){
		
			case "article":
				$news_id = $_SESSION['param2'];
				$query = $this->db->query("SELECT * FROM `news` WHERE `id` = ?", array($news_id));
				$news = $query->row();
				
				$this->session->set_userdata(array('subtitle'=>$news->title));

				
				break;
			case "add_article_do":
				$id = $this->add();
				redirect(page_url().'article/'.$id);
				break;
			case "edit_article_do":
				$id = $this->update();
				redirect(page_url().'article/'.$id);
				break;
			case "delete_article":
				$id = $_SESSION['param2'];
				$this->delete_news($id);
				redirect(page_url());
		}
	}
	
	
	function PreHTML(){
		$prehtml = $this->load->view('modules/news/form-new', $data, true);
		$prehtml .= $this->load->view('modules/news/form-edit', $data, true);

		return $prehtml;
	}
	
	
	function HTML($content, $author_mode){
		


		switch ($_SESSION['param1']){
			case "add_article":
				$return = 'add';
				break;
			case "edit_article":
				$return = 'edit';
				$news_id = $_SESSION['param2'];
				break;
			case "article":
				$news_id = $_SESSION['param2'];
				break;
			case "page":
				$cur_page = $_SESSION['param2'];
				break;
		}
		
		$options = json_decode($content->options);
		
		$data['content'] 	= $content;
		$data['author_mode']= $author_mode;
		$data['news_id']	= $news_id;
		$data['cur_page']	= $cur_page;
		$data['npp']		= $npp = $options->npp;
		$data['excerpt']	= $excerpt = $options->excerpt;
		
	
	    if ($cur_page){
	    	$data['l1'] = $l1 = ($cur_page - 1) * ($npp) + 1;
	    	$data['l2'] = $l2 = $npp;
	    } else {
	    	$data['l1'] = $l1 = 0;
	    	$data['l2'] = $l2 = $npp;
	    }
	    
	    $query = $this->db->query("SELECT COUNT(`id`) AS `total_entries` FROM `news` WHERE `name` = ?", array($content->content));
	    $row = $query->row();
	    
	    $data['total_entries'] = $row->total_entries;
	    
		if ($news_id){
			$query = $this->db->query("SELECT * FROM `news` WHERE `id` = '$news_id'");
			$data['entry'] = $query->row();

		} else {
		    $query = $this->db->query("SELECT * FROM `news` WHERE `name` = '{$content->content}' ORDER BY `date` DESC LIMIT $l1, $l2");
		    $data['entries'] = $query->result();
			
		}
		
		
		if ($return == 'add'){
			return $this->load->view('modules/news/post-add',$data,true);
		} else if ($return == 'edit'){
			return $this->load->view('modules/news/post-edit',$data,true);	
		} else {
			return $this->load->view('modules/news/html',$data,true);
		}
		
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
	
		$query = $this->db->query("SELECT * FROM `contents` WHERE `id` = ?", array($id));
		$content = $query->row();

		$sql = $this->db->insert_string('news', array(
			'name' 		=> $content->content,
			'content' 	=> 'Welcome to PlexCMS News. This is a hello world to test if your configuration is working properly. If you see this post, it means your news content is ready for your posts.',
			'excerpt'	=> 'Welcome to PlexCMS News. This is a hello world to test if your configuration is working properly. If you see this post, it means your news content is ready for your posts.',
			'title'		=> 'Hello World!',
			'date'		=> date('Y-m-d'),
			'cat'		=> $cat,
			'tags'		=> $tags,
			'user'		=> 'admin',
			'datetime' 	=> date('Y-m-d H:i:s'),
			'status' 	=> 'Published'
		));
		
		$this->db->query($sql);
	
	}
	
	function Updated($id, $published){
		
	}
	
	
	function Get_news_by_id(){
		$id = $this->input->post('id');
		$query = $this->db->query("SELECT * FROM `news` WHERE `id` = ?", array($id));
		
		echo json_encode($query->row());
	}
	
	
	function Add(){
		$name 	= $this->input->post('name');
		$cat	= $this->input->post('cat');
		$tags	= $this->input->post('tags');
		$text 	= $this->input->post('text');
		$excerpt= $this->input->post('excerpt');
		$title 	= $this->input->post('title');
		$action = $this->input->post('action');
		$img 	= $this->input->post('image');
		$action	= $this->input->post('action');
		
		list($day, $month, $year) = explode('/',$this->input->post('date'));
		$date = $year . '-' . $month . '-' . $day;
		
		$date .= ' ' . date('H:i:s',strtotime($this->input->post('time')));
		
		$sql = $this->db->insert_string('news', array(
			'name' 		=> $name,
			'image'		=> $img,
			'content' 	=> $text,
			'excerpt'	=> $excerpt,
			'title'		=> $title,
			'date'		=> $date,
			'cat'		=> $cat,
			'tags'		=> $tags,
			'user'		=> 'admin',
			'datetime' 	=> $date,
			'status' 	=> $action == 'Save' ? 'Saved' : 'Published'
		));
		
		$this->db->query($sql);
		
		return $this->db->insert_id();
	}
	
	function Update(){
		$id		= $this->input->post('id');
		$name 	= $this->input->post('name');
		$cat	= $this->input->post('cat');
		$tags	= $this->input->post('tags');
		$text 	= $this->input->post('text');
		$excerpt= $this->input->post('excerpt');
		$title 	= $this->input->post('title');
		$action = $this->input->post('action');
		$img 	= $this->input->post('image');
		$action	= $this->input->post('action');

		
		list($day, $month, $year) = explode('/',$this->input->post('date'));
		$date = $year . '-' . $month . '-' . $day;
		
		$date .= ' ' . date('H:i:s',strtotime($this->input->post('time')));
		
		$sql = $this->db->update_string('news', array(
			'name' 		=> $name,
			'image'		=> $img,
			'content' 	=> $text,
			'excerpt'	=> $excerpt,
			'title'		=> $title,
			'date'		=> $date,
			'cat'		=> $cat,
			'tags'		=> $tags,
			'status' 	=> $action == 'Save' ? 'Saved' : 'Published'
		),"`id` = '$id'");
		
		$this->db->query($sql);
		
		return $id;
	}
	
	function Update_status(){
		$id		= $this->input->post('id');
		$status = $this->input->post('status');
		
		$sql = $this->db->update_string('news', array(
			'status' => $status
		),"`id` = '$id'");
		
		if ($this->db->query($sql)) echo $id;
		else return 'failed';
	}
	
	function Delete_news($id){
		if (!$id) $id = $this->input->post('id');
		if ($this->db->query("DELETE FROM `news` WHERE `id` = '$id'")) return true;
		else return false;
	}

		
}