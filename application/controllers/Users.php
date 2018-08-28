<?php defined('BASEPATH') OR exit('no direct script access allowed');

// use Restserver\Libraries\REST_Controller;
require APPPATH .'/libraries/REST_Controller.php';

class Users extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('user_model');
		header("Access-Control-Allow-Origin: *");
	}

	public function user_register_post() {
		$_POST = $this->security->xss_clean($_POST);

		$this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[users.username]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|alpha_numeric');
		// $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');

		// validate post data
		if($this->form_validation->run() == FALSE) {
			$message = array(
				'status' => false,
				'error' => $this->form_validation->error_array(),
				'message' => validation_errors()
			);
			$this->response($message, REST_Controller::HTTP_NOT_FOUND);
		}
		else {
			$insert_data = [
				'username' => $this->input->post('username',TRUE),
				'password' => md5($this->input->post('password',TRUE)),
				'email' => $this->input->post('email',TRUE),
				'created_at' =>time() 
			];
			$output = $this->user_model->insert_user($insert_data);
			if($output > 0 AND !empty($output)) {
				$this->response(array('status'=>'true'),REST_Controller::HTTP_OK);
			}
			else {
				$this->response(array('status'=>'false'),REST_Controller::HTTP_NOT_FOUND);
			}
		}
	}
	public function fetch_all_users_get() {
		$data = $this->user_model->fetch_all_users();
		$this->response($data);
	}
	public function user_login_post() {
		$_POST = $this->security->xss_clean($_POST);

		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|alpha_numeric');

		// validate post data
		if($this->form_validation->run() == FALSE) {
			$message = array(
				'status' => false,
				'error' => $this->form_validation->error_array(),
				'message' => validation_errors()
			);
			$this->response($message, REST_Controller::HTTP_NOT_FOUND);
		}
		else {
			$output = $this->user_model->user_login($this->input->post('username'),$this->input->post('password'));

			if(!empty($output AND $output != FALSE)) {

			// Load autherization token library
			$this->load->library('Authorization_Token');
			// Generate token
			$token_data = array(
					'username' => $output->username,
					'email' => $output->email,
					'createdat' => $output->created_at,
					'time' => time(), 
				);
			$user_token = $this->authorization_token->generateToken($token_data);



				$userdata = array(
					'username' => $output->username,
					'email' => $output->email,
					'createdat' => $output->created_at,
					'token' => $user_token,
				);
				$this->response(array('login'=>'true','user'=>$userdata),REST_Controller::HTTP_OK);
			}
			else {
				$this->response(array('login'=>'false'),REST_Controller::HTTP_NOT_FOUND);
			}
		}
	}
}

 ?>