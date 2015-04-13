<?php namespace App\Http\Controllers;


use Illuminate\Routing\Controller as BaseController;
use Request;
use Session;
use DB;


class UserController extends BaseController {


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
		$input=Request::all();
		if (Request::isMethod('post')){
    		$this->add_new_user($input['username'],null,$input['password']);
    		$this->login($input['username'],$input['password']);
		}
		else if(Request::isMethod('get')){

			$user_id = Session::get('user_id');
			if($user_id==null){
				//check for login and reject
				$user_logged_in=$this->login($input['username'],$input['password']);
				if(!$user_logged_in){
					return json_encode(['response'=>'fail','reason'=>'The user could not be validated']);
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
		//DB::insert('insert into users (name,password,device_id) values (?, ?,?)', [$user_name, $password,1]);
		$query=DB::insert('insert into users (name,password,device_id) values (?, ?,?)', [$user_name, $password,1]);


	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return True or false
	 */
	public function login($user_name,$password){
		$output=false;
		$query=DB::select("SELECT * FROM users WHERE name=:name AND password=:password",['name'=>$user_name,'password'=>$password]);
		if(count($query)==1){
			Session::put('user_id', $query[0]->id);
			Session::put('user_name',$query[0]->name);	
			$output=true;
		}
		return $output;

	}

	public function get_user_details(){
		//$encrypted_token = $encrypter->encrypt(csrf_token());		
		$array['user_id']=Session::get('user_id');
		$array['user_name']=Session::get('user_name');
		$array['_token']=csrf_token();
		return $array;
	}

}
?>