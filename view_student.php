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
//if session auth is not ST, it will redirect to faculty_subject.php
else if($_SESSION["SESS_AUTH"] != "ST" && !isset($_POST['course_id'])){
	header("Location:faculty_subject.php");
}
?>
<div id="heading">
  <h1><u><b><center>Time Table Information System</center></b></u></h1>
</div>

<head>
<style type="text/css">
@import url("main.css");
@import url("template.css");
table tr td, table tr th {
	padding: 1px;
	margin: 0;
}
.margin{
margin-left:22px;
margin-right:22px;
}
#view_table{
background-color: rgb(238,238,238);
margin:-.7px;
margin-left:22px;
margin-right:22px;
}

</style>
</head>
<body>
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
	
$course_rs_std_info = "-1";
if(isset($_POST['course_id'])){
	$course_rs_std_info = $_POST['course_id'];
}
else if (isset($_SESSION['cid'])) {
  $course_rs_std_info = $_SESSION['cid'];
}
$branch_rs_std_info = "-1";
if(isset($_POST['branch_id'])){
	$branch_rs_std_info = $_POST['branch_id'];
}
else if (isset($_SESSION['bid'])) {
  $branch_rs_std_info = $_SESSION['bid'];
}
$semester_rs_std_info = "-1";
if(isset($_POST['semester'])){
	$semester_rs_std_info = $_POST['semester'];
}
else if (isset($_SESSION['sem'])) {
  $semester_rs_std_info = $_SESSION['sem'];
}
//selecting database
mysql_select_db($database_conn_time_table, $conn_time_table);
$query_rs_std_info = sprintf("SELECT * FROM time_table_info, feedback_faculty, feedback_subject, classroom WHERE time_table_info.subject_id=feedback_subject.subject_id AND time_table_info.emp_id=feedback_faculty.emp_id AND course_id=%s AND branch_id=%s  AND semester=%s AND classroom.classroom_id=time_table_info.classroom_id", GetSQLValueString($course_rs_std_info, "text"),GetSQLValueString($branch_rs_std_info, "text"),GetSQLValueString($semester_rs_std_info, "text"));
//perform query
$rs_std_info = mysql_query($query_rs_std_info, $conn_time_table) or die(mysql_error());
//returns total number of rows in the set
$totalRows_rs_std_info = mysql_num_rows($rs_std_info);
//if there is no any row, it will print Wrong Selection
if($totalRows_rs_std_info <1)
{
?>
	<center>
	<div class="notification error" style="margin-right:22px; margin-left:22px;">There is no any class for the given entries</div>
	</center>

<?php
}
else
{
	$subjects=array();
	$teachers=array();
	$subjectnames=array();
	$classroom=array();
	$print_str_subjects=array();
	while($row_std_info=mysql_fetch_assoc($rs_std_info))
	{
		$classroom[$row_std_info['slot_id']]=" (". $row_std_info['building'] ." : ". $row_std_info['room'] .")";
		if(isset($subjects[$row_std_info['slot_id']]))
		{
			$subjects[$row_std_info['slot_id']]=$subjects[$row_std_info['slot_id']].", ".$row_std_info['subject_id'];
			$print_str_subjects[$row_std_info['slot_id']]=$print_str_subjects[$row_std_info['slot_id']].$row_std_info['subject_id']."<br>".$classroom[$row_std_info['slot_id']]."<br>";
		}
		else
		{
			$subjects[$row_std_info['slot_id']]=$row_std_info['subject_id'];
			$print_str_subjects[$row_std_info['slot_id']]=$row_std_info['subject_id']."<br>".$classroom[$row_std_info['slot_id']]."<br>";
		}
		
		$subjectnames[$row_std_info['subject_id']]=$row_std_info['subject_name'];
		$teachers[$row_std_info['subject_id']]=$row_std_info['salutation']." ".$row_std_info['first_name']." ".$row_std_info['middle_name']." ".$row_std_info['last_name'];
		
	}
?>
<div id="view_table">
<center>
<h3><a href="#" onclick="history.go(-1)">GO BACK</a></h3>
<h3><?php if(isset($_POST['branch_id']) && isset($_POST['course_id']) && isset($_POST['semester'])) echo "For ".$_POST['course_id']." ".$_POST['branch_id']." ".$_POST['semester']; else echo "For ".$_SESSION['course_id']." ".$_SESSION['branch_id']." ".$_SESSION['semester'];?></h3>
<table width="95%" height="304" border="1" class="margin">
    <tr class="heading">
								<th width="8%">&nbsp;</th>
								<th width="6%"><p>1</p><p>8:00-8:50</p></th>
								<th width="6%"><p>2</p><p>8:50-9:40</p></th>
								<th width="7%"><p>3</p><p>9:40-10:30</p></th>
								<th width="8%"><p>4</p><p>10:30-11:20</p></th>
								<th width="8%"><p>5</p><p>11:20-12:10</p></th>
								<td width="2%" rowspan="6">&nbsp;</td>
								<th width="6%"><p>6</p><p>1:30-2:20</p></th>
								<th width="6%"><p>7</p><p>2:20-3:10</p></th>
								<th width="6%"><p>8</p><p>3:10-4:00</p></th>
								<th width="6%"><p>9</p><p>4:00-4:50</p></th>
								<th width="6%"><p>10</p><p>4:50-5:40</p></th>
							</tr> 
<?php    
    //$day array of 5 days
	$day=array("MONDAY","TUESDAY","WEDNESDAY","THURSDAY","FRIDAY");
    for ($i=0; $i<5; $i++)
  	{
		echo "<tr>";
		echo "<th>". $day[$i] ."</th>";
		for($j=1; $j<=10; $j++)
		{
			echo "<td class='entry' ";
			if (array_key_exists($i*10+$j,$subjects))
			{
				echo "title='".$subjectnames[$subjects[$i*10+$j]]." (".$teachers[$subjects[$i*10+$j]].")'><center>";
				echo $print_str_subjects[$i*10+$j];
			}
			else echo ">";
			echo "</center></td>";
		}
		echo "</tr>";
  	}
?>

</table>
</br>
<div>
<?php
	mysql_free_result($rs_std_info);
	//print button to take window print
	echo "<button id='printBtn'>Print</button>";

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
				left: 0;m
			}
		</style>	
		<script type="text/javascript">
		var pr = document.getElementById("printBtn");
		$(".print").hide();
		$("#printBtn").click(function() {
			$(".-feedback-search-bar, .-feedback-navbar, .-feedback-footer").hide();
			$(".-feedback-content").addClass("print-content");
			$(".hidden_div").show();
			$(this).hide();
			window.print();
			$(".-feedback-search-bar, .-feedback-navbar, .-feedback-footer").show();
			$(".-feedback-content").removeClass("print-content");
			$(".hidden_div").hide();
			$(this).show();

		});
	</script>	
</div>
<div class="hidden_div" style="display:none">
<?php

$usubs=array_unique($subjects);
foreach ($usubs as $usub)
{
	if(!strstr($usub,","))
	{
		echo $usub ." " . $subjectnames[$usub] ."(" . $teachers[$usub] .")";
		echo "<br />";
	}
}
?>
</div>
</center>
<?php drawFooter(); ?>
</body>
