<?php
	//including layout, authentication and configuration php files 	
	require_once("../Includes/Layout.php");
	require_once("../Includes/Auth.php");
	require_once("../Includes/ConfigSQL.php");
	auth("hod");
 
	//to draw header drawHeader() is calling
	drawHeader("TimeTable Info System"); 

//initialize the session if it is not initialized
if (!isset($_SESSION)) {
  session_start();
}

//to redirect to logout.php page if user is not logged in
if (!isset($_SESSION['id']) || !isset($_POST['time_slot'])) {
	header("Location:../Logout.php");
}
?>
<div id="heading">
	<p><h1><u><b><center>Time Table Information System</center></b></u></h1></p>
</div>
<?php
// a function used to get value from strings
if (!function_exists("GetSQLValueString")) {
		function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
			if (PHP_VERSION < 6) {
				$theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
			}

			$theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

			switch ($theType) {
				case "text": $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL"; break;    
				case "long":
				case "int": $theValue = ($theValue != "") ? intval($theValue) : "NULL"; break;
				case "double": $theValue = ($theValue != "") ? doubleval($theValue) : "NULL"; break;
				case "date": $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL"; break;
				case "defined": $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue; break;
			}
			return $theValue;
		}
	}
?>	
<style type="text/css">
@import url("main.css");
@import url("template.css");
</style>
<?php	
	
	//assign posted value of time_slot to $slots array
	$slots=$_POST['time_slot'];
	//loop for each slot
	foreach ($slots as $slot_id){
		$sql="DELETE FROM timetable_info where slot_id='".$slot_id."' and course_id='".$_POST['course']."' and branch_id='".$_POST['branch']."' and section='".$_POST['section']."' and semester='".$_POST['semester']."' and session='".$_POST['session']."'";
		//print $sql;
		//checks whether the query is performed or not
		if(!$mysqli->query($sql)){
        	echo "query:" . $sql;	
		}
  	}
	//to redirect to view_faculty.php
	header("location: index.php?notification='Edited'&content='The deleted timeslots are available now'&type='success'");
?>

</div>
</center>
<?php 
//to draw footer
drawFooter(); 
?>