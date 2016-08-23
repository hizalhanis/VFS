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

		

		$this->load->view('header', $data);
		$this->load->view('cases/index', $data);
		$this->load->view('footer', $data);
	}

    function New_case($do){
        $data['tab'] = "new-case";
        
        
            $id = $this->record->add($data);
            redirect('cases/gi_entry/'.$id);
            
        
    }
    
    function Edit($id,$do){
		$data['tab'] = "case-list";

		if ($do == 'do'){



			$data = $this->input->post();


			$data = $this->input->post();


			$this->record->edit($id, $data);
			redirect('cases/view/'.$id);

		} else {


			$this->load->view('header', $data);
			$this->load->view('cases/edit', $data);
			$this->load->view('footer', $data);
		}
	}


	function Case_list(){
		$data['tab'] = "case-list";

        $data['cases'] = $this->record->get_cases(array(
				'limit' 	=> 50
			));


		$this->load->view('header', $data);
		$this->load->view('cases/case_list', $data);
		$this->load->view('footer', $data);
	}

	function Filter(){
		$data['tab']    = "case-list";
        $date           = $this->input->post('date-selected');
        
        //set date
        
        $noq 			= $this->db->query("SELECT COUNT(DISTINCT Date) AS `no_row` FROM `survey_gen` WHERE Date is not null");
        $q_norow        = $noq->row();
        $aaa            = $q_norow->no_row;
        
        $query          = $this->db->query("SELECT DISTINCT Date AS `date` FROM `survey_gen` WHERE Date is not null ORDER BY Date DESC");
        for ($m = 0; $m < $aaa; $m++){
            $row = $query->row($m);
            $year_list[] = $row->date;
        }

        $date_selected = $year_list[$date];

        $case_data = array(

            'limit' 	=> 100,

            'dt'		=> $date_selected
        );
		

		$this->session->set_userdata(array(
			'case_dt'	=> $date_selected
		));


		if ($rn = $this->input->post('report_number')){
			$case_data = '';

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
		$data['tab'] = "new-case";
        
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
        

		$this->load->view('surveymode', $data);
		$this->load->view('cases/record_entry', $data);
		$this->load->view('footer', $data);
	}

	


	function View($id){
		$data['tab'] = "case-list";

		$data['case'] 	= $case = $this->record->get_case_by_id($id);


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
							$map_to				=> $answer,
                            'Date'              => date("d/m/Y"),
						), "`id` = '$row_id'");


					} else {
						$this->db->insert($table, array(
							'ReportNumber'		=> $rn,
                            'Date'              => date("d/m/Y"),
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

        $this->db->query("DELETE FROM `survey_gen` WHERE `id` = ?", array($id));

        if ($data['tab'] == "case-list") {
           redirect('cases/case_list');
        } else {
            redirect('cases/overview');
        }
        

	}

		
}