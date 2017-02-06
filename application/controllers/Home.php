<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library('form_validation');
		$this->load->model('user_model');
		$this->load->model('video_model');
		$this->load->library('template');
		$this->load->library('session');
		$this->load->library('backblaze');
		// $this->load->library('form_helper');

		// Controller Specific CSS and Scripts
		
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
			redirect('home/welcome');
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
			$this->data['videos'] = $this->video_model->get_all_video_by_email($this->session->userdata('email'));
			$this->data['user'] = $this->user_model->get_current_user();

			$this->template->load('application', 'home/dashboard', $this->css, $this->script, $this->data);
		} else {
			redirect('user/login');
		}
	}

	public function video_detail($timestamp) {			
			$video = $this->video_model->get_video_by_timestamp($timestamp)[0];
			$categories = $this->user_model->get_default_categories()[0];
			$custom_categories = $this->user_model->get_categories_by_video_and_email($timestamp);
			$clips = $this->user_model->get_clips_by_video_and_email($timestamp);

			if ($this->user_model->is_logged_in()) {
				$users = $this->user_model->get_sharable_users();
			} else {
				$users = array();
			}
			$this->css = array('home_video_detail');
			$this->script = array('home_video_detail');

			$this->data['video'] = $video;
			$this->data['default_categories'] = $categories['default_categories'];
			$this->data['custom_categories'] = $custom_categories;
			$this->data['clips'] = $clips;
			$this->data['error'] = $this->session->flashdata('error');
			$this->data['message'] = $this->session->flashdata('message');
			$this->data['users'] = $users;
			$this->data['loggedin'] = $this->user_model->is_logged_in();

			$this->template->load('application', 'home/video_detail', $this->css, $this->script, $this->data);
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

	public function share() {
		$result = array();

		if ($this->user_model->is_logged_in()) {
			$share_with = $this->input->post('share_with');
			$video_timestamp = $this->input->post('video');
			$sharable_arr = explode(',', $share_with);
			$flag = false;
			foreach ($sharable_arr as $man) {
				if ($this->user_model->share_with($this->session->userdata('email'), $man, $video_timestamp)) {
					$fullname = $this->session->userdata('user_name');
					$video_ = $this->video_model->get_video_by_timestamp($video_timestamp)[0];
					$url = json_decode($video_['info'])->url;
					$url = base_url() . 'video_detail/' . $video_timestamp;
					$message = $this->load->view('mails/video_share', array('user' => $fullname, 'url' => $url), true);

					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

					// More headers
					$headers .= 'From: <administrator@videre.com>' . "\r\n";
					$subject = "Videre - Share videos";

					$flag = mail(trim($man),$subject,$message,$headers);

				} else {
					$flag = false;
				}
			}
			if ($flag) {
				$result['status'] = "ok";
			} else {
				$result['status'] = "fail";
			}
		} else {
			$result['status'] = "fail";
		}
		echo json_encode($result);
	}

	
}
