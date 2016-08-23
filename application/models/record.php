<?php
class Record extends CI_Model {

	function __construct(){
	
		parent::__construct();
		
	}
	
	function Add($data){
	
		list($day, $month, $year) = explode('/',$data['date']);
		$date = $year . '-' . $month . '-' . $day;
		
		$time = date('Hi',strtotime($data['time']));
		

		$this->db->insert('survey_gen', array(
			'date'						=> $date,
			'added_by'					=> $this->user->data('username'),
			'added_on'					=> date('Y-m-d H:i:s')
		));
		
		$insert_id = $this->db->insert_id();
		
		$zeroes = 6 - strlen($insert_id);
		for ($i = 0; $i < $zeroes; $i++){
			$zeroes_str .= '0';
		}
		$rn = date('y') . $zeroes_str . $insert_id;
		
		$this->db->update('survey_gen', array(
			'ReportNumber'	=> $rn
		),"`id` = '$insert_id'");
		
		return $insert_id;
	}
	
	function Get_case_by_id($id){
		$query = $this->db->query("SELECT * FROM `survey_gen` WHERE `id` = ?", array($id));
		return $query->row();
	}
	
	
	function Get_cases($data){
 		
 		if ($data['report_number']){
			$filter[] = "`ReportNumber` = " . $this->db->escape($data['report_number']);
 		}
 		
 		if ($data['dt']){
	 		$filter[] = "`Date` = " . $this->db->escape($data['dt']);
	 		
 		}

 		if ($data['limit'] && is_numeric($data['limit'])){
	 		$limit_string = " LIMIT " . $data['limit'];
 		}
 		
 		if ($data['sort_by']){
 			$order = $data['order'] == 'DESC' ? 'DESC' : 'ASC';
			$sort_string = " ORDER BY `".$data['sort_by'] . "` " . $order;
 		}
 		
 		if (is_array($filter)){
 			$filter_string = ' WHERE ' . implode(' AND ', $filter);
 		}
		
		$query = $this->db->query("SELECT * FROM `survey_gen` {$filter_string} {$sort_string} {$limit_string}");
		return $query->result();
	}
	
	function Edit($id, $data){
	
		
		$date = $data['date'];
		
		$time = date('Hi',strtotime($data['time']));

		$this->db->update('survey_gen', array(
			'date'						=> $date,
			'modified_by'				=> $this->user->data('username'),
			'modified_on'				=> date('Y-m-d H:i:s')
		), "`id` = '$id'");
		
	}
	
	
}