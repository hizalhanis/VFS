<?php

class Main extends CI_Controller {
	
	function Index(){
		redirect('cases');
	}
	
	function Update_qnos(){
		$query = $this->db->query("SELECT * FROM `questionnaire_2` WHERE `logic` LIKE 'vehicle-motorcycle' ORDER BY `id` ASC");
		$x = 1;
		foreach ($query->result() as $cq){
			$this->db->update('questionnaire_2',array(
				'no'	=> $x
			),"`id` = '{$cq->id}'");
			$x++;
		}
    }
	
	function Generate_column_names($type){
		
		$types = array('case','vehicle-motorcycle','vehicle-truck','vehicle-car','vehicle-bus','vehicle');
		if (!in_array($type, $types)) die('Invalid type');
	
		echo '<h1>Column Names</h1>';
		
		if ($type == 'vehicle'){
			$query = $this->db->query("SELECT * FROM `questionnaire` WHERE `logic` LIKE '$type-%' ORDER BY `id` ASC");
		} else {
			$query = $this->db->query("SELECT * FROM `questionnaire` WHERE `logic` = '$type' ORDER BY `id` ASC");		
		}
		echo "<table>";
		foreach ($query->result() as $q){
			if ($q->type == 'matrix-answer'){
				$qdata = json_decode($q->question);
				$question = $qdata->description;
			} else {
				$question = $q->question;
			}
			
			list($field, $ignored) = explode('(',$question);
			
			
			$field = str_replace('(','',strtolower(trim($field)));
			$field = str_replace(')','', $field);
			$field = str_replace('-', '', $field);
			$field = str_replace('  ', ' ', $field);
			
			$a = explode(" ", $field);
			if (count($a) > 2){
				$x = 0;
				$c = array();
				foreach ($a as $b){
					if ($x == 0 || $x == 1){
						$part = $b[0];
					} else {
						$part = $b;
					}
					
					$c[] = $part;
					$x++;
				}
				$field = implode('_', $c);
			} else {
				$field = str_replace(' ', '_', $field);
			}
			
			$field = str_replace('/','_',$field);
			$field = str_replace('.','',$field);
			$field = str_replace(',','',$field);
			
			echo "<tr>";
			echo "<td>";
			echo $question;
			echo "</td>";
			echo "<td>";
			echo $field;
			echo "</td>";
			echo "</tr>";
			
			$id = $q->id;
			
			$this->db->update('questionnaire', array(
				'map_to'	=> $field
			),"`id` = '$id'");
			
			$gfs[$field] = $field;
			
		}
		
		
		echo "</table>";
		
		$type = str_replace('-','_', $type);
		
		$sql = 'CREATE TABLE `'.$type.'` ( 
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `reference_id` VARCHAR(255) DEFAULT NULL,';
		
		foreach ($gfs as $field)  {
			$sql .= "`$field` VARCHAR(255) DEFAULT NULL,";
		}
		
		$sql .= "  PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		
		echo $sql;
		
	}
	
	
	function Update_fields(){
		$query = $this->db->query("SELECT * FROM `questionnaire` WHERE `logic` LIKE 'vehicle-%'");
		foreach ($query->result() as $q){

			if ($q->type == 'single-answer'){	


				$a = explode(';',$q->answers);
				$y = 1;
				$ds = null;
				foreach ($a as $x){

					$d = null;
					$d->no = $y;
					$d->value = trim($x);
					$ds[] = $d;
					$y++;
				}
				
				$ans = json_encode($ds);

				$this->db->update('questionnaire',array(
					'answers'	=> $ans
				),"`id` = '{$q->id}'");


				
				echo $ans . '<br />';
			}
			
			

		}
		
	}
	
	function Update_fields_2(){
		$query = $this->db->query("SELECT * FROM `questionnaire` WHERE `logic` LIKE 'vehicle-%'");
		foreach ($query->result() as $q){

			if ($q->type == 'matrix-answer'){	
				$x = null;
				$qans = null;
				$d = null;
				
				$x->description = $q->question_imported;
				
				$d->no = 1;
				$d->question = $q->question_imported;
				$qans[] =  $d;
				
				$x->questions = $qans;
				$x->labels = null;
				$x->comments = 0;
				$x->comments_description = "";
				$x->state_reason_if_min = false;
				$x->state_reason_if_max = false;
				
				$question = json_encode($x);

				$this->db->update('questionnaire',array(
					'question'	=> $question
				),"`id` = '{$q->id}'");


				
				echo $question . '<br />';
			}
			
			

		}
	}
	
	function Update_fields_3(){
		$query = $this->db->query("SELECT * FROM `questionnaire` WHERE `logic` LIKE 'vehicle-%'");
		foreach ($query->result() as $q){

			if ($q->type == 'single-answer'){	

				$this->db->update('questionnaire',array(
					'question'	=> $q->question_imported
				),"`id` = '{$q->id}'");


				
				echo $question . '<br />';
			}
			
			

		}
	}
	
}