<?php

// Mobile API Controller

class API extends CI_Controller {

	function Get_case_form(){
			
		$data['questions']	= $this->questionnaire->get_questions('case',0,false);
		$data['id']			= $id;
		$data['logic']		= 'case';
		$data['case']		= $case;
		
		
		$html = $this->load->view('api/record_entry', $data, true);
		
		
		$rd->status = 'ok';
		$rd->html = $html;
		
		
		echo json_encode($rd);

	}	

	function Get_General_Form(){
			
		$data['questions']	= $this->questionnaire->get_questions('general-information',0,false);
		$data['id']			= $id;
		$data['logic']		= 'general-information';
		$data['case']		= $case;
		
		
		$html = $this->load->view('api/record_entry', $data, true);
		
		
		$rd->status = 'ok';
		$rd->html = $html;
		
		
		echo json_encode($rd);

	}	
	
	function Submit_case(){
		header('Access-Control-Allow-Origin: *');
		
		$case 		= json_decode($this->input->post('data'));
        
        
		$user_id 	= $this->input->post('user_id');
		
		$RN			= $case->data->report_number;
    
		$this->db->insert('survey_gen', array(
			'ReportNumber'		=> $case->data->ReportNumber,
			'Date'				=> date('d/m/Y'),
			'added_by'			=> $user_id,
			'added_on'			=> date('Y-m-d H:i:s')
		));
		
		$id = $this->db->insert_id();
		
		
		$zeroes = 6 - strlen($id);
		for ($i = 0; $i < $zeroes; $i++){
			$zeroes_str .= '0';
		}
		$RN = date('y') . $zeroes_str . $id;
		
		$this->db->update('survey_gen', array(
			'ReportNumber'	=> $RN
		),"`id` = '$id'");
		
		$report_number = $RN;

		
		// PROCESS CASE DATA

		$general_data = $case->caseData;
		
		foreach ($general_data as $vid => $question_data ){
				if ($question_data->map_to){
					
                    if (is_array($question_data->ans)){
						$ans = implode(',', $question_data->ans);				
					} else {
						$ans = $question_data->ans;				
					}
                    
                    $query = $this->db->query("SELECT `id` FROM `survey_gen` WHERE `ReportNumber` = ?", array($RN));
                    
					if ($query->num_rows()){
						$cdi = $query->row();
						$cdi_id = $cdi->id;
						
						$this->db->update('survey_gen', array(
							str_replace(' ','',$question_data->map_to) 	=> $ans
						),"`id` = '{$cdi_id}'");
					} else {
						$this->db->insert('survey_gen', array(
							'ReportNumber'								=> $RN,
							str_replace(' ','',$question_data->map_to)	=> $ans
						));
					}
	
                }
        }
		
	
        $res->rn		= $report_number;
		$res->status 	= 'OK';
		$res->details	= 'Record has been submitted.';
			
		echo json_encode($res);
		die();

		
    }
	
	
	function Get_Users_List(){
		$query = $this->db->query("SELECT * FROM `users`;");
		
		$rd->status = 'ok';
		$rd->data	= $query->result();
		
		echo json_encode($rd);
	}
	
	
}