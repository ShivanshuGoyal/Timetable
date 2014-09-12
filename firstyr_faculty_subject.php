<?php
	//including feedbacklayout php file 	
	require_once("../Includes/SessionAuth.php");
	require_once("../Includes/AuthAllFac.php");
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
?>
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
$colname_rs_faculty_time_table = "-1";
if (isset($_SESSION['SESS_USERNAME'])){
  $colname_rs_faculty_time_table = $_SESSION['SESS_USERNAME'];
}
//selecting required database
mysql_select_db($database_conn_time_table, $conn_time_table);

//to format the expression into string variable
$query_rs_faculty_time_table = sprintf("SELECT * FROM time_table_info_firstyr WHERE emp_id = %s", GetSQLValueString($colname_rs_faculty_time_table, "text"));

//perform query
$rs_faculty_time_table = mysql_query($query_rs_faculty_time_table, $conn_time_table) or die(mysql_error());

//to find the total number of rows in the set
$totalRows_rs_faculty_time_table = mysql_num_rows($rs_faculty_time_table);


// mapping slots and subject ids
//listing subjects
$lectures=array();
$subjects=array();
$classrooms=array();
while ($row= mysql_fetch_assoc($rs_faculty_time_table)) {
	$lectures[$row['slot_id']]=$row['subject_id'];
	$classrooms[$row['slot_id']]=$row['classroom_id'];
	$subjects[]=$row['subject_id'];  
}

//removing duplicate subject_ids
array_unique($subjects);
$subject_str=implode("','",$subjects);

// fetching subject id and names
mysql_select_db($database_conn_time_table, $conn_time_table);
$query_rs_subject = "SELECT * FROM feedback_subject WHERE subject_id IN('" . $subject_str ."')"   ;
$rs_subject = mysql_query($query_rs_subject, $conn_time_table) or die(mysql_error());
$totalRows_rs_subject = mysql_num_rows($rs_subject);


$query_timeslot = "SELECT emp_id from time_table_info_firstyr WHERE emp_id ='".$_SESSION['SESS_USERNAME']."'";
$timeslot = mysql_query($query_timeslot, $conn_time_table) or die(mysql_error());
$totalRows_timeslot = mysql_num_rows($timeslot);

?>
<style type="text/css">
	@import url("template.css");
</style>
<style type="text/css">
@import url("main.css");
table tr td, table tr th {
	padding: 4px;
	margin: 0;
}
.margin{
margin-left:22px;
margin-right:22px;	
}
.view_table{
background-color: rgb(238,238,238);
margin:-13px;
margin-left:22px;
margin-right:22px;
}

</style>
<body>
<div id="heading">
	<h1><u><b><center>Time Table Information System</center></b></u></h1>
</div>
<center>
<div class="view_table">
<h3>
<p><a href="section_select.php">View TimeTable For a Section</a> | <a href="classroom_select.php">View TimeTable For A Classroom</a> | <a href="student_select.php">View TimeTable For A Branch</a></p>
</h3>
<h3>Time Table For You</h3>
<center>
<?php
	if($totalRows_timeslot<1){
	echo '<div class="notification error">There is no any timetable exist for you.</div>';
}
else{
echo '
<table width="90%" height="304" border="1" class="margin">
    <tr class="heading">
								<th width="8%">&nbsp;</th>
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
				mysql_select_db($database_conn_time_table, $conn_time_table);
					$query_rs_classroom = "SELECT * FROM classroom WHERE classroom_id ='" . $classrooms[$i*10+$j] ."'"   ;
					$rs_classroom = mysql_query($query_rs_classroom, $conn_time_table) or die(mysql_error());
					$row_classroom= mysql_fetch_assoc($rs_classroom);
				echo  "title='ClassRoom: ". $row_classroom['building']."-".$row_classroom['room']."'><center>";
				echo $lectures[$i*10+$j];
				echo "<br>";					
					mysql_select_db($database_conn_time_table, $conn_time_table);
					$query_rs_course_branch = "SELECT * FROM time_table_info_firstyr WHERE emp_id ='" . $_SESSION['SESS_USERNAME'] ."' AND slot_id='" . ($i*10+$j) . "'"  ;
					$rs_course_branch = mysql_query($query_rs_course_branch, $conn_time_table) or die(mysql_error());
					
					while ($row_course_branch= mysql_fetch_assoc($rs_course_branch)) {
						echo $row_course_branch['sec'];
						echo "<br>";
					}
				}
			echo "</center></td>";
		}
		echo "</tr>";
  	}
echo '

</table>
</br>
<button id="printBtn">Print</button>
</center>';

echo '</div>';
 if($totalRows_rs_subject)
{
echo '
<center>
<div style="margin:12px; 	color: #009;">';

		mysql_free_result($rs_faculty_time_table); 
		//Showing subject_id and subject_name mapping
		while ($sub= mysql_fetch_assoc($rs_subject)) {
			echo $sub['subject_id'] . "    " . $sub['subject_name'];
			echo "<br />";
		}
		//to free up the $rs_subject
		mysql_free_result($rs_subject);
		//print button to take the print of the page
		
	echo "</center>";
}
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
		<script type="text/javascript">
		var pr = document.getElementById("printBtn");
		$("#printBtn").click(function() {
			$(".-feedback-search-bar, .-feedback-navbar, .-feedback-footer").hide();
			$(".-feedback-content").addClass("print-content");
			$(this).hide();
			window.print();
			$(".-feedback-search-bar, .-feedback-navbar, .-feedback-footer").show();
			$(".-feedback-content").removeClass("print-content");
			$(this).show();

		});
	</script>	

</div>
</center>
<?php drawFooter(); ?>
</body>
