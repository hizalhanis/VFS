<?php

class System extends Controller {
	
	function Test(){
	
		$this->db->query("
		ALTER TABLE  `news` ADD  `classname` VARCHAR( 128 ) NULL ,
ADD  `excerpt` TEXT NULL ,
ADD  `news_date` DATE NULL
		");
		
		$query = $this->db->query("SELECT * FROM `news`");
		$result = $query->result();
		
		echo '<pre>';
		print_r($result);
		echo '</pre>';
	}

}