<?php 


class HomeController extends BaseController {


	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('guest');
	}

	public function base(){
		$input=Input::all();
		if (Request::isMethod('post')){
    		//add_new_user
		}
		else (Request::isMethod('get')){

			$user_id = Session::get('user_id');
			if($user_id==null){
				//check for login and reject
				$user_logged_in=$this->login($input['username'],$input['password']);
				if()
			}
		}


	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function add_new_user($user_name,$name,$password)
	{
	
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return True or false
	 */
	public function login($user_name,$password){

	}

	public function get_user_details($user_id){

	}

}
?>