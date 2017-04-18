<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library('form_validation');
		$this->load->model('user_model');
		$this->load->library('template');
		$this->load->library('session');
		
		$this->data = array();
		$this->script = array();

	}

	public function index() {

		if ($this->user_model->is_logged_in()) {
			if ($this->session->userdata('user_type') == '0') {
				redirect('user/upgrade_membership');
			} else {
				redirect('home/dashboard');
			}
		} else {
			$this->css = array('home_welcome');
			$this->template->load('main', 'home/welcome', $this->css, $this->script, $this->data);
		}
	}

	public function welcome() {
		$this->css = array('home_welcome');
		$this->template->load('main', 'home/welcome', $this->css, $this->script, $this->data);
	}
	public function terms() {
		$this->css = array('home_welcome');
		$this->template->load('main', 'home/_terms', $this->css, $this->script, $this->data);
	}
	public function policy() {
		$this->css = array('home_welcome');
		$this->template->load('main', 'home/_policy', $this->css, $this->script, $this->data);
	}
	public function agreement() {
		$this->css = array('home_welcome');
		$this->template->load('main', 'home/_agreement', $this->css, $this->script, $this->data);
	}


	public function dashboard() {
		if ($this->user_model->is_logged_in()) {
			$this->css = array('home_dashboard');
			$this->script = array('home_dashboard');
			
			$this->data['error'] = $this->session->flashdata('error');
			$this->data['message'] = $this->session->flashdata('message');
			$this->data['user'] = $this->user_model->get_current_user();

			$this->template->load('application', 'home/dashboard', $this->css, $this->script, $this->data);
		} else {
			redirect('user/login');
		}
	}

	public function do_upload() {
		$config['upload_path']          = './uploads/';
		$config['allowed_types']        = 'mov|mp4';
		$config['max_size']             = 5000000;
		$config['file_name'] = time().'_'.$_FILES["video"]['name'];
		
		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('video')) {
			$this->session->set_flashdata('error', $this->upload->display_errors());
			redirect('home/dashboard');
		} else {
			$file_path = $this->upload->data()['file_path'];
			$file_name = $this->upload->data()['file_name'];
			$this->user_model->save_video($file_path, $file_name);
			$this->session->set_flashdata('message', "Successfully uploaded!");
			redirect('home/dashboard');
		}
	}
}
