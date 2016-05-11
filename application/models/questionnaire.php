<?php

class Questionnaire extends CI_Model {

	function Get_actual_answer($logic, $mapto, $index){
		$query = $this->db->query("SELECT * FROM `questionnaire` WHERE `logic` = ? AND `map_to` = ?", array($logic, $mapto));
		$question = $query->row();
		
		if ($question->type == 'single-answer' || $question->type == 'multiple-answer'){
			$answers = json_decode($question->answers);
			
			foreach ($answers as $answer){
				if ($answer->no == $index){
					return $answer->value;
				}
			}
		} 
		
	}
	
	function Get_questions($logic, $no = 0, $display_all = true){
		if (!$display_all){
			$display_filter = " `display` = 1 ";
		} 
		
		if ($logic){
			if ($display_filter) $display_filter = " AND " . $display_filter;
			if ($no){
				$query = $this->db->query("SELECT * FROM `questionnaire` WHERE `logic` = ? AND `no` = ? {$display_filter} ORDER BY `no`", array($logic, $no));
				return $query->row();
			} else {
				$query = $this->db->query("SELECT * FROM `questionnaire` WHERE `logic` = ? {$display_filter} ORDER BY `no`", array($logic));
				return $query->result();
			}			
		} else {
			if ($display_filter) $display_filter = " WHERE " . $display_filter;
			$query = $this->db->query("SELECT * FROM `questionnaire` {$display_filter} ORDER BY `logic` ASC, `no` ASC");
			return $query->result();		
		}
	}
	
	
	function Get_question_form($logic, $no){
		
		$data['logic'] 	= $logic;
		$data['no']		= $no;
		
		$question = $this->get_questions($logic, $no);
		
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
		    $data['state_reason_if_min'] = $qn->state_reason_if_min;
		    $data['state_reason_if_max'] = $qn->state_reason_if_max;
		    $data['other']	= json_decode($question->other);
		    
		} else {
		    $data['question'] = $question->question;
		    $data['answers'] = json_decode($question->answers);
		    $data['comments_description'] = $question->comments_description;
		    $data['comments'] = $question->comments;
		    $data['other']	= json_decode($question->other);

		}
		
		$data['am']			= $question->modifier;
		$data['js']			= $question->script;
		$data['type']		= $question->type;
		$data['map_to']		= $question->map_to;
		$data['display']	= $question->display;
		$data['data_type']	= $question->data_type;
		
		return $data;
		
	}

	function Update_question(){
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
				'type'					=> $this->input->post('type'),
				'logic'					=> $logic,
				'no'					=> $no,
				'question'				=> $question,
				'answers'				=> $answers,
				'comments'				=> $this->input->post('comments_description') ? 1 : 0,
				'comments_description' 	=> $this->input->post('comments_description'),
				'other'					=> json_encode($this->input->post('other')),
				'modifier'				=> $this->input->post('modifier'),
				'script'				=> $this->input->post('script')
			));
		} else {
			$this->db->update('questionnaire', array(
				'type'					=> $this->input->post('type'),
				'question'				=> $question,
				'answers'				=> $answers,
				'comments'				=> $this->input->post('comments_description') ? 1 : 0,
				'comments_description' 	=> $this->input->post('comments_description'),
				'other'					=> json_encode($this->input->post('other')),
				'modifier'				=> $this->input->post('modifier'),
				'script'				=> $this->input->post('script')
			), "`survey_id` = '{$id}' AND `no` = '{$no}'");
		}
		
		echo '<script language="javascript" type="text/javascript">window.top.window.formSubmitted("'.$id.'","'.$no.'");</script>';
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
	
	function Sort_questions(){
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
	}



}