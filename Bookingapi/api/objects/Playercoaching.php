<?php
class Playercoaching {
//class Playercoaching { 

	//database connection and table name
	private $conn; 	
	private $table_name = 'playercoachingdetails'; 
	
	 // object properties
	public $PlayerID;
	public $PackageType;
	public $PackageAmount;
	public $Coaching;
	Public $CoachingSlot;
	public $HealthDetails;
	public $Special;
	Public $NoOfDays;
		
	// // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }	
	
	// Create and Update Coach Details
	public function create($Pageaction){
	try{
		if($Pageaction == "Add")
		{
			// query to insert record
		$query = "INSERT INTO
                " . $this->table_name . "
            SET PlayerID =:PlayerID, PackageType =:PackageType, PackageAmount =:PackageAmount
			,Coaching =:Coaching,CoachingSlot =:CoachingSlot, HealthDetails =:HealthDetails
			,NoOfDays =:NoOfDays, Special =:Special";
		}
		else
		{
				// query to Update record
		$query = "UPDATE
                " . $this->table_name . "
				SET PackageType =:PackageType, PackageAmount =:PackageAmount
				,Coaching =:Coaching,CoachingSlot =:CoachingSlot, HealthDetails =:HealthDetails
				,NoOfDays =:NoOfDays, Special =:Special
				WHERE PlayerID =:PlayerID";		
		}
		// prepare query
		$stmt = $this->conn->prepare($query);
 
		// bind values
		$stmt->bindParam(":PlayerID", $this->PlayerID);
		$stmt->bindParam(":PackageType", $this->PackageType);
		$stmt->bindparam(":PackageAmount", $this->PackageAmount);    
		$stmt->bindparam(":Coaching", $this->Coaching);
		$stmt->bindparam(":CoachingSlot", $this->CoachingSlot);
		$stmt->bindparam(":HealthDetails", $this->HealthDetails);
		$stmt->bindparam(":Special", $this->Special);
		$stmt->bindparam(":NoOfDays", $this->NoOfDays);
	
		// execute query
		if($stmt->execute()){
			return true;
		}
	}
	catch(PDOException $exception){
            echo "Read error: " . $exception->getMessage();
    } 		
		return false;    
	}
}
?>
