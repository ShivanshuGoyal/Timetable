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
	header("Location:classroom.php");
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
	
$colname_rs_classroom = "-1";
if (isset($_POST['building'])) {
  $colname_rs_classroom = $_POST['building'];
}
$colname2_rs_classroom = "-1";
if (isset($_POST['room'])) {
  $colname2_rs_classroom = $_POST['room'];
}
//selecting required database
mysql_select_db($database_conn_time_table, $conn_time_table);
//returns a relation having classroom_id, room and building
$query_rs_classroom = sprintf("SELECT * FROM classroom WHERE building = %s AND room=%s", GetSQLValueString($colname_rs_classroom, "text"),GetSQLValueString($colname2_rs_classroom, "text"));
//perform query
$rs_classroom = mysql_query($query_rs_classroom, $conn_time_table) or die(mysql_error());
//returns a row from the record set
$row_rs_classroom = mysql_fetch_assoc($rs_classroom);
//returns number of rows
$totalRows_rs_classroom = mysql_num_rows($rs_classroom);

if (isset($_POST['building'])) {	
	$emp_id = addslashes($_POST["eid"]);
	$subject_id = addslashes($_POST["sid"]);
	$course_id = addslashes($_POST["cid"]);
	$branch_id = addslashes($_POST["bid"]);
	$classroom_id = addslashes($row_rs_classroom['classroom_id']);
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
    <th width="80">Subject_id</th>
    <th width="129">Course_id</th>
    <th width="133">Branch_id</th>
    <th width="125">Classroom</th>
    <th width="125">Semester</th>
  </tr>
  <tr class="entry">
    <td><?php echo $subject_id; ?></td>
    <td><?php echo $course_id; ?></td>
    <td><?php echo $branch_id; ?></td>
    <td><?php echo $_POST['building']." - ".$_POST['room']; ?></td>
    <td><?php echo $sem; ?></td>
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
		$s = "SELECT * FROM time_table_info WHERE slot_id = $slot_id and classroom_id = $classroom_id";
		//returns a relation having classroom_id, room and building
		$r = "SELECT * FROM classroom WHERE classroom_id = $classroom_id";
		//perform query
		if($resr = mysql_query($r,$conn_time_table))
		{
			//fetches a row at pointed location
			$ansr = mysql_fetch_assoc($resr);
		}
		$i=0;$tag=1;
		$err=0;
		$merg=0;
		if($res = mysql_query($s,$conn_time_table))
		{
			$i=0;
			$ans=mysql_fetch_assoc($res);
			if($ans) $i++;
		}
		//if slot_id is occupied
		if($i)
		{
			//checks whether the coming user is same as the existing user at the slot_id or not
			if($_POST["eid"] != $ans['emp_id'])
			{	
				//sets $err variable to 1
				$err=1;
				$tag=0;
			}
			//if a faculty wants to take classes for two different subjects at a time
			else if($_POST["eid"] == $ans['emp_id'] && $subject_id != $ans['subject_id'])
			{
				$err=1;
				echo "<div class='notification'>";
				echo "You are already taking class for: Subject: ".$subject_id.",  Course: ".$course_id.",  Branch: ".$branch_id."  ,Semester:  ".$sem.",  Day: ".$day[($slot_id-1)/10].", Class: ".$ansr['building']." - ".$ansr['room'];
				echo "<br><br></div>";
			}
			//if the already existing faculty trying to insert the already added subject at the same time slot
			else if($_POST["eid"] == $ans['emp_id'] && $subject_id == $ans['subject_id'])
			{
				$err=1;
				$eql=0;
				$array = mysql_query($s,$conn_time_table);
				while($answ=mysql_fetch_assoc($array))
				{
					//if branch is also same, then entries will be identical
					if($_POST["bid"] == $answ['branch_id'])
					{
						echo "<div class='notification'>";
						echo "Record already Added for: Subject: ".$subject_id.",  Course: ".$course_id.",  Branch: ".$branch_id."  ,Semester:  ".$sem.",  Day: ".$day[($slot_id-1)/10].", Class: ".$ansr['building']." - ".$ansr['room'];
						echo "<br><br></div>";
						$eql=1;
						break;
					}
				}
				//if branches are different, faculty will merge the classes of two branches for a subject
				if(!$eql)
				{
					$merg=1;
					echo "<div class='notification'>";
					echo "You are merging Subject:".$_POST['sid'].", Course: ".$_POST['cid'].", Branch: ".$_POST['bid'].", Semester: ".$_POST['sem']."</br>with</br> already added Subject: ".$subject_id.",  Course: ".$course_id.",  Branch: ".$branch_id."  ,Semester:  ".$sem." on Day: ".$day[($slot_id-1)/10]." in Class: ".$_POST['building']." - ".$_POST['room'];
					echo "<br><br></div>";	
				}
			}
			
		}
		//checks faculty is merging or not
		if($merg)
		{
			//inserts a new row with the coming values in the time_table_info table
			$sqls="INSERT INTO time_table_info
				( `emp_id`, `subject_id`, `course_id`, `branch_id`, `slot_id`, `classroom_id`, `semester`) 
				VALUES
				( '".$emp_id."', '".$subject_id."', '".$course_id."', '".$branch_id."', '".$slot_id."', '".$classroom_id."', '". $sem. "')";
			mysql_query($sqls,$conn_time_table);
		}
		//if slot_id is free
		else if(!$err)
		{
			$sql="INSERT INTO time_table_info
				( `emp_id`, `subject_id`, `course_id`, `branch_id`, `slot_id`, `classroom_id`, `semester`) 
				VALUES
				( '".$emp_id."', '".$subject_id."', '".$course_id."', '".$branch_id."', '".$slot_id."', '".$classroom_id."', '". $sem. "')";
			if(mysql_query($sql,$conn_time_table))
			{
				echo "<div class='notification success'>";
				echo "Record Added Successfully for: Subject: ".$subject_id.",  Course: ".$course_id.",  Branch: ".$branch_id."  ,Semester:  ".$sem.",  Day: ".$day[($slot_id-1)/10].", Class: ".$_POST['building']." - ".$_POST['room'];
				echo "<br><br></div>";
			}	
		}
		//if two different faculties are trying to take the same class at the same time
		else if($err && !$tag)
		{
		?>
			<div class="notification error">
			Time Table Clash for : <?php
			echo "Subject: ".$subject_id.",  Course: ".$course_id.",  Branch: ".$branch_id."  ,Semester:  ".$sem.",  Day: ".$day[($slot_id-1)/10].", Class: ".$_POST['building']." - ".$_POST['room'];
			echo "<br></div>";			
			$flag=false;
		}
  	}
	if($flag)
		echo "<h3><center><div><a href='view_faculty.php'>| VIEW TIMETABLE |</a></div></center></h3>";
	else
		echo "<h3><center><div><a href='classroom.php'>| TRY AGAIN |</a></div></center></h3>";
	//to free up $rs_classroom
	mysql_free_result($rs_classroom);
?>

</div>
</center>
<?php drawFooter(); ?>