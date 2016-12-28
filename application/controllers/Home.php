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

	}

	public function index() {

		if ($this->user_model->is_logged_in()) {
			if ($this->session->userdata('user_type') == '0') {
				redirect('user/upgrade_membership');
			} else {
				redirect('home/dashboard');
			}
		} else {
			redirect('user/login');
		}
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
		if ($this->user_model->is_logged_in()) {
			
			$video = $this->video_model->get_video_by_timestamp($timestamp)[0];
			$categories = $this->user_model->get_default_categories($this->session->userdata('email'))[0];
			$custom_categories = $this->user_model->get_categories_by_video_and_email($timestamp, $this->session->userdata('email'));
			$clips = $this->user_model->get_clips_by_video_and_email($timestamp, $this->session->userdata('email'));

			$users = $this->user_model->get_sharable_users();
			// $this->backblaze->download_file_by_id(json_decode($video['info'])->file_id);
			$this->css = array('home_video_detail');
			$this->script = array('home_video_detail');

			$this->data['video'] = $video;
			$this->data['default_categories'] = $categories['default_categories'];
			$this->data['custom_categories'] = $custom_categories;
			$this->data['clips'] = $clips;
			$this->data['error'] = $this->session->flashdata('error');
			$this->data['message'] = $this->session->flashdata('message');
			$this->data['users'] = $users;

			$this->template->load('application', 'home/video_detail', $this->css, $this->script, $this->data);

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

	public function share() {
		$result = array();

		if ($this->user_model->is_logged_in()) {
			$share_with = $this->input->post('share_with');
			$video_timestamp = $this->input->post('video');
			$sharable_arr = explode(',', $share_with);
			$flag = false;
			foreach ($sharable_arr as $man) {
				if ($this->user_model->share_with($this->session->userdata('email'), $man, $video_timestamp)) {
					$flag = true;
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

	public function save_category() {
		$email = $this->session->userdata('email');
		$video = $this->input->post('video');
		$category_name = $this->input->post('category_name');
		$result = array();
		if ($this->video_model->save_category($email, $video, $category_name)) {
			$result['status'] = 'ok';
			$result['name'] = $category_name;
		} else {
			$result['status'] = 'fail';
		}
		echo json_encode($result);
	}

	public function save_clip() {
		$email = $this->input->post('email');
		$category = $this->input->post('category');
		$video = $this->input->post('video');

		$info_array = array('start' => $this->input->post('start'), 'end' => $this->input->post('end'));
		$info = json_encode($info_array);
		$result = array();
		if ($this->video_model->save_clip($email, $video, $category, $info)) {
			$result['status'] = 'ok';
		} else {
			$result['status'] = 'fail';
		}
		echo json_encode($result);
	}
}
