<?php
	//including layout, authentication and configuration php files 	
	require_once("../Includes/Layout.php");
	require_once("../Includes/Auth.php");
	require_once("../Includes/ConfigSQL.php");
	auth("stu","ft","hod");

	//initialize the session if it is not initialized
	if (!isset($_SESSION)) {
		session_start();
	}

	//to redirect to logout.php page if user is not logged in
	if (!isset($_SESSION['id'])) {
		header("Location:../Logout.php");
	}
?>
<style type="text/css">
@import url("main.css");
@import url("template.css");
.margin{
margin-left:1%;
margin-right:5%;	
}
</style>

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

$colname_rs_faculty_time_table = "-1";
if (isset($_SESSION['id'])){
  $colname_rs_faculty_time_table = $_SESSION['id'];
}

//to format the expression into string variable
$query_rs_faculty_time_table = sprintf("SELECT * FROM timetable_info_firstyear WHERE section='".$_GET['b']."'");
$query_rs_croom_info = sprintf("SELECT * FROM timetable_info_firstyear, user_details, subjects WHERE timetable_info_firstyear.emp_id=user_details.id AND timetable_info_firstyear.subject_id=subjects.subject_id AND section = %s", GetSQLValueString($_GET['b'], "text"));
//perform query
$rs_faculty_time_table = $mysqli->query($query_rs_faculty_time_table) or die(mysql_error());
$rs_faculty_time_t = $mysql->query($query_rs_croom_info) or die(mysql_error());
//to find the total number of rows in the set
$totalRows_rs_faculty_time_table = $rs_faculty_time_table->num_rows;
if(!$totalRows_rs_faculty_time_table){
	drawNotification("Error","There is no any class exists for given section.","error");
}
else
{
// mapping slots and subject ids
//listing subjects
$lectures=array();
$subjects=array();
while ($row= $rs_faculty_time_t->fetch_assoc()) {
	$lectures[$row['slot_id']]=$row['subject_id'];
	$subject_name[$row['slot_id']]=$row['subject_name'];
	$faculty[$row['slot_id']]=$row['emp_id'];
	$classroom_id= $row['classroom_id'];
}

$query_classroom="SELECT building,room FROM timetable_classrooms WHERE classroom_id='".$classroom_id."'";
$classroom_result = $mysqli->query($query_classroom) or die(mysql_error());
$ans=$classroom_result->fetch_assoc();
echo '
<h3>For Section '.$_GET['b'].' and class '.$ans['building'].' '.$ans['room'].'</h3>
<table  width="90%" height="304" border="1" class="margin">
    <tr class="heading">
								<th width="7%">&nbsp;</th>
								<th width="6%"><p>1</p><p>8:00-8:50</p></th>
								<th width="6%"><p>2</p><p>8:50-9:40</p></th>
								<th width="6%"><p>3</p><p>9:40-10:30</p></th>
								<th width="6%"><p>4</p><p>10:30-11:20</p></th>
								<th width="6%"><p>5</p><p>11:20-12:10</p></th>
								<td width="2%" rowspan="6">&nbsp;</td>
								<th width="6%"><p>6</p><p>1:30-2:20</p></th>
								<th width="6%"><p>7</p><p>2:20-3:10</p></th>
								<th width="6%"><p>8</p><p>3:10-4:00</p></th>
								<th width="6%"><p>9</p><p>4:00-4:50</p></th>
								<th width="6%"><p>10</p><p>4:50-5:40</p></th>
							</tr>';
	
    //an array $day of 5 days
	$day=array("MONDAY","TUESDAY","WEDNESDAY","THURSDAY","FRIDAY");
    //for loop run 5 times, one time for each day
	for ($i=0; $i<5; $i++)
  	{
		echo "<tr>";
		echo "<th>". $day[$i] ."</th>";
		for($j=1; $j<=10; $j++)
		{
			
			echo "<td class='entry'";
			if (array_key_exists($i*10+$j,$lectures))
			{    
				echo  "title='".$faculty[$i*10+$j]."'><center>";
				echo $lectures[$i*10+$j];
				echo "<br>";					
				echo $subject_name[$i*10+$j];
			echo "<div class='hidden_div' style='display:none'>".$faculty[$i*10+$j]."</div>";
			}
			echo "</center></td>";
		}
		echo "</tr>";
  	}
echo '

</table>
</center>
</br>';
echo "<button id='printBtn'>Print</button>";
echo '</div>';
}
?>
<style type="text/css" media="print">
			.print-content {
				display: block;
				width: parent;
				height: auto;
				margin: auto;
				position: relative;
				top: 0;
				left: 0;
			}
		</style>	

</div>