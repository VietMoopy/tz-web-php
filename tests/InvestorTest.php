<?php
require_once '../models/Database.php';
require_once '../models/DAOsurfer.php';
require_once '../models/DAOinvestor.php';
require_once '../models/Surfer.php';
require_once '../models/Investor.php';
use PHPUnit\Framework\TestCase;

// We consider you have already tested SurferTest

class InvestorTest extends PHPUnit_Framework_TestCase{
	
	var $db;
	var $params = array(
    "mail" => "vietmoopy",
	"pseudo" => "vietmoopy",
	"name" => "nguyen",
	"firstname" => "alex",
	"passwrd" => "1234",
	"address" => "addr 24"	
	);
	var $surfer;
	var $investor;
	
	public function setUp(){ // First we create a connection to our database and we empty tables
		$this->db = Database::getInstance();
	}
	
	public function test_insertInvestor(){ // We insert a surfer first. Then we insert an investor with the same mail and we test if the return is the same mail that we used to insert 
		$this->surfer = new Surfer($this->params);
		DAOsurfer::insertSurfer($this->surfer);
		$this->investor = new Investor($this->params);
		$resultMail = DAOinvestor::insertInvestor($this->investor);
		$this->assertEquals($this->params['mail'],$resultMail);
	}
		
	 public function test_getInvestorFromMail(){ // We test if the result is a Investor object and if its attributes are the same that we used during the insert
		$result = DAOinvestor::getInvestorFromMail($this->params['mail']);
		$this->assertEquals($this->params['mail'], $result->getMail());
		$this->assertEquals($this->params['address'], $result->getAddress());
	}

	public function test_deleteInvestor(){ // We test when we select the deleted investor if the result is empty when we use getInvestorFromMail
		$result = DAOinvestor::deleteInvestor($this->params['mail']);
		$this->assertEquals($result, true);
	}
	
	public function test_getInvestorList(){ // We insert a fixed number of investor then we test if this number is the same as the number of results
		$sql = "TRUNCATE TABLE Artist";
		$this->db->query($sql);
		$sql = "TRUNCATE TABLE Investor";
		$this->db->query($sql);
		$sql = "TRUNCATE TABLE Surfer";
		$this->db->query($sql);
		for ($i=1; $i<=5; $i++) {
			$this->params['mail']= $i.$this->params['mail'];
			$surferElt = new Surfer($this->params);
			DAOsurfer::insertSurfer($surferElt);
			$investorElt = new Investor($this->params);
			DAOinvestor::insertInvestor($investorElt);
		}
		$result = DAOinvestor::getInvestorList();
		$this->assertCount(5,$result);
	}
	
}
?>
