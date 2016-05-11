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
	
	function Get_Motorcycle_Form(){
		$data['questions']	= $this->questionnaire->get_questions('motorcycle-infra',0,false);
		$data['id']			= $id;
		$data['logic']		= 'motorcycle-infra';
		$data['case']		= $case;
		
		
		$html = $this->load->view('api/record_entry', $data, true);
		
		$rd->status = 'ok';
		$rd->html = $html;
		
		echo json_encode($rd);


	}
	
	function Get_PublicTransport_Form(){
		$data['questions']	= $this->questionnaire->get_questions('public-transport',0,false);
		$data['id']			= $id;
		$data['logic']		= 'public-transport';
		$data['case']		= $case;
		
		$html = $this->load->view('api/record_entry', $data, true);
		
		$rd->status = 'ok';
		$rd->html = $html;
		
		echo json_encode($rd);

	}
	
	function Get_Pedestrian_Form(){
		$data['questions']	= $this->questionnaire->get_questions('pedestrian-infra',0,false);
		$data['id']			= $id;
		$data['logic']		= 'pedestrian-infra';
		$data['case']		= $case;
		
		$html = $this->load->view('api/record_entry', $data, true);
		
		$rd->status = 'ok';
		$rd->html = $html;
		
		echo json_encode($rd);

		
	}
	
	function Get_RoadSurface_Form(){
		$data['questions']	= $this->questionnaire->get_questions('road-surface',0,false);
		$data['id']			= $id;
		$data['logic']		= 'road-surface';
		$data['case']		= $case;
		
		$html = $this->load->view('api/record_entry', $data, true);
		
		$rd->status = 'ok';
		$rd->html = $html;
		
		echo json_encode($rd);

		
	}
	
	function Get_RoadSideSafety_Form(){
		$data['questions']	= $this->questionnaire->get_questions('road-side-safety',0,false);
		$data['id']			= $id;
		$data['logic']		= 'road-side-safety';
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
		
		$this->db->insert('case', array(
			'team'				=> $case->data->team,
			'team_leader'		=> $case->data->leaderid,
			'ReportNumber'		=> $case->data->ReportNumber,
			'month'				=> $case->data->date,
			'latitude'			=> $case->data->latitude,
			'longitude'			=> $case->data->longitude,
			'team_members'		=> $case->data->membersid,
			'nama_jalan'		=> $case->data->nama_jalan,
			'nama_tempat'		=> $case->data->nama_tempat,
			//'sketch'			=> $case->sketchData,
			'added_by'			=> $user_id,
			'added_on'			=> date('Y-m-d H:i:s')
		));
		
		$id = $this->db->insert_id();
		
		
		$zeroes = 6 - strlen($id);
		for ($i = 0; $i < $zeroes; $i++){
			$zeroes_str .= '0';
		}
		$RN = date('y') . $zeroes_str . $id;
		
		$this->db->update('case', array(
			'ReportNumber'	=> $RN
		),"`id` = '$id'");
		
		$report_number = $RN;

		
		// PROCESS CASE DATA

		$general_data = $case->caseData;
		
		foreach ($general_data as $vid => $question_data ){
			//foreach ($motorcycle as $question_data){
				
				if ($question_data->map_to){
					if (is_array($question_data->ans)){
						$ans = implode(',', $question_data->ans);				
					} else {
						$ans = $question_data->ans;				
					}
					
					$query = $this->db->query("SELECT `id` FROM `emetra_gi` WHERE `ReportNumber` = ?", array($RN));
					
					if ($query->num_rows()){
						$cdi = $query->row();
						$cdi_id = $cdi->id;
						
						$this->db->update('emetra_gi', array(
							str_replace(' ','',$question_data->map_to) 	=> $ans
						),"`id` = '{$cdi_id}'");
					} else {
						$this->db->insert('emetra_gi', array(
							'ReportNumber'								=> $RN,
							str_replace(' ','',$question_data->map_to)	=> $ans
						));
					}
	
				}
			//}
		}
		
		// PROCESS MOTORCYCLE DATA
		
		$motorcycle_data = $case->motorcycleData;
		
		foreach ($motorcycle_data as $vid => $question_data ){
			//foreach ($motorcycle as $question_data){
				
				if ($question_data->map_to){
					if (is_array($question_data->ans)){
						$ans = implode(',', $question_data->ans);				
					} else {
						$ans = $question_data->ans;				
					}
					
					$query = $this->db->query("SELECT `id` FROM `emetra_mi` WHERE `ReportNumber` = ?", array($RN));
					
					if ($query->num_rows()){
						$cdi = $query->row();
						$cdi_id = $cdi->id;
						
						$this->db->update('emetra_mi', array(
							str_replace(' ','',$question_data->map_to) 	=> $ans
						),"`id` = '{$cdi_id}'");
					} else {
						$this->db->insert('emetra_mi', array(
							'ReportNumber'								=> $RN,
							str_replace(' ','',$question_data->map_to)	=> $ans
						));
					}
	
				}
			//}
		}
		
		// PROCESS PUBLIC TRANSPORT DATA
		
		$publictransport_data = $case->publictransportData;
		
		foreach ($publictransport_data as $vid => $question_data ){
			//foreach ($publictransport as $question_data){
				if ($question_data->map_to){
				
					if (is_array($question_data->ans)){
						$ans = implode(',', $question_data->ans);				
					} else {
						$ans = $question_data->ans;				
					}
					
					$query = $this->db->query("SELECT `id` FROM `emetra_pt` WHERE `ReportNumber` = ?", array($RN));
					
					if ($query->num_rows()){
						$cdi = $query->row();
						$cdi_id = $cdi->id;
						
						$this->db->update('emetra_pt', array(
							$question_data->map_to => $ans
						),"`id` = '{$cdi_id}'");
					} else {
						$this->db->insert('emetra_pt', array(
							'ReportNumber'			=> $RN,
							$question_data->map_to 	=> $ans
						));
					}
	
				}
			//}
		}
		
		// PROCESS PEDESTRIAN DATA
		
		$pedestrian_data = $case->pedestrianData;
		
		foreach ($pedestrian_data as $vid => $question_data ){
			//foreach ($pedestrian as $question_data){
				if ($question_data->map_to){
				
					if (is_array($question_data->ans)){
						$ans = implode(',', $question_data->ans);				
					} else {
						$ans = $question_data->ans;				
					}
					
					$query = $this->db->query("SELECT `id` FROM `emetra_pi` WHERE `ReportNumber` = ?", array($RN));
					
					if ($query->num_rows()){
						$cdi = $query->row();
						$cdi_id = $cdi->id;
						
						$this->db->update('emetra_pi', array(
							$question_data->map_to 	=> $ans
						),"`id` = '{$cdi_id}'");
					} else {
						$this->db->insert('emetra_pi', array(
							'ReportNumber'			=> $RN,
							$question_data->map_to 	=> $ans
						));
					}
	
				}
			//}
		}
		
		// PROCESS ROAD SURFACE DATA
		
		$roadsurface_data = $case->roadsurfaceData;
		
		foreach ($roadsurface_data as $vid => $question_data ){
			//foreach ($roadsurface as $question_data){
				if ($question_data->map_to){
				
					if (is_array($question_data->ans)){
						$ans = implode(',', $question_data->ans);				
					} else {
						$ans = $question_data->ans;				
					}
					
					$query = $this->db->query("SELECT `id` FROM `emetra_rs` WHERE `ReportNumber` = ?", array($RN));
					
					if ($query->num_rows()){
						$cdi = $query->row();
						$cdi_id = $cdi->id;
						
						$this->db->update('emetra_rs', array(
							$question_data->map_to 	=> $ans
						),"`id` = '{$cdi_id}'");
					} else {
						$this->db->insert('emetra_rs', array(
							'ReportNumber'			=> $RN,
							$question_data->map_to 	=> $ans
						));
					}
	
				}
			//}
		}
		
		// PROCESS ROAD SIDE SAFETY DATA
		
		$roadsidesafety_data = $case->roadsidesafetyData;
		
		foreach ($roadsidesafety_data as $vid => $question_data ){
			//foreach ($roadsidesafety as $question_data){
				if ($question_data->map_to){
				
					if (is_array($question_data->ans)){
						$ans = implode(',', $question_data->ans);				
					} else {
						$ans = $question_data->ans;				
					}
					
					$query = $this->db->query("SELECT `id` FROM `emetra_rss` WHERE `ReportNumber` = ?", array($RN));
					
					if ($query->num_rows()){
						$cdi = $query->row();
						$cdi_id = $cdi->id;
						
						$this->db->update('emetra_rss', array(
							$question_data->map_to 	=> $ans
						),"`id` = '{$cdi_id}'");
					} else {
						$this->db->insert('emetra_rss', array(
							'ReportNumber'			=> $RN,
							$question_data->map_to 	=> $ans
						));
					}
	
				}
			//}
		}

		foreach ($case->images as $imgid => $image){
			$this->db->insert('photos', array(
				'case_id'	=> $id,
				//'image'		=> 'blob',
				'file'		=> 'image',
				'blob'		=> $image
			));
		}
		
		// PROCESS SKETCH DATA

		$this->db->update('case', array(
			'sketch'	=> $case->sketchData
		), "`id` = '{$id}'");
		
		$res->rn		= $report_number;
		$res->status 	= 'OK';
		$res->details	= 'Record has been submitted.';
			
		echo json_encode($res);
		die();

		
	}
	
	function Get_branch_list(){
		$query = $this->db->query("SELECT * FROM `branch` ORDER BY `name` ASC");
		
		$html = '<select name="branch" class="select-branch">';
		foreach ($query->result() as $branch){
			$html .= '<option value="'.$branch->id.'">'.$branch->name.'</option>';
		}
		$html .= '</select>';
		
		$rd->status = 'ok';
		$rd->html	= $html;
		
		echo json_encode($rd);
	}
	
	function Get_users(){
		$id = $this->input->post('branch_id');
		
		$query = $this->db->query("SELECT *, branch.name AS `branch_name`, branch.city AS `city`, branch.state AS `state` FROM `users` LEFT JOIN `branch` ON users.branch = branch.id WHERE `branch` = ?", array($id));
		
		$rd->status = 'ok';
		$rd->data	= $query->result();
		
		echo json_encode($rd);
	}
	
	function Get_Users_List(){
		$query = $this->db->query("SELECT * FROM `users`;");
		
		$rd->status = 'ok';
		$rd->data	= $query->result();
		
		echo json_encode($rd);
	}
	
	function Get_Groups_List(){
		$query = $this->db->query("SELECT * FROM `teams` ORDER BY `name` ASC");
		
		$html = '<select name="team" class="select-team">';
		foreach ($query->result() as $team){
			$html .= '<option value="'.$team->id.'">'.$team->name.'</option>';
		}
		$html .= '</select>';
		
		$rd->status = 'ok';
		$rd->html	= $html;
		
		echo json_encode($rd);
	}
}