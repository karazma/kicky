<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// database connection will be here
// include database and object files
include_once '../config/database.php';
include_once '../objects/Playerinfo.php';
include_once '../objects/Playerprogress.php';
include_once '../objects/PlayerTransaction.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$PlayerinfoDetails = new Playerinfo($db);
$PlayerinfoProgress = new Playerprogress($db);
$PlayerinfoTranscation = new PlayerTransaction($db);
 
// read products will be here
// query products
$PlayerinfoDetails = $PlayerinfoDetails->readAll();
$PlayerinfoProgress = $PlayerinfoProgress->readAll();
$PlayerinfoTranscation = $PlayerinfoTranscation->readAll();
//$num = $stmt->rowCount();

  // PlayerinfoDetails array
    $PlayerinfoDetails_arr=array();
    $PlayerinfoDetails_arr["PlayerInfo"]=array();
	$PlayerinfoDetails_arr["PlayerProgress"]=array();
	$PlayerinfoDetails_arr["PlayerTrans"]=array();
	
if(!empty($PlayerinfoDetails)) 	
{
// check if more than 0 record found    
		array_push($PlayerinfoDetails_arr["PlayerInfo"], str_replace("null","",$PlayerinfoDetails));
		array_push($PlayerinfoDetails_arr["PlayerProgress"], str_replace("null","",$PlayerinfoProgress));
		array_push($PlayerinfoDetails_arr["PlayerTrans"], str_replace("null","",$PlayerinfoTranscation));
    // set response code - 200 OK
    http_response_code(200);
 
    // show products data in json format
   // echo json_encode(array('success'=> 1,'posts'=>$PlayerinfoDetails_arr));
   echo json_encode($PlayerinfoDetails_arr);

 }
// no products found will be here
else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no products found
    echo json_encode($PlayerinfoDetails_arr);
}
?>