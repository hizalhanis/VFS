<?php

class Term extends CI_Model {
	
	function Dropdown_list($term, $label){
		if (!$label) $label = "Select";
		$term_list[] = $label;
		$query = $this->db->query("SELECT * FROM `terms` WHERE `term` = ? ORDER BY `value` ASC", array($term));
		
		foreach ($query->result() as $item){
			$term_list[$item->name] = $item->value;
		}
		
		return $term_list;
	}
	
}