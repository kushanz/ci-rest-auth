<?php defined('BASEPATH') OR exit('no direct script access allowed');
 
class Articles extends MY_Controller {

	public function __construct() {
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
	}

	public function addArticle_post() {
		echo json_encode($_POST);
	}

}