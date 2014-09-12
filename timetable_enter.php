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
if (!isset($_SESSION['id'])) {
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
	//to know the classroomid
	$classroom_id_query="select classroom_id from timetable_classroom_classes where course_id='".$_POST['course']."' and branch_id='".$_POST['branch']."' and semester='".$_POST['semester']."' and section='".$_POST['section']."'";
	$classroom_id_ans=$mysqli->query($classroom_id_query);
	$row_classroom_id=$classroom_id_ans->fetch_assoc();
	$classroom_id=$row_classroom_id['classroom_id'];

	for($i=1;$i<=50;$i++){
		if(isset($_POST['subject'.$i])){
			$subject=$_POST['subject'.$i];
			$faculty=$_POST['faculty'.$i];
			
			$sql="insert into timetable_info Values('".$faculty."','".$subject."','".$_POST['course']."','".$_POST['branch']."','".$i."','".$classroom_id."','".$_POST['semester']."','".$_POST['section']."','".$_POST['session']."')";	
			if(!$mysqli->query($sql)){
        	echo "query:" . $sql;	
			}
		}
	}
	//to redirect to view_faculty.php
	header("location: index.php?notification='Added'&content='The added timeslots are occupied now'&type='success'");
?>

</div>
</center>
<?php 
//to draw footer
drawFooter(); 
?>