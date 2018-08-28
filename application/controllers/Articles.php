<?php defined('BASEPATH') OR exit('no direct script access allowed');

// use Restserver\Libraries\REST_Controller;
require APPPATH .'/libraries/REST_Controller.php';

class Articles extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('user_model');
		header("Access-Control-Allow-Origin: *");
	}

}