<?php
	//including layout, authentication and configuration php files 	
	require_once("../Includes/Layout.php");
	require_once("../Includes/Auth.php");
	require_once("../Includes/ConfigSQL.php");
	auth("stu");
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
	
//assigning user session value to $sid variable
if (isset($_SESSION['id'])) {
  $sid = $_SESSION['id'];
}

//to format the expression into string variable
$query_rs_student = sprintf("SELECT * FROM user_details,stu_academic,branches,courses where user_details.id=%s AND user_details.id=stu_academic.id AND stu_academic.branch_id=branches.id AND stu_academic.course_id=courses.id, GetSQLValueString($sid, "text")");

//performing query
$rs_student = $mysqli->query($query_rs_student) or die($mysqli->error());

//each call returns a row from the recordset given by the result of mysql_query() i.e. $rs_student
$row_rs_student = $rs_student->fetch_assoc();

//to find the total number of rows in the set
$totalRows_rs_student = $mysqli->num_rows($rs_student);
?>

<style type="text/css">
@import url("template.css");
@import url("main.css");
.list_table {
	color: #000;
	background-color: rgb(238,238,238);
	margin: 5px;
	margin-left:22px;
	margin-right:22px;
	padding: 2px;
}
.list_table table {
	background-color: #fff;
	margin: 2px;
	padding: 2px;
	
}
.list_table table tr td {
text-align:center;
}
table tr td, table tr th {
	padding: 4px;
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

<body>
<div id="heading">
	<h1><u><b><center>Time Table Information System</center></b></u></h1>
</div>

<?php
$query_firstyr_student="select * From ug_commsemesterlog, timetable_classroom, timetable_classroom_firstyear where ug_commsemesterlog.stu_id='".$_SESSION['id']."' AND ug_commsemesterlog.section=timetable_classroom_firstyear.section AND timetable_classroom.classroom_id=timetable_classroom_firstyear.classroom_id";

$result_firstyr_student=$mysqli->query($query_firstyr_student);
$numofrows=$mysqli->num_rows($result_firstyr_student);

if($numofrows){
		$row_firstyr_student=$result_firstyr_student->fetch_assoc();
		?>
	<center>
	<div class="list_table">
		<h3><p><a href="section_select.php">View TimeTable For a Section</a> | <a href="classroom_select.php">View TimeTable For A Classroom</a> | <a href="student_select.php">View Time Table For  A Branch</a></p></h3>
		<?php
		echo "<h3>You are in Section ".$row_firstyr_student['section'].", class ".$row_firstyr_student['building']." ".$row_firstyr_student['room'].", Semester ".$row_firstyr_student['sem_type']." ".$row_firstyr_student['semester']."</h3>";
		echo "<h3>TimeTable For Student[ ".$_SESSION['id']." ]</h3>";
		$query_rs_std_infos = sprintf("SELECT * FROM timetable_info_firstyear, user_details, subjects WHERE timetable_info_firstyear.subject_id=subjects.subject_id AND time_table_info_firstyr.emp_id=user_details.id AND semester='".$row_firstyr_student['semester']."' and sec='".$row_firstyr_student['section']."' and classroom_id='".$row_firstyr_student['classroom_id']."'");
		$result_rs_std_infos= $mysqli->query($query_rs_std_infos) or die(mysql_error());
		$subjects=array();
		$teachers=array();
		$subjectnames=array();
		while($row_std_infos=$result_rs_std_infos->fetch_assoc()){
			$subjects[$row_std_infos['slot_id']]=$row_std_infos['subject_id'];
			$subjectnames[$row_std_infos['subject_id']]=$row_std_infos['name'];
			$teachers[$row_std_infos['subject_id']]=$row_std_infos['salutation']. " " . $row_std_infos['first_name']. " " . $row_std_infos['middle_name'] . " " . $row_std_infos['last_name'];
		}
		?>
		<div id="view_table">
		<center>
			<table id="view_table" width="90%" height="304" border="1" class="margin">
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
				</tr>
		<?php   
		//$day array of 5 days
		$day=array("MONDAY","TUESDAY","WEDNESDAY","THURSDAY","FRIDAY");
		for ($i=0; $i<5; $i++){
			echo "<tr>";
			echo "<th>". $day[$i] ."</th>";
			for($j=1; $j<=10; $j++)	{
				echo "<td class='entry'";
				if (array_key_exists($i*10+$j,$subjects)){	
					echo "title='".$teachers[$subjects[$i*10+$j]]."'>";
					echo $subjects[$i*10+$j]."<br>".$subjectnames[$subjects[$i*10+$j]];
				}
				else echo ">";
				echo "</td>";
				}
			echo "</tr>";
		}
		?>
		</table>
		</br>						
		<button id="printBtn">Print</button>
		</center>
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
}?>
</div>
	</div>
<?php
}
else{
//to check the exitence of a single row
if($totalRows_rs_student)
{
	//to initialize some session values with their corresponding values from database
	$_SESSION['cid']=$row_rs_student['course_id'];
	$_SESSION['bid']=$row_rs_student['branch_id'];
	$_SESSION['sem']=$row_rs_student['semester'];
?>
	<center>
	<div class="list_table">
		<h3><p><a href="section_select.php">View TimeTable For a Section</a> | <a href="classroom_select.php">View Table For A Classroom</a> | <a href="student_select.php">View Time Table For  A Branch</a></p></h3>
		<h3>TimeTable For You</h3>
		<?php
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
				$query_rs_std_info = sprintf("SELECT * FROM time_table_info, user_details, subjects, timetable_classroom WHERE time_table_info.subject_id=subjects.subject_id AND time_table_info.emp_id=feedback_faculty.emp_id AND course_id=%s AND branch_id=%s  AND semester=%s AND classroom.classroom_id=time_table_info.classroom_id", GetSQLValueString($course_rs_std_info, "text"),GetSQLValueString($branch_rs_std_info, "text"),GetSQLValueString($semester_rs_std_info, "text"));
				//perform query
				$rs_std_info = mysql_query($query_rs_std_info, $conn_time_table) or die(mysql_error());
				//returns total number of rows in the set
				$totalRows_rs_std_info = mysql_num_rows($rs_std_info);
				//if there is no any row, it will print Wrong Selection
				if($totalRows_rs_std_info <1)
				{
				?>
					<center>
					<div class="notification" style="margin-right:22px; margin-left:22px;">There is no any class for you.</div>
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
						$teachers[$row_std_info['subject_id']]=$row_std_info['salutation']. " " . $row_std_info['first_name']. " " . $row_std_info['middle_name'] . " " . $row_std_info['last_name'];
						
					}
				?>
				<div id="view_table">
						<center>
						<table id="view_table" width="90%" height="304" border="1" class="margin">
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
									echo "<td class='entry'";
									if (array_key_exists($i*10+$j,$subjects))
									{	
										echo "title='".$subjectnames[$subjects[$i*10+$j]]." (".$teachers[$subjects[$i*10+$j]].")'>";
										echo $print_str_subjects[$i*10+$j];
									}
									else echo ">";
									echo "</td>";
								}
								echo "</tr>";
							}
						?>
						</table>
						</br>
						
						<button id="printBtn">Print</button>
					</center>
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
}?>
</div>
				
	</div>

	
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
	
<?php
}
}
}

//to free up the $rs_student
mysql_free_result($rs_student); 

//to draw footer calling drawFooter()
drawFooter(); ?>