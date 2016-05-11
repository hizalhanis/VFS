<?php

class Survey extends Model {

	var $FB_ENABLED			= false;
	var $FB_APP_ID			= '';
	var $FB_APP_SECRET		= '';
	
	var $SQL				= "
		
		CREATE TABLE `survey_answers` (
		  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `survey_id` int(11) DEFAULT NULL,
		  `type` varchar(128) DEFAULT NULL,
		  `no` int(11) DEFAULT NULL,
		  `answer` text,
		  `other` text,
		  `datetime` datetime DEFAULT NULL,
		  `user` varchar(128) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1;
		
		CREATE TABLE `survey_questions` (
		  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `survey_id` int(11) DEFAULT NULL,
		  `no` int(11) DEFAULT NULL,
		  `type` varchar(32) DEFAULT NULL,
		  `question` text,
		  `answers` text,
		  `comments` int(1) DEFAULT NULL,
		  `comments_description` text,
		  `has_right_answer` int(1) DEFAULT NULL,
		  `right_answer` text,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;
		
		CREATE TABLE `surveys` (
		  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `name` text,
		  `introduction` text,
		  `conclusion` text,
		  `thank_you` text,
		  `added_on` datetime DEFAULT NULL,
		  `added_by` varchar(128) DEFAULT NULL,
		  `modified_on` datetime DEFAULT NULL,
		  `modified_by` varchar(128) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;";
	
	function Survey(){
		parent::Model();

		$this->check_sql_tables();

		$this->name				= 'Survey';
		$this->namespace		= 'survey';

		$this->css_namespace	= 'lcms-survey';

		$this->js_includes		= 'lcms.survey.js';
		$this->css_includes 	= 'lcms.survey.css';

		
		$this->js_resources 	= 'survey.js';		
		$this->css_resources	= 'survey.css';


	}
	
	function Hooks($author_mode){

		switch ($_SESSION['param1']){
		
			case "preview":
			
				if ($author_mode){
				
					$id 		= $_SESSION['param2'];
					$query 		= $this->db->query("SELECT * FROM `contents` WHERE `content` = ? AND `type` = 'survey'", array($id));
					$content	= $query->row();
					$options 	= json_decode($content->options);
					
					$data['survey']		= $this->get_survey_by_id($id);
					$data['questions']	= $this->get_survey_questions($id);
					
					$output = $this->load->view('modules/survey/interface/survey-preview', $data, true);
					
					die($output);
				}
			
				break;
						
			case "login":
				$username 	= $this->input->post('username');
				$password 	= $this->input->post('password');
				$id		 	= $this->input->post('id');
				
				$query = $this->db->query("SELECT * FROM `contents` WHERE `content` = ? AND `type` = 'survey'", array($id));
				$content = $query->row();
				
				$options = json_decode($content->options);
				
				
				$access_string = $username . ':' . $password;
				
				
				if (preg_match('/'.$access_string.'/',$options->logins)){
					$_SESSION['surveyactive'] = $id;
				
				} 
				
				if ($content->page) redirect('p/'.$content->page);
				
				break;
				
			case "submit_answer":
				$id		= $this->input->post('id');
				$no		= $this->input->post('no');
				$type	= $this->input->post('type');
				$ans	= $this->input->post('ans');
				$other	= $this->input->post('other');
				
				$this->db->insert('survey_answers', array(
					'survey_id'		=> $id,
					'type'			=> $type,
					'no'			=> $no,
					'ans'			=> $ans,
					'other'			=> $other
				));
				
				return;
				break;
		}		
		
		
	}
	
	function Presave($content, $options){	
		$options = json_decode($options);
		
		$this->db->insert('surveys', array(
			'name'					=> $options->name,
			'added_on'				=> date('Y-m-d H:i:s'),
			'added_by'				=> $this->user->data('username')
		));
		
		$id = $this->db->insert_id();
	
		$data->content = $id;
		$data->options = json_encode($options);
		return $data;
	}
	
	function Preupdate($id, $content, $options){
		$options = json_decode($options);
		
		$this->db->update('surveys', array(
			'name'					=> $options->name,
			'added_on'				=> date('Y-m-d H:i:s'),
			'added_by'				=> $this->user->data('username')
		),"`id` = '{$id}'");
			
		$data->content = $id;
		$data->options = json_encode($options);
		return $data;
	}
	
	function Saved($id, $published){
		
	}
	
	function Updated($id, $published){
		
	}	
	
	
	function PreHTML(){
		$prehtml = $this->load->view('modules/survey/form-new', $data, true);
		$prehtml .= $this->load->view('modules/survey/form-edit', $data, true);
		return $prehtml;
	}
	
	
	function HTML($content, $author_mode){
		$data['current_page'] 	= $current_page = base_url() .  'p/' . $content->page . '/';
		$data['author_mode']	= $author_mode;
		
		switch ($_SESSION['param1']){
		
			case "edit":
				
				if ($author_mode){
					$data['id'] 		= $id = $content->content;
					$data['survey']		= $this->get_survey_by_id($id);
					$data['questions']	= $this->get_survey_questions($id);
					
					

					$output .= $this->load->view('modules/survey/interface/edit', $data, true);
				}
				
				break;
				
			
		
			default: 
				$id = $content->content;
				$options = json_decode($content->options);
				
				if ($options->password_protected && $_SESSION['surveyactive'] != $id){

					$data['id'] = $content->content;					
					$output .= $this->load->view('modules/survey/interface/login', $data, true);
								
				} else {

					$data['survey']		= $this->get_survey_by_id($id);
					$data['questions']	= $this->get_survey_questions($id);

				
					$output .= $this->load->view('modules/survey/interface/survey', $data, true);
				}
				
				break;
		}
		
		return $output;
	}	
	
	function Get_survey_by_id($id){
		$query = $this->db->query("SELECT * FROM `surveys` WHERE `id` = ?", array($id));
		return $query->row();
	}
	
	function Get_survey_questions($id, $no = 0){
		if ($no){
			$query = $this->db->query("SELECT * FROM `survey_questions` WHERE `survey_id` = ? AND `no` = ? ORDER BY `no`", array($id, $no));
			return $query->row();
		} else {
			$query = $this->db->query("SELECT * FROM `survey_questions` WHERE `survey_id` = ? ORDER BY `no`", array($id));
			return $query->result();
		}

	}
	
	function Get_page_form(){
		$type = $this->input->post('type');
		$id = $this->input->post('id');
				
		$query = $this->db->query("SELECT * FROM `surveys` WHERE `id` = ?", array($id));
		$data['survey'] = $query->row();
		
		switch ($type){
			case 'introduction':
				$this->load->view('modules/survey/interface/form-introduction', $data);
				break;
			case 'conclusion':
				$this->load->view('modules/survey/interface/form-conclusion', $data);			
				break;
			case 'thank_you':
				$this->load->view('modules/survey/interface/form-thank-you', $data);
				break;
		}
	
	}
	
	function Update_page(){
		$id = $this->input->post('id');
		$type = $this->input->post('type');		
		$content = $this->input->post('content');
		
		$this->db->update('surveys', array(
			$type => $content
		), "`id` = '{$id}'");
		
		echo '<script language="javascript" type="text/javascript">window.top.window.formSubmitted();</script>';
	}
	
	function Get_question_form(){
		$data['id'] = $id = $this->input->post('id');
		$data['no'] = $no = $this->input->post('no');
				
		$query = $this->db->query("SELECT * FROM `surveys` WHERE `id` = ?", array($id));
		$data['survey'] = $query->row();
		
		$question = $this->get_survey_questions($id, $no);
		
		if ($question->type == 'matrix-choice' || $question->type == 'matrix-choice-ma' || $question->type == 'matrix-answer'){
		    $qn = json_decode($question->question);
		    $data['question'] = $qn->description;
		    foreach ($qn->questions as $a){
		    	$answer = null;
		    	$answer->value = $a->question;
		    	$ans[] = $answer;
		    }
		    $data['labels'] = $qn->labels;
		    $data['answers'] = $ans;
		    $data['comments_description'] = $qn->comments_description;
		    $data['comments'] = $qn->comments;
		} else {
		    $data['question'] = $question->question;
		    $data['answers'] = json_decode($question->answers);
		}
		
		$data['type'] = $question->type;
		
		$this->load->view('modules/survey/interface/form-question', $data);
		
	}
	
	function Update_question(){
		$id		= $this->input->post('id');
		$no 	= $this->input->post('no');
		$type	= $this->input->post('type');
		
		if ($type == 'matrix-choice' || $type == 'matrix-choice-ma' || $type == 'matrix-answer'){
				
			$x = 1;
	    	foreach ($this->input->post('qn') as $qni){
	    		$qn 			= null;
	    		$qn->no 		= $x;
	    		$qn->question	= $qni; 
	    		$qns[] 			= $qn;
	    		$x++;
	    	}
	    
	    	print_r($_REQUEST);
			    	
	    	$x = 1;
	    	foreach ($this->input->post('label') as $labeli){
	    		$label 			= null;
	    		$label->no		= $x;
	    		$label->value	= $labeli;
	    		$labels[]		= $label;
	    		$x++;
	    	}

	    	$question->description			= $this->input->post('question');
	    	$question->questions			= $qns;
	    	$question->labels 				= $labels;				
	    	$question->comments				= $this->input->post('comments_description') ? 1 : 0;
	    	$question->comments_description	= $this->input->post('comments_description');
	    	
	    	$question = json_encode($question);
	    } else {
	    	
	    	$question = $this->input->post('question');
	    	$x = 1;
	    	foreach ($this->input->post('answers') as $answeri){
	    		$answer 		= null;
	    		
	      		if ($answeri[0] == '*'){
		    		$answer->type = 'other';
		    		$answeri = substr($answeri, 1);
	    		}
	    		
	    		$answer->no		= $x;
	    		$answer->value	= $answeri;
	    		$answers[] 		= $answer;

	    		$x++;
	    	}
	    	
	    	$answers = json_encode($answers);
	    }		
		
		if ($no == 0){
			$query = $this->db->query("SELECT * FROM `survey_questions` WHERE `survey_id` = ?", array($id));
			$no = $query->num_rows() + 1;

			$this->db->insert('survey_questions', array(
				'type'				=> $this->input->post('type'),
				'survey_id'			=> $id,
				'no'				=> $no,
				'question'			=> $question,
				'answers'			=> $answers,
				'comments'				=> $this->input->post('comments_description') ? 1 : 0,
				'comments_description' 	=> $this->input->post('comments_description'),
				'has_right_answer'	=> $this->input->post('has_right_answer'),
				'right_answer'		=> $right_answer
			));
		} else {
			$this->db->update('survey_questions', array(
				'type'				=> $this->input->post('type'),
				'question'			=> $question,
				'answers'			=> $answers,
				'comments'				=> $this->input->post('comments_description') ? 1 : 0,
				'comments_description' 	=> $this->input->post('comments_description'),
				'has_right_answer'	=> $this->input->post('has_right_answer'),
				'right_answer'		=> $right_answer
			), "`survey_id` = '{$id}' AND `no` = '{$no}'");
		}
		
		echo '<script language="javascript" type="text/javascript">window.top.window.formSubmitted("'.$id.'","'.$no.'");</script>';
	}
	
	function Place_answer(){
		
		
	}
	
	function Summary_list(){
		$id 				= $this->input->post('id');
		$data['survey']		= $this->get_survey_by_id($id);
		$data['questions']	= $this->get_survey_questions($id);
					
		$this->load->view('modules/survey/interface/list', $data);
	}
	
	function Delete_question(){
		$no = $this->input->post('id');
		$id = $this->input->post('survey_id');
		
		$this->db->query("DELETE * FROM `survey_questions` WHERE `survey_id` = ? AND `no` = ?", array($id, $no));
		$this->renumber_questions();
		
	}
	
	function Renumber_questions(){
		$id = $this->input->post('id');
		$query = $this->db->query("SELECT * FROM `survey_questions` WHERE `survey_id` = ? ORDER BY `no` ASC", array($id));
		
		$no = 1;
		foreach ($query->result() as $q){
			$this->db->update('survey_questions', array(
				'no'	=> $no
			),"`id` = '{$q->id}'");
			
			$no++;
		}
		
	}
	
	
	function Check_sql_tables(){
	
		
		$db = $this->db->database;
		$query = $this->db->query("SHOW TABLES IN {$db}");
		$current_tables = $query->result();		
		
		foreach ($current_tables as $table_result){
			$current_table = $table_result->{'Tables_in_'.$db};
			$query = $this->db->query("SHOW COLUMNS IN {$db}.{$current_table}");
			$columns = $query->result();
			foreach ($columns as $column){
				$table_columns[$current_table][$column->Field] = $column;
				$existing_tables[$current_table] = 1;
			}
		}
		
		$sql = $this->sql;
						
		$context = str_replace("CREATE", "\nCREATE", str_replace("\n","",$sql));
		$statements = explode("\n", $context);
		
		$clean = true;
		foreach ($statements as $statement){
			if (trim($statement)){
				preg_match_all('/.*CREATE\s+TABLE\s+`(\S+)`\s*\((.*)\).*/', $statement, $matches);
				$table = trim($matches[1][0]);
				if ($table){
									
					
					if (!$existing_tables[$table]){
					
						$this->db->query($matches[0][0]);
				
						$clean = false;		
						
					} else {
				
						$cols_line = preg_split('/,(?![^\(\)]*\))/', $matches[2][0]);
						foreach ($cols_line as $col_line){
							if (preg_match("/PRIMARY\s+KEY.*/", $col_line)){
								// primary key
								
								
							} else {
								// column
								preg_match('/`(\w+)`\s+(.*)/', $col_line, $coldata_matches);
								$field = $coldata_matches[1];
								$attr = $coldata_matches[2];
								
								
								if (!is_object($table_columns[$table][$field])){
									$this->db->query("ALTER TABLE `{$table}` ADD `{$field}` {$attr} AFTER `{$last_field}`");
									$clean = false;
								}
								
								$last_field = $field;
								
							}
						}
					}	
				}
			}
		}
	}
	

	
}