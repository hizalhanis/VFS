<?php

class Page extends Controller {
	
	var $author_mode;
	var $ctls;
	
	function Page(){
		parent::Controller();

		$this->load->model('application/models/user');
		$this->load->model('application/models/cms');
		$this->load->model('application/models/controls');		

		$this->load->helper('content_helper');
		
		$this->ctls = $this->controls->core_controls();
		
		if ($this->user->is_alive()) $this->author_mode = true;
	}
	
	function index(){
		$page = $this->cms->main_page();
		$this->view($page);
	}
	
	function view($page, $param1, $param2, $param3, $param4, $param5, $param6){

	
		
		if ($this->cms->subpage_of($page, $param1)){
			$page = $param1;
			
			
			$_SESSION['param1'] = $param2;
			$_SESSION['param2'] = $param3;
			$_SESSION['param3'] = $param4;
			$_SESSION['param4'] = $param5;
			$_SESSION['param5'] = $param6;

		} else {
			$_SESSION['param1'] = $param1;
			$_SESSION['param2'] = $param2;
			$_SESSION['param3'] = $param3;
			$_SESSION['param4'] = $param4;
			$_SESSION['param5'] = $param5;

		}
			
		$this->session->set_userdata(array('page'=>$page));
		$_SESSION['page'] = $cur_page = $page;


		
		$theme = $this->cms->current_theme();
		$page = $this->cms->page($cur_page);
		
		
		if ($page == $this->cms->main_page()) $data['main'] = true;
		


		$data['headers'] = "<base href=\"". base_url() ."\" />"."\n";	
		$data['headers'] .= $this->cms->theme_link()."\n";
		$data['headers'] .= $this->cms->essential_css_link()."\n";
		$data['headers'] .= $this->cms->essential_js_link()."\n";

		$data['title'] = $page->title;

		$pseudo_page = $page->name . ($param1 ? '/'.$param1 : '') . ($param2 ? '/'.$param2 : '') . ($param3 ? '/'.$param3 : '') . ($param4 ? '/'.$param4 : '') . ($param5 ? '/'.$param5 : '');
		if ($is_pseudo_page = $this->cms->check_pseudo_page($pseudo_page)){
			$data['navigation'] = $this->cms->navigation($pseudo_page);	

		} else {
			$data['navigation'] = $this->cms->navigation($page->name);
		}
		$data['content'] = $this->cms->load_contents($page, $this->author_mode);

		$controls = $_SESSION['lcms_toolbars'] = $this->ctls;		
		
		if ($this->author_mode){
			if ($is_pseudo_page){
				$js_controller = '<script type="text/javascript">var site_url = "'.site_url().'"; var base_url = "'.base_url().'"; var lcmsCurrentPage = "'.$pseudo_page.'";</script>'."\n";	
			} else {
				$js_controller = '<script type="text/javascript">var site_url = "'.site_url().'"; var base_url = "'.base_url().'"; var lcmsCurrentPage = "'.$cur_page.'";</script>'."\n";
			}

			$js_controller .= '<script type="text/javascript" src="'.base_url().'js/jquery.js"></script>'."\n";
			$js_controller .= '<script type="text/javascript" src="'.base_url().'js/jqueryui.js"></script>'."\n";
			$js_controller .= '<script type="text/javascript" src="'.base_url().'js/nicedit.js"></script>'."\n";
			$js_controller .= '<script type="text/javascript" src="'.base_url().'js/beautify.js"></script>'."\n";
			$js_controller .= '<script type="text/javascript" src="'.base_url().'js/lcms.js"></script>'."\n";
			$js_controller .= '<script type="text/javascript" src="'.base_url().'js/plugins.js"></script>'."\n";
			
			$controller_css = '<link rel="stylesheet" href="'.base_url().'css/controller.css" />'."\n";
			$controller_css .= '<link rel="stylesheet" href="'.base_url().'css/ui-lightness/jquery-ui-1.7.2.custom.css" />'."\n";

		}


		foreach ($controls as $control){

			$this->{$control}->hooks($this->author_mode);

			if ($this->author_mode){
				if (is_array($this->{$control}->js_includes)){
	    			foreach ($this->{$control}->js_includes as $js_include){
	    				$src = base_url() . 'modules/' . $control .'/' . $js_include;
	    				$js_controller .= '<script type="text/javascript" src="'.$src.'"></script>'."\n";	
	    			}
	    		} elseif ($this->{$control}->js_includes != null) {
	    			$src = base_url() . 'modules/' . $control .'/'  . $this->{$control}->js_includes;
	    			$js_controller .= '<script type="text/javascript" src="'.$src.'"></script>'."\n";	
	    		}
	
	    		if (is_array($this->{$control}->css_includes)){
	    			foreach ($this->{$control}->css_includes as $css_include){
	    				$src = base_url() . 'modules/' . $control .'/' . $css_include;
	    				$controller_css .= '<link rel="stylesheet" href="'.$src.'" />."\n"';	
	    			}
	    		} elseif ($this->{$control}->css_includes != null) {
	
	    			$src = base_url() . 'modules/' . $control .'/' . $this->{$control}->css_includes;
	    			$controller_css .= '<link rel="stylesheet" href="'.$src.'" />'."\n";	
	    		}
	    	}
	    	
			if (is_array($this->{$control}->js_resources)){
	        	foreach ($this->{$control}->js_resources as $js_resources){
	        		$src = (strpos($js_resources,':')) ? $js_resources : base_url() . 'modules/' . $control .'/' . $js_resources;
	        		$js_controller .= '<script type="text/javascript" src="'.$src.'"></script>'."\n";	
	        	}
	        } elseif ($this->{$control}->js_resources != null) {
	        	$src = base_url() . 'modules/' . $control .'/'  . $this->{$control}->js_resources;
	        	$js_controller .= '<script type="text/javascript" src="'.$src.'"></script>'."\n";	
	        }
	
	        if (is_array($this->{$control}->css_resources)){
	        	foreach ($this->{$control}->css_resources as $css_resources){
	        		$src = base_url() . 'modules/' . $control .'/' . $css_resources;
	        		$controller_css .= '<link rel="stylesheet" href="'.$src.'" />'."\n";	
	        	}
	        } elseif ($this->{$control}->css_resources != null) {	
	        	$src = base_url() . 'modules/' . $control .'/' . $this->{$control}->css_resources;
	        	$controller_css .= '<link rel="stylesheet" href="'.$src.'" />'."\n";	
	        }
		}


		$data['headers'] .= $js_controller;		
		$data['headers'] .= $controller_css;
		
		$data['subtitle'] = $this->session->userdata('subtitle') ? ' &raquo; ' . $this->session->userdata('subtitle') : '';
		$this->session->set_userdata(array('subtitle'=>''));
		
		if (file_exists('themes/'. $theme->directory.'/'.$page->layout.'.php')){
			$this->load->view('themes/'. $theme->directory.'/'.$page->layout.'.php', $data);
		} else {
			if ($this->author_mode){
				$this->load->view('interface/no-layout',$data);
			} else {
				$this->load->view('interface/four-o-four',$data);
			}
		}

	}
	
	function Ajax($request, $control, $action, $id){
		
		switch ($request){
			case 'controller_bar':
				$current_page = $this->input->post('current_page');
				$this->controls->controller_bar($current_page);
			break;
			case 'forms':
				$current_page = $this->input->post('current_page');
				$this->controls->forms($current_page);
			break;
			case 'page_list':
				$current_page = $this->input->post('current_page');
				$page_data = $this->cms->page($current_page);
				
				$result->new_page_list = $this->controls->page_list(NULL, true, true);
				$result->edit_page_list = $this->controls->page_list($page_data->parent, true, true, $current_page);
				$result->jump_nav_list = $this->controls->page_list($current_page, true, false);
				$result->current_page = $current_page;
				$result->data = $page_data;
				
				echo json_encode($result);
			break;
			case 'layouts':
				$current_page = $this->input->post('current_page');			
				$this->controls->layouts($current_page);
			break;
			case 'new_page':
				$this->cms->new_page();
			break;
			case 'edit_page':
				$this->cms->edit_page();
			break;
			case 'save_order':
				$order = $this->input->post('order');
				$this->cms->save_order($order);
			break;
			case 'save_content_order':
				$order = $this->input->post('order');
				$this->cms->save_content_order($order);
			break;
			case 'delete_content':
				$id = $this->input->post('id');
				$this->cms->delete_content($id);
			break;
			case 'delete_page':
				$page = $this->input->post('page');
				$this->cms->delete_page($page);
			break;
			case 'reload':
				$id = $this->input->post('id');
				$content = $this->cms->get_content_by_id($id);
				
				if (!$content->in_all) $common = 'lcms-uncommon-handle';
				else $common = '';
				
				if ($content->published) $published = 'lcms-published-handle-on';
				else $published = '';
				
				echo "<li class=\"lcms-editable-object\" type=\"{$content->type}\" rel=\"{$content->id}\" published=\"{$content->published}\" common=\"{$content->in_all}\">";
				echo "<div class=\"lcms-content-controls\">
					<a class=\"lcms-drag-handle\"><span>Drag</span></a>
					<a class=\"lcms-edit-handle lcms-content-edit\"><span>Edit</span></a>
					<a class=\"lcms-versions-handle\"><span>Edit</span></a>
					<a class=\"lcms-delete-handle lcms-content-delete\"><span>Delete</span></a>
					<a class=\"lcms-common-handle {$common}\"><span>Common</span></a>
					<a class=\"lcms-published-handle {$published}\"><span>Published</span></a>
				</div>";

				$control = $content->type;
				echo $this->{$control}->html($content, true, true);
				echo '</li>';
			break;
			case 'get_content_data':
				$id = $this->input->post('id');
				$content = $this->cms->get_content_by_id($id);
				echo json_encode($content);
				break;
			case 'save_content':
			
				$control 	= $this->input->post('type');
				$content	= $this->input->post('content');
				$options	= $this->input->post('options');
				$class 		= $this->input->post('class');
				$location	= $this->input->post('location');
				$page 		= $this->input->post('page');
				
				if (method_exists($this->{$control}, 'presave')){
					$data = $this->{$control}->presave($content, $options);
				} else {
					$data->content = $content;
					$data->options = $options;
				}

				$id = $this->cms->save_content($control, $data->content, $data->options, $class, $location, $page, false);
				
				if (method_exists($this->{$control}, 'saved')){
					$data = $this->{$control}->saved($id, false); 
				} else {
					$data = 'null';
				}
				
				$content = $this->cms->get_content_by_id($id);
				$control = $content->type;
				
				if (!$content->in_all) $common = 'lcms-uncommon-handle';
				else $common = '';
				
				if ($content->published) $published = 'lcms-published-handle-on';
				else $published = '';
				
				$html = "<li class=\"lcms-editable-object\" type=\"{$content->type}\" rel=\"{$content->id}\" published=\"{$content->published}\" common=\"{$content->in_all}\">";
				$html .= "<div class=\"lcms-content-controls\">
					<a class=\"lcms-drag-handle\"><span>Drag</span></a>
					<a class=\"lcms-edit-handle lcms-content-edit\"><span>Edit</span></a>
					<a class=\"lcms-versions-handle\"><span>Edit</span></a>
					<a class=\"lcms-delete-handle lcms-content-delete\"><span>Delete</span></a>
					<a class=\"lcms-common-handle {$common}\"><span>Common</span></a>
					<a class=\"lcms-published-handle {$published}\"><span>Published</span></a>
				</div>";
				
				$html .= $this->{$control}->html($content, true);				
				$html .= "</li>";
				
				$result->id 		= $id;
				$result->data 		= $data;
				$result->content 	= $content;
				$result->options 	= $options;
				$result->class 		= $class;
				$result->type		= $control;
				$result->html		= $html;
				
				echo json_encode($result);
			break;
			case 'save_publish_content':
				
				$control 	= $this->input->post('type');
				$content	= $this->input->post('content');
				$options	= $this->input->post('options');
				$class 		= $this->input->post('class');
				$location	= $this->input->post('location');
				$page 		= $this->input->post('page');
				
				if (method_exists($this->{$control}, 'presave')){
					$data = $this->{$control}->presave($content, $options);
				} else {
					$data->content = $content;
					$data->options = $options;
				}

				$id = $this->cms->save_content($control, $data->content, $data->options, $class, $location, $page, true);
				
				if (method_exists($this->{$control}, 'saved')){
					$data = $this->{$control}->saved($id, false); 
				} else {
					$data = 'null';
				}
				
				$content = $this->cms->get_content_by_id($id);
				$control = $content->type;
				
				if (!$content->in_all) $common = 'lcms-uncommon-handle';
				else $common = '';
				
				if ($content->published) $published = 'lcms-published-handle-on';
				else $published = '';
				
				$html = "<li class=\"lcms-editable-object\" type=\"{$content->type}\" rel=\"{$content->id}\" published=\"{$content->published}\" common=\"{$content->in_all}\">";
				$html .= "<div class=\"lcms-content-controls\">
					<a class=\"lcms-drag-handle\"><span>Drag</span></a>
					<a class=\"lcms-edit-handle lcms-content-edit\"><span>Edit</span></a>
					<a class=\"lcms-versions-handle\"><span>Edit</span></a>
					<a class=\"lcms-delete-handle lcms-content-delete\"><span>Delete</span></a>
					<a class=\"lcms-common-handle {$common}\"><span>Common</span></a>
					<a class=\"lcms-published-handle {$published}\"><span>Published</span></a>
				</div>";
				
				$html .= $this->{$control}->html($content, true);				
				$html .= "</li>";

				
				$result->id 		= $id;
				$result->data 		= $data;
				$result->content 	= $content;
				$result->options 	= $options;
				$result->class 		= $class;
				$result->type		= $control;
				$result->html		= $html;
				
				echo json_encode($result);
			break;
			case 'update_content':
			
				$id			= $this->input->post('id');
				$control 	= $this->input->post('type');
				$content	= $this->input->post('content');
				$options	= $this->input->post('options');
				$class 		= $this->input->post('class');
				$location	= $this->input->post('location');
				$page 		= $this->input->post('page');
				
				
				if (method_exists($this->{$control}, 'presave')){
					$data = $this->{$control}->presave($content, $options);
				} else {
					$data->content = $content;
					$data->options = $options;
				}

				$this->cms->update_content($id, $control, $data->content, $data->options, $class, $location, $page, false);

				
				if (method_exists($this->{$control}, 'saved')){
					$data = $this->{$control}->updated($id, false); 
				} else {
					$data = 'null';
				}
				
				$content = $this->cms->get_content_by_id($id);
				$control = $content->type;
				
				if (!$content->in_all) $common = 'lcms-uncommon-handle';
				else $common = '';
				
				if ($content->published) $published = 'lcms-published-handle-on';
				else $published = '';
				
				$html = "<li class=\"lcms-editable-object\" type=\"{$content->type}\" rel=\"{$content->id}\" published=\"{$content->published}\" common=\"{$content->in_all}\">";
				$html .= "<div class=\"lcms-content-controls\">
					<a class=\"lcms-drag-handle\"><span>Drag</span></a>
					<a class=\"lcms-edit-handle lcms-content-edit\"><span>Edit</span></a>
					<a class=\"lcms-versions-handle\"><span>Edit</span></a>
					<a class=\"lcms-delete-handle lcms-content-delete\"><span>Delete</span></a>
					<a class=\"lcms-common-handle {$common}\"><span>Common</span></a>
					<a class=\"lcms-published-handle {$published}\"><span>Published</span></a>
				</div>";

				$html .= $this->{$control}->html($content, true);
				$html .= '</li>';
				
				
				$result->id 		= $id;
				$result->data 		= $data;
				$result->content 	= $content;
				$result->options 	= $options;
				$result->class 		= $class;
				$result->type		= $control;
				$result->html		= $html;
				
				echo json_encode($result);
				
			break;
			case 'update_publish_content':
			
				$id			= $this->input->post('id');
				$control 	= $this->input->post('type');
				$content	= $this->input->post('content');
				$options	= $this->input->post('options');
				$class 		= $this->input->post('class');
				$location	= $this->input->post('location');
				$page 		= $this->input->post('page');
				
				if (method_exists($this->{$control}, 'presave')){
					$data = $this->{$control}->presave($content, $options);
				} else {
					$data->content = $content;
					$data->options = $options;
				}

				$this->cms->update_content($id, $control, $data->content, $data->options, $class, $location, $page, true);
				
				
				
				if (method_exists($this->{$control}, 'saved')){
					$data = $this->{$control}->updated($id, false); 
				} else {
					$data = 'null';
				}
				
				$content = $this->cms->get_content_by_id($id);
				$control = $content->type;
				
				if (!$content->in_all) $common = 'lcms-uncommon-handle';
				else $common = '';
				
				if ($content->published) $published = 'lcms-published-handle-on';
				else $published = '';
				
				$html = "<li class=\"lcms-editable-object\" type=\"{$content->type}\" rel=\"{$content->id}\" published=\"{$content->published}\" common=\"{$content->in_all}\">";
				$html .= "<div class=\"lcms-content-controls\">
					<a class=\"lcms-drag-handle\"><span>Drag</span></a>
					<a class=\"lcms-edit-handle lcms-content-edit\"><span>Edit</span></a>
					<a class=\"lcms-versions-handle\"><span>Edit</span></a>
					<a class=\"lcms-delete-handle lcms-content-delete\"><span>Delete</span></a>
					<a class=\"lcms-common-handle {$common}\"><span>Common</span></a>
					<a class=\"lcms-published-handle {$published}\"><span>Published</span></a>
				</div>";

				$html .= $this->{$control}->html($content, true);
				$html .= '</li>';
				
				
				$result->id 		= $id;
				$result->data 		= $data;
				$result->content 	= $content;
				$result->options 	= $options;
				$result->class 		= $class;
				$result->type		= $control;
				$result->html		= $html;
				
				echo json_encode($result);
			break;
			case 'published':
				$id 		= $this->input->post('id');
				$published 	= $this->input->post('published');
				echo $id . $published;
				$this->cms->published($id, $published);
			break;
			case 'common':
				$id 	= $this->input->post('id');
				$common = $this->input->post('common');
				echo $id . $common;
				$this->cms->common($id, $common);			
			break;
			case 'control':
				$this->load->model('modules/'.$control.'/'.$control);
				$this->{$control}->{$action}($id);
			break;
			case 'content_revisions':
				$id = $this->input->post('id');
				$data['revisions'] = $this->cms->get_content_revisions($id);

				$this->load->view('interface/revisions-manager', $data);
				
			break;
			case 'get_content_by_id':
				$id = $this->input->post('id');
				$content = $this->cms->get_content_by_id($id);
				$control = $content->type;
				
				if (!$content->in_all) $common = 'lcms-uncommon-handle';
				else $common = '';
				
				if ($content->published) $published = 'lcms-published-handle-on';
				else $published = '';
				

				$html = "<li class=\"lcms-editable-object\" type=\"{$content->type}\" rel=\"{$content->id}\" published=\"{$content->published}\" common=\"{$content->in_all}\">";
				$html .= "<div class=\"lcms-content-controls\">
					<a class=\"lcms-drag-handle\"><span>Drag</span></a>
					<a class=\"lcms-edit-handle lcms-content-edit\"><span>Edit</span></a>
					<a class=\"lcms-versions-handle\"><span>Edit</span></a>
					<a class=\"lcms-delete-handle lcms-content-delete\"><span>Delete</span></a>
					<a class=\"lcms-common-handle {$common}\"><span>Common</span></a>
					<a class=\"lcms-published-handle {$published}\"><span>Published</span></a>
				</div>";

				$html .= $this->{$control}->html($content, true);
				$html .= '</li>';
				echo $html;
				
			break;
			case 'commit_revision':
				$current	= $this->input->post('current');
				$published 	= $this->input->post('published');
				
				$this->cms->commit_revision($current, $published);
				echo $current . ' ' . $published;
			break;
				
		}
	}
}

