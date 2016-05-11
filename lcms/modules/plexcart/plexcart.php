<?php

class Plexcart extends Model {

	var $FB_ENABLED			= false;
	var $FB_APP_ID			= '';
	var $FB_APP_SECRET		= '';
	
	function plexcart(){
		parent::Model();
		
		session_start();
		
		$fb_login = $this->request('uses_fb_login');
		if ($fb_login->enabled){
			$this->FB_ENABLED		= true;
			$this->FB_APP_ID		= $fb_login->fb_app_id;
			$this->FB_APP_SECRET	= $fb_login->fb_app_secret;
		}


		$this->name				= 'PlexCart';
		$this->namespace		= 'plexcart';

		$this->css_namespace	= 'lcms-plexcart';


		$this->js_includes		= 'lcms.plexcart.js';
		$this->css_includes 	= 'lcms.plexcart.css';

		if ($fb_login->enabled){
			$this->js_resources		= array('plexcart.js','http://connect.facebook.net/en_US/all.js');
		} else {
			$this->js_resources 	= 'plexcart.js';
		}
		
		$this->css_resources	= 'plexcart.css';
		


	}
	
	function Hooks(){

			
		$query = $this->db->query("SELECT * FROM `contents` WHERE `type` = 'plexcart'");
		$content = $query->row();
		$_SESSION['options'] 	= base64_encode($content->options);
		
		$current_page = base_url() . 'p/' . $content->page . '/';
		
		
		if (!$_SESSION['warehouse']){
			$order_by_warehouse = $this->request('order_by_warehouse');	
			if (!$order_by_warehouse->enabled){
			   	$_SESSION['warehouse'] = $order_by_warehouse->warehouse_id;
			}
		}
		    	

		switch ($_SESSION['param1']){
				
				
			case "logout":
				$_SESSION['session']	 	= null;
				$_SESSION['ip'] 			= null;
				$_SESSION['plexcart_items']	= null;
				redirect($current_page);
			
				break;
				
				
			case "update_cart":
			
				$this->update_cart();
				
				redirect($current_page . 'cart');
				
				break;
				
			case "login_do":
			
				$username = $this->input->post('username');
				$password = $this->input->post('password');
				
				$res = $this->request('auth_customer', array('username'=>$username,'password'=>md5($password)));
				
				
				if ($res->data == 'incorrect'){
					redirect($current_page . 'login/incorrect');

				} else {
					
					$_SESSION['session'] 	= base64_encode($res->data);
					$_SESSION['ip']		 	= $_SERVER['REMOTE_ADDR'];
					
					
					if ($_SESSION['param2'] == 'cart'){
						
						redirect($current_page . 'cart');
						
					} else {
						
						redirect($current_page);
						
					}
		    
				}
				break;
			
			case "register_do":
			


				if ($this->input->post('email') != $this->input->post('retype_email')){

					redirect($current_page . 'register/email_err');
				}
				
				if ($this->input->post('password') != $this->input->post('retype_password')){

					redirect($current_page . 'register/pass_err');
				}
				
				if (($this->input->post('ic') != $this->input->post('retype_ic')) && ($this->input->post('country') == 'Malaysia')){

					redirect($current_page . 'register/ic_err');
				}

				// Validate IC and Email existence in dB [zadx]	
				$validate_res = $this->request('verify_contacts_by_field',array(
				    'email'			=> $this->input->post('email'),
				    'ic'			=> $this->input->post('ic')
				));
				
				if ($validate_res->data == 'ic_false'){
				    redirect($current_page . 'register/ic_false');
				} else if ($validate_res->data == 'email_false') {
					redirect($current_page . 'register/email_false');
				}	
				
				// If user specify Alternative State (state_alt), it takes precedence [zadx]
				$state_alt = $this->input->post('state_alt');
				if($state_alt != ""){
					$state			=	$state_alt;
				} else {
					$state			=	$this->input->post('state');
				}


				$res = $this->request('register_customer',array(
					'link'			=> $current_page,
				    'email'			=> $this->input->post('email'),
				    'ic'			=> $this->input->post('ic'),
				    'introducer_ic'	=> $this->input->post('introducer_ic'),				    				    
				    'name'			=> $this->input->post('name'),
				    'mobile'		=> $this->input->post('mobile'),
				    'address1'		=> $this->input->post('address1'),
				    'address2'		=> $this->input->post('address2'),
				    'city'			=> $this->input->post('city'),
				    'state'			=> $state,
				    'zipcode'		=> $this->input->post('zipcode'),
				    'country'		=> $this->input->post('country'),
				    'password'		=> md5($this->input->post('password'))
				));
				
				if ($res->data == 'ok'){
				    redirect($current_page . 'register_ok');
				} else {
					redirect($current_page . 'register');
				}
				
				break;
				
			case "checkout":
				if ($this->input->post('gateway') && $this->input->post('shipping') && $this->input->post('delivery')){
					$_SESSION['gateway']	= $this->input->post('gateway');
					$_SESSION['shipping']	= $this->input->post('shipping');
					$_SESSION['delivery']	= $this->input->post('delivery');
				}
				redirect($current_page . 'checkout_confirm');
				
				break;

				
				
			case "checkout_do":

				if ($_SESSION['session'] == null) return;
			
				$delivery	= $_SESSION['delivery'];
				$gateway_id	= $_SESSION['gateway'];
				$shipping	= $_SESSION['shipping'];
				$items 		= $_SESSION['plexcart_items'];
							
				$shipping	= $this->request('get_shipping_service_by_id', array('id'=>$shipping));
				$gateway	= $this->request('get_payment_gateway_by_id', array('id'=>$gateway_id));
				$user 		= $this->request('get_customer_information', array('session'=> base64_decode($_SESSION['session']),'fb' => $_SESSION['fb_logged_in']));
				
				$res = $this->request('get_delivery_charges', array('warehouse_id'=>$_SESSION['warehouse'],'zipcode'=>$delivery['zipcode'], 'country'=>$delivery['country'], 'service'=>$shipping, 'items'=>json_encode($items)));
				$delivery_charges = $res;
				
				$order = $this->request('checkout',array(
					'items'				=> json_encode($items),
					'user'				=> json_encode($user),
					'gateway'			=> json_encode($gateway),
					'shipping'			=> json_encode($shipping),
					'delivery'			=> json_encode($delivery),
					'delivery_charges'	=> json_encode($delivery_charges),
					'warehouse_id'		=> $_SESSION['warehouse']
				));
				
				$order_id = $order->id;
				
				foreach ($items as $item){
					$subtotal = $item['qty'] * $item['price'];
					$total_qty += $item['qty'];
					$total += $subtotal;
				}
				
				$amount = $delivery_charges->total_rate + $total;
				

				eval($gateway->initiate_script);				

				break;
				
			case "callback_do":
				$gateway_id = $_SESSION['param2'];
				$gateway	= $this->request('get_payment_gateway_by_id', array('id'=>$gateway_id));
				
				eval($gateway->callback_script);
				
				$order = $this->request('complete', array(
					'time'				=> time(),
					'secret'			=> 'plexcart!@#$',
					'id'				=> $order_id,
					'payment_status'	=> $payment_status,
					'status'			=> $status,
					'remarks'			=> $remarks,
					'txn_reference'		=> $txn_reference,
					'txn_date'			=> $txn_date
				));
				
				if ($ok){
					redirect($current_page . 'checkout_successful');
				} else {
					redirect($current_page . 'checkout_failed');
				}
				
				break;
				
			case "password_check_do":
				
				$res = $this->request('request_password_reset',array(
					'email'		=> $this->input->post('email'),
				    'mobile'	=> $this->input->post('mobile')
				));
				
				if ($res->data == 'ok'){
				    redirect($current_page . 'forgot_password/ok');
				} else {
					redirect($current_page . 'forgot_password/mismatch');
				}

				break;		
				
		}

	}
	
	
	function PreHTML(){
		$prehtml = $this->load->view('modules/plexcart/form-new', $data, true);
		$prehtml .= $this->load->view('modules/plexcart/form-edit', $data, true);
		return $prehtml;
	}
	
	
	function HTML($content, $author_mode){

		$data['content'] = $content;

		$data['options'] 		= $this->options = $options = json_decode($content->options);
		$data['current_page'] 	= $current_page = base_url() .  'p/' . $content->page . '/';
		$data['assets_url']		= $this->options->cdn_url;
		$data['cart_items'] 	= $_SESSION['plexcart_items'];
		
		$data['FB_APP_ID']		= $this->FB_APP_ID;		

		
		switch ($_SESSION['param1']){
		
			case "test_mail":
			
				$res = $this->request('test_mail');
				
				print_r($res);
				
				break;
					
			case "account":

				$data['user'] 		= $this->request('get_customer_information', array('session'=> base64_decode($_SESSION['session']),'fb' => $_SESSION['fb_logged_in']));
				$results 			= $this->load->view('modules/plexcart/interface/account', $data, true);
							
				break;
				
			case "account_update_do":
			
				$data['user'] = $user = $this->request('get_customer_information', array('session'=> base64_decode($_SESSION['session']),'fb' => $_SESSION['fb_logged_in']));
				
				if ($this->input->post('submit')){
					if (trim($this->input->post('password'))){
						if (strlen(trim($this->input->post('password'))) >= 6){					
							if ($this->input->post('password') == $this->input->post('retype_password')){

								if (strtoupper($user->password) == strtoupper(md5($this->input->post('old_password')))){
										$password = trim($this->input->post('password'));
								} else {
									$data['status']	= 'error3';
								}
							} else {
								$data['status']	= 'error2';
							}					
						} else {
							$data['status']	= 'error1';
						}
					}
					
					$userdata = array(
						'company'			=> trim($this->input->post('company')),
						'name'				=> trim($this->input->post('name')),
						'mobile'			=> trim($this->input->post('mobile')),
						'address1'			=> trim($this->input->post('address1')),
						'address2'			=> trim($this->input->post('address2')),
						'city'				=> trim($this->input->post('city')),
						'state'				=> trim($this->input->post('state')),
						'zipcode'			=> trim($this->input->post('zipcode')),
						'country'			=> trim($this->input->post('country')),
						'password'			=> $password,
						'id'				=> $user->id
					);
					
					$ok = true;
					
					if (!$userdata['name']) $ok = false;
					if (!$userdata['address1']) $ok = false;
					if (!$userdata['city']) $ok = false;
					if (!$userdata['mobile']) $ok = false;
					if (!$userdata['zipcode']) $ok = false;
					if (!$userdata['country']) $ok = false;
					
					if (!$data['status']){
						if (!$ok){
							$data['status'] = 'error';
						} else {			
							$this->request('update_account', $userdata);
							$data['status'] = 'ok';
						}
					}
				}
				// Retrieve back updated information [zadx]
				$data['user'] = $user = $this->request('get_customer_information', array('session'=> base64_decode($_SESSION['session']),'fb' => $_SESSION['fb_logged_in']));
				
				$results = $this->load->view('modules/plexcart/interface/account', $data, true);
				
				break;		
				
			case "login":
				$data['incorrect'] = $_SESSION['param2'];

				$results = $this->load->view('modules/plexcart/interface/login', $data, true);
			
				break;
			
			case "forgot_password":
			
				$data['result'] = $_SESSION['param2'];
				
				$results = $this->load->view('modules/plexcart/interface/password', $data, true);
				
				break;
					
			case "register":
			
				$data['error'] = $_SESSION['param2'];
				$tnc_data = $this->request('member_tnc');
				$data['tnc'] = $tnc_data->tnc;
				
				$results = $this->load->view('modules/plexcart/interface/register', $data, true);
				
				break;
			
			case "register_test":
			
				$data['error'] = $_SESSION['param2'];
				$tnc_data = $this->request('member_tnc');
				$data['tnc'] = $tnc_data->tnc;
				
				$results = $this->load->view('modules/plexcart/interface/register_test', $data, true);
				
				break;	
				
			case "register_ok":

				$results = $this->load->view('modules/plexcart/interface/register_ok', $data, true);
			
				break;
				
				
			case "history":

				$user 	= $this->request('get_customer_information', array('session'=> base64_decode($_SESSION['session']),'fb' => $_SESSION['fb_logged_in']));
				$curr 	= $this->request('get_currency');
				
				$data['orders'] 		= $this->request('get_customer_history', array('id'=>$user->id));
				$data['currency']		= $curr->currency;
			
				$results = $this->load->view('modules/plexcart/interface/history', $data, true);
			
				break;
				
			case "order":
				
				$id		= $_SESSION['param2'];
				$curr	= $this->request('get_currency');
				
				$data['user']			= $user = $this->request('get_customer_information', array('session'=> base64_decode($_SESSION['session']),'fb' => $_SESSION['fb_logged_in']));
				$data['order']			= $order = $this->request('get_order_by_id', array('id'=>$id, 'user_id'=>$user->id));
				$data['warehouse']		= $this->request('get_warehouse_by_id', array('id'=>$order->warehouse_id));
				$data['currency']		= $curr->currency;
				
				$breadcrumb = "<h3><a href=\"{$current_page}/history\">My Orders</a> &raquo; Order #{$order->id}</h3>";
				
				$results = $this->load->view('modules/plexcart/interface/order', $data, true);
				
				break;
			
			
			case "cart":
			
				$data['pgs']			= $this->request('get_payment_gateways');
				$data['shippings']		= $this->request('get_shipping_services');
				$data['user'] 			= $user = $this->request('get_customer_information', array('session'=> base64_decode($_SESSION['session']),'fb' => $_SESSION['fb_logged_in']));
				$data['online_rule']	= $this->request('get_online_min_purchase');
				$results = $this->load->view('modules/plexcart/interface/cart', $data, true);

			
				break;
				
			case "checkout_confirm":
				
				$data['gateway']		= $gateway = $_SESSION['gateway'];
				$data['shipping'] 		= $shipping = $_SESSION['shipping'];
				$data['delivery'] 		= $delivery = $_SESSION['delivery'];
				
				$items 					= $_SESSION['plexcart_items'];				
				$res 					= $this->request('get_delivery_charges', array('warehouse_id'=>$_SESSION['warehouse'], 'zipcode'=>$delivery['zipcode'], 'country'=>$delivery['country'], 'service'=>$shipping, 'items'=>json_encode($items)));
				
				$data['delivery'] 		= $res;
				$data['shipping']		= $this->request('get_shipping_service_by_id', array('id'=>$shipping));
				$data['gateway']		= $this->request('get_payment_gateway_by_id', array('id'=>$gateway));				
				$data['user']			= $this->request('get_customer_information', array('session'=> base64_decode($_SESSION['session']),'fb' => $_SESSION['fb_logged_in']));
				$data['items']			= $items;
				
				$data['warehouse']		= $this->request('get_warehouse_by_id', array('id'=>$_SESSION['warehouse']));
				
				$data['online_rule']	= $this->request('get_online_min_purchase');

				$results = $this->load->view('modules/plexcart/interface/checkout', $data, true);
				
			
				break;
				
			case "checkout_successful":
				
				$_SESSION['plexcart_items'] = '';
				
				$results = $this->load->view('modules/plexcart/interface/checkout_successful', $data, true);
				
				break;
				
			case "checkout_failed":

				$results = $this->load->view('modules/plexcart/interface/checkout_failed', $data, true);			
				
				break;
				
			case "page":
				$data['page'] = $page		= $_SESSION['param2'];
				
				
		    	$result = $this->request('get_items', array('page'=>$page,'warehouse'=>$_SESSION['warehouse']));
		    	
		    	$data['items']				= $result->items;
		    	$data['limit']				= $result->limit;
		    	$data['total']				= $result->total;
		    	
		    	$results = $this->load->view('modules/plexcart/interface/items', $data, true);
		    	
				break;

					
		    case "search":
				$data['page'] = $page		= $_SESSION['param3'] ? $_SESSION['param3'] : 1;
				
		    	$data['is_search_page']		= true;
		    	if ($_SESSION['plexcart_search_term']){
		    		if ($this->input->post('q')){
			       		$data['search_term']	= $search_term = $this->input->post('q');
		    		} else {
			    		$data['search_term']	= $search_term = $_SESSION['plexcart_search_term'];
			    	}
		    	} else {
		    		$data['search_term']		= $search_term = $this->input->post('q');
		    	}
		    	
		    	
		    	if ($search_term){
			    	$_SESSION['plexcart_search_term'] = $search_term;
		    	}
		    	
		    	$result		 				= $this->request('get_items',array('term'=>$search_term,'page'=>$page,'warehouse'=>$_SESSION['warehouse']));
		    	
		    	$data['items']				= $result->items;
		    	$data['limit']				= $result->limit;
		    	$data['total']				= $result->total;

		    	
		    	$results = $this->load->view('modules/plexcart/interface/items', $data, true);
		    	
		    	break;
		    	
		    case "tag":
		    
				$data['page'] = $page		= $_SESSION['param2'];
		    
		    	$data['tag']				= $tag = ucwords(reverse_clean_url($_SESSION['param2']));
		    	
		    	$result		 				= $this->request('get_items',array('tag'=>$tag,'page'=>$page,'warehouse'=>$_SESSION['warehouse']));
		    	
		       	$data['items']				= $result->items;
		    	$data['limit']				= $result->limit;
		    	$data['total']				= $result->total;

		    	$data['is_tag_page']		= true;
		    	$data['user'] 				= $user = $this->request('get_customer_information', array('session'=> base64_decode($_SESSION['session']),'fb' => $_SESSION['fb_logged_in']));
		    					    	
		    	
		    	$results = $this->load->view('modules/plexcart/interface/items', $data, true);
		    	
		    	break;
		    	
		    case "category":
		    	$data['cat'] 				= $cat = ucwords(reverse_clean_url($category_page = $_SESSION['param2']));
		    	if ($_SESSION['param3'] == 'page'){
			    	$data['page'] = $page	= $_SESSION['param4'];
		    	} else {
		    		$data['subcat']			= $subcat = ucwords(reverse_clean_url($subcategory_page = $_SESSION['param3']));
			    	$data['page'] = $page	= $_SESSION['param5'];
		    	}
		    	$data['category_page']		= $current_page . 'category/' . $category_page;
		    	$data['subcategory_page']	= $current_page . 'category/' . $category_page . '/' . $subcategory_page;		    	
		    	$data['subcategories'] 		= $this->request('get_subcategories', array('cat'=>$cat));

		    	$data['user'] 				= $user = $this->request('get_customer_information', array('session'=> base64_decode($_SESSION['session']),'fb' => $_SESSION['fb_logged_in']));
		    	
				
		    	$result		 				= $this->request('get_items',array('cat'=>$cat,'subcat'=>$subcat,'warehouse'=>$_SESSION['warehouse'],'page'=>$page));
		       	$data['items']				= $result->items;
		    	$data['limit']				= $result->limit;
		    	$data['total']				= $result->total;

		    	
		    	if ($subcat){
		    		$data['is_subcategory_page'] = true;
		    		$data['is_category_page'] = false;
		    	} else {
		    		$data['is_subcategory_page'] = false;
			    	$data['is_category_page'] = true;
		    	}
		    	
		    	$results = $this->load->view('modules/plexcart/interface/items', $data, true);
		    	
		    	break;
		    	
		    	
		    case 'verify':
				$id		= $_SESSION['param2'];
				$code	= $_SESSION['param3'];
				
				
				$res = $this->request('verify_customer', array('id'=>$id,'code'=>$code));
				
				$data['res'] = $res->data;
				
				$results = $this->load->view('modules/plexcart/interface/verify', $data, true);
				
		    	break;

		    	
		    case "item":
		    
		    	$id 						= $_SESSION['param2'];		    	
		    	$data['item'] 				= $item = $this->request('get_item_by_id',array('id'=>$id,'warehouse'=>$_SESSION['warehouse']));
		    	$data['category_page']		= $current_page . 'category/' . clean_url($item->category);
		    	$data['subcategory_page']	= $current_page . 'category/' . clean_url($item->category) . '/' . clean_url($item->subcategory);
		    	$data['cat'] 				= $item->category;
		    	$data['subcat']				= $item->subcategory;
				$data['user'] 				= $user = $this->request('get_customer_information', array('session'=> base64_decode($_SESSION['session']),'fb' => $_SESSION['fb_logged_in']));

		    	
		    	$results = $this->load->view('modules/plexcart/interface/item', $data, true);
		    	
		    	break;
		    	
		    	
		    case 'change_store':
		    	$_SESSION['warehouse'] = null;
			    $_SESSION['plexcart_items'] = null;
			    
		    	$data['warehouses'] = $this->request('get_warehouses');
		    	$results = $this->load->view('modules/plexcart/interface/warehouses', $data, true);
		    	break;
		    	
		    	
		    	
		    case 'warehouse':
		    	
		    	$id = $_SESSION['param2'];
		    	$_SESSION['warehouse'] = $id;
		    	
		    default: 
		    	$order_by_warehouse = $this->request('order_by_warehouse');

		    	if ($order_by_warehouse->enabled){

			    	if ($_SESSION['warehouse']){

				    	$result = $this->request('get_items', array('warehouse' => $_SESSION['warehouse']));   
				    	
				    	$data['items'] = $result->items;
				    	$data['limit'] = $result->limit;
				    	$data['total'] = $result->total;
				    	
				    	$data['user'] = $user = $this->request('get_customer_information', array('session'=> base64_decode($_SESSION['session']),'fb' => $_SESSION['fb_logged_in']));
				    	$results = $this->load->view('modules/plexcart/interface/items', $data, true);

			    	
			    	} else {
				    	

				    	$data['warehouses'] = $this->request('get_warehouses');
				    	$results = $this->load->view('modules/plexcart/interface/warehouses', $data, true);
				    	
			    	}
			    	
		    	} else {
		    	
				    	$_SESSION['warehouse'] = $order_by_warehouse->warehouse_id;
				    	$result = $this->request('get_items', array('warehouse'=>$_SESSION['warehouse']));

				    	$data['items'] = $result->items;
				    	$data['limit'] = $result->limit;
				    	$data['total'] = $result->total;

				    	$data['user'] = $user = $this->request('get_customer_information', array('session'=> base64_decode($_SESSION['session']),'fb' => $_SESSION['fb_logged_in']));
				    	$results = $this->load->view('modules/plexcart/interface/items', $data, true);


		    	}
		    
		    	break;
		}
		
		$searchbox = $this->load->view('modules/plexcart/interface/searchbox', $data, true);
		
		
		$output .= "<script type=\"text/javascript\">
					var lcms_plexcart_ucc = '{$options->ucc}';
					var lcms_plexcart_ccc = '{$options->ccc}';
				</script>
				<div class=\"lcms-plexcart-searchbox\">{$searchbox}</div>
				<div class=\"lcms-plexcart-data\"></div>
				<div class=\"lcms-plexcart-container\">
					{$results}
				</div>";
		
		return $output;
		
		
	}
	
	function Presave($content, $options){		
		$data->content = $content;
		$data->options = $options;
		return $data;
	}
	
	function Preupdate($id, $content, $options){
		$data->content = $content;
		$data->options = $options;
		return $data;		
	}
	
	function Saved($id, $published){
		
	}
	
	function Updated($id, $published){
		
	}
	
	
	
	function Add_to_cart(){
		$id = $this->input->post('id');
		$qty = $this->input->post('qty') ? $this->input->post('qty') : 1;
		
		$item = $this->request('get_item_by_id', array('id'=>$id));
		$user = $this->request('get_customer_information', array('session'=> base64_decode($_SESSION['session']),'fb' => $_SESSION['fb_logged_in']));
		
		$items = $_SESSION['plexcart_items'];
		
		if ($items[$id]){
			$items[$id]['qty'] = $qty;
		} else {
		
			// Get price based on ranking [Azad]
			if (is_object($user)){
				//print_r($user);
				if ($user->rank){
					$item->price = $item->{'price'.$item->rules->{str_replace(' ','_',$user->rank)}};
				}
			}	
			
			
			$cart_item['name'] = $item->name;
			$cart_item['price'] = $item->discount_price ? $item->discount_price : $item->price;
			$cart_item['actual_price'] = $item->price;
			$cart_item['img'] = $item->primary_image;
			$cart_item['qty'] = $qty;
			$cart_item['id'] = $id;
			$cart_item['min'] = $item->min_cart_qty;
			$cart_item['max'] = $item->max_cart_qty;
			
			$items[$id] = $cart_item;
		}
		
		$_SESSION['plexcart_items'] = $items;

	}
	
	function Remove_from_cart(){
		$id = $this->input->post('id');
		$items = $_SESSION['plexcart_items'];
		
		foreach ($items as $item_id => $item){
			if ($id == $item['id']){
			
			} else {
				$new_items[$item_id] = $item;
			}
		}
		$_SESSION['plexcart_items'] = $new_items;
	}
	
	function Cart_data(){
	
		session_start();
	
		$items = $_SESSION['plexcart_items'];
		
		$total_qty = 0;
		$total = 0;
		$user = $this->request('get_customer_information', array('session'=> base64_decode($_SESSION['session']),'fb' => $_SESSION['fb_logged_in']));

		$data['user'] = $user;
		
		foreach ($items as $item){
			$subtotal = $item['qty'] * $item['price'];
			$total_qty += $item['qty'];
			$total += $subtotal;
		}
		
		
		$data['warehouse'] = $this->request('get_warehouse_by_id', array('id'=> $_SESSION['warehouse']));
		$data['items'] = $items;
		$data['total'] = number_format($total,2);
		$data['qty'] = $total_qty;

		
		$query = $this->db->query("SELECT * FROM `contents` WHERE `type` = 'plexcart' AND `published` = 1");
		
		if (!$query->num_rows()){
			echo 'false';	
			return;	
		} 
		
		$content = $query->row();
		
		
		$data['current_page'] = base_url() . 'p/' . $content->page . '/';
		
		
		$data['options'] = json_decode($content->options);
		$order_by_warehouse = $this->request('order_by_warehouse');

		$data['warehouse_enabled']	= $order_by_warehouse->enabled;
		$data['categories']		 	= $this->request('get_categories');
		
		$data['fb_enabled']			= $this->FB_ENABLED;
		$data['fb_app_id']			= $this->FB_APP_ID;
		
		
		$curr 	= $this->request('get_currency');		
		$data['currency'] = $curr->currency;
		
		echo $this->load->view('modules/plexcart/interface/cart_info', $data, true);
		
	}
	
	function Update_cart(){
		$current_items = $_SESSION['plexcart_items'];
		$input_item = $this->input->post('item');
				
		foreach ($current_items as $id => $item){
			$item['qty'] = $input_item[$id];
			$items[$id] = $item;
		}
		
		
		$_SESSION['plexcart_items'] = $items;
	}
	
	function FB_login(){
		$user = $this->FB_user();
		
		$res = $this->request('register_fb_user',array('userdata'=>json_encode($user)));
		
		if ($user){
			
			$_SESSION['session'] 		= base64_encode($res->data);
			$_SESSION['ip']		 		= $_SERVER['REMOTE_ADDR'];
			$_SESSION['fb_logged_in']	= true;
			
			$obj->status = 'ok';
			$obj->user = $user;
			echo json_encode($obj);
		} else {
			$obj->status = 'false';
			$obj->user = $user;
			echo json_encode($obj);
		}
	}
	
	
	function FB_user(){
			echo $this->FB_APP_ID;
			echo ' S:' . $this->FB_APP_SECRET;

			$cookie = $this->get_facebook_cookie($this->FB_APP_ID, $this->FB_APP_SECRET);
			$this->fb_user = json_decode(file_get_contents('https://graph.facebook.com/me?access_token='.$cookie['access_token']));
			$this->fb_request = true;

			return $this->fb_user;
    }
	
	
	function get_facebook_cookie($app_id, $app_secret) {
	    if ($_COOKIE['fbsr_' . $app_id] != '') {
	        return $this->get_new_facebook_cookie($app_id, $app_secret);
	    } else {
	        return $this->get_old_facebook_cookie($app_id, $app_secret);
	    }
	}
	
	function get_old_facebook_cookie($app_id, $app_secret) {
	    $args = array();
	    parse_str(trim($_COOKIE['fbs_' . $app_id], '\\"'), $args);
	    ksort($args);
	    $payload = '';
	    foreach ($args as $key => $value) {
	        if ($key != 'sig') {
	            $payload .= $key . '=' . $value;
	        }
	    }
	    if (md5($payload . $app_secret) != $args['sig']) {
	        return array();
	    }
	    return $args;   
	}
	
	function get_new_facebook_cookie($app_id, $app_secret) {
		$signed_request = $this->parse_signed_request($_COOKIE['fbsr_' . $app_id], $app_secret);
		// $signed_request should now have most of the old elements
		$signed_request[uid] = $signed_request[user_id]; // for compatibility 
		if (!is_null($signed_request)) {
			// the cookie is valid/signed correctly
			// lets change "code" into an "access_token"
			$access_token_response = file_get_contents("https://graph.facebook.com/oauth/access_token?client_id=$app_id&redirect_uri=&client_secret=$app_secret&code=$signed_request[code]");
			parse_str($access_token_response);
			$signed_request[access_token] = $access_token;
			$signed_request[expires] = time() + $expires;
		}
		return $signed_request;
	}
	
	function parse_signed_request($signed_request, $secret) {
		list($encoded_sig, $payload) = explode('.', $signed_request, 2); 
	
		// decode the data
		$sig = $this->base64_url_decode($encoded_sig);
		$data = json_decode($this->base64_url_decode($payload), true);
	
		if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
			error_log('Unknown algorithm. Expected HMAC-SHA256');
			return null;
		}
	
		// check sig
		$expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
		if ($sig !== $expected_sig) {
			error_log('Bad Signed JSON signature!');
			return null;
		}

		return $data;
	}
	
	function base64_url_decode($input) {
		return base64_decode(strtr($input, '-_', '+/'));
	}

	
	
	function Request($request, $data){
	
		$option = json_decode(base64_decode($_SESSION['options']));
		
		$data['api_key'] = $option->api_key;
		
		$postdata = http_build_query($data);
	
	
		$opts = array('http' =>
	  	    array(
	  	        'method'  => 'POST',
	  	        'header'  => 'Content-type: application/x-www-form-urlencoded',
	  	        'content' => $postdata
	  	    )
	  	);

	  	$context  = stream_context_create($opts);					
	  	
    	
	  	$data = json_decode($res = file_get_contents($option->api_url.'/'.$request.'/json','',$context));
	  	


	  	if ($data->status == 'Failed'){
		  	echo 'Request Error: ' . $data->reason;
	  	} else {
		  	return $data;
	  	}
	}
	
}