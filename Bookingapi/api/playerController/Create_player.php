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
include_once '../objects/playerinfo.php';
include_once '../objects/playercoaching.php';
include_once '../objects/PlayerTransaction.php';
include_once '../objects/Playerprogress.php';
 
$database = new Database();
$db = $database->getConnection();

// initialize object
$PlayerinfoDetails = new Playerinfo($db);
$PlayerinfoCoaching = new Playercoaching($db);
$PlayerinfoProgress = new Playerprogress($db);
$PlayerinfoTransaction= new PlayerTransaction($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"), true);

$db->beginTransaction();

$Mode = (object) $data["Mode"][0];
//$PlayerinfoDetails = $data["PlayerDetails"][0]; 

//Page Header Save section
if($Mode->PageMode == "Add")
{	
	$dataplayer= (object) $data["playerDetails"][0];
	$datacoaching= (object) $data["CoachingDetails"][0];
	// make sure data is not empty
	if(!empty($dataplayer->Name)){
    // set product property values
	$PlayerinfoDetails->PlayerID = ($Mode->PageAction == "Add") ? $PlayerinfoDetails->count() : $dataplayer->PlayerID;
    $PlayerinfoDetails->Name = $dataplayer->Name;
    $PlayerinfoDetails->AGE = $dataplayer->AGE;
    $PlayerinfoDetails->Gender = $dataplayer->Gender;
	$PlayerinfoDetails->BloodGroup = $dataplayer->BloodGroup;
    $PlayerinfoDetails->Status = $dataplayer->Status;
    $PlayerinfoDetails->FatherName = $dataplayer->FatherName;
    $PlayerinfoDetails->MotherName = $dataplayer->MotherName;
    $PlayerinfoDetails->Grade = $dataplayer->Grade;
	$PlayerinfoDetails->Phone1 = $dataplayer->Phone1;
    $PlayerinfoDetails->Phone2 = $dataplayer->Phone2;
	$PlayerinfoDetails->Phone3 = $dataplayer->Phone3;
    $PlayerinfoDetails->Email1 = $dataplayer->Email1;
    $PlayerinfoDetails->Email2 = $dataplayer->Email2;
    $PlayerinfoDetails->Address = $dataplayer->Address;
	$PlayerinfoDetails->Institution = $dataplayer->Institution;
	$PlayerinfoDetails->DOB = $dataplayer->DOB;	
    $PlayerinfoDetails->DOJ = $dataplayer->DOJ;
	try 
	{	
		// create the product
		//echo $PlayerinfoDetails->PlayerID;
		if($PlayerinfoDetails->create($Mode->PageAction)){
		$PlayerinfoCoaching->PlayerID = $PlayerinfoDetails->PlayerID;
		$PlayerinfoCoaching->PackageType = $datacoaching->PackageType;
		$PlayerinfoCoaching->PackageAmount = $datacoaching->PackageAmount;
		$PlayerinfoCoaching->Coaching = $datacoaching->Coaching;
		$PlayerinfoCoaching->CoachingSlot = $datacoaching->CoachingSlot;
		$PlayerinfoCoaching->HealthDetails = $datacoaching->HealthDetails;
		$PlayerinfoCoaching->Special = $datacoaching->Special;
		$PlayerinfoCoaching->NoOfDays = $datacoaching->NoOfDays;
		//make sure Coaching data is not empty
		if(!empty($datacoaching->Coaching)){
		// set product property values		
		
			//echo $PlayerinfoDetails->PackageType;
			// run your code here
			if($PlayerinfoCoaching->create($Mode->PageAction)){
					$db->commit(); //**** Commit Transaction			
					// set response code - 201 created
					http_response_code(201);
					// tell the user
					echo json_encode(array("PlayerID" => $PlayerinfoDetails->PlayerID));
			}	
		}
		
	
	 // if unable to create the product, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
        echo json_encode(array("message" => "Unable to create product."));
		}
	}
 }
	catch(Exception $ex)
	{
		echo $ex->getMessage();
	}			 
   
}
 
// tell the user data is incomplete
else{
	$dataprogress = (object) $data["playerProgress"][0]; 
	if($Mode->PageType == "Progress")
	{			
		// set Progress property values
		$PlayerinfoProgress->PlayerID = $dataprogress->PlayerID;
		$PlayerinfoProgress->ReviewDate = $dataprogress->ReviewDate;
		$PlayerinfoProgress->Height = $dataprogress->Height;
		$PlayerinfoProgress->Weight = $dataprogress->Weight;
		$PlayerinfoProgress->ShoulderSize = $dataprogress->ShoulderSize;
		$PlayerinfoProgress->HipSize = $dataprogress->HipSize;
		$PlayerinfoProgress->ProgressPercent = $dataprogress->ProgressPercent;
		$PlayerinfoProgress->CoachRemarks = $dataprogress->CoachRemarks;
		$PlayerinfoProgress->HeadCoachRemarks = $dataprogress->HeadCoachRemarks;
		if($PlayerinfoProgress->create($Mode->PageAction))
		{
			http_response_code(200);
			// tell the user
			echo json_encode(array("message" => "success");
		}
		else
		{
			// set response code - 404 
			http_response_code(404);
 
			// tell the user
			echo json_encode(array("message" => "Table Error"));
		}
	}
	else
	{
		// set Progress property values
		$PlayerinfoTransaction->PlayerID = $dataprogress->PlayerID;
		$PlayerinfoTransaction->Sno = $dataprogress->Sno;
		$PlayerinfoTransaction->PaymentDate = $dataprogress->PaymentDate;
		$PlayerinfoTransaction->PaymentMode = $dataprogress->PaymentMode;
		$PlayerinfoTransaction->Amount = $dataprogress->Amount;
		$PlayerinfoTransaction->ReceivedBy = $dataprogress->ReceivedBy;
		$PlayerinfoTransaction->Remarks = $dataprogress->Remarks;
		$PlayerinfoTransaction->PaymentType = $dataprogress->PaymentType;
		
		if($PlayerinfoTransaction->create($Mode->PageAction)
		{
			http_response_code(200);
					// tell the user
			echo json_encode(array("message" => "success"));
		}
		else
		{
			// set response code - 404 
			http_response_code(404);
 
			// tell the user
			echo json_encode(array("message" => "Table Error"));
		}
	}
	
    
	}
}
//For Transaction Save Section
else{
		
} 
?>