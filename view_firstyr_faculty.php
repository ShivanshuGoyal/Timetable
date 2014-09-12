<?php
	//including layout, authentication and configuration php files 	
	require_once("../Includes/Layout.php");
	require_once("../Includes/Auth.php");
	require_once("../Includes/ConfigSQL.php");
	auth("ft");
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

<style type="text/css">
@import url("template.css");
</style>
<style type="text/css">
@import url("main.css");
table tr td, table tr th {
	padding: 3px;
	margin: 0;
}
.margin{
margin-left:22px;
margin-right:22px;	
}
.view_table{
background-color: rgb(238,238,238);
margin:-.7px;
margin-left:22px;
margin-right:22px;
}

</style>

<div id="heading">
	<h1><u><b><center>Time Table Information System</center></b></u></h1>
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
$query_timeslot = "SELECT * from timetable_info_firstyear WHERE emp_id ='".$_SESSION['id']."'";
$timeslot = $mysqli->query($query_timeslot) or die(mysql_error());
$totalRows_timeslot = $timeslot->num_rows;

?>
	<div class="view_table">
<center>
<?php
	if($totalRows_timeslot<1){
	drawNotification("Error","There is no any timetable exist for you.","error");
}
else{

while ($row= $mysqli->fetch_assoc($timeslot)) {
	$lectures[$row['slot_id']]=$row['subject_id'];
	$classrooms[$row['slot_id']]=$row['classroom_id'];
	$subjects[]=$row['subject_id'];  
}
//removing duplicate subject_ids
array_unique($subjects);
$subject_str=implode("','",$subjects);

$query_rs_subject = "SELECT * FROM subjects WHERE subject_id IN('" . $subject_str ."')"   ;
$rs_subject = $mysqli->query($query_rs_subject) or die(mysql_error());
$totalRows_rs_subject = $rs_subject->num_rows;

echo "<h3><a href='faculty_subject.php'>GO BACK</a></h3>";
echo "<h3>Time Table For You</h3>";
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
					$query_rs_classroom = "SELECT * FROM timetable_classrooms WHERE classroom_id ='" . $classrooms[$i*10+$j] ."'"   ;
					$rs_classroom = $mysqli->query($query_rs_classroom) or die(mysql_error());
					$row_classroom= $rs_classroom->fetch_assoc();
				echo  "title='ClassRoom: ". $row_classroom['building']."-".$row_classroom['room']."'><center>";
				echo $lectures[$i*10+$j];
				echo "<br>";					
					$query_rs_course_branch = "SELECT * FROM timetable_info_firstyear WHERE emp_id ='" . $_SESSION['id'] ."' AND slot_id='" . ($i*10+$j) . "'"  ;
					$rs_course_branch = $mysqli->query($query_rs_course_branch) or die(mysql_error());
					
					while ($row_course_branch= $rs_course_branch->fetch_assoc()) {
						echo $row_course_branch['section'];
						echo "<br>";
					}
				echo "<div class='hidden_div' style='display:none'>ClassRoom: ". $row_classroom['building']."-".$row_classroom['room']."</div>";
				}
			echo "</center></td>";
		}
		echo "</tr>";
  	}
echo '

</table>
</center>
</br>';

echo '</div>';
 if($totalRows_rs_subject)
{
echo '
<center>
<div>';

		//mysql_free_result($timeslot); 
		//Showing subject_id and subject_name mapping
		while ($sub= $mysqli->fetch_assoc($rs_subject)) {
			echo $sub['subject_id'] . "    " . $sub['subject_name'];
			echo "<br />";
		}
		//print button to take the print of the page
echo "<button id='printBtn'>Print</button>";
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
</center>
<?php	
//to draw footer
drawFooter(); 
?>