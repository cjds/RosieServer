<?php namespace App\Http\Controllers;


use Illuminate\Routing\Controller as BaseController;
use Request;
use Session;
use DB;

class RecipeController extends BaseController {


	public function base(){
		$input=Request::all();
		if (Request::isMethod('post')){
    		//add new recipe?
    		$recipe_steps=json_decode($input['recipe_steps']);
    		$this->addRecipe($input['recipe_name'],$input['recipe_description'],$recipe_steps);
		}
		else if(Request::isMethod('get')){
			if(!array_key_exists('recipe_id',$input	)){
				return json_encode($this->getrecipes());
			}
			else{
				return json_encode($this->getrecipe($input['recipe_id']));	
			}
		}
	}

	public function addRecipe($recipe_name,$recipe_description,$recipe_steps){
		//
		$query=DB::insert('insert into recipe (name,description) values (?, ?)', [$recipe_name,$recipe_description]);

		$select=DB::select('SELECT MAX(id) as maxID FROM recipe');
		$id=$select[0]->maxID;
		
		foreach ($recipe_steps as $i => $recipe_step) {
			$query=DB::insert('insert into recipe_steps (step,recipe_id,step_number,machine_instruction,step_title) values (?, ?,?,?,?)', [$recipe_step->step_title,$id, $i,$recipe_step->machine_instruction,$recipe_step->step_title]);
		}
		return true;
	}

	public function getrecipes(){
		$query=DB::select("Select * from recipe",[]);
		return $query;		
	}


	public function getrecipe($id){
		$query=DB::select("Select * from recipe r, recipe_steps rs WHERE r.id=rs.recipe_id AND r.id=:id ORDER BY rs.step_number",['id'=>$id]);
		return $query;
	}
}
?>
