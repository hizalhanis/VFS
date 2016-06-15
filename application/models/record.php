<?php
class Record extends CI_Model {

	function __construct(){
	
		parent::__construct();
		
	}
	
	function Add($data){
	
		list($day, $month, $year) = explode('/',$data['date']);
		$date = $year . '-' . $month . '-' . $day;
		
		$time = date('Hi',strtotime($data['time']));
		

		$this->db->insert('case', array(
			'month'						=> $date,
			'nama_tempat'				=> $data['nama_tempat'],
			'added_by'					=> $this->user->data('username'),
			'added_on'					=> date('Y-m-d H:i:s')
		));
		
		$insert_id = $this->db->insert_id();
		
		$zeroes = 6 - strlen($insert_id);
		for ($i = 0; $i < $zeroes; $i++){
			$zeroes_str .= '0';
		}
		$rn = date('y') . $zeroes_str . $insert_id;
		
		$this->db->update('case'.$this->current_data, array(
			'ReportNumber'	=> $rn
		),"`id` = '$insert_id'");
		
		return $insert_id;
	}
	
	function Get_case_by_id($id){
		$query = $this->db->query("SELECT * FROM `case{$this->current_data}` WHERE `id` = ?", array($id));
		return $query->row();
	}
	
	function Get_case_photos_by_id($id){
		$query = $this->db->query("SELECT * FROM `photos` WHERE `case_id` = ?", array($id));
		return $query->result();
	}
	
	function Update_case_status($id, $status){
		$this->db->update('cases', array(
			'status'	=> $status
		),"`id` = '$id'");
		
	}
	
	function Update_case_progress($id, $progress){
		$this->db->update('cases', array(
			'progress' 	=> $progress
		),"`id` = '$id'");
	}
	
	function Get_cases($data){
 		
 		if ($data['state']){
	 		$filter[] = "`state` = " . $this->db->escape($data['state']);
 		}
 		
 		if ($data['status']){
	 		$filter[] = "`status` = " . $this->db->escape($data['status']);
 		}
 		
 		if (isset($data['verified'])){
	 		$filter[] = "`verified` = " . $this->db->escape($data['verified']);	 		
 		}
 		
 		if ($data['vehicle_count']){
	 		$filter[] = "`No_Veh_Involved` = " . $this->db->escape($data['vehicle_count']);
 		}
 		
 		if ($data['branch']){
	 		$filter[] = "`District_Code` = " . $this->db->escape($data['branch']);
 		}
 		
 		if ($data['report_number']){
			$filter[] = "`ReportNumber` = " . $this->db->escape($data['report_number']);
 		}
 		
 		if ($data['df']){
 			list($day, $month, $year) = explode('/', $data['df']);
 			$date = $year . '-' . $month . '-' . $day;
	 		$filter[] = "`month` >= " . $this->db->escape($date);
 		}
 		
 		if ($data['dt']){
 			list($day, $month, $year) = explode('/', $data['dt']);
 			$date = $year . '-' . $month . '-' . $day;
	 		$filter[] = "`month` <= " . $this->db->escape($date);
	 		
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
		
		$query = $this->db->query("SELECT * FROM `case{$this->current_data}` {$filter_string} {$sort_string} {$limit_string}");
		return $query->result();
	}
	
	function Edit($id, $data){
	
		list($day, $month, $year) = explode('/',$data['month']);
		$date = $year . '-' . $month . '-' . $day;
		
		$time = date('Hi',strtotime($data['time']));

		$this->db->update('case', array(
			'month'						=> $date,
			'nama_tempat'				=> $data['nama_tempat'],
			'modified_by'				=> $this->user->data('username'),
			'modified_on'				=> date('Y-m-d H:i:s')
		), "`id` = '$id'");
		
	}
	
	function Save_sketch($id, $data){
		$this->db->update('case'.$this->current_data, array(
			'sketch'		=> $data
		), "`id` = '$id'");
	}
	
	function Delete_photo($case_id, $id){
		$this->db->query("DELETE FROM `photos` WHERE `case_id` = ? AND `id` = ?", array($case_id, $id));
	}
	
}