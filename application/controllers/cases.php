<?php

// Cases Controller

class Cases extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->current_data = $this->session->userdata('yeardata');

	}

	function Index(){
		$this->overview();
	}

	function Overview(){
		$data['tab'] = "overview";

		$query = $this->db->query("SELECT COUNT(`id`) AS `freq`, `status` FROM `case{$this->current_data}` GROUP BY `status`");
		$data['stats'] = $query->result();

		if ($this->user->data('type') == 'Superadmin'){
			$data['cases'] = $this->record->get_cases(array(
				'limit' 	=> 5,
				'sort_by'	=> 'id',
				'order'		=> 'desc'
			));

			$data['unverified_cases'] = $this->record->get_cases(array(
				'limit' 	=> 10,
				'sort_by'	=> 'id',
				'order'		=> 'asc',
				'verified'	=> ''
			));

		} else {
			$data['cases'] = $this->record->get_cases(array(
				'limit' 	=> 5,
				//'branch'	=> $this->user->data('branch'),
				'sort_by'	=> 'id',
				'order'		=> 'desc'
			));

			$data['unverified_cases'] = $this->record->get_cases(array(
				//'branch'	=> $this->user->data('branch'),
				'sort_by'	=> 'id',
				'order'		=> 'asc',
				'verified'	=> ''
			));

		}

		$this->load->view('header', $data);
		$this->load->view('cases/index', $data);
		$this->load->view('footer', $data);
	}

	function New_case($do){
		$data['tab'] = "new-case";

		if ($do == 'do'){

			$branch = $this->branch->get_branch_by_id($this->input->post('District_Code'));

			$data = $this->input->post();

			$data['branch'] = $branch->id;
			$data['state']	= $branch->state;
			$data['city']	= $branch->city;
			$data['status']	= 'Tidak Lengkap';

			$id = $this->record->add($data);
			redirect('cases/view/'.$id);

		} else {
			$data['branch'] = $this->branch->get_branch();
			$data['bd']		= $this->branch->get_branch_dropdown('Pilih Cawangan');

			$this->load->view('header', $data);
			$this->load->view('cases/new', $data);
			$this->load->view('footer', $data);
		}
	}

	function Edit($id,$do){
		$data['tab'] = "case-list";

		if ($do == 'do'){

			$branch = $this->branch->get_branch_by_id($this->input->post('District_Code'));

			$data = $this->input->post();


			$data = $this->input->post();
			$data['branch'] = $branch->id;
			$data['state']	= $branch->state;
			$data['city']	= $branch->city;

			$this->record->edit($id, $data);
			redirect('cases/view/'.$id);

		} else {
			$data['branch'] = $this->branch->get_branch();
			$data['case']	= $this->record->get_case_by_id($id);
			$data['bd']		= $this->branch->get_branch_dropdown('Pilih Cawangan');

			$this->load->view('header', $data);
			$this->load->view('cases/edit', $data);
			$this->load->view('footer', $data);
		}
	}


	function Case_list(){
		$data['tab'] = "case-list";

		if ($this->user->data('type') == 'Superadmin'){
			$data['cases'] = $this->record->get_cases(array(
				'limit' 	=> 50,
				'df'		=> $this->session->userdata('case_df') ? $this->session->userdata('case_df') : date('1/m/Y'),
				'dt'		=> $this->session->userdata('case_dt') ? $this->session->userdata('case_dt') : date('d/m/Y')
			));

		} else {
			$data['cases'] = $this->record->get_cases(array(
				'limit' 	=> 50,
				//'branch'	=> $this->user->data('branch'),
				'df'		=> $this->session->userdata('case_df') ? $this->session->userdata('case_df') : date('1/m/Y'),
				'dt'		=> $this->session->userdata('case_dt') ? $this->session->userdata('case_dt') : date('d/m/Y')
			));
		}

		$this->load->view('header', $data);
		$this->load->view('cases/case_list', $data);
		$this->load->view('footer', $data);
	}

	function Filter(){
		$data['tab'] = "case-list";

		if ($this->user->data('type') == 'Superadmin'){
			$case_data = array(
				//'branch'	=> $this->input->post('branch'),
				'limit' 	=> 1000,
				'df'		=> $this->input->post('df'),
				'status'	=> $this->input->post('status'),
				'dt'		=> $this->input->post('dt')
			);
		} else {
			$case_data = array(
				'limit' 	=> 1000,
				//'branch'	=> $this->user->data('branch'),
				'df'		=> $this->input->post('df'),
				'status'	=> $this->input->post('status'),
				'dt'		=> $this->input->post('dt')
			);

		}

		$this->session->set_userdata(array(
			'case_df'	=> $this->input->post('df'),
			'case_dt'	=> $this->input->post('dt')
		));


		if ($rn = $this->input->post('report_number')){
			$case_data = '';
			$case_data['branch']		= $this->user->data('branch');
			$case_data['report_number'] = $rn;
		}


		$data['cases'] = $this->record->get_cases($case_data);

		$this->load->view('header', $data);
		$this->load->view('cases/case_list', $data);
		$this->load->view('footer', $data);

	}



	function Case_entry($id){
		$data['tab'] = "case-list";

		$case = $this->record->get_case_by_id($id);


		$data['info']		= $case;

		$data['questions']	= $this->questionnaire->get_questions('case');
		$data['id']			= $id;
		$data['logic']		= 'case';
		$data['case']		= $case;

		$this->load->view('header', $data);
		$this->load->view('cases/record_entry', $data);
		$this->load->view('footer', $data);
	}

	function Gi_entry($id){
		$case = $this->record->get_case_by_id($id);

		$query = $this->db->query("SELECT * FROM `survey_gen` WHERE `ReportNumber` = ?", array($case->ReportNumber));
		if (!$query->num_rows()){
			$this->db->insert("survey_gen", array(
				'ReportNumber'	=> $case->ReportNumber
			));
		} else {
			$data['info']		= $query->row();
		}

		$data['questions']	= $qn = $this->questionnaire->get_questions('general-information');
		$data['logic']		= 'general-information';
		$data['id']			= $id;
		$data['case']		= $case;
		$data['sub_id']		= $sub_id;

		$this->load->view('header', $data);
		$this->load->view('cases/record_entry', $data);
		$this->load->view('footer', $data);
	}

	


	function View($id){
		$data['tab'] = "case-list";

		$data['case'] 	= $case = $this->record->get_case_by_id($id);
		$data['branch'] = $this->branch->get_branch_by_id($case->branch);
		$data['photos']	= $this->record->get_case_photos_by_id($id);

		$this->load->view('header',$data);
		$this->load->view('cases/view', $data);
		$this->load->view('footer',$data);
	}


	

	function Ajax($request){

		switch ($request){

			

			case "submit_answer":

				$logic 	= $this->input->post('logic');
				$no 	= $this->input->post('no');
				$map_to	= $this->input->post('map_to');
				$rn		= $this->input->post('report_number');
				$sub_id	= $this->input->post('sub_id');
				$type	= $this->input->post('type');

				switch ($logic){
					case "general-information": $table = 'survey_gen'; break;
					
				}


				switch ($type){
					case "single-answer":
						$answer = $this->input->post('ans');
						break;
					case "multiple-answer":
						$answer = implode(',', $this->input->post('ans'));
						break;
					case "matrix-answer":
						$answer = implode(',', $this->input->post('ans'));
						break;
				}


				//if ($logic == 'case'){
					$query = $this->db->query("SELECT * FROM `$table` WHERE `ReportNumber` = ?", array($rn));

					if ($query->num_rows()){

						$row = $query->row();
						$row_id = $row->id;

						$this->db->update($table, array(
							$map_to				=> $answer
						), "`id` = '$row_id'");


					} else {
						$this->db->insert($table, array(
							'ReportNumber'		=> $rn,
							$map_to				=> $answer
						));
					}
/* 				} else {
					$query = $this->db->query("SELECT * FROM `$table` WHERE `ReportNumber` = ? AND `sub_id` = ?", array($rn, $sub_id));

					if ($query->num_rows()){

						$row = $query->row();
						$row_id = $row->id;

						$this->db->update($table, array(
							$map_to				=> $answer
						),"`id` = '$row_id}'");


					} else {
						$this->db->insert($table, array(
							'ReportNumber'		=> $rn,
							'sub_id'			=> $sub_id,
							$map_to				=> $answer
						));
					}


				} */

				echo '<script language="javascript" type="text/javascript">window.top.window.ansSubmitted('.$no.');</script>';

				break;



		}



	}

	function Delete($id){
		$case = $this->record->get_case_by_id($id);

		$this->db->query("DELETE FROM `case{$this->current_data}` WHERE `id` = ?", array($id));

		redirect('cases/case_list');

	}

		
}