<?php
// Call LeaveQuotaTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "LeaveQuotaTest::main");
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once "testConf.php";

define('ROOT_PATH', $rootPath);
define('WPATH', $webPath);
$_SESSION['WPATH'] = WPATH;

require_once "LeaveQuota.php";
require_once ROOT_PATH."/lib/confs/Conf.php";

/**
 * Test class for LeaveQuota.
 * Generated by PHPUnit_Util_Skeleton on 2006-10-19 at 06:19:48.
 */
class LeaveQuotaTest extends PHPUnit_Framework_TestCase {
    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    
    public $classLeaveQuota = null;
    public $connection = null;
    
    public static function main() {
        require_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite("LeaveQuotaTest");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @access protected
     */
    protected function setUp() {
    	$this->classLeaveQuota = new LeaveQuota(); 
    	
    	$conf = new Conf();
    	
    	$this->connection = mysql_connect($conf->dbhost.":".$conf->dbport, $conf->dbuser, $conf->dbpass);
		
        mysql_select_db($conf->dbname);
        
        mysql_query("INSERT INTO `hs_hr_employee` VALUES ('011', 'Arnold', 'Subasinghe', '', 'Arnold', 0, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, '', '', '', '', '0000-00-00', '', NULL, NULL, NULL, NULL, '', '', '', 'AF', '', '', '', '', '', '', NULL, '0000-00-00', '')");
		mysql_query("INSERT INTO `hs_hr_employee` VALUES ('012', 'Mohanjith', 'Sudirikku', 'Hannadige', 'MOHA', 0, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, '', '', '', '', '0000-00-00', '', NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00', NULL)");
		mysql_query("INSERT INTO `hs_hr_employee` VALUES ('020', 'MohanjithX', 'SudirikkuX', 'HannadigeX', 'MOHAX', 0, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, '', '', '', '', '0000-00-00', '', NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00', NULL)");
		mysql_query("INSERT INTO `hs_hr_employee` VALUES ('015', 'Mohanjith1', 'Sudirikku1', 'Hannadige1', 'MOHA1', 0, NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, '', '', '', '', '0000-00-00', '', NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00', NULL)");
		
		mysql_query("INSERT INTO `hs_hr_leavetype` VALUES ('LTY010', 'Medical', 1)");	
		mysql_query("INSERT INTO `hs_hr_leavetype` VALUES ('LTY011', 'Casual', 1)");
		
		mysql_query("INSERT INTO `hs_hr_employee_leave_quota` VALUES ('LTY010', '012', 10);");
		mysql_query("INSERT INTO `hs_hr_employee_leave_quota` VALUES ('LTY011', '012', 20);");		
		mysql_query("INSERT INTO `hs_hr_employee_leave_quota` VALUES ('LTY010', '011', 10);");
		mysql_query("INSERT INTO `hs_hr_employee_leave_quota` VALUES ('LTY011', '011', 20);");
		
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     */
    protected function tearDown() {
    	mysql_query("DELETE FROM `hs_hr_leavetype` WHERE `Leave_Type_ID` = 'LTY010'", $this->connection);
    	mysql_query("DELETE FROM `hs_hr_leavetype` WHERE `Leave_Type_ID` = 'LTY011'", $this->connection);
    	
    	mysql_query("DELETE FROM `hs_hr_employee` WHERE `emp_number` = '011'", $this->connection);
    	mysql_query("DELETE FROM `hs_hr_employee` WHERE `emp_number` = '012'", $this->connection);
    	mysql_query("DELETE FROM `hs_hr_employee` WHERE `emp_number` = '020'", $this->connection);
    	mysql_query("DELETE FROM `hs_hr_employee` WHERE `emp_number` = '015'", $this->connection);
    	
    	mysql_query("DELETE FROM `hs_hr_employee_leave_quota` WHERE `Employee_ID` = '012'", $this->connection);
    	mysql_query("DELETE FROM `hs_hr_employee_leave_quota` WHERE `Employee_ID` = '011'", $this->connection);
    	mysql_query("DELETE FROM `hs_hr_employee_leave_quota` WHERE `Employee_ID` = '020'", $this->connection);
    	mysql_query("DELETE FROM `hs_hr_employee_leave_quota` WHERE `Employee_ID` = '015'", $this->connection);
    	
    	$this->connection = null;
    }
    
    public function testAddLeaveQuotaAccuracy1() {
    	$expected[] = array("LTY010", "Medical", "10");
        $expected[] = array("LTY011", "Casual", "20");  
        
        for ($i=0; $i < count($expected); $i++) {
        	
    		$this->classLeaveQuota->setLeaveTypeId($expected[$i][0]);
    		$this->classLeaveQuota->setNoOfDaysAllotted($expected[$i][2]);
    		
    		$res = $this->classLeaveQuota->addLeaveQuota("020"); 
    		
    		$this->assertNotNull($res, "Addition failed - $i ");
        } 

    	$res = $this->classLeaveQuota->fetchLeaveQuota("020"); 
               
        $this->assertNotNull($res, "No record found ");

        $this->assertEquals(count($res), 2, "Number of records found is not accurate ");  
        
        for ($i=0; $i < count($res); $i++) {
        	$this->assertEquals($res[$i]->getLeaveTypeId(), $expected[$i][0], "Didn't return expected result ");
        	$this->assertEquals($res[$i]->getLeaveTypeName(), $expected[$i][1], "Didn't return expected result ");
        	$this->assertEquals($res[$i]->getNoOfDaysAllotted(), $expected[$i][2], "Didn't return expected result ");
        }
    }

    /**
     * @todo Implement testEditLeaveQuota().
     */    
    public function testEditLeaveQuota() {
    	$expected[] = array("LTY010", "Medical", "15");
        $expected[] = array("LTY011", "Casual", "18");  
        
        for ($i=0; $i < count($expected); $i++) {
        	
    		$this->classLeaveQuota->setLeaveTypeId($expected[$i][0]);
    		$this->classLeaveQuota->setNoOfDaysAllotted($expected[$i][2]);
    		$this->classLeaveQuota->setEmployeeId("015");
    		
    		$res = $this->classLeaveQuota->editLeaveQuota(); 
    		
    		$this->assertNotNull($res, "Didn't add non exsistant record - $i ");
        }
    }
    public function testEditLeaveQuota1() {
    	
        $expected[] = array("LTY010", "Medical", "15");
        $expected[] = array("LTY011", "Casual", "18");  
        
        for ($i=0; $i < count($expected); $i++) {
        	
    		$this->classLeaveQuota->setLeaveTypeId($expected[$i][0]);
    		$this->classLeaveQuota->setNoOfDaysAllotted($expected[$i][2]);
    		$this->classLeaveQuota->setEmployeeId("011");
    		
    		$res = $this->classLeaveQuota->editLeaveQuota(); 
    		
    		$this->assertNotNull($res, "Addition failed - $i ");
        } 

    	$res = $this->classLeaveQuota->fetchLeaveQuota("011"); 
               
        $this->assertNotNull($res, "No record found ");

        $this->assertEquals(count($res), 2, "Number of records found is not accurate ");  
        
        for ($i=0; $i < count($res); $i++) {
        	$this->assertEquals($res[$i]->getLeaveTypeId(), $expected[$i][0], "Didn't return expected result ");
        	$this->assertEquals($res[$i]->getLeaveTypeName(), $expected[$i][1], "Didn't return expected result ");
        	$this->assertEquals($res[$i]->getNoOfDaysAllotted(), $expected[$i][2], "Didn't return expected result ");
        }
    }

    /**
     * @todo Implement testDeleteLeaveQuota().
     */
    /*public function testDeleteLeaveQuota() {
        // Remove the following line when you implement this test.
        $this->markTestIncomplete(
          "This test has not been implemented yet."
        );
    }*/

    /**
     * @todo Implement testFetchLeaveQuota().
     */
    public function testFetchLeaveQuota() {
        $res = $this->classLeaveQuota->fetchLeaveQuota("015");
        
        $this->assertNull($res, "Retured non exsistant record ");            
    }
    
    public function testFetchLeaveQuotaAccuracy() {
    	
        $res = $this->classLeaveQuota->fetchLeaveQuota("012"); 
               
        $this->assertNotNull($res, "No record found ");

        $this->assertEquals(count($res), 2, "Number of records found is not accurate ");
                
        $expected[] = array("LTY010", "Medical", "10");
        $expected[] = array("LTY011", "Casual", "20");                
        
        for ($i=0; $i < count($res); $i++) {
        	$this->assertEquals($res[$i]->getLeaveTypeId(), $expected[$i][0], "Didn't return expected result ");
        	$this->assertEquals($res[$i]->getLeaveTypeName(), $expected[$i][1], "Didn't return expected result ");
        	$this->assertEquals($res[$i]->getNoOfDaysAllotted(), $expected[$i][2], "Didn't return expected result ");
        }    
    }
}

// Call LeaveQuotaTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "LeaveQuotaTest::main") {
    LeaveQuotaTest::main();
}
?>
