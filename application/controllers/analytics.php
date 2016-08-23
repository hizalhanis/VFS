<?php

// Analytics Controller 

class Analytics extends CI_Controller {

	function __construct(){
	
		parent::__construct();
		
		$this->current_data = $this->session->userdata('yeardata');
		
	}
	
	
	function Contingency(){
		$data['tab'] = 'analytics';
		
		$data['questions'] 	= $questions = $this->questionnaire->get_questions();
		
					
		$dd[] = 'Select Filter';
					
		foreach ($questions as $q){

			if ($q->type == 'matrix-answer'){
				$obj 			= json_decode($q->question);
				$dd[$q->map_to] = $obj->description;
				$dda[$q->map_to]= $obj->description;
							
			} else {
				$dd[$q->map_to] = $q->question;
				$dda[$q->map_to]= $q->question;
			}

		}
		$data['dropdown'] = $dd;
		$data['dropdown_axis'] = $dda;
		
		
		
		$questions = $this->questionnaire->get_questions('case');
		$ddc[] = 'Select Filter';					
		foreach ($questions as $q){

			if ($q->type == 'matrix-answer'){
				$obj 			= json_decode($q->question);
				$ddc[$q->map_to] = $obj->description;
			} else {
				$ddc[$q->map_to] = $q->question;
			}

		}
		$data['dropdown_general'] = $ddc;
		
		
				
		$this->load->view('header', $data);
		$this->load->view('analytics/contingency', $data);
		$this->load->view('footer', $data);
    }
	
	function Contingency_Process(){
		$data['tab'] = 'analytics';
		
		$answer_type 	= $this->input->post('result_type');
		$x				= $this->input->post('x');
		$y				= $this->input->post('y');
		$date			= $this->input->post('date-selected');
		$query 			= $this->db->query("SELECT * FROM `questionnaire` WHERE `map_to` = ?", array($x));
		$x_question		= $query->row();
		
		$query 			= $this->db->query("SELECT * FROM `questionnaire` WHERE `map_to` = ?", array($y));
		$y_question		= $query->row();
		
		
        //set date
        
        $noq 			= $this->db->query("SELECT COUNT(DISTINCT Date) AS `no_row` FROM `survey_gen` WHERE Date is not null");
        $q_norow        = $noq->row();
        $aaa            = $q_norow->no_row;
        
        $query          = $this->db->query("SELECT DISTINCT Date AS `date` FROM `survey_gen` WHERE Date is not null ORDER BY Date DESC");
        for ($m = 0; $m < $aaa; $m++){
            $row = $query->row($m);
            $year_list[] = $row->date;
        }
        
        if (empty($date) AND $date != 0) {
            $date_selected = " and date = '" . $year_list[$m-1] . "'";
        } else if ($date == $m) {
            $date_selected = '';
        } else {
            $date_selected = " and date = '" . $year_list[$date] . "'";
        }
       
        if ($x_question->type == 'matrix-answer'){
			$obj = json_decode($x_question->question);
			$x_question->q = $obj->description;
		} else {
			$x_question->q = $x_question->question;
		}

		if ($y_question->type == 'matrix-answer'){
			$obj = json_decode($y_question->question);
			$y_question->q = $obj->description;
		} else {
			$y_question->q = $y_question->question;
		}
		
		
		$q = $this->input->post('q');
				
		foreach ($q as $mapto => $answer){
			$filter = null;
			foreach ($answer as $subans){
				list($val, $no, $qnc, $qtype) = explode('|',$subans);
				$filter_desc[$qnc][] = $val;
				if ($qtype == 'multiple-answer'){
					$filter[] = " FIND_IN_SET('$no', `{$mapto}`) ";
				} elseif ($qtype == 'matrix-answer'){
					$filter[] = "`{$mapto}` = '$no'";
				} else {
					$filter[] = "`{$mapto}` = '$no'";
				}
			}
			$or_filters[] = ' (' . implode(' OR ', $filter) . ' ) ';
			$i++;
		}
		
		$filter = implode(' AND ', $or_filters);
		
		if ($filter) $filter = ' AND ( ' . $filter . ' )';
		
		$qnx = json_decode($x_question->answers);
		$qny = json_decode($y_question->answers);
		
		
		if ($x_question->data_type == 'date'){
			if ($this->current_data) $year = str_replace('_','',$this->current_data);
			else $year = date('Y');

			for ($m = 1; $m <= 12; $m++){
				$x_field[] = $year . '-' . $m;
			}
			$qnx = $x_field;

		} elseif ($x_question->data_type == 'time'){

			for ($t = 0; $t < 12; $t++){
				$x_field[] = sprintf('%04d', 200 * $t) . ' - ' . sprintf('%04d', 200 * ($t+1) - 1);
			}
			$qnx = $x_field;
		
        } elseif ($x_question->data_type == 'age'){
		
			for ($a = 0; $a < 10; $a++){
				$x_field[] = $a * 10 . ' - ' . ((($a+1) * 10) - 1);
			}
			$qnx = $x_field;			
		
        } else {
			foreach ($qnx as $field){
				$x_field[] = $field->value;
			}			
		}
		
		if ($y_question->data_type == 'date'){
			if ($this->current_data) $year = str_replace('_','',$this->current_data);
			else $year = date('Y');
			
			for ($m = 1; $m <= 12; $m++){
				$y_field[] = $year . '-' . $m;
			}
			
			$qny = $y_field;
		
        } elseif ($y_question->data_type == 'time'){
			for ($t = 0; $t < 12; $t++){
				$y_field[] = sprintf('%04d', 200 * $t) . ' - ' . sprintf('%04d', 200 * ($t+1) - 1);
			}
			$qny = $y_field;	
		
        } elseif ($y_question->data_type == 'age'){
			for ($a = 0; $a < 10; $a++){
				$y_field[] = $a * 10 . ' - ' . ((($a+1) * 10) - 1);
			}

			$qny = $y_field;
		
        
        
        } elseif ($y_question->type == 'matrix-answer'){
            
            $norow 			= $this->db->query("SELECT COUNT(DISTINCT $y) AS `no_row` FROM `survey_gen` WHERE $y is not null $date_selected");
            $y_norow         = $norow->row();
            $aa              = $y_norow->no_row;
            
            $xquery = $this->db->query("SELECT DISTINCT $y AS `freq` FROM `survey_gen` WHERE $y is not null $date_selected");
            for ($a = 0; $a < $aa; $a++){
                $row = $xquery->row($a);
                $y_field[] = $row->freq;
            }
            
            $qny = $y_field;
           
        } else {
			foreach ($qny as $field){
				$y_field[] = $field->value;
			}
		}
		
	

		
		$x = 0;
		foreach ($qnx as $qnx_sub){
			$y = 0;
			foreach ($qny as $qny_sub){
				$xno = $qnx_sub->no;
				$yno = $qny_sub->no;
				
				
				if ($x_question->type == 'multiple-answer'){
					$xfilter = "FIND_IN_SET('{$xno}', `{$x_question->map_to}`)";
				} else {
					if ($x_question->data_type == 'date'){
						$st		= strtotime($x_field[$x] . '-01');
						$start 	= date('Y-m-d', $st);
						$end 	= date('Y-m-t', $st);
						
						$xfilter = "( `{$x_question->map_to}` >= '{$start}' AND `{$x_question->map_to}` <= '{$end}') ";
					} elseif ($x_question->data_type == 'time'){
						list($start, $end) = explode($x_field[$x]);
						$start = trim($start);
						$end = trim($end);
						$xfilter = "( `{$x_question->map_to}` >= '{$start}' AND `{$x_question->map_to}` <= '{$end}') ";						
					} elseif ($x_question->data_type == 'age') {
						list($start, $end) = explode($x_field[$x]);
						$start = trim($start);
						$end = trim($end);
						$xfilter = "( `{$x_question->map_to}` >= '{$start}' AND `{$x_question->map_to}` <= '{$end}') ";					
					} else {
						$xfilter = "`{$x_question->map_to}` = '{$xno}'";
					}
				}
		
				if ($y_question->type == 'multiple-answer'){
					$yfilter = "FIND_IN_SET('{$yno}', `{$y_question->map_to}`)";
				} else {
					if ($y_question->data_type == 'date'){
						$st		= strtotime($y_field[$y] . '-01');
						$start 	= date('Y-m-d', $st);
						$end 	= date('Y-m-t', $st);
						
						$yfilter = "( `{$y_question->map_to}` >= '{$start}' AND `{$y_question->map_to}` <= '{$end}') ";

					} elseif ($y_question->data_type == 'time'){
						list($start, $end) = explode('-',$y_field[$y]);
						$start = trim($start);
						$end = trim($end);

						$yfilter = "( `{$y_question->map_to}` >= '{$start}' AND `{$y_question->map_to}` <= '{$end}') ";
					} elseif ($y_question->data_type == 'age'){
						list($start, $end) = explode('-',$y_field[$y]);
						$start = trim($start);
						$end = trim($end);

						$yfilter = "( `{$y_question->map_to}` >= '{$start}' AND `{$y_question->map_to}` <= '{$end}') ";

                    } elseif ($aa > 0){
                        $yfilter = "`{$y_question->map_to}` = '$y_field[$y]'";
                    
                    } else {
						$yfilter = "`{$y_question->map_to}` = '{$yno}'";
					}
				}

					
                
                if ($aa > 0) {
                    $xquery = $this->db->query("SELECT COUNT(DISTINCT ReportNumber) AS `freq` FROM `survey_gen` WHERE ($xfilter AND $yfilter $date_selected) $filter");
                    $sql = "SELECT COUNT(DISTINCT ReportNumber) AS `freq` FROM `survey_gen` WHERE ($xfilter AND $y_count[$y] $date_selected) $filter $qny_sub $qnx_sub";
                
                } else {
                    $xquery = $this->db->query("SELECT COUNT(DISTINCT ReportNumber) AS `freq` FROM `survey_gen` WHERE ($xfilter AND $yfilter $date_selected) $filter");
                    $sql = "SELECT COUNT(DISTINCT ReportNumber) AS `freq` FROM `survey_gen` WHERE ($xfilter AND $yfilter $date_selected) $filter $qny_sub $qnx_sub";
                }    
                
                $row = $xquery->row();
                
                $chartdata[$x][$y] = $row->freq;
                
                $queries[$x][$y] = $sql;
                    
                
                $y++;
			}
			$x++; 
						
		}
		
		$data['answer_type']    = $answer_type;
		$data['filter_desc']    = $filter_desc;
		$data['x_field']        = $x_field;
		$data['y_field']        = $y_field;
		$data['x_axis']         = $x_question->q;
		$data['y_axis']         = $y_question->q;
		$data['chartdata']      = $chartdata;
		$data['queries']        = $queries;
		$data['date_picked']	= $date;
        $data['date_print']     = $year_list[$date];
		$base64 = base64_encode(json_encode($data));
		
		$data['base64'] = $base64;
			
		$this->load->view('header', $data);			
		$this->load->view('analytics/result', $data);
		$this->load->view('footer', $data);
	}
	
	function Excel(){
		$data = json_decode(base64_decode($this->input->post('data')));
        
        $filename = "VFS_data_" . date('Ymd') . ".xls";
		
        header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		
		$this->load->view('analytics/excel', $data);
		
	}
                                               
                                               
                                               
     function Frequencies(){
         $data['tab'] = 'frequency';
         $data['questions'] 	= $questions = $this->questionnaire->get_questions();
         $dd[] = 'Select Filter';
         foreach ($questions as $q){
         if ($q->type == 'matrix-answer'){
         $obj 			= json_decode($q->question);
         $dd[$q->map_to] = $obj->description;
         $dda[$q->map_to]= $obj->description;
         } else {
         $dd[$q->map_to] = $q->question;
         $dda[$q->map_to]= $q->question;
         }
         }
         $data['dropdown'] = $dd;
         $data['dropdown_axis'] = $dda;
         $questions = $this->questionnaire->get_questions('case');
         $ddc[] = 'Select Filter';
         foreach ($questions as $q){
         if ($q->type == 'matrix-answer'){
         $obj 			= json_decode($q->question);
         $ddc[$q->map_to] = $obj->description;
         } else {
         $ddc[$q->map_to] = $q->question;
         }
         }
         $data['dropdown_general'] = $ddc;
         $this->load->view('header', $data);
         $this->load->view('analytics/frequencies', $data);
         $this->load->view('footer', $data);
         }
                                               
    function Frequency_process(){
         $data['tab']   = 'frequency';
         $answer_type 	= $this->input->post('result_type');
         $x				= $this->input->post('y');
         $y				= $this->input->post('y');
        $date				= $this->input->post('date-selected');
         $query 			= $this->db->query("SELECT * FROM `questionnaire` WHERE `map_to` = ?", array($y));
         $x_question		= $query->row();
         $query 			= $this->db->query("SELECT * FROM `questionnaire` WHERE `map_to` = ?", array($y));
         $y_question		= $query->row();
        
        
        
        //set date
        
        $noq 			= $this->db->query("SELECT COUNT(DISTINCT Date) AS `no_row` FROM `survey_gen` WHERE Date is not null");
        $q_norow        = $noq->row();
        $aaa            = $q_norow->no_row;
        
        $query          = $this->db->query("SELECT DISTINCT Date AS `date` FROM `survey_gen` WHERE Date is not null  ORDER BY Date DESC");
        for ($m = 0; $m < $aaa; $m++){
            $row = $query->row($m);
            $year_list[] = $row->date;
        }
        
        if (empty($date) AND $date != 0) {
            $date_selected = " and date = '" . $year_list[$m-1] . "'";
        } else if ($date == $m) {
            $date_selected = '';
        } else {
            $date_selected = " and date = '" . $year_list[$date] . "'";
        }

  
        
             if ($y_question->type == 'matrix-answer'){
                $obj = json_decode($y_question->question);
                $y_question->q = $obj->description;
             } else {
                 $y_question->q = $y_question->question;
             }
         $q = $this->input->post('q');
             foreach ($q as $mapto => $answer){
                 $filter = null;
             foreach ($answer as $subans){
                 list($val, $no, $qnc, $qtype) = explode('|',$subans);
                 $filter_desc[$qnc][] = $val;
                      if ($qtype == 'multiple-answer'){
                           $filter[] = " FIND_IN_SET('$no', `{$mapto}`) ";
                       } elseif ($qtype == 'matrix-answer'){
                            $filter[] = "`{$mapto}` = '$no'";
                       } else {
                            $filter[] = "`{$mapto}` = '$no'";
                       }
                       }
                       $or_filters[] = ' (' . implode(' OR ', $filter) . ' ) ';
                       $i++;
                       }
                                               
                   $filter = implode(' AND ', $or_filters);
                       if ($filter) $filter = ' AND ( ' . $filter . ' )';
                             $qnx = json_decode($x_question->answers);
                             $qny = json_decode($y_question->answers);
                             if ($x_question->data_type == 'date'){
                                     if ($this->current_data) $year = str_replace('_','',$this->current_data);
                                     else $year = date('Y');
                                               for ($m = 1; $m <= 12; $m++){
                                               $x_field[] = $year . '-' . $m;
                                               }
                                               $qnx = $x_field;
                                               
                                      } elseif ($x_question->data_type == 'time'){
                                               
                                               for ($t = 0; $t < 12; $t++){
                                               $x_field[] = sprintf('%04d', 200 * $t) . ' - ' . sprintf('%04d', 200 * ($t+1) - 1);
                                               }
                                               $qnx = $x_field;
                                               
                                      } elseif ($x_question->data_type == 'age'){
                                               
                                               for ($a = 0; $a < 10; $a++){
                                               $x_field[] = $a * 10 . ' - ' . ((($a+1) * 10) - 1);
                                               }
                                               $qnx = $x_field;			
                                               
                                       } else {
                                               foreach ($qnx as $field){
                                               $x_field[] = '';
                                               }			
                                       }
        
                                      if ($y_question->data_type == 'date'){
                                               if ($this->current_data) $year = str_replace('_','',$this->current_data);
                                               else $year = date('Y');
                                               
                                               for ($m = 1; $m <= 12; $m++){
                                               $y_field[] = $year . '-' . $m;
                                               }
                                               
                                               $qny = $y_field;
                                               
                                       } elseif ($y_question->data_type == 'time'){
                                               for ($t = 0; $t < 12; $t++){
                                               $y_field[] = sprintf('%04d', 200 * $t) . ' - ' . sprintf('%04d', 200 * ($t+1) - 1);
                                               }
                                               $qny = $y_field;	
                                               
                                       } elseif ($y_question->data_type == 'age'){
                                               for ($a = 0; $a < 10; $a++){
                                               $y_field[] = $a * 10 . ' - ' . ((($a+1) * 10) - 1);
                                               }
                                               
                                               $qny = $y_field;
                                               
                                       } elseif ($y_question->type == 'matrix-answer'){
                                               
                                               $norow 			= $this->db->query("SELECT COUNT(DISTINCT $y) AS `no_row` FROM `survey_gen` WHERE $y is not null $date_selected");
                                               $y_norow         = $norow->row();
                                               $aa              = $y_norow->no_row;
                                               
                                               $xquery = $this->db->query("SELECT DISTINCT $y AS `field_name` FROM `survey_gen` WHERE $y is not null $date_selected GROUP BY $y");
                                               for ($a = 0; $a < $aa; $a++){
                                               $row = $xquery->row($a);
                                               $y_field[] = $row->field_name;
                                               }
                                               
                                               
                                               $xquery = $this->db->query("SELECT COUNT(*) AS `freq` FROM `survey_gen` WHERE $y is not null $date_selected GROUP BY $y");
                                               for ($a = 0; $a < $aa; $a++){
                                               $row = $xquery->row($a);
                                               $y_count[] = $row->freq;
                                               }
                                               
                                               
                                               $qny = $y_field;
                                               
                                        } else {
                                               foreach ($qny as $field){
                                               $y_field[] = $field->value;
                                               }
                                        }
        
                                               
                                               $x = 0;
                                               
                                               $y = 0;
                                               foreach ($qny as $qny_sub){
                                               $xno = $qnx_sub->no;
                                               $yno = $qny_sub->no;
                                               
                                               
                                               if ($x_question->type == 'multiple-answer'){
                                               $xfilter = "FIND_IN_SET('{$xno}', `{$x_question->map_to}`)";
                                               
                                               } else {
                                               
                                               if ($x_question->data_type == 'date'){
                                               $st		= strtotime($x_field[$x] . '-01');
                                               $start 	= date('Y-m-d', $st);
                                               $end 	= date('Y-m-t', $st);
                                               
                                               $xfilter = "( `{$x_question->map_to}` >= '{$start}' AND `{$x_question->map_to}` <= '{$end}') ";
                                               
                                               } elseif ($x_question->data_type == 'time'){
                                               list($start, $end) = explode($x_field[$x]);
                                               $start = trim($start);
                                               $end = trim($end);
                                               $xfilter = "( `{$x_question->map_to}` >= '{$start}' AND `{$x_question->map_to}` <= '{$end}') ";						
                                               
                                               } elseif ($x_question->data_type == 'age') {
                                               list($start, $end) = explode($x_field[$x]);
                                               $start = trim($start);
                                               $end = trim($end);
                                               $xfilter = "( `{$x_question->map_to}` >= '{$start}' AND `{$x_question->map_to}` <= '{$end}') ";					
                                               
                                               } else {
                                               $xfilter = "`{$x_question->map_to}` = '{$xno}'";
                                               }
                                               }
                                               
                                               if ($y_question->type == 'multiple-answer'){
                                               $yfilter = "FIND_IN_SET('{$yno}', `{$y_question->map_to}`)";
                                                
                                                } else {
                                               
                                               if ($y_question->data_type == 'date'){
                                               $st		= strtotime($y_field[$y] . '-01');
                                               $start 	= date('Y-m-d', $st);
                                               $end 	= date('Y-m-t', $st);
                                               
                                               $yfilter = "( `{$y_question->map_to}` >= '{$start}' AND `{$y_question->map_to}` <= '{$end}') ";
                                               
                                               } elseif ($y_question->data_type == 'time'){
                                               list($start, $end) = explode('-',$y_field[$y]);
                                               $start = trim($start);
                                               $end = trim($end);
                                               
                                               $yfilter = "( `{$y_question->map_to}` >= '{$start}' AND `{$y_question->map_to}` <= '{$end}') ";
                                               
                                               } elseif ($y_question->data_type == 'age'){
                                               list($start, $end) = explode('-',$y_field[$y]);
                                               $start = trim($start);
                                               $end = trim($end);
                                               
                                               $yfilter = "( `{$y_question->map_to}` >= '{$start}' AND `{$y_question->map_to}` <= '{$end}') ";
                                                   
                                                   
                                               } else {
                                               $yfilter = "`{$y_question->map_to}` = '{$yno}'";
                                               }
                                               }
                                               
                                               $xquery = $this->db->query("SELECT COUNT(DISTINCT ReportNumber) AS `freq` FROM `survey_gen` WHERE ($yfilter $date_selected) ");
                                               $row = $xquery->row();
                                               $chartdata[$x][$y] = $row->freq;
                                               $queries[$x][$y] = $sql;
                                               
                                               if ($aa > 0) {
                                               
                                               $chartdata[$x][$y] = $y_count[$y];
                                               
                                               }
                                               $y++;
                                               }
                                               
                                               $data['answer_type']= $answer_type;
                                               $data['filter_desc']= $filter_desc;
                                               $data['x_field']	= $x_field;
                                               $data['y_field']	= $y_field;
                                               $data['x_axis'] 	= $x_question->q;
                                               $data['y_axis'] 	= $y_question->q;
                                               $data['chartdata']	= $chartdata;
                                            $data['date_picked']	= $date;
                                            $data['date_print']     = $year_list[$date];
                                               $data['queries']	= $queries;
                                               $base64 = base64_encode(json_encode($data));
                                               $data['base64'] = $base64;
                                               $this->load->view('header', $data);
                                               $this->load->view('analytics/f_result', $data);
                                               $this->load->view('footer', $data);
                                               }
    
    
    
    
    
    
    function Index(){
        $data['tab'] = 'dashboard';
        $this->load->view('header', $data);
        $this->load->view('analytics/index', $data);
        $this->load->view('footer', $data);
    }
    
    
    function Process(){
        $data['tab']        = 'dashboard';
        $date				= $this->input->post('date-selected');
        $data['questions'] 	= $questions = $this->questionnaire->get_questions();
        
        foreach ($questions as $q){
        $yy[]				= $q->map_to;
        }
        
        //set date
        
        $noq 			= $this->db->query("SELECT COUNT(DISTINCT Date) AS `no_row` FROM `survey_gen` WHERE Date is not null");
        $q_norow        = $noq->row();
        $aaa            = $q_norow->no_row;
            
        $query          = $this->db->query("SELECT DISTINCT Date AS `date` FROM `survey_gen` WHERE Date is not null ORDER BY Date DESC");
        for ($m = 0; $m < $aaa; $m++){
            $row = $query->row($m);
            $year_list[] = $row->date;
        }
          
        if (empty($date) AND $date != 0) {
            $date_selected = " and date = '" . $year_list[$m-1] . "'";
        } else if ($date == $m) {
            $date_selected = '';
        } else {
            $date_selected = " and date = '" . $year_list[$date] . "'";
        }
        
        
       
        //count number of questions in table
        $noq 			= $this->db->query("SELECT COUNT(*) AS `no_row` FROM `questionnaire`");
        $q_norow         = $noq->row();
        $aaa              = $q_norow->no_row;
        /*
        $xquery = $this->db->query("SELECT ($q->map_to) AS `question` FROM `survey_gen`");
        for ($a = 0; $a < $aa; $a++){
            $row = $xquery->row($a);
            $y_question[] = $row->question;
        }
        
       
       */
        
        
    for ($t = 0; $t < $aaa; $t++){
       
       
        $y				= $yy[$t];
        $query 			= $this->db->query("SELECT * FROM `questionnaire` WHERE `map_to` = ?", array($y));
        $y_question		= $query->row();
        
        
        if ($y_question->type == 'matrix-answer'){
            $obj = json_decode($y_question->question);
            $y_question->q = $obj->description;
        } else {
            $y_question->q = $y_question->question;
        }
        
        $qny = json_decode($y_question->answers);
        $y_axis[]     = $y_question->q;
        
        if ($y_question->data_type == 'date'){
            if ($this->current_data) $year = str_replace('_','',$this->current_data);
            else $year = date('Y');
            
            for ($m = 1; $m <= 12; $m++){
                $y_field[$t][] = $year . '-' . $m;
            }
            
            $qny = $y_field;
            
        } elseif ($y_question->data_type == 'time'){
            for ($t = 0; $t < 12; $t++){
                $y_field[$t][] = sprintf('%04d', 200 * $t) . ' - ' . sprintf('%04d', 200 * ($t+1) - 1);
            }
            $qny = $y_field;
            
        } elseif ($y_question->data_type == 'age'){
            for ($a = 0; $a < 10; $a++){
                $y_field[$t][] = $a * 10 . ' - ' . ((($a+1) * 10) - 1);
            }
            
            $qny = $y_field;

            
        } elseif ($y_question->type == 'matrix-answer'){
            
            $norow 			= $this->db->query("SELECT COUNT(DISTINCT $y) AS `no_row` FROM `survey_gen` WHERE $y is not null $date_selected");
            $y_norow         = $norow->row();
            $aa              = $y_norow->no_row;
            
            $xquery = $this->db->query("SELECT DISTINCT $y AS `field_name` FROM `survey_gen` WHERE $y is not null $date_selected GROUP BY $y");
            for ($a = 0; $a < $aa; $a++){
                $row = $xquery->row($a);
                $y_field[$t][] = $row->field_name;
            }
            
            
            $xquery = $this->db->query("SELECT COUNT(*) AS `freq` FROM `survey_gen` WHERE $y is not null $date_selected GROUP BY $y");
            for ($g = 0; $g < $aa; $g++){
                $row = $xquery->row($g);
                $y_count[$g] = $row->freq;
            }
            
            $qny = $y_field;
            
        } else {
            foreach ($qny as $field){
                $y_field[$t][] = $field->value;
                
                
            }
        }
        
        

        
        $y = 0;
        
        foreach ($qny as $qny_sub){
            $yno = $qny_sub->no;
            
            if ($y_question->type == 'multiple-answer'){
                $yfilter = "FIND_IN_SET('{$yno}', `{$y_question->map_to}`)";
                
            } else {
                
                if ($y_question->data_type == 'date'){
                    $st		= strtotime($y_field[$t][$y] . '-01');
                    $start 	= date('Y-m-d', $st);
                    $end 	= date('Y-m-t', $st);
                    
                    $yfilter = "( `{$y_question->map_to}` >= '{$start}' AND `{$y_question->map_to}` <= '{$end}') ";
                    
                } elseif ($y_question->data_type == 'time'){
                    list($start, $end) = explode('-',$y_field[$t][$y]);
                    $start = trim($start);
                    $end = trim($end);
                    
                    $yfilter = "( `{$y_question->map_to}` >= '{$start}' AND `{$y_question->map_to}` <= '{$end}') ";
                    
                } elseif ($y_question->data_type == 'age'){
                    list($start, $end) = explode('-',$y_field[$t][$y]);
                    $start = trim($start);
                    $end = trim($end);
                    
                    $yfilter = "( `{$y_question->map_to}` >= '{$start}' AND `{$y_question->map_to}` <= '{$end}') ";
                    
                } else {
                    $yfilter = "`{$y_question->map_to}` = '{$yno}'";
                }
            }
            
            $yquery = $this->db->query("SELECT COUNT(DISTINCT ReportNumber) AS `freq` FROM `survey_gen` WHERE ($yfilter $date_selected) ");
            $row = $yquery->row();
            $chartdata[$t][$y] = $row->freq;
            $queries[$t][$y] = $sql;
            
            $y++;
        }
        
        if ($aa > 0) {
            for ($g = 0; $g < $aa; $g++){
                $chartdata[$t][$g] = $y_count[$g];
            }
        }
        
        
        $y = 0;
        $aa = 0;
    }
        
        
        $data['y_field']        = $y_field;
        $data['y_axis']         = $y_axis;
        $data['chartdata']      = $chartdata;
        $data['totalq']         = $aaa;
        $data['date_picked']	= $date;
        $base64                 = base64_encode(json_encode($data));
        $data['base64']         = $base64;
        $this->load->view('header', $data);
        $this->load->view('analytics/index', $data);
        $this->load->view('footer', $data);
    }

    
    
    
    
    

}