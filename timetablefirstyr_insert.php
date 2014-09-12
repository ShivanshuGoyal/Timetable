<?php
	//including feedbacklayout php file 	
	require_once("../Includes/FeedbackLayout.php");
 
	//to draw header drawHeader() is calling
	drawHeader("TimeTable Info System"); 

//initialize the session if it is not initialized
if (!isset($_SESSION)) {
  session_start();
}

//to redirect to logout.php page if user is not logged in
if (!isset($_SESSION['SESS_USERNAME'])) {
	header("Location:../Logout.php");
}
//to redirect to classroom.php if user is logged in but it doesn't have posted value of time_slot
else if(isset($_SESSION['SESS_USERNAME']) && !isset($_POST['time_slot'])){
	header("Location:dataoperator_timetable.php");
}

?>
<div id="heading">
	<h1><u><b><center>Time Table Information System</center></b></u></h1>	
</div>
<div class="class_room">
<center><h3><a href="#" onclick="history.go(-1)">GO BACK</a></h3></center>
<?php
//to include conn_time_table.php to make connection with database
require_once('Connections/conn_time_table.php');

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

//selecting required database
mysql_select_db($database_conn_time_table, $conn_time_table);
$query_classroom="SELECT * FROM classroom where classroom_id='".$_POST['classroomid']."'";
$result_classroom=mysql_query($query_classroom);
$row_classroom=mysql_fetch_assoc($result_classroom);

if (isset($_POST['faculty'])) {	
	$faculty = addslashes($_POST["faculty"]);
	$subject = addslashes($_POST["subject"]);
	$sec = addslashes($_POST["sec"]);
	$semtype = addslashes($_POST["semtype"]);
	$sem = addslashes($_POST["sem"]);
}		
?>
<style type="text/css">
@import url("main.css");
@import url("template.css");
.class_room{
background-color: rgb(238,238,238);
margin:-.7px;
margin-left:22px;
margin-right:22px;
}

.class_room table tr td {
text-align:center;
}
</style>
	
<center>

<h2><b><u>List Of Subjects Taught By The Faculty</u></b></h2><br>
<table width="626" border="1">
  <tr class="heading">
    <th width="80">Faculty</th>
    <th width="129">Subject</th>
    <th width="133">Section</th>
	<th width="125">ClassRoom</th>
    <th width="125">Semester</th>
    <th width="125">Semester Type</th>
  </tr>
  <tr class="entry">
    <td><?php echo $faculty; ?></td>
    <td><?php echo $subject; ?></td>
    <td><?php echo $sec; ?></td>
    <td><?php echo $row_classroom['building']." ".$row_classroom['room']; ?></td>
	<td><?php echo $sem; ?></td>
	<td><?php echo $semtype; ?></td>
  </tr>
</table>
<br />
<?php	
	$flag=true;
	$slots=$_POST['time_slot'];
	//array $day of 5 days
	$day=array("Monday","Tuesday","Wednesday","Thursday","Friday");
	foreach ($slots as $slot_id)
  	{
		//returns a relation having info_id, emp_id, subject_id, branch_id, course_id, slot_id, classroom_id and semester
		$s = "SELECT * FROM time_table_info_firstyr WHERE slot_id = '".$slot_id."' and classroom_id = '".$_POST['classroomid']."'";
		$i=0;
		$r="select * from time_slot where slot_id='".$slot_id."'";
		$result_r=mysql_query($r);
		$row_r=mysql_fetch_assoc($result_r);
		
		if($res = mysql_query($s,$conn_time_table))
		{
			$i=0;
			$ans=mysql_fetch_assoc($res);
			if($ans) $i++;
		}
		//if slot_id is occupied
		if($i)
		{	
			echo "<div class='notification error'>".$row_r['day']." (".$row_r['start_time']."-".$row_r['end_time'].") slot is already occupied by emp_id (".$ans['emp_id'].")</div>";	
		}
		else
		{
				$perform="INSERT INTO `time_table_info_firstyr` (`emp_id`, `subject_id`, `sec`, `classroom_id`, `slot_id`, `semester`, `sem_type`) VALUES ('".$faculty."','".$subject."','".$sec."','".$_POST['classroomid']."','".$slot_id."','".$sem."','".$semtype."')";
				if(mysql_query($perform))
				echo "<div class='notification success'>TimeTable for the given slot ".$row_r['day']." (".$row_r['start_time']."-".$row_r['end_time'].") has been successfully inserted.</div>";
		}
	}
	echo "<h3><center><div><a href='dataoperator_timetable.php'>| HOME |</a></div></center></h3>";	
	?>

</div>
</center>
<?php drawFooter(); ?>