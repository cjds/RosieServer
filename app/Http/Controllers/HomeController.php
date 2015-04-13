<?php namespace App\Http\Controllers;


use Illuminate\Routing\Controller as BaseController;
use Request;
use Session;
use DB;


class HomeController extends BaseController {


	public function recipes(){
		
	}

	public function gettoken(){
		echo csrf_token();
	}

}?>