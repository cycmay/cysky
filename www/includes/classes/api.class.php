<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');

$api_version = "1.0";

class Api {
	
	public static function get_user_data($username){
		global $api_version;
		
		$data = User::find_by_sql("SELECT username,first_name,last_name,gender,last_login,country,investments_made,amount_invested FROM users WHERE username = '{$username}' LIMIT 1 ");
		
		if(isset($data[0])){
			$array = array("api_version" => $api_version, "data" => array("username" => $data[0]->username,"first_name" => $data[0]->first_name,"last_name" => $data[0]->last_name,"gender" => $data[0]->gender,"last_login" => $data[0]->last_login, "country" => $data[0]->country, "investments_made" => $data[0]->investments_made, "amount_invested" => $data[0]->amount_invested) );
			return json_encode($array);
		} else {
			return json_encode("No user found");
		}		
	}
	
	public static function get_user_projects($username){
		global $api_version;
		
		$user_id = User::find_id_by_username($username);
		$data = Investments::find_by_sql("SELECT id,name,description,investment_wanted,amount_invested,expires,main_description,category_id,investor_count FROM investments WHERE creator_id = '{$user_id->user_id}' ");
		
		$multi_array = array();
		$counter = 0;
		foreach($data as $data){
			$multi_array[$counter] = array(
				"id" => $data->id,
				"name" => $data->name,
				"description" => $data->description,
				"goal" => $data->investment_wanted,
				"invested" => $data->amount_invested,
				"ends" => $data->expires,
				"main_description" => $data->main_description,
				"category_id" => $data->category_id,
				"investor_count" => $data->investor_count
			);
			$counter++;
		}
		
		if(isset($data)){
			$array = array("api_version" => $api_version, "data" => $multi_array);
			return json_encode($array);
		} else {
			return json_encode("No user found");
		}		
	}
	
	public static function get_project($id){
		global $api_version;
		
		$data = Investments::find_by_sql("SELECT id,name,description,investment_wanted,amount_invested,expires,main_description,category_id,investor_count FROM investments WHERE id = '{$id}' LIMIT 1 ");
		
		if(isset($data[0])){
			$array = array("api_version" => $api_version, "data" => array(
				"id" => $data[0]->id,
				"name" => $data[0]->name,
				"description" => $data[0]->description,
				"goal" => $data[0]->investment_wanted,
				"invested" => $data[0]->amount_invested,
				"ends" => $data[0]->expires,
				"main_description" => $data[0]->main_description,
				"category_id" => $data[0]->category_id,
				"investor_count" => $data[0]->investor_count
			) );
			return json_encode($array);
		} else {
			return json_encode("No user found");
		}		
	}
	

}
