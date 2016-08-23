<?php

class Database extends CI_Controller {
	
	function Index(){
		$data['tab'] = 'main';
	
		$this->load->view('header', $data);
		$this->load->view('database/index', $data);
		$this->load->view('footer', $data);
	
	}
	
	function Download_csv(){
		
		$query = $this->db->query("SELECT * FROM `survey_gen` ");
	    $list = $query->result();
	    	
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=data.csv');
		
		// create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');
		
		// output the column headings
		foreach ($list[0] as $col => $val){
			$colnames[] = $col;
		}
		fputcsv($output, $colnames);
		
		$list = $query->result_array();
		
		foreach ($list as $row){
			fputcsv($output, $row);
		}
	}
	
	
	function Upload_csv(){
	
		
		$config['upload_path']		= './uploads';
		$config['allowed_types']	= 'csv';
		$config['max_size']			= '10240';
			
		$this->load->library('upload', $config);
		
		if ($this->upload->do_upload('file')){

			$data = $this->upload->data('file');
			


			$row = 0;			
			if (($handle = fopen('uploads/'.$data['file_name'], 'r')) !== FALSE) {
			    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
			        $num = count($data);
			        if ($row == 0){
				        for ($c=0; $c < $num; $c++) {
				        	$colnames[$c] = $data[$c];
				        }
			        } else {
				        for ($c=0; $c < $num; $c++) {

				            $rows[$row][$colnames[$c]] = $data[$c];
				        }
				        
			        }
			        $row++;
			    }
			    fclose($handle);
			}
			
			
			foreach ($rows as $row){
				$id = $row['id'];				
				$query = $this->db->query("SELECT * FROM `survey_gen` WHERE `id` = ?", array($id));
				if ($query->num_rows()){

					$this->db->update("survey_gen", $row, "`id` = '{$id}'");
					$total_updates++;
				} else {
					$this->db->insert("survey_gen", $row);
					$total_new++;
				}
			
			}
			
			$data['upload_ok'] = true;
			
		} else {
			$data['upload_error'] = true;
		}
		
		$data['tab'] = 'main';
		

		$data['total_updates'] 	= $total_updates;
		$data['total_new']		= $total_new;
	
		$this->load->view('header', $data);
		$this->load->view('database/index', $data);
		$this->load->view('footer', $data);

	}
}