<?php

class Media extends Model {

	function upload(){
		$config['upload_path'] = './media/';
		$config['allowed_types'] = 'pdf|doc|dot|dotcx|xls|xlsx|ppt|pptx|pps|ppsx|swf|mov|avi|wmv|mp4|m4a|ai|eps|svg|cdr|psd|mp3|wav|ogg|mid|zip|rar|gz|tar|rtf|txt|gif|jpg|png|jpeg|bmp';
		$config['max_size']	= '10240';
		$config['remove_spaces'] = true;
		
		$this->load->library('upload', $config);
		
		$res = 1;
		
		if (is_array($_FILES['file_upload']['tmp_name'])){
			$i = 0;
			foreach ($_FILES['file_upload']['name'] as $fname){
			
				if (!$this->upload->do_upload('file_upload', $i)){
					$error = $this->upload->display_errors();
					$res = 0;
				} else {
					$data = $this->upload->data('file_upload', $i);
					if ($data['file_ext'] == '.gif' || $data['file_ext'] == '.jpg' || $data['file_ext'] == '.jpeg' || $data['file_ext'] == '.png' || $data['file_ext'] == '.bmp'){
						$type = 'Images';
					} elseif ($data['file_ext'] == '.doc' || $data['file_ext'] == '.dot' || $data['file_ext'] == '.docx' || $data['file_ext'] == '.xls' || $data['file_ext'] == '.xlsx' || $data['file_ext'] == '.ppt' || $data['file_ext'] == '.pptx' || $data['file_ext'] == '.pps' || $data['file_ext'] == '.ppsx' || $data['file_ext'] == '.pot' || $data['file_ext'] == '.pdf' || $data['file_ext'] == '.rtf'){
						$type = 'Documents';
					} elseif ($data['file_ext'] == '.psd' || $data['file_ext'] == '.cdr' || $data['file_ext'] == '.ai' || $data['file_ext'] == '.eps' || $data['file_ext'] == '.svg'){
						$type = 'Editable Graphics';
					} elseif ($data['file_ext'] == '.wmv' || $data['file_ext'] == '.avi' || $data['file_ext'] == '.swf' || $data['file_ext'] == '.mov' || $data['file_ext'] == '.mp4' || $data['file_ext'] == '.m4a' || $data['file_ext'] == '.mp3' || $data['file_ext'] == '.3gp' || $data['file_ext'] == '.3gpp' || $data['file_ext'] == '.divx'){
						$type = 'Videos';
					} elseif ($data['file_ext'] == '.mp3' || $data['file_ext'] == '.wav' || $data['file_ext'] == '.ogg' || $data['file_ext'] == '.mid'){
						$type = 'Music';
					} elseif ($data['file_ext'] == '.zip' || $data['file_ext'] == '.rar' || $data['file_ext'] == '.tar' || $data['file_ext'] == '.gz'){
						$type = 'Compressed Folders';
					} elseif ($data['file_ext'] == '.txt'){
						$type = 'Notes';
					} else {
						$type = 'Other';
					}
					$sql = $this->db->insert_string('media',array(
						'name' 		=> $data['file_name'],
						'type'		=> $type,
						'folder'	=> $this->input->post('group') ? $this->input->post('group') : '',
						'extension'	=> $data['file_ext'],
						'width'		=> $data['image_width'],
						'height'	=> $data['image_height'],						
						'datetime'	=> date('Y-m-d H:i:s')
					));
					$this->db->query($sql);
	
				}
				
				$i++;
			}
		
		}
		
		return $res;

	}

	function category_list(){
		$query = $this->db->query("SELECT * FROM `media` GROUP BY `type`");
		$files = $query->result();
		$output = "<ul class=\"lcms-file-category-list\">";
		foreach ($files as $file){
			$output .= "<li class=\"lcms-file-type\">{$file->type}</li>";
		}
		$output .= "</ul>";
		return $output;
	}
	
	function file_list($cat){
		$query = $this->db->query("SELECT * FROM `media` WHERE `type` = ?", array($cat));
		$files = $query->result();
		foreach ($files as $file){
			$ext = strtolower(str_replace('.','',$file->extension));
			$output .= "<li rel=\"{$file->id}\" folder=\"{$file->folder}\" class=\"lcms-file-type lcms-ft-$ext\">{$file->name}</li>";
		}
		return $output;
	}
	
	function file_preview($id){
		$query = $this->db->query("SELECT * FROM `media` WHERE `id` = '$id'");
		$file = $query->row();
		
		if ($file->type == 'Images'){
			$output .= "<div class=\"lcms-file-image-container\"><img src=\"".base_url()."/media/{$file->name}\" alt=\"{$file->name}\" /></div>";
		}

		$size = filesize('./media/'.$file->name);
		if ($size < 1024){
			$size = $size . ' bytes';
		} else if ($size < (1024 * 1024)){
			$size = round(($size / 1024),2) . ' KB';
		} else if ($size < (1024 * 1024 * 1024)){
			$size = round(($size / (1024 * 1024)),2) . ' MB';
		} else if ($size < (1024 * 1024 * 1024 * 1024)){
			$size = round($size / (1024 * 1024 * 1024),2) . ' GB';
		} else if ($size < (1024 * 1024 * 1024 * 1024 * 1024)){
			$size = round($size / (1024 * 1024 * 1024 * 1024),2) . ' TB';
		}
		
		$upload_date = date('H:i A j M, Y', strtotime($file->datetime));
		
		$path = base_url() . 'media/' . $file->name;
				
		$output .= "<div style=\"float: right\"><a class=\"lcms-btn lcms-file-delete\" rel=\"{$file->id}\">Delete</a></div>";
		$output .= "<table style=\"font-size: 8pt; width: 100%;\">
			<tr>
				<td style=\"text-align:right; padding-right: 5px\">Size:</td>
				<td>$size</td>
			</tr>
			<tr>
				<td style=\"text-align:right; padding-right: 5px\">Type:</td>
				<td>{$file->type}</td>
			<tr>
				<td style=\"text-align:right; padding-right: 5px\">Uploaded On:</td>
				<td>$upload_date</td>
			</tr>
			<tr>
				<td style=\"text-align:right; padding-right: 5px\">URL</td>
				<td><input type=\"text\" class=\"lcms-text\" readonly value=\"{$path}\" style=\"width: 100%\" />
			</tr>
			</table>";
			
		
		return $output;
	}
	
}

?>