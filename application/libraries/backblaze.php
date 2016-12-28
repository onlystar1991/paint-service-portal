<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Backblaze {

	public function authorize_account() {
		$credentials = base64_encode("f338ea910e1b" . ":" . "00187d52324c6e56b5ab048e4634536b97b990b7ac");

		$url = "https://api.backblazeb2.com/b2api/v1/b2_authorize_account";

		$session = curl_init($url);
		// Add headers
		$headers = array();
		$headers[] = "Accept: application/json";
		$headers[] = "Authorization: Basic " . $credentials;
		curl_setopt($session, CURLOPT_HTTPHEADER, $headers);  // Add headers

		curl_setopt($session, CURLOPT_HTTPGET, true);  // HTTP GET
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true); // Receive server response
		$server_output = curl_exec($session);
		
		$httpcode = curl_getinfo($session, CURLINFO_HTTP_CODE);

		if ($httpcode == 200) {
			$return = array();

			$result = json_decode($server_output);
			$return['auth_token'] = $result->authorizationToken;
			$return['api_url'] = $result->apiUrl;
			$return['download_url'] = $result->downloadUrl;
			$return['account_id'] = $result->accountId;
			
			curl_close($session);
			return $return;

		} else {
			curl_close ($session);
			return false;
		}
	}

	public function get_upload_url($bucket_id) {

		$response = $this->authorize_account();
		if ($response) {
			$session = curl_init($response['api_url'] .  "/b2api/v1/b2_get_upload_url");

			$data = array("bucketId" => $bucket_id);
			$post_fields = json_encode($data);
			curl_setopt($session, CURLOPT_POSTFIELDS, $post_fields); 
			$headers = array();
			$headers[] = "Authorization: " . $response['auth_token'];
			curl_setopt($session, CURLOPT_HTTPHEADER, $headers); 

			curl_setopt($session, CURLOPT_POST, true);
			curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec($session);
			$httpcode = curl_getinfo($session, CURLINFO_HTTP_CODE);
			curl_close ($session);

			if ($httpcode == 200) {
				$return = array();
				$result = json_decode($server_output);
				$return['bucket_id'] = $result->bucketId;
				$return['upload_url'] = $result->uploadUrl;
				$return['authorization_token'] = $result->authorizationToken;
				$return['download_url'] = $response['download_url'];
				return $return;
			} else {
				return $return;
			}
		} else {
			return false;
		}
	}

	public function create_bucket($bucket_name, $bucket_type = "allPublic") {
		$response = $this->authorize_account();

		if ($response) {
			$session = curl_init($response['api_url'] .  "/b2api/v1/b2_create_bucket");
			// Add post fields
			$data = array("accountId" => $response['account_id'], "bucketName" => $bucket_name, "bucketType" => $bucket_type);
			$post_fields = json_encode($data);
			curl_setopt($session, CURLOPT_POSTFIELDS, $post_fields); 
			// Add headers
			$headers = array();
			$headers[] = "Authorization: " . $response['auth_token'];
			curl_setopt($session, CURLOPT_HTTPHEADER, $headers); 
			curl_setopt($session, CURLOPT_POST, true);
			curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec($session);
			$httpcode = curl_getinfo($session, CURLINFO_HTTP_CODE);
			curl_close ($session);

			if ($httpcode == 200) {
				$return = array();
				$result = json_decode($server_output);
				$return['account_id'] = $result->accountId;
				$return['bucket_id'] = $result->bucketId;
				$return['bucket_name'] = $result->bucketName;
				$return['bucket_type'] = $result->bucketType;
				$return['bucket_info'] = $result->bucketInfo;

				return $return;
			} else {
				return false;
			}			
		} else {
			return false;
		}
	}

	public function upload_video_file($filepath, $filename, $bucket_id) {

		$return = $this->get_upload_url($bucket_id);

		if ($return) {
			$my_file = $filepath . $filename;
			$handle = fopen($my_file, 'r');
			$read_file = fread($handle,filesize($my_file));

			$upload_url = $return['upload_url'];
			$upload_auth_token = $return['authorization_token'];
			$content_type = "video/mp4";
			$sha1_of_file_data = sha1_file($my_file);

			$session = curl_init($upload_url);

			// Add read file as post field
			curl_setopt($session, CURLOPT_POSTFIELDS, $read_file);
			// Add headers
			$headers = array();
			$headers[] = "Authorization: " . $upload_auth_token;
			$headers[] = "X-Bz-File-Name: " . $filename;
			$headers[] = "Content-Type: " . $content_type;
			$headers[] = "X-Bz-Content-Sha1: " . $sha1_of_file_data;
			curl_setopt($session, CURLOPT_HTTPHEADER, $headers); 

			curl_setopt($session, CURLOPT_POST, true);
			curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec($session);
			$httpcode = curl_getinfo($session, CURLINFO_HTTP_CODE);
			curl_close ($session);

			if ($httpcode == 200) {
				$response = array();
				$result = json_decode($server_output);
				$response['account_id'] = $result->accountId;
				$response['bucket_id'] = $result->bucketId;
				$response['file_size'] = $result->contentLength;

				$response['file_id'] = $result->fileId;
				$response['file_name'] = $result->fileName;
				$response['upload_file_stamp'] = $result->uploadTimestamp;
				$response['download_url'] = $return['download_url'];

				return $response;
			} else {
				return false;
			}
		}
	}

}