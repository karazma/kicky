<?php
// required headers
//header("Access-Control-Allow-Origin: *");
// header("Content-Type: application/json; charset=UTF-8");
//header("Access-Control-Allow-Methods: POST");
// header("Access-Control-Max-Age: 3600");
// header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//Feature Method of Cross Origin
//http://stackoverflow.com/questions/18382740/cors-not-working-php
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

//http://stackoverflow.com/questions/15485354/angular-http-post-to-php-and-undefined
$postdata = file_get_contents("php://input");
 
// get database connection
include_once '../config/database.php';
 
// instantiate product object
include_once '../objects/playercoaching.php';
 
$database = new Database();
$db = $database->getConnection();

// initialize object
$PlayerinfoDetails = new Playercoaching($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"), true);

$db->beginTransaction();
$dataplayer= (object) $data["playerDetails"][0];
$datacoaching= (object) $data["CoachingDetails"][0];
//$PlayerinfoDetails = $data["PlayerDetails"][0]; 
echo $datacoaching->PlayerID;
// make sure data is not empty
if(!empty($datacoaching->Coaching)){
    // set product property values
		$PlayerinfoDetails->PlayerID = $datacoaching->PlayerID;
		$PlayerinfoDetails->PackageType = $datacoaching->PackageType;
		$PlayerinfoDetails->PackageAmount = $datacoaching->PackageAmount;
		$PlayerinfoDetails->Coaching = $datacoaching->Coaching;
		$PlayerinfoDetails->CoachingSlot = $datacoaching->CoachingSlot;
		$PlayerinfoDetails->HealthDetails = $datacoaching->HealthDetails;
		$PlayerinfoDetails->Special = $datacoaching->Special;
		$PlayerinfoDetails->NoOfDays = $datacoaching->NoOfDays;
		//make sure Coaching data is not empty		
			// run your code here
			try{
				if($PlayerinfoDetails->create()){
				$db->commit(); //**** Commit Transaction			
				// set response code - 201 created
				http_response_code(201);
				// tell the user
				echo json_encode(array("PlayerID" => $PlayerinfoDetails->PlayerID));
				}	
			 // if unable to create the product, tell the user
				else{
				// set response code - 503 service unavailable
				http_response_code(503); 
				// tell the user
				echo json_encode(array("message" => "Unable to create product."));
				}
			}
			catch(Exception $ex)
			{
				echo $ex->getMessage();
			}	 
} 
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to create product. Data is incomplete."));
}
 
?>