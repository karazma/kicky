<?php
class Expensedetails{
 
    // database connection and table name
    private $conn;
    private $table_name = "expensedetails";
 
    // object properties
    public $ExpenseID;
    public $Date;
    public $ExpenseType;
    public $MonthOF;
    public $Description;
    public $Amount;
    public $TaxAmount;
	public $NetAmount;
	public $PaidBy;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
		
	// read products
	function read(){ 
    // select all query
	try{
    $query = "SELECT
                a.ExpenseID, a.Date, a.ExpenseType, a.MonthOf, a.Description, a.Discount, a.Amount, a.TaxAmount, a.NetAmount, a.PaidBy
            FROM
                " . $this->table_name . "  a ";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
	}
	catch(PDOException $exception){
            echo "Read error: " . $exception->getMessage();
    } 
	
    return $stmt;
	}
}