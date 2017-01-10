<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library('form_validation');
		$this->load->model('user_model');
		$this->load->library('template');
		$this->load->library('backblaze');
		$this->load->helper('string');
		
		// Controller Specific CSS and Scripts
		$this->css = array('login');
		$this->script = array('login');
		$this->data = array();

		$this->load->library('stripe');
		$this->load->library('email');
	}

	public function index() {
		if ($this->user_model->is_logged_in()) {
			redirect('/');
		} else {
			redirect('user/login');
		}
	}

	public function login()
	{
		$this->form_validation->set_rules('email', 'Email', 'required');
		
		$this->form_validation->set_rules('password', 'Password', 'required');
		

		if ($this->form_validation->run() == true) {

			if ($this->user_model->login($this->input->post('email'), $this->input->post('password'))) {
				$this->session->set_flashdata('message', "Successfully logged in.");
				redirect('/', 'refresh');
			} else {
				$this->session->set_flashdata('message', "Invalid Email/Password.");
				redirect('user/login', 'refresh');
			}

		} else {

			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['email'] = array(
				'class' => 'form-control',
				'placeholder' => 'Your email',
				'name' => 'email',
				'id' => 'email',
				'type' => 'email',
				'value' => $this->form_validation->set_value('email'),
			);

			$this->data['password'] = array(
				'class' => 'form-control',
				'placeholder' => 'Password',
				'name' => 'password',
				'id' => 'password',
				'type' => 'password',
			);
			$this->template->load('main', 'user/login', $this->css, $this->script, $this->data);
		}
	}


	public function register()
	{
		$this->form_validation->set_rules('first_name', 'First Name', 'required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required');
		$this->form_validation->set_rules('agree', 'Agree Terms', 'required', array('required' => 'You should agree to Terms of Service'));
		$this->form_validation->set_rules('email', 'Email', 'trim|required|is_unique[tbl_users.email]', 
			array(
				'is_unique' => 'This %s already exists.'
			)
		);

		$this->form_validation->set_rules('password', 'Password', 'trim|min_length[8]|required');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');

		if ($this->form_validation->run() == true) {
			if ($this->user_model->register($this->input->post('first_name'), $this->input->post('first_name'), $this->input->post('email'), $this->input->post('password'))) {


				$message = $this->load->view('mails/registeration', array(), true);

				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

				// More headers
				$headers .= 'From: <webmaster@example.com>' . "\r\n";
				$subject = "Videre - Thanks for regsiteration";

				$to = $this->input->post('email');
				$flag = mail($to,$subject,$message,$headers);
				
				redirect('user/login', 'refresh');

			} else {
				$this->session->set_flashdata('message', "Email already taken.");
				redirect('user/register', 'refresh');
			}
		} else {

			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['first_name'] = array(
				'class' => 'form-control',
				'placeholder' => 'First Name',
				'name' => 'first_name',
				'id' => 'first_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('first_name'),
			);

			$this->data['last_name'] = array(
				'class' => 'form-control',
				'placeholder' => 'Last Name',
				'name' => 'last_name',
				'id' => 'last_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('last_name'),
			);

			$this->data['email'] = array(
				'class' => 'form-control',
				'placeholder' => 'Your email',
				'name' => 'email',
				'id' => 'email',
				'type' => 'email',
				'value' => $this->form_validation->set_value('email'),
			);

			$this->data['password'] = array(
				'class' => 'form-control',
				'placeholder' => 'Password',
				'name' => 'password',
				'id' => 'password',
				'type' => 'password',
				'value' => $this->form_validation->set_value('password'),
			);
			$this->data['confirm_password'] = array(
				'class' => 'form-control',
				'placeholder' => 'Confirm Password',
				'name' => 'confirm_password',
				'id' => 'confirm_password',
				'type' => 'password',
			);
			$this->template->load('main', 'user/register', $this->css, $this->script, $this->data);
		}
	}

	public function upgrade_membership() {

		if (!$this->user_model->is_logged_in()) {
			redirect('user/login', 'refresh');
		} else {
			$this->data['expired'] = $this->user_model->is_expired($this->session->userdata('email'));
			$this->css = array('select_plan');
			$this->script = array('select_plan');
			$this->template->load('main', 'home/select_plan', $this->css, $this->script, $this->data);
		}
	}

	public function get_sharable_users() {
		return json_encode($this->user_model->get_sharable_users());
	}

	public function setting() {
		if (!$this->user_model->is_logged_in()) {
			redirect('user/login', 'refresh');
		} else {
			$this->css = array('setting');
			$this->script = array('setting');
			$this->data = array();
			$this->data['active_tab'] = 1;
			$this->prepare_for_user_management();

			$this->template->load('main', 'user/setting', $this->css, $this->script, $this->data);
		}

	}

	public function account() {
		if (!$this->user_model->is_logged_in()) {
			redirect('user/login', 'refresh');
		} else {
			$this->css = array('setting');
			$this->script = array('setting');
			$this->data = array();
			$this->data['active_tab'] = 2;
			
			$this->prepare_for_user_management();

			$this->template->load('main', 'user/setting', $this->css, $this->script, $this->data);
		}
		
	}
	public function security() {
		if (!$this->user_model->is_logged_in()) {
			redirect('user/login', 'refresh');
		} else {
			$this->css = array('setting');
			$this->script = array('setting');
			$this->data = array();
			$this->data['active_tab'] = 3;

			$this->prepare_for_user_management();

			$this->data['user'] = $this->user_model->get_current_user();
			$this->template->load('main', 'user/setting', $this->css, $this->script, $this->data);
		}
		
	}

	private function prepare_for_user_management() {
		$this->data['all_size'] = $this->user_model->get_entire_size_by_email($this->session->userdata('email'));
		$this->data['user_type'] = $this->session->userdata('user_type');
		$this->data['user'] = $this->user_model->get_current_user();
		$this->data['error'] = $this->session->flashdata('error');
		$this->data['message'] = $this->session->flashdata('message');
	}

	public function delete_account() {
		if ($this->session->userdata('email')) {
			if ($this->user_model->delete_account($this->session->userdata('email'))) {
				$this->session->set_flashdata('message', "Successfully Deleted.");
				redirect('user/login', 'refresh');
			} else {
				$this->session->set_flashdata('error', "Something went wrong.");
				redirect('user/account', 'refresh');
			};

		} else {
			redirect('user/login', 'refresh');
		}
	}

	public function subscribe() {
		$token_id = $this->input->post('token_id');
		$plan_id = $this->input->post('plan_id');

		$customer = $this->stripe->addCustomer(array(
			"description" => "Customer",
			"source" => $token_id,
			"plan" => $plan_id
		));
		
		$customer_data = $customer->__toJSON();
		$is_basic = strpos($plan_id, 'basic');
		$is_medium = strpos($plan_id, 'medium');
		$is_super = strpos($plan_id, 'super');

		$user_type = 0;

		if ($is_basic !== false) {
			$user_type = 1;
		} else if ($is_medium !== false) {
			$user_type = 2;
		} else if ($is_super !== false) {
			$user_type = 3;
		} else {
			$user_type = 0;
		}
		$this->user_model->update_customer_id(json_decode($customer_data)->id, $user_type, $plan_id);
		$this->session->set_userdata('custom_id', json_decode($customer_data)->id);
		$this->session->set_userdata('user_type', $user_type);

		$result = array('status' => 'ok', 'user_type' => $user_type, 'plan_id' => $plan_id);
		echo json_encode($result);
	}

	public function change_user_name() {
		$email = $this->input->post('email');
		$first_name = $this->input->post('first_name');
		$last_name = $this->input->post('last_name');
		$result = array();
		if ($this->user_model->update_user_name($email, $first_name, $last_name)) {
			$result['status'] = 'ok';
		} else {
			$result['status'] = 'fail';
		}
		echo json_encode($result);
	}

	public function change_user_email() {
		$email = $this->input->post('email');
		$new_email = $this->input->post('new_email');
		$result = array();
		if ($this->user_model->update_user_email($email, $new_email)) {
			$result['status'] = 'ok';
		} else {
			$result['status'] = 'fail';
		}
		echo json_encode($result);
	}

	public function photo_upload() {
		$config['upload_path']          = './uploads/';
		$config['allowed_types']        = 'png|jpg|jpeg';
		$config['max_size']             = 5000;
		$config['file_name'] = time().'_'.$_FILES["photo"]['name'];
		
		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('photo')) {
			$this->session->set_flashdata('error', $this->upload->display_errors());
			redirect('user/setting');
		} else {
			$file_name = $this->upload->data()['file_name'];
			$this->user_model->change_photo($file_name);
			$this->session->set_flashdata('message', "Successfully uploaded!");
			redirect('user/setting');
		}
	}
	public function delete_photo() {
		if (!$this->user_model->is_logged_in()) {
			$this->session->set_flashdata('error', "You need to login first.");
			redirect('user/login', 'refresh');
		} else {
			$this->user_model->change_photo("");
			$this->session->set_flashdata('message', "Successfully deleted!");
			redirect('user/setting');
		}
	}

	public function change_pass() {
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]|callback_password_check');
		$this->form_validation->set_rules('_password', 'New Password', 'trim|required|min_length[8]');
		$this->form_validation->set_rules('_confirm', 'Password Confirmation', 'trim|required|matches[_password]');

		if ($this->form_validation->run() == true) {
			if ($this->user_model->update_password($this->session->userdata('email'), $this->input->post('password'))) {
				$this->session->set_flashdata('message', 'Password has been changed.');	
			} else {
				$this->session->set_flashdata('error', 'Something went wrong.');
			}
		} else {
			$this->session->set_flashdata('error', validation_errors());
		}
		redirect('user/security', 'refresh');
	}

	public function password_check($str) {
		if ($this->user_model->password_check($str)) {
			return TRUE;
		} else {
			$this->form_validation->set_message('password_check', 'Password is incorrect');
			return FALSE;
		}
	}

	public function forget_password() {
		$this->prepare_for_user_management();
		$this->template->load('main', 'user/input_email', $this->css, $this->script, $this->data);
	}

	public function send_token() {

		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required|callback_email_check');

		if ($this->form_validation->run() == true) {
			$token = random_string('alnum', 25);
			if ($this->user_model->set_password_reset_token($token, $this->input->post('email'))) {
				$reset_url = base_url() . "reset_password/" . $token;
				
				$config['protocol'] = 'smtp';
				$config['smtp_host'] = 'localhost';
				$config['mailtype'] = 'html';
				$config['crlf']        = "\r\n";        // CHANGED FROM DEFAULTS
				$config['newline']     = "\r\n"; 
				$config['validation']	= FALSE;

				$this->email->initialize($config);

				$this->email->from('noreply@hiddendomain.com');
				$this->email->to($this->input->post('email'));

				$this->email->subject('Password Reset');
				$this->email->message('Testing the email class. <a href="http://www.google.co.uk">test</a>');   

				$flag = $this->email->send();
				if ($flag) {
					die("success");
				} else {
					$this->email->print_debugger();
					die('fail');
				}
			} else {
				$this->session->set_flashdata('error', "Something went wrong. Please try again later");
				redirect('user/forget_password', 'refresh');
			}
		} else {
			$this->session->set_flashdata('error', validation_errors());
			redirect('user/forget_password', 'refresh');
		}
	}

	public function email_check($str) {

		if ($this->user_model->email_check($str)) {
			return TRUE;
		} else {
			$this->form_validation->set_message('email_check', 'User does not exists.');
			return FALSE;
		}
	}

	public function reset_password($token) {
		$check = $this->user_model->check_token($token);
		if ($check) {
			$this->prepare_for_user_management();
			$this->data['email'] = $check;
			$this->data['token'] = $token;
			$this->template->load('main', 'user/new_password', $this->css, $this->script, $this->data);
		} else {
			$this->session->set_flashdata('error', 'Invalid reset token');
			redirect('user/login', 'refresh');
		}
	}

	public function set_new_password() {
		$this->form_validation->set_rules('password', 'Password', 'trim|min_length[8]|required');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');
		if ($this->form_validation->run() == true) {
			if ($this->user_model->set_new_password($this->input->post('password'), $this->input->post('email'))) {
				$this->session->set_flashdata('message', "Password Successfully Reset.");
				redirect('user/login', 'refresh');
			} else {
				$this->session->set_flashdata('error', "Something went wrong. Please try agin later");
				redirect('user/reset_password/'.$this->input->post('token'), 'refresh');
			}
		} else {
			$this->session->set_flashdata('error', validation_errors());
			redirect('user/reset_password/'.$this->input->post('token'), 'refresh');
		}
	}

	public function logout() {
		$this->session->sess_destroy();
		redirect('/', 'refresh');
	}

	public function stripe_callback() {
		var_dump($this->input->post());
		echo "<script>alert('callback')</script>";
	}
}
