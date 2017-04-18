<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('UNPAID', 1);
define('PAID', 2);

class User_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
	}

	public function login($email, $password) {
		$query = $this->db->where('email', $email)
					->limit(1)
					->get('tbl_users');

		if ($query->num_rows() === 1) {
			$user = $query->row();
			if (md5($password) == $user->pass) {
				$session_data = array(
					'user_name'			=> $user->first_name. " ".$user->last_name,
					'email'				=> $user->email,
					'user_id'			=> $user->id,
					'user_type'			=> $user->user_type,
					'bucket_id'			=> $user->bucket_id,
					'customer_id'		=> $user->customer_id
				);

				$this->session->set_userdata($session_data);
				return true;
			} else {
				return false;
			}
		}
		return false;
	}

	public function is_logged_in() {
		return $this->session->userdata('email') ? true : false;
	}

	public function register($first_name, $last_name, $email, $password) {
		$query = $this->db->select('email, id, pass')
				->where('email', $email)
				->limit(1)
				->get('tbl_users');
		if ($query->num_rows() === 1) {
			return false;
		} else {
			$data = array (
				'first_name' => $first_name,
				'last_name' => $last_name,
				'email' => $email,
				'pass' => md5($password),
				'register' => date("Y-m-d H:i:s")
			);
			return $this->db->insert('tbl_users', $data);
		}
		return false;
	}

	public function get_default_categories() {
		$query = $this->db->select('default_categories')
				->limit(1)
				->get('tbl_users');
		return $query->result_array();
	}

	public function generate_bucket_name($base_name) {
		$result = str_replace('@', '-', $base_name);
		$result = str_replace('.', '-', $result);
		return $result;
	}

	public function get_categories_by_video_and_email($timestamp) {
		$query = $this->db->where(array('video' => $timestamp))
				->get('tbl_categories');
		return $query->result_array();
	}

	public function get_clips_by_video_and_email($timestamp) {
		$query = $this->db->get_where('tbl_clips', array('video' => $timestamp));
		return $query->result_array();
	}


	public function get_entire_size_by_email($email) {
		$query = $this->db->where('email', $email)
				->get('tbl_videos')->result_array();
		$file_size = 0;
		foreach ($query as $video) {
			$info = json_decode($video['info']);
			$file_size += $info->file_size / 1000 / 1000;
		}
		return $file_size;
	}

	public function get_sharable_users() {
		$query = $this->db->where('email !=', $this->session->userdata('email'))
				->get('tbl_users')->result_array();
		return $query;
	}

	public function share_with($sender, $receiver, $timestamp) {
		$this->db->where('sender', $sender);
		$this->db->where('receiver', trim($receiver));
		$this->db->where('video', $timestamp);
		$this->db->delete('tbl_shares');

		return $this->db->insert('tbl_shares', 
			array('video' 	=> $timestamp,
				'sender' 	=> $sender,
				'receiver' 	=> trim($receiver),
			)
		);
	}

	public function get_current_user() {
		return $this->db->get_where('tbl_users', array('email' => $this->session->userdata('email')))->result_array()[0];
	}

	public function delete_account($email) {
		$this->db->where('email', $email);
		$user = $this->db->get('tbl_users')->result_array()[0];
		$this->db->where('email', $email);
		$this->db->delete('tbl_users');
	}

	public function update_customer_id($customer_id, $user_type, $plan_id) {
		$this->db->where('email', $this->session->userdata('email'));
		$user = $this->db->get('tbl_users')->result_array()[0];
		
		$this->db->where('email', $this->session->userdata('email'));
		return $this->db->update('tbl_users', array('customer_id' => $customer_id, 'user_type' => $user_type, 'plan_id' => $plan_id));
	}

	public function update_user_name($email, $first_name, $last_name) {
		$this->db->where('email', $email);
		return $this->db->update('tbl_users', array('first_name' => $first_name, 'last_name' => $last_name));
	}
	
	public function update_user_email($email, $new_email) {
		$this->db->where('email', $email);
		return $this->db->update('tbl_users', array('email' => $new_email));
	}

	public function change_photo($file_name) {
		$this->db->where('email', $this->session->userdata('email'));
		if ($file_name == "") {
			return $this->db->update('tbl_users', array("photo" => ""));
		} else {
			return $this->db->update('tbl_users', array("photo" => base_url() . 'uploads/' . $file_name));
		}
	}

	public function password_check($password) {
		$query = $this->db->where(array('email' => $this->session->userdata('email'), 'pass' => md5($password)))
					->limit(1)
					->get('tbl_users');

		if ($query->num_rows() === 1) {
			return false;
		} else {
			return true;
		}
	}
	public function email_check($email) {
		$query = $this->db->where(array('email' => $email))
					->limit(1)
					->get('tbl_users');

		if ($query->num_rows() === 1) {
			return true;
		} else {
			return false;
		}
	}

	public function update_password($email, $new_password) {
		$this->db->where('email', $email);
		return $this->db->update('tbl_users', array('pass' => md5($new_password)));
	}

	public function set_password_reset_token($token, $email) {
		$this->db->where('email', $email);
		return $this->db->update('tbl_users', array('reset_password_token' => $token));
	}

	public function check_token($token) {
		$query = $this->db->where(array('reset_password_token' => $token))
					->limit(1)
					->get('tbl_users');
		if ($query->num_rows() === 1) {
			$user = $query->row();
			return $user->email;
		} else {
			return false;
		}
	}
	public function set_new_password($new_pass, $email) {
		$this->db->where('email', $email);
		return $this->db->update('tbl_users', array('pass' => md5($new_password)));
	}

	public function is_expired($email) {
		$query = $this->db->where(array('email' => $email))
						->limit(1)
						->get('tbl_users');
		if ($query->num_rows() === 1) {
			$user = $query->row();

			$now = strtotime("now");
			$diff =  $now - strtotime($user->register);
			return $diff > 6 * 7 * 24 * 3600 ? TRUE : FALSE;
		} else {
			return false;
		}
	}
	public function make_unpaid_account_for_customer_id($customer_id) {
		$this->db->where('customer_id', $customer_id);
		$flag = $this->db->update('tbl_users', array('paid_status' => UNPAID));
		$query = $this->db->where('customer_id', $customer_id)
						->limit(1)
						->get('tbl_users');

		$result = array();
		$result['status'] = $flag;
		if ($query->num_rows() === 1) {
			$user = $query->row();
			$result['email'] = $user->email;
		}
		return $result;
	}

	public function make_paid_account_for_customer_id($customer_id) {
		$this->db->where('customer_id', $customer_id);
		$flag = $this->db->update('tbl_users', array('paid_status' => PAID));
		$query = $this->db->where('customer_id', $customer_id)
						->limit(1)
						->get('tbl_users');

		$result = array();
		$result['status'] = $flag;
		if ($query->num_rows() === 1) {
			$user = $query->row();
			$result['email'] = $user->email;
		}
		return $result;
	}
	public function delete_user_with_customer_id($customer_id) {
		$query = $this->db->where('customer_id', $customer_id)
						->limit(1)
						->get('tbl_users');

		$result = array();
		if ($query->num_rows() === 1) {
			$user = $query->row();
			$result['email'] = $user->email;
		}

		$this->db->where('customer_id', $customer_id);
		$flag = $this->db->delete('tbl_users');
		$result['status'] = $flag;

		return $result;
	}
}