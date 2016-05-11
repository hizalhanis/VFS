<?php

// Config Controller

class Config extends CI_Controller {
	
	function Index(){
		$this->overview();
	}
	
	function Overview(){
		redirect('config/general_information');
	}
	
	function General_information(){
		$data['logic'] 	= 'general-information';
		$data['tab']	= 'general_information';
		$this->load->view('header');
		$this->load->view('config/questions', $data);
		$this->load->view('footer');
	}
	
	
	
	function Ajax($request){
		
		switch ($request){
		
			case "summary_list":
				$logic 				= $this->input->post('logic');
				$data['questions']	= $this->questionnaire->get_questions($logic);
							
				$this->load->view('config/ajax/list', $data);
				break;
				
			case "get_question_form":
			
				$logic 	= $this->input->post('logic');
				$no 	= $this->input->post('no');
				

				
				switch ($logic){
					case "general-information": $table = 'survey_gen'; break;
					
				}
				
				$query = $this->db->query("SHOW COLUMNS FROM `$table`");
				foreach ($query->result() as $col){
					$map_to_array[$col->Field] = $col->Field;
				}				
				
				if ($no) $data	= $this->questionnaire->get_question_form($logic, $no);
				
				$data['map_to_array'] 	= $map_to_array;
				$data['logic']			= $logic;

				
				$this->load->view('config/ajax/form-question', $data);
				
				break;
				
				
			case "sort_questions":
			
			
				$logic = $this->input->post('logic');
		
				if (strpos($this->input->post('order'),',')){
					$order = explode(',', $this->input->post('order'));
				} else {
					$order[] = $this->input->post('order');
				}
				
				$x = 1;
				foreach ($order as $id){
					$this->db->update('questionnaire',array(
						'no'	=> $x
					), "`id` = '{$id}'");
					$x++;
				}
				
				echo 'ok';
				
				break;
			
			case "delete_question":
			
				$qid = $this->input->post('qid');
				$logic = $this->input->post('logic');
				
				$this->db->query("DELETE FROM `questionnaire` WHERE `logic` = ? AND `id` = ?", array($logic, $qid));
				$this->renumber_questions();
				
				break;
				
				
			case "update_question":
			
				$logic	= $this->input->post('logic');
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
			    	$question->state_reason_if_min	= $this->input->post('state_reason_if_min');
			    	$question->state_reason_if_max	= $this->input->post('state_reason_if_max');
			    	
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
					$query = $this->db->query("SELECT * FROM `questionnaire` WHERE `logic` = ?", array($logic));
					$no = $query->num_rows() + 1;
		
					$this->db->insert('questionnaire', array(
						'type'				=> $this->input->post('type'),
						'logic'				=> $logic,
						'no'				=> $no,
						'question'			=> $question,
						'answers'			=> $answers,
						'comments'				=> $this->input->post('comments_description') ? 1 : 0,
						'comments_description' 	=> $this->input->post('comments_description'),
						'other'				=> json_encode($this->input->post('other')),
						'modifier'			=> $this->input->post('modifier'),
						'script'			=> $this->input->post('script'),
						'map_to'			=> $this->input->post('map_to'),
						'display'			=> $this->input->post('display'),
						'data_type'			=> $this->input->post('data_type')
					));
				} else {
					$this->db->update('questionnaire', array(
						'type'				=> $this->input->post('type'),
						'question'			=> $question,
						'answers'			=> $answers,
						'comments'				=> $this->input->post('comments_description') ? 1 : 0,
						'comments_description' 	=> $this->input->post('comments_description'),
						'other'				=> json_encode($this->input->post('other')),
						'modifier'			=> $this->input->post('modifier'),
						'script'			=> $this->input->post('script'),
						'map_to'			=> $this->input->post('map_to'),
						'display'			=> $this->input->post('display'),
						'data_type'			=> $this->input->post('data_type')

					), "`logic` = '{$logic}' AND `no` = '{$no}'");
				}
				
				echo '<script language="javascript" type="text/javascript">window.top.window.formSubmitted("'.$logic.'","'.$no.'");</script>';
			

				
		}

	}
	
	function Renumber_questions(){
		$logic = $this->input->post('logic');
		$query = $this->db->query("SELECT * FROM `questionnaire` WHERE `logic` = ? ORDER BY `no` ASC", array($logic));
		
		$no = 1;
		foreach ($query->result() as $q){
			$this->db->update('questionnaire', array(
				'no'	=> $no
			),"`id` = '{$q->id}'");
			
			$no++;
		}
		
	}
}