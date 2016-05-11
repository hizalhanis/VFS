<?php

// Analytics Controller 

class Analytics extends CI_Controller {

	function __construct(){
	
		parent::__construct();
		
		$this->current_data = $this->session->userdata('yeardata');
		
	}
	
	
	function Index(){
		$data['tab'] = 'analytics';
		
		$data['questions'] 	= $questions = $this->questionnaire->get_questions();
		
					
		$dd[] = 'Select Filter';
					
		foreach ($questions as $q){

			if ($q->type == 'matrix-answer'){
				$obj 			= json_decode($q->question);
				$dd[$q->map_to] = $obj->description;
				$dda[$q->map_to]= $obj->description;
							
			} else {
				$dd[$q->map_to] = $q->question;
				$dda[$q->map_to]= $q->question;
			}

		}
		$data['dropdown'] = $dd;
		$data['dropdown_axis'] = $dda;
		
		
		
		$questions = $this->questionnaire->get_questions('case');
		$ddc[] = 'Select Filter';					
		foreach ($questions as $q){

			if ($q->type == 'matrix-answer'){
				$obj 			= json_decode($q->question);
				$ddc[$q->map_to] = $obj->description;
			} else {
				$ddc[$q->map_to] = $q->question;
			}

		}
		$data['dropdown_general'] = $ddc;
		
		
		$questions = $this->questionnaire->get_questions('vehicle-car');
		$ddv[] = 'Select Filter';					
		foreach ($questions as $q){

			if ($q->type == 'matrix-answer'){
				$obj 			= json_decode($q->question);
				$ddv[$q->map_to] = $obj->description;
			} else {
				$ddv[$q->map_to] = $q->question;
			}

		}
		$data['dropdown_vehicle_car'] = $ddv;
		
		
		$questions = $this->questionnaire->get_questions('vehicle-motorcycle');
		unset($ddv);
		$ddv[] = 'Select Filter';					
		foreach ($questions as $q){

			if ($q->type == 'matrix-answer'){
				$obj 			= json_decode($q->question);
				$ddv[$q->map_to] = $obj->description;
			} else {
				$ddv[$q->map_to] = $q->question;
			}

		}
		$data['dropdown_vehicle_motorcycle'] = $ddv;
		
		$questions = $this->questionnaire->get_questions('vehicle-bus');
		unset($ddv);
		$ddv[] = 'Select Filter';					
		foreach ($questions as $q){

			if ($q->type == 'matrix-answer'){
				$obj 			= json_decode($q->question);
				$ddv[$q->map_to] = $obj->description;
			} else {
				$ddv[$q->map_to] = $q->question;
			}

		}
		$data['dropdown_vehicle_bus'] = $ddv;
		
		$questions = $this->questionnaire->get_questions('vehicle-truck');
		unset($ddv);
		$ddv[] = 'Select Filter';					
		foreach ($questions as $q){

			if ($q->type == 'matrix-answer'){
				$obj 			= json_decode($q->question);
				$ddv[$q->map_to] = $obj->description;
			} else {
				$ddv[$q->map_to] = $q->question;
			}

		}
		$data['dropdown_vehicle_truck'] = $ddv;
		
		
		
		$this->load->view('header', $data);
		$this->load->view('analytics/index', $data);
		$this->load->view('footer', $data);
	}
	
	function Process(){
		$data['tab'] = 'analytics';
		
		$answer_type 	= $this->input->post('result_type');
		$x				= $this->input->post('x');
		$y				= $this->input->post('y');
		
		$query 			= $this->db->query("SELECT * FROM `questionnaire` WHERE `map_to` = ?", array($x));
		$x_question		= $query->row();
		
		$query 			= $this->db->query("SELECT * FROM `questionnaire` WHERE `map_to` = ?", array($y));
		$y_question		= $query->row();
		
		if ($x_question->type == 'matrix-answer'){
			$obj = json_decode($x_question->question);
			$x_question->q = $obj->description;
		} else {
			$x_question->q = $x_question->question;
		}

		if ($y_question->type == 'matrix-answer'){
			$obj = json_decode($y_question->question);
			$y_question->q = $obj->description;
		} else {
			$y_question->q = $y_question->question;
		}
		
		
		$q = $this->input->post('q');
				
		foreach ($q as $mapto => $answer){
			$filter = null;
			foreach ($answer as $subans){
				list($val, $no, $qnc, $qtype) = explode('|',$subans);
				$filter_desc[$qnc][] = $val;
				if ($qtype == 'multiple-answer'){
					$filter[] = " FIND_IN_SET('$no', `{$mapto}`) ";
				} elseif ($qtype == 'matrix-answer'){
					$filter[] = "`{$mapto}` = '$no'";
				} else {
					$filter[] = "`{$mapto}` = '$no'";
				}
			}
			$or_filters[] = ' (' . implode(' OR ', $filter) . ' ) ';
			$i++;
		}
		
		$filter = implode(' AND ', $or_filters);
		
		if ($filter) $filter = ' AND ( ' . $filter . ' )';
		
		$qnx = json_decode($x_question->answers);
		$qny = json_decode($y_question->answers);
		
		
		if ($x_question->data_type == 'date'){
			if ($this->current_data) $year = str_replace('_','',$this->current_data);
			else $year = date('Y');

			for ($m = 1; $m <= 12; $m++){
				$x_field[] = $year . '-' . $m;
			}
			$qnx = $x_field;

		} elseif ($x_question->data_type == 'time'){

			for ($t = 0; $t < 12; $t++){
				$x_field[] = sprintf('%04d', 200 * $t) . ' - ' . sprintf('%04d', 200 * ($t+1) - 1);
			}
			$qnx = $x_field;
		} elseif ($x_question->data_type == 'age'){
		
			for ($a = 0; $a < 10; $a++){
				$x_field[] = $a * 10 . ' - ' . ((($a+1) * 10) - 1);
			}
			$qnx = $x_field;			
		} else {
			foreach ($qnx as $field){
				$x_field[] = $field->value;
			}			
		}
		
		if ($y_question->data_type == 'date'){
			if ($this->current_data) $year = str_replace('_','',$this->current_data);
			else $year = date('Y');
			
			for ($m = 1; $m <= 12; $m++){
				$y_field[] = $year . '-' . $m;
			}
			
			$qny = $y_field;
		} elseif ($y_question->data_type == 'time'){
			for ($t = 0; $t < 12; $t++){
				$y_field[] = sprintf('%04d', 200 * $t) . ' - ' . sprintf('%04d', 200 * ($t+1) - 1);
			}
			$qny = $y_field;	
		} elseif ($y_question->data_type == 'age'){
			for ($a = 0; $a < 10; $a++){
				$y_field[] = $a * 10 . ' - ' . ((($a+1) * 10) - 1);
			}

			$qny = $y_field;
		} else {
			foreach ($qny as $field){
				$y_field[] = $field->value;
			}
		}
		
	

		
		$x = 0;
		foreach ($qnx as $qnx_sub){
			$y = 0;
			foreach ($qny as $qny_sub){
				$xno = $qnx_sub->no;
				$yno = $qny_sub->no;
				
				
				if ($x_question->type == 'multiple-answer'){
					$xfilter = "FIND_IN_SET('{$xno}', `{$x_question->map_to}`)";
				} else {
					if ($x_question->data_type == 'date'){
						$st		= strtotime($x_field[$x] . '-01');
						$start 	= date('Y-m-d', $st);
						$end 	= date('Y-m-t', $st);
						
						$xfilter = "( `{$x_question->map_to}` >= '{$start}' AND `{$x_question->map_to}` <= '{$end}') ";
					} elseif ($x_question->data_type == 'time'){
						list($start, $end) = explode($x_field[$x]);
						$start = trim($start);
						$end = trim($end);
						$xfilter = "( `{$x_question->map_to}` >= '{$start}' AND `{$x_question->map_to}` <= '{$end}') ";						
					} elseif ($x_question->data_type == 'age') {
						list($start, $end) = explode($x_field[$x]);
						$start = trim($start);
						$end = trim($end);
						$xfilter = "( `{$x_question->map_to}` >= '{$start}' AND `{$x_question->map_to}` <= '{$end}') ";					
					} else {
						$xfilter = "`{$x_question->map_to}` = '{$xno}'";
					}
				}
		
				if ($y_question->type == 'multiple-answer'){
					$yfilter = "FIND_IN_SET('{$yno}', `{$y_question->map_to}`)";
				} else {
					if ($y_question->data_type == 'date'){
						$st		= strtotime($y_field[$y] . '-01');
						$start 	= date('Y-m-d', $st);
						$end 	= date('Y-m-t', $st);
						
						$yfilter = "( `{$y_question->map_to}` >= '{$start}' AND `{$y_question->map_to}` <= '{$end}') ";

					} elseif ($y_question->data_type == 'time'){
						list($start, $end) = explode('-',$y_field[$y]);
						$start = trim($start);
						$end = trim($end);

						$yfilter = "( `{$y_question->map_to}` >= '{$start}' AND `{$y_question->map_to}` <= '{$end}') ";
					} elseif ($y_question->data_type == 'age'){
						list($start, $end) = explode('-',$y_field[$y]);
						$start = trim($start);
						$end = trim($end);

						$yfilter = "( `{$y_question->map_to}` >= '{$start}' AND `{$y_question->map_to}` <= '{$end}') ";

					} else {
						$yfilter = "`{$y_question->map_to}` = '{$yno}'";
					}
				}
				
				if ($answer_type == 'cases'){
					$xquery = $this->db->query("SELECT COUNT(DISTINCT case{$this->current_data}.ReportNumber) AS `freq` FROM `case{$this->current_data}`
						WHERE ($xfilter AND $yfilter) $filter");
						
$sql = "SELECT COUNT(DISTINCT case{$this->current_data}.ReportNumber) AS `freq` FROM `case{$this->current_data}`
WHERE ($xfilter AND $yfilter) $filter $qny_sub $qnx_sub";
						
				} else {
					$xquery = $this->db->query("SELECT COUNT(DISTINCT vehicle{$this->current_data}.id) AS `freq` FROM `vehicle{$this->current_data}`
						LEFT JOIN `case{$this->current_data}` ON vehicle{$this->current_data}.ReportNumber = case{$this->current_data}.ReportNumber
						LEFT JOIN `injury_info{$this->current_data}`  ON vehicle{$this->current_data}.ReportNumber = injury_info{$this->current_data}.ReportNumber 
						WHERE ($xfilter AND $yfilter) $filter"); 
						
					$sql = "SELECT COUNT(DISTINCT vehicle{$this->current_data}.id) AS `freq` FROM `vehicle{$this->current_data}`
WHERE ($xfilter AND $yfilter) $filter $qny_sub $qnx_sub";
						
				}
				

				
				$row = $xquery->row();
				$chartdata[$x][$y] = $row->freq;
				$queries[$x][$y] = $sql;
				
				
				$y++;
			}
			$x++; 
			
			
		}

		
		$data['answer_type']= $answer_type;
		$data['filter_desc']= $filter_desc;
		$data['x_field']	= $x_field;
		$data['y_field']	= $y_field;
		$data['x_axis'] 	= $x_question->q;
		$data['y_axis'] 	= $y_question->q;
		$data['chartdata']	= $chartdata;
		$data['queries']	= $queries;
		
		$base64 = base64_encode(json_encode($data));
		
		$data['base64'] = $base64;
			
		$this->load->view('header', $data);			
		$this->load->view('analytics/result', $data);
		$this->load->view('footer', $data);
	}
	
	function Excel(){
		$data = json_decode(base64_decode($this->input->post('data')));
		
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		
		$this->load->view('analytics/excel', $data);
		
	}
	
	function Heatmap(){
		$data['tab'] = 'heat-map';
		
		
		$data['questions']	= $this->questionnaire->get_questions();


		$questions = $this->questionnaire->get_questions('case');					
		$dd[] = 'Select Filter';
					
		foreach ($questions as $q){
			if ($q->type == 'matrix-answer'){
				$obj = json_decode($q->question);
				$dd[$q->map_to] = $obj->description;
							
							
			} else {
				$dd[$q->map_to] = $q->question;
			}

		}
		$data['dropdown'] = $dd;
		
		
		
		$questions = $this->questionnaire->get_questions('case');
		$ddc[] = 'Select Filter';					
		foreach ($questions as $q){

			if ($q->type == 'matrix-answer'){
				$obj 			= json_decode($q->question);
				$ddc[$q->map_to] = $obj->description;
			} else {
				$ddc[$q->map_to] = $q->question;
			}

		}
		$data['dropdown_general'] = $ddc;
		
		
		$questions = $this->questionnaire->get_questions('vehicle-car');
		$ddv[] = 'Select Filter';					
		foreach ($questions as $q){

			if ($q->type == 'matrix-answer'){
				$obj 			= json_decode($q->question);
				$ddv[$q->map_to] = $obj->description;
			} else {
				$ddv[$q->map_to] = $q->question;
			}

		}
		$data['dropdown_vehicle_car'] = $ddv;
		
		
		$questions = $this->questionnaire->get_questions('vehicle-motorcycle');
		unset($ddv);
		$ddv[] = 'Select Filter';					
		foreach ($questions as $q){

			if ($q->type == 'matrix-answer'){
				$obj 			= json_decode($q->question);
				$ddv[$q->map_to] = $obj->description;
			} else {
				$ddv[$q->map_to] = $q->question;
			}

		}
		$data['dropdown_vehicle_motorcycle'] = $ddv;
		
		$questions = $this->questionnaire->get_questions('vehicle-bus');
		unset($ddv);
		$ddv[] = 'Select Filter';					
		foreach ($questions as $q){

			if ($q->type == 'matrix-answer'){
				$obj 			= json_decode($q->question);
				$ddv[$q->map_to] = $obj->description;
			} else {
				$ddv[$q->map_to] = $q->question;
			}

		}
		$data['dropdown_vehicle_bus'] = $ddv;
		
		$questions = $this->questionnaire->get_questions('vehicle-truck');
		unset($ddv);
		$ddv[] = 'Select Filter';					
		foreach ($questions as $q){

			if ($q->type == 'matrix-answer'){
				$obj 			= json_decode($q->question);
				$ddv[$q->map_to] = $obj->description;
			} else {
				$ddv[$q->map_to] = $q->question;
			}

		}
		$data['dropdown_vehicle_truck'] = $ddv;


		
		$query = $this->db->query("SELECT `latitude`, `longitude`, `ReportNumber` FROM `case{$this->current_data}`");
		$data['coords'] = $query->result();
		
		$this->load->view('header', $data);
		$this->load->view('analytics/heatmap', $data);
		$this->load->view('footer', $data);
		
	}
	
	function Heatmap_process(){
	
	
		
		$q = $this->input->post('q');
				
		foreach ($q as $mapto => $answer){
			$filter = null;
			foreach ($answer as $subans){
				list($val, $no, $qnc, $qtype) = explode('|',$subans);
				$filter_desc[$qnc][] = $val;
				if ($qtype == 'multiple-answer'){
					$filter[] = " FIND_IN_SET('$no', `{$mapto}`) ";
				} else {
					$filter[] = "`{$mapto}` = '$no'";
				}
			}
			$or_filters[] = ' (' . implode(' OR ', $filter) . ' ) ';
			$i++;
		}
		
		$filter = implode(' AND ', $or_filters);
		
		if (!$filter) $filter = '1 = 1';
		
		
		$xquery = $this->db->query("SELECT case{$this->current_data}.ReportNumber  FROM `case{$this->current_data}`
						WHERE $filter");
		$accidents = $xquery->result();
		
		
		
		foreach ($accidents as $accident){
			$case = null;
			$query = $this->db->query("SELECT `latitude` AS `lat`, `longitude` AS `lng`, `ReportNumber`, `h_l_jalan`,`jenis_kerb`, `keadaan_jalan`, `j_p_jalan`  FROM `case{$this->current_data}` WHERE `ReportNumber` = ?", array($accident->ReportNumber));
			$case = $query->row();
			
			$case->h_l_jalan 	= $this->questionnaire->get_actual_answer('case','h_l_jalan', $case->h_l_jalan);
			$case->jenis_kerb 	= $this->questionnaire->get_actual_answer('case','jenis_kerb', $case->jenis_kerb);
			$case->keadaan_jalan= $this->questionnaire->get_actual_answer('case','keadaan_jalan', $case->keadaan_jalan);
			$case->j_p_jalan	= $this->questionnaire->get_actual_answer('case','j_p_jalan', $case->j_p_jalan);
			
			$coords[] = $case;
		}
		
		$base64 = base64_encode(json_encode($coords));
		

		echo '<script language="javascript" type="text/javascript">window.top.window.readyMap('.json_encode($coords).');</script>';
		echo '<script>
			var excelData = "'.$base64.'";
			$("input.data").val(excelData);
		</script>';
		
	}
	
	function Heatmap_Excel(){
		$data = json_decode(base64_decode($this->input->post('data')));
		
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		
		$this->load->view('analytics/excel', $data);
		
	}
	
	function POI_info($rn){
		$query = $this->db->query("SELECT * FROM `case{$this->current_data}` WHERE `ReportNumber` = ?", array($rn));
		$case = $query->row();
		
		$data['rn']				= $case->ReportNumber;
		$data['h_l_jalan'] 		= $this->questionnaire->get_actual_answer('case','h_l_jalan', $case->h_l_jalan);
		$data['jenis_kerb'] 	= $this->questionnaire->get_actual_answer('case','jenis_kerb', $case->jenis_kerb);
		$data['keadaan_jalan']	= $this->questionnaire->get_actual_answer('case','keadaan_jalan', $case->keadaan_jalan);
		$data['j_p_jalan']		= $this->questionnaire->get_actual_answer('case','j_p_jalan', $case->j_p_jalan);
		
		$this->load->view('analytics/poi', $data);
		

	}
	
	

}