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
    		$this->add_new_user($input['username'],$input['name'],$input['password']);
		}
		else (Request::isMethod('get')){

			$user_id = Session::get('user_id');
			if($user_id==null){
				//check for login and reject
				$user_logged_in=$this->login($input['username'],$input['password']);
				if(!$user_logged_in){
					return json_encode(['response':'fail','reason':'The user could not be validated']);
				}
			}

		}
		return json_encode($this->get_user_details());
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function add_new_user($user_name,$name,$password)
	{
		///TODO
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return True or false
	 */
	public function login($user_name,$password){
		Session::put('user_id', '');
		Session::put('user_name',$user_name);	
	}

	public function get_user_details(){
		$array['user_id']=Session::get('user_id');
		$array['user_name']=Session::get('user_name');
		return $array;
	}

}
?>