<?php
class Playerprogress {

	//database connection and table name
	private $conn; 	
	private $table_name = 'playerprogress'; 
	
	 // object properties
	public $PlayerID;
	public $ReviewDate;
	public $Height;
	public $Weight;
	Public $ShoulderSize;
	public $HipSize;
	public $ProgressPercent;
	Public $CoachRemarks;
	Public $HeadCoachRemarks;
		
	// // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
	
	// read products
	function readAll(){ 
    // select all query
	$PlayerinfoDetails_item = array();
	try{
    $query = 	"SELECT PlayerID, ReviewDate, Height, Weight, ShoulderSize , HipSize,
				ProgressPercent, CoachRemarks, HeadCoachRemarks 
				FROM  " . $this->table_name . "  a ";
	
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // execute query
    $stmt->execute();
	
		// retrieve our table contents
		// fetch() is faster than fetchAll()
		// http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
	
        $PlayerinfoDetails_item=array(
            "PlayerID" => $PlayerID,
            "ReviewDate" => $ReviewDate,
            "Height" => $Height,
            "Weight" => $Weight,
            "ShoulderSize" => $ShoulderSize,
            "HipSize" => $HipSize,
			"ProgressPercent" => $ProgressPercent,
			"CoachRemarks" => $CoachRemarks,			
			"HeadCoachRemarks" => $HeadCoachRemarks,					
			);
		}
	}
	catch(PDOException $exception){
            echo "Read error: " . $exception->getMessage();
    } 
    return $PlayerinfoDetails_item;
	}
	
		
	//Create and Update 
	public create ($PageAction)
	{
		try{
			if($PageAction == "Add")
			{
			$query = 	"INSERT INTO 
						".this->table_name"
						SET PlayerID=:PlayerID, ReviewDate=:ReviewDate, Height=:Height, Weight=:Weight, ShoulderSize=:ShoulderSize
						HipSize=:HipSize, ProgressPercent=:ProgressPercent, CoachRemarks=:CoachRemarks, HeadCoachRemarks=:HeadCoachRemarks";
			}
			else
			{				
			$query = 	"UPDATE
						".this->table_name"
						SET Height=:Height, Weight=:Weight, ShoulderSize=:ShoulderSize
						HipSize=:HipSize, ProgressPercent=:ProgressPercent, CoachRemarks=:CoachRemarks, HeadCoachRemarks=:HeadCoachRemarks
						WHERE PlayerID=:PlayerID and ReviewDate=:ReviewDate";
			}
			$stmt = $this->conn->prepare($query);
			
			$stmt->bindParam(":PlayerID", $this->PlayerID);
			$stmt->bindParam(":Sno", $this->Sno);
			$stmt->bindParam(":PaymentDate", $this->PaymentDate);
			$stmt->bindParam(":PaymentMode", $this->PaymentMode);
			$stmt->bindParam(":PaymentType", $this->PaymentType);
			$stmt->bindParam(":Amount", $this->Amount);
			$stmt->bindParam(":Remarks", $this->Remarks);
			$stmt->bindParam(":ReceivedBy", $this->ReceivedBy);
			
				// execute query
				if($stmt->execute()){
					return true;
				}
		}
		catch(Exception $exception)
		{
			echo "Read error: " . $exception->getMessage();
		}
	}
	
}
?>