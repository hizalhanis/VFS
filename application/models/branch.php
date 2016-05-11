<?php

class Branch extends CI_Model {

	function Get_branch_dropdown($label){
		if ($label) $bd[] = $label;
		else $bd[] = "Select";
		$query = $this->db->query("SELECT * FROM `branch` ORDER BY `name` ASC");
		foreach ($query->result() as $branch){
			$bd[$branch->id] = $branch->name;
		}
		return $bd;
	}
	
	function Get_branch_by_id($id){
		$query = $this->db->query("SELECT * FROM `branch` WHERE `id` = ?", array($id));
		return $query->row();
	}
	
	function Get_branch(){
		$id = $this->user->data('branch');
		return $this->Get_branch_by_id($id);
	}
		
	function Add($data){
		$this->db->insert('branch',array(
			'type'		=> $data['type'],
			'name'		=> $data['name'],
			'address'	=> $data['address'],
			'city'		=> $data['city'],
			'state'		=> $data['state']
		));
		
		return $this->db->insert_id();
	}
	
	function Edit($id){
		$this->db->update('branch',array(
			'type'		=> $data['type'],
			'name'		=> $data['name'],
			'address'	=> $data['address'],
			'city'		=> $data['city'],
			'state'		=> $data['state']
		),"`id` = ?", array($id));
		
		
	}
	
	function Dropdown_list($label){
		if (!$label) $label = "Select";
		$branch_list[] = $label;
		
		$query = $this->db->query("SELECT * FROM `branch` ORDER BY `name` ASC");
		foreach ($query->result() as $branch){
			$branch_list[$branch->id] = $branch->name;
		}
		
		return $branch_list;
	}
	
	function Name($id){
		$query = $this->db->query("SELECT * FROM `branch` WHERE `id` = ?", array($id));
		$branch = $query->row();
		
		return $branch->name;
	}
	
	function Delete($id, $transfer_id){
		
		$query = $this->db->query("SELECT * FROM `branch` WHERE `id` = ?", array($id));
		
		if ($query->num_rows()){
			$transfer_branch = $query->row();
			$this->db->query("DELETE FROM `branch` WHERE `id` = ?", array($id));
			
			return true;
		}
		
		return false;
	}
	
}