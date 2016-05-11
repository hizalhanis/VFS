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

		$query = $this->db->query("SELECT * FROM `emetra_gi` WHERE `ReportNumber` = ?", array($case->ReportNumber));
		if (!$query->num_rows()){
			$this->db->insert("emetra_gi", array(
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

	function Mi_entry($id){
		$case = $this->record->get_case_by_id($id);

		$query = $this->db->query("SELECT * FROM `emetra_mi` WHERE `ReportNumber` = ?", array($case->ReportNumber));
		if (!$query->num_rows()){
			$this->db->insert("emetra_mi", array(
				'ReportNumber'	=> $case->ReportNumber
			));
		} else {
			$data['info']		= $query->row();
		}

		$data['questions']	= $qn = $this->questionnaire->get_questions('motorcycle-infra');
		$data['logic']		= 'motorcycle-infra';
		$data['id']			= $id;
		$data['case']		= $case;
		$data['sub_id']		= $sub_id;

		$this->load->view('header', $data);
		$this->load->view('cases/record_entry', $data);
		$this->load->view('footer', $data);
	}

	function Pi_entry($id){
		$case = $this->record->get_case_by_id($id);

		$query = $this->db->query("SELECT * FROM `emetra_pi` WHERE `ReportNumber` = ?", array($case->ReportNumber));
		if (!$query->num_rows()){
			$this->db->insert("emetra_pi", array(
				'ReportNumber'	=> $case->ReportNumber
			));
		} else {
			$data['info']		= $query->row();
		}

		$data['questions']	= $qn = $this->questionnaire->get_questions('pedestrian-infra');
		$data['logic']		= 'pedestrian-infra';
		$data['id']			= $id;
		$data['case']		= $case;
		$data['sub_id']		= $sub_id;

		$this->load->view('header', $data);
		$this->load->view('cases/record_entry', $data);
		$this->load->view('footer', $data);
	}

	function Pt_entry($id){
		$case = $this->record->get_case_by_id($id);

		$query = $this->db->query("SELECT * FROM `emetra_pt` WHERE `ReportNumber` = ?", array($case->ReportNumber));
		if (!$query->num_rows()){
			$this->db->insert("emetra_pt", array(
				'ReportNumber'	=> $case->ReportNumber
			));
		} else {
			$data['info']		= $query->row();
		}

		$data['questions']	= $qn = $this->questionnaire->get_questions('public-transport');
		$data['logic']		= 'public-transport';
		$data['id']			= $id;
		$data['case']		= $case;
		$data['sub_id']		= $sub_id;

		$this->load->view('header', $data);
		$this->load->view('cases/record_entry', $data);
		$this->load->view('footer', $data);
	}


	function Rs_entry($id){
		$case = $this->record->get_case_by_id($id);

		$query = $this->db->query("SELECT * FROM `emetra_rs` WHERE `ReportNumber` = ?", array($case->ReportNumber));
		if (!$query->num_rows()){
			$this->db->insert("emetra_rs", array(
				'ReportNumber'	=> $case->ReportNumber
			));
		} else {
			$data['info']		= $query->row();
		}

		$data['questions']	= $qn = $this->questionnaire->get_questions('road-surface');
		$data['logic']		= 'road-surface';
		$data['id']			= $id;
		$data['case']		= $case;
		$data['sub_id']		= $sub_id;

		$this->load->view('header', $data);
		$this->load->view('cases/record_entry', $data);
		$this->load->view('footer', $data);
	}

	function Rss_entry($id){
		$case = $this->record->get_case_by_id($id);

		$query = $this->db->query("SELECT * FROM `emetra_rss` WHERE `ReportNumber` = ?", array($case->ReportNumber));
		if (!$query->num_rows()){
			$this->db->insert("emetra_rss", array(
				'ReportNumber'	=> $case->ReportNumber
			));
		} else {
			$data['info']		= $query->row();
		}

		$data['questions']	= $qn = $this->questionnaire->get_questions('road-side-safety');
		$data['logic']		= 'road-side-safety';
		$data['id']			= $id;
		$data['case']		= $case;
		$data['sub_id']		= $sub_id;

		$this->load->view('header', $data);
		$this->load->view('cases/record_entry', $data);
		$this->load->view('footer', $data);
	}


	function Vehicle_entry($id, $sub_id, $v, $type){
		$data['tab'] = "case-list";

		$case = $this->record->get_case_by_id($id);

		$query = $this->db->query("SELECT * FROM `vehicle{$this->current_data}` WHERE `ReportNumber` = ? AND `sub_id` = ?", array($case->ReportNumber, $sub_id));
		if (!$query->num_rows()){
			$this->db->insert("vehicle{$this->current_data}", array(
				'ReportNumber'	=> $case->ReportNumber,
				'sub_id'		=> $sub_id,
				'type'			=> $type
			));
		} else {
			$data['info']		= $query->row();
		}





		$data['questions']	= $qn = $this->questionnaire->get_questions('vehicle-'.$type);
		$data['logic']		= 'vehicle-'.$type;
		$data['id']			= $id;
		$data['case']		= $case;
		$data['sub_id']		= $sub_id;


		$this->load->view('header', $data);
		$this->load->view('cases/record_entry', $data);
		$this->load->view('footer', $data);
	}

	function Injury_entry($id, $sub_id){
		$data['tab'] = "case-list";

		$case = $this->record->get_case_by_id($id);

		$query = $this->db->query("SELECT * FROM `injury_info{$this->current_data}` WHERE `ReportNumber` = ? AND `sub_id` = ?", array($case->ReportNumber, $sub_id));
		$data['info']		= $query->row();

		$data['questions']	= $this->questionnaire->get_questions('injury');
		$data['logic']		= 'injury';
		$data['id']			= $id;
		$data['case']		= $case;
		$data['sub_id']		= $sub_id;

		$this->load->view('header', $data);
		$this->load->view('cases/record_entry', $data);
		$this->load->view('footer', $data);
	}

	function Injury_p_entry($id, $sub_id){
		$data['tab'] = "case-list";

		$case = $this->record->get_case_by_id($id);

		$query = $this->db->query("SELECT * FROM `injury_info{$this->current_data}`` WHERE `ReportNumber` = ? AND `sub_id` = ?", array($case->ReportNumber, $sub_id));
		$data['info']		= $query->row();

		$data['questions']	= $this->questionnaire->get_questions('injury_p');
		$data['logic']		= 'injury_p';
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


	function Sketch($id, $do){
		$data['tab'] = "case-list";

		if ($do == 'do'){

		} else {
			$this->load->view('header', $data);
			$this->load->view('cases/sketch', $data);
			$this->load->view('footer', $data);
		}

	}

	function read_gps_location($file){
	    if (is_file($file)) {
	        $info = exif_read_data($file);
	        if (isset($info['GPSLatitude']) && isset($info['GPSLongitude']) &&
	            isset($info['GPSLatitudeRef']) && isset($info['GPSLongitudeRef']) &&
	            in_array($info['GPSLatitudeRef'], array('E','W','N','S')) && in_array($info['GPSLongitudeRef'], array('E','W','N','S'))) {

	            $GPSLatitudeRef  = strtolower(trim($info['GPSLatitudeRef']));
	            $GPSLongitudeRef = strtolower(trim($info['GPSLongitudeRef']));

	            $lat_degrees_a = explode('/',$info['GPSLatitude'][0]);
	            $lat_minutes_a = explode('/',$info['GPSLatitude'][1]);
	            $lat_seconds_a = explode('/',$info['GPSLatitude'][2]);
	            $lng_degrees_a = explode('/',$info['GPSLongitude'][0]);
	            $lng_minutes_a = explode('/',$info['GPSLongitude'][1]);
	            $lng_seconds_a = explode('/',$info['GPSLongitude'][2]);

	            $lat_degrees = $lat_degrees_a[0] / $lat_degrees_a[1];
	            $lat_minutes = $lat_minutes_a[0] / $lat_minutes_a[1];
	            $lat_seconds = $lat_seconds_a[0] / $lat_seconds_a[1];
	            $lng_degrees = $lng_degrees_a[0] / $lng_degrees_a[1];
	            $lng_minutes = $lng_minutes_a[0] / $lng_minutes_a[1];
	            $lng_seconds = $lng_seconds_a[0] / $lng_seconds_a[1];

	            $lat = (float) $lat_degrees+((($lat_minutes*60)+($lat_seconds))/3600);
	            $lng = (float) $lng_degrees+((($lng_minutes*60)+($lng_seconds))/3600);

	            //If the latitude is South, make it negative.
	            //If the longitude is west, make it negative
	            $GPSLatitudeRef  == 's' ? $lat *= -1 : '';
	            $GPSLongitudeRef == 'w' ? $lng *= -1 : '';

	            return array(
	                'lat' => $lat,
	                'lng' => $lng
	            );
	        }
	    }
	    return false;
	}

	function Delete_photo($case_id, $id){
		$this->record->delete_photo($case_id, $id);
		redirect('cases/view/'.$case_id);
	}

	function Ajax($request){

		switch ($request){

			case "set_team_leader":

				$team_leader = $this->input->post('team_leader');
				$case_id = $this->input->post('case_id');

				$this->db->update("case{$this->current_data}", array(
					'team_leader'	=> $team_leader
				),"`id` = '$case_id'");

				break;

			case "set_team":
				$team = $this->input->post('team');
				$case_id = $this->input->post('case_id');

				$this->db->update("case{$this->current_data}", array(
					'team'	=> $team
				),"`id` = '$case_id'");

				$users = $this->user->team_users($team);
				echo '<option>Pilih Ketua Kumpulan</option>';
				foreach ($users as $id => $user){
					if ($id){
						echo '<option value="'.$id.'">'.$user.'</option>';
					}
				}

				break;

			case "set_team_leader":

				break;

			case "set_vehicle_type":

				break;

			case "update_verified":
				$case_id 	= $this->input->post('case_id');
				$status		= $this->input->post('status');

				$this->db->query("UPDATE `case{$this->current_data}` SET `verified` = ? WHERE `id` = ?", array($status, $case_id));

				echo 'ok';

				break;

			case "update_status":
				$case_id 	= $this->input->post('case_id');
				$status		= $this->input->post('status');

				$this->db->query("UPDATE `case{$this->current_data}` SET `status` = ? WHERE `id` = ?", array($status, $case_id));

				echo 'ok';

				break;

			case "update_cadangan":
				$case_id 	= $this->input->post('case_id');
				$text		= $this->input->post('text');

				$this->db->query("UPDATE `case{$this->current_data}` SET `cadangan` = ? WHERE `id` = ?", array($text, $case_id));

				echo 'ok';

				break;
			case "update_ulasan":
				$case_id 	= $this->input->post('case_id');
				$text		= $this->input->post('text');

				$this->db->query("UPDATE `case{$this->current_data}` SET `ulasan` = ? WHERE `id` = ?", array($text, $case_id));

				echo 'ok';

				break;

			case "upload_photo":

				$id = $this->input->post('case_id');


				$config['upload_path'] 		= './assets/images/';
				$config['allowed_types'] 	= 'gif|png|jpg';
				$config['max_size']			= '4096';

				$this->load->library('upload');
				$this->upload->initialize($config);

				$ok = true;

			    if ($this->upload->do_upload('photo')){
			    	$photo_data = $this->upload->data('photo');
			    	$photo = $photo_data['file_name'];
			    } else {
				    $err[] = "Please make sure you have uploaded your photo correctly.";
				    $ok = false;
			    }

			    $geotag = $this->read_gps_location('assets/images/'.$photo);

			    if ($ok){
					$this->db->insert('photos', array(
						'case_id'	=> $id,
						'file' 		=> $photo,
						'geo_lat'	=> $geotag->lat,
						'geo_lng'	=> $geotag->lng
					));
			    }

			    echo '<script language="javascript" type="text/javascript">window.top.window.readyPhoto("'.$xphoto.'");</script>';

				break;

			case "save_sketch":

				$data 	= $this->input->post('data');
				$id 	= $this->input->post('id');

				$this->record->save_sketch($id, $data);
				echo 'ok';

				break;

			case "sketch":

				$id 	= $this->input->post('id');

				$record = $this->record->get_case_by_id($id);

				header('Content-type: image/png');

				echo base64_decode(str_replace('data:image/png;base64,','',$record->sketch));

				break;

			case "get_sketch":

				$id 	= $this->input->post('id');

				$record = $this->record->get_case_by_id($id);

				echo $record->sketch;

				break;

			case "submit_answer":

				$logic 	= $this->input->post('logic');
				$no 	= $this->input->post('no');
				$map_to	= $this->input->post('map_to');
				$rn		= $this->input->post('report_number');
				$sub_id	= $this->input->post('sub_id');
				$type	= $this->input->post('type');

				switch ($logic){
					case "general-information": $table = 'emetra_gi'; break;
					case "motorcycle-infra": $table = 'emetra_mi'; break;
					case "pedestrian-infra": $table = 'emetra_pi'; break;
					case "public-transport": $table = 'emetra_pt'; break;
					case "road-surface": $table = 'emetra_rs'; break;
					case "road-side-safety": $table = 'emetra_rss'; break;
					case "visual-aid": $table = 'emetra_va'; break;
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

	function Select_year($year){
		$this->session->set_userdata(array('yeardata'=>$year));
		redirect();
	}


	function Search_roads(){
		$search = $_REQUEST['q'];


		$query = $this->db->query("SELECT * FROM `roads` WHERE `name` LIKE '$search%' ORDER BY `name`");
		$items = $query->result();

		foreach ($items as $item){
			$response[] = array($item->name . '|'.$item->parlimen, $item->name . ' ('.$item->parlimen.')');
		}

		header('Content-type: application/json');
		echo json_encode($response);
	}
}