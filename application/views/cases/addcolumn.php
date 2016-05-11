<?php
				echo '<script language="javascript" type="text/javascript">alert("hello");</script>';
				
				for ($x = 1; $x <= 100; $x++) {
    $query = $this->db->query("ALTER TABLE `emetra_gi` ADD sp_b_".$x." VARCHAR(10)");
} 
	
