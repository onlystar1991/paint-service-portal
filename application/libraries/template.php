<?php  
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Template 
	{
		var $ci;
		function __construct() {
			$this->ci =& get_instance();
		}
		function load($tpl_view, $body_view = null, $csses = array(), $scripts = array(), $data = null) {
			if ( ! is_null( $body_view ) ) {
				if ( file_exists( APPPATH.'views/'.$tpl_view.'/'.$body_view ) ) {
					$body_view_path = $tpl_view.'/'.$body_view;
				} else if ( file_exists( APPPATH.'views/'.$tpl_view.'/'.$body_view.'.php' ) ) {
					$body_view_path = $tpl_view.'/'.$body_view.'.php';
				} else if ( file_exists( APPPATH.'views/'.$body_view ) ) {
					$body_view_path = $body_view;
				} else if ( file_exists( APPPATH.'views/'.$body_view.'.php' ) ) {
					$body_view_path = $body_view.'.php';
				} else {
					show_error('Unable to load the requested file: ' . $tpl_name.'/'.$view_name.'.php');
				}

				$body = $this->ci->load->view($body_view_path, $data, TRUE);
				$extra_csses = '';
				foreach ($csses as $css) {
					$extra_csses .= ('<link href="' . base_url() . 'assets/css/' . $css . '.css" rel="stylesheet">');
				}
				$extra_jses = '';
				foreach ($scripts as $script) {
					$extra_jses .= ('<script type="text/javascript" src="' . base_url() . 'assets/js/' . $script . '.js"></script>');
				}

				if ( is_null($data) ) {
					$data = array('body' => $body);
				} else if ( is_array($data) ) {
					$data['body'] = $body;
					$data['script'] = $extra_jses;
					$data['style'] = $extra_csses;
				} else if ( is_object($data) ) {
					$data->body = $body;
					$data->script = $script;
				}
			}
			$this->ci->load->view('templates/'.$tpl_view, $data);
		}
	}