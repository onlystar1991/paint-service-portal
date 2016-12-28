<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function get_all_video_by_email($email) {
		$query = $this->db->where('email', $email)
					->get('tbl_videos');

		return $query->result_array();
	}

	public function get_video_by_timestamp($timestamp) {
		$query = $this->db->where('timestamp', $timestamp)
				->limit(1)
				->get('tbl_videos');

		if ($query->num_rows() === 0) {
			return false;
		} else {
			return $query->result_array();
		}
		return false;
	}

	public function save_category($email, $video, $category_name) {
		$query = $this->db->where(array('video' => $video, 'name' => $category_name))->get('tbl_categories');
		if ($query->num_rows() > 0) {
			return false;
		} else {
			$data = array(
				'email' => $email,
				'video' => $video,
				'timestamp' => round(microtime(true) * 1000),
				'name' => $category_name
			);
			return $this->db->insert('tbl_categories', $data);
		}
	}

	public function save_clip($email, $video, $category, $info) {
		$data = array(
			'email' => $email,
			'timestamp' => round(microtime(true) * 1000),
			'video' => $video,
			'category' => $category,
			'info' => $info
		);
		return $this->db->insert('tbl_clips', $data);
	}
}