<?php defined('BASEPATH') OR exit('no direct script access allowed');

use Restserver\Libraries\REST_Controller;
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
 
class MY_Controller extends Restserver\Libraries\REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('Authorization_Token');
		header("Access-Control-Allow-Origin: *");

		// check authontication
		$is_auth = $this->authorization_token->validateToken();
		if(!empty($is_auth) AND $is_auth['status'] === TRUE) {

		}
		else {
			$this->response([
				'status' => FALSE,
				'message' => $is_auth['message']
			], REST_Controller::HTTP_NOT_FOUND);
		}
	}


}