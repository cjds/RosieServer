<?php  namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Request;
use Session;
use DB;

class DeviceController extends BaseController {


	public function currentstate(){
		$input=Request::all();
		if (Request::isMethod('get')){
			//change current state to new state
			//go to next state
			//next step
			return json_encode($this->getCurrentState($input['device_id']));
		}
		else if(Request::isMethod('post')){
			$sender=$input['sender'];

			if($sender=='human'){
				if(array_key_exists('device_id',$input))
					$device_id=$input['device_id'];
				else
					$device_id=NULL;
				if(array_key_exists('recipe_id',$input))
					$recipe_id=$input['recipe_id'];
				else
					$recipe_id=NULL;
				return json_encode($this->pushNextState($device_id,$recipe_id));
			}
			else{
				//$device_id,$machine_current_state,$machine_step
				return $this->machinePushNextState($input['device_id'],$input['current_state'],$input['recipe_step']);
			}
		}
	}


	//get current state of the machine
	public function getCurrentState($device_id){ 
		$query=DB::select("SELECT * FROM device WHERE id=:device_id",['device_id'=>$device_id]);
		return $query;
		//SELECT * FrOM device WHERE id=:device_id
	}


	//when user presses done it will go here
	//basically saying we're done with this step what's next
	public function pushNextState($device_id,$recipe_id){
		//get next step
		//set next step as device step
		//UPDATE device SET device_step= :recipe_step WHERE idd=:device_id

		//get new recipe
		if($recipe_id!=null){
			$query=DB::select("SELECT rs.id,MIN(step_number) FROM recipe_steps rs WHERE  rs.recipe_id=$recipe_id");			
			$recipe_step=$query[0]->id;
			$query=DB::update("UPDATE device SET device_step=? WHERE id=?",[$recipe_step,$device_id]);		
		}
		else{
			$query=DB::select("SELECT rs.id,MIN(step_number) as step_number
				FROM recipe_steps rs
				WHERE step_number>(SELECT step_number FROM device d,recipe_steps rs WHERE d.device_step=rs.id AND d.id=:device_id1)
				AND rs.recipe_id=(SELECT rs.recipe_id FROM device d,recipe_steps rs WHERE d.device_step=rs.id AND d.id=:device_id2)
				",['device_id1'=>$device_id,'device_id2'=>$device_id]);

			if(count($query)==1)
				$recipe_step=$query[0]->id;
			else
				$recipe_step=NULL;
			$query=DB::update("UPDATE device SET device_step=? WHERE id=?",[$recipe_step,$device_id]);		
		}
		return true;
	}

	//when machine is done it will go here
	//machine_current_state : what the machine is currently doing instruction
	//machine_step : where in the recipe it is
	//device_step : where in the recipe the server is
	public function machinePushNextState($device_id,$machine_current_state,$machine_step){
		$data=null;
		$idle=0;
		$machine_instruction=0;
		$device_step=$this->getCurrentState($device_id)[0]->device_step;
		//if machine is idle
		if($machine_current_state==$idle){
			$query=DB::select("SELECT rs.id,MIN(step_number),rs.user_step
				FROM recipe_steps rs
				WHERE step_number=(SELECT step_number FROM device d,recipe_steps rs WHERE d.device_step=rs.id AND d.id=:device_id1)",
				['device_id1'=>$device_id]);
			if($query[0]->user_step){
				// we are good machine can keep waiting
				return $device_step.' '.$machine_current_state;
			}			
			else{
				$query=DB::select("SELECT rs.id,MIN(step_number) as step_number,machine_instruction
					FROM recipe_steps rs
					WHERE step_number>(SELECT step_number FROM device d,recipe_steps rs WHERE d.device_step=rs.id AND d.id=:device_id1)
					AND rs.recipe_id=(SELECT rs.recipe_id FROM device d,recipe_steps rs WHERE d.device_step=rs.id AND d.id=:device_id2)
					",['device_id1'=>$device_id,'device_id2'=>$device_id]);
				if(count($query)==1){
					$recipe_step=$query[0]->id;
					$machine_instruction=$query[0]->machine_instruction;
				}
				else
					$recipe_step=null;
				$query=DB::update("UPDATE device SET device_step=? WHERE id=?",[$recipe_step,$device_id]);		
				return $recipe_step." ".$machine_instruction;
			}
		}
		else{
			//we are good. machine is not idle therefore no new instructions
			return $device_step.' '.$machine_current_state;
		}
	}
}
?>
