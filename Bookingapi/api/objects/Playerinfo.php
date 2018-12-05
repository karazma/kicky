<?php
class Playerinfo{
 
    // database connection and table name
    private $conn;
    private $table_name = "playerinfo";
	
    // object properties
    public $PlayerID;
    public $Name;
    public $DOB;
    public $AGE;
    public $Gender;
    public $BloodGroup;
    public $Height;
	public $Weight;
    public $DOJ;
	public $FatherName;
	public $MotherName;
	public $Phone1;
	public $Phone2;
	public $Phone3;
	public $Email1;
	public $Email2;
	public $Address;
	public $Institution;
	public $Grade;
	public $Status;
	public $PaymentStatus;
	public $EntryDate;
	
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
		
	// read products
	function readAll(){ 
    // select all query
	$Result=array();
	try{		
		$query = "SELECT PlayerID, Name, AGE , Gender, BloodGroup , Status, DOB, DOJ, Grade, FatherName, MotherName
			,Phone1, Phone2, Phone3, Email1, Email2, Address, Institution, EntryDate	FROM
                " . $this->table_name . "  a ";
	
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
            "Name" => $Name,
            "AGE" => $AGE,
            "DOB" => $DOB,
            "Gender" => $Gender,
            "BloodGroup" => $BloodGroup,
			"DOJ" => $DOJ,
			"FatherName" => $FatherName,			
			"MotherName" => $MotherName,			
			"Phone1" => $Phone1,			
			"Phone2" => $Phone2,			
			"Phone3" => $Phone3,			
			"Email1" => $Email1,			
			"Email2" => $Email2,			
			"Address" => $Address,			
			"Institution" => $Institution,			
			"Grade" => $Grade,
			"Status" => $Status,
			"EntryDate" => $EntryDate,			
			);
			
			array_push($Result, $PlayerinfoDetails_item);
		}
	}
	catch(PDOException $exception){
            echo "Read error: " . $exception->getMessage();
    } 
	
    return $Result;
	}
	
	// get PalyerId by increment depend upon category
	public function count(){
    $query = "(SELECT  IFNULL(CONCAT( 'C000' , (SELECT CAST((SUBSTRING_INDEX(playerid, 'C', -1) + 1) AS CHAR)  
			FROM   " . $this->table_name . "  WHERE PlayerID LIKE 'C%' ORDER BY PLAYERID DESC LIMIT  1)), 'C0001') as PlayerID )";
 
    $stmt = $this->conn->prepare( $query );
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['PlayerID'];
	}
	
	// Create and Update the Player Details
	function create($Pageaction){
	//$s = microtime(true);
	//print_r($Action);
	try
	{
		if($Pageaction == "Add")
		{
		 // query to insert record
		$query = "INSERT INTO
                " . $this->table_name . "
            SET
                PlayerID =:PlayerID , Name =:Name, AGE =:AGE, Gender =:Gender, BloodGroup=:BloodGroup,Institution=:Institution,
				Status=:Status,  DOB=:DOB, DOJ=:DOJ, Grade=:Grade, FatherName=:FatherName, MotherName=:MotherName, Phone1=:Phone1, 
				Phone2=:Phone2, Phone3=:Phone3, Email1=:Email1, Email2=:Email2, Address=:Address, EntryDate =  NOW()";	 
				// , Institution=:Institution,  PackageType:PackageType,
				//, Status=:Status, PaymentStatus=:PaymentStatus ";
				//;
		}
		else
		{
			 // query to Update
			 $query = 	"UPDATE
						" . $this->table_name . "
						SET Name =:Name, AGE =:AGE, Gender =:Gender, BloodGroup=:BloodGroup,Institution=:Institution,
						Status=:Status,  DOB=:DOB, DOJ=:DOJ, Grade=:Grade, FatherName=:FatherName, MotherName=:MotherName, Phone1=:Phone1, 
						Phone2=:Phone2, Phone3=:Phone3, Email1=:Email1, Email2=:Email2, Address=:Address
						WHERE PlayerID =:PlayerID";
		
		}		
 
		// prepare query
		$stmt = $this->conn->prepare($query);
		// bind values
		$stmt->bindParam(":PlayerID", $this->PlayerID);
		$stmt->bindParam(":Name", $this->Name);
		$stmt->bindParam(":AGE", $this->AGE);    
		$stmt->bindParam(":Gender", $this->Gender);
		$stmt->bindParam(":BloodGroup", $this->BloodGroup);
		$stmt->bindParam(":Status", $this->Status);
		$stmt->bindparam(":FatherName", $this->FatherName);
		$stmt->bindparam(":MotherName", $this->MotherName);
		$stmt->bindparam(":Grade", $this->Grade);
		$stmt->bindparam(":Phone1", $this->Phone1);
		$stmt->bindparam(":Phone2", $this->Phone2);
		$stmt->bindparam(":Phone3", $this->Phone3);
		$stmt->bindparam(":Email1", $this->Email1);
		$stmt->bindparam(":Email2", $this->Email2);
		$stmt->bindparam(":Address", $this->Address);	
		$stmt->bindparam(":Institution", $this->Institution);	
		$stmt->bindParam(":DOB", $this->DOB);	
		$stmt->bindParam(":DOJ", $this->DOJ);
	
		// execute query
		if($stmt->execute()){
			// $e =microtime(true);
			// echo ($e-$s);
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