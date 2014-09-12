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
<style type="text/css">
	@import url("template.css");
</style>
<body>
<div id="heading">
	<h1><u><b><center>Time Table Information System</center></b></u></h1>
</div>

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
	
$colname_rs_faculty_subject = "-1";
if (isset($_SESSION['SESS_USERNAME'])) {
  $colname_rs_faculty_subject = $_SESSION['SESS_USERNAME'];
}
//selecting required database
mysql_select_db($database_conn_time_table, $conn_time_table);
//returns a relation having emp_id, subject_id, session, session_year, course_id, branch_id, semester, course_year
$query_rs_faculty_subject = sprintf("SELECT * FROM feedback_subjectfaculty, feedback_subjectdetails WHERE emp_id = %s AND feedback_subjectdetails.subject_id=feedback_subjectfaculty.subject_id", GetSQLValueString($colname_rs_faculty_subject, "text"));
//perform query
$rs_faculty_subject = mysql_query($query_rs_faculty_subject, $conn_time_table) or die(mysql_error());
//returns a row from the record set
$row_rs_faculty_subject = mysql_fetch_assoc($rs_faculty_subject);
$colname_rs_faculty_subject = "805";
if (isset($_SESSION['SESS_USERNAME'])) {
  $colname_rs_faculty_subject = $_SESSION['SESS_USERNAME'];
}

$query_rs_faculty_subject = sprintf("SELECT * FROM feedback_faculty,feedback_subjectfaculty, feedback_subjectdetails , feedback_subject WHERE feedback_subjectdetails.subject_id IN ( Select Distinct subject_id from feedback_subjectfaculty where emp_id=%s ) and feedback_faculty.emp_id=feedback_subjectfaculty.emp_id and feedback_subject.subject_id=feedback_subjectdetails.subject_id", GetSQLValueString($colname_rs_faculty_subject, "text"));
$rs_faculty_subject = mysql_query($query_rs_faculty_subject, $conn_time_table) or die(mysql_error());
$row_rs_faculty_subject = mysql_fetch_assoc($rs_faculty_subject);
$totalRows_rs_faculty_subject = "806";
if (isset($_SESSION['SESS_USERNAME'])) {
  $totalRows_rs_faculty_subject = $_SESSION['SESS_USERNAME'];
}
$colname_rs_faculty_subject = "-1";
mysql_select_db($database_conn_time_table, $conn_time_table);
$query_rs_faculty_subject = sprintf("SELECT * FROM feedback_subjectfaculty, feedback_subjectdetails WHERE emp_id = %s AND feedback_subjectdetails.subject_id=feedback_subjectfaculty.subject_id", GetSQLValueString($colname_rs_faculty_subject, "text"));
$rs_faculty_subject = mysql_query($query_rs_faculty_subject, $conn_time_table) or die(mysql_error());
$row_rs_faculty_subject = mysql_fetch_assoc($rs_faculty_subject);
$totalRows_rs_faculty_subject = mysql_num_rows($rs_faculty_subject);$colname_rs_faculty_subject = "805";
if (isset($_SESSION['SESS_USERNAME'])) {
  $colname_rs_faculty_subject =$_SESSION['SESS_USERNAME'];
}

$query_rs_faculty_subject = sprintf("SELECT * FROM feedback_faculty,feedback_subjectfaculty, feedback_subjectdetails, feedback_subject, feedback_branch, feedback_course WHERE feedback_subjectdetails.semester NOT IN (1,2) AND feedback_faculty.emp_id = %s AND feedback_subjectdetails.subject_id=feedback_subjectfaculty.subject_id and feedback_faculty.emp_id=feedback_subjectfaculty.emp_id and feedback_subject.subject_id=feedback_subjectdetails.subject_id  and feedback_course.course_id = feedback_subjectdetails.course_id  and feedback_branch.branch_id = feedback_subjectdetails.branch_id ORDER BY feedback_subjectdetails.subject_id,feedback_subjectdetails.course_id", GetSQLValueString($colname_rs_faculty_subject, "text"));
$rs_faculty_subject = mysql_query($query_rs_faculty_subject, $conn_time_table) or die(mysql_error());
$row_rs_faculty_subject = mysql_fetch_assoc($rs_faculty_subject);
$totalRows_rs_faculty_subject = mysql_num_rows($rs_faculty_subject);

?>
<style type="text/css">

.list_table {
	color: #000;
	background-color: rgb(238,238,238);
	margin: 5px;
	margin-right:22px;
	margin-left:22px;
	padding: 2px;
	
}
.list_table table tr{
	background-color: #fff;
	margin: 2px;
	padding: 2px;
	border: thin solid #003;
}
.list_table table tr td {
text-align:center;
}

</style>

<?php if($totalRows_rs_faculty_subject)
{
?>

<center>
<div class="list_table">
<h3>
<p><a href="view_firstyr_faculty.php">Firstyear TimeTable </a> | <a href="view_faculty.php" >View TimeTable</a> | <a href="classroom_select.php">TimeTable For A Classroom</a> | <a href="student_select.php">TimeTable For A Branch</a> | <a href="section_select.php">TimeTable For a Section</a> | <a href="faculty_time_table_edit.php">Delete Time Table</a></p>
</h3>
<table width="948" height="86" border="1">
  <tr>
  	<b><u><h4>
    <th>Subject Id</th>
    <th>Subject Name</th>
    <th>Course</th>
    <th>Branch</th>
    <th>Semester</th>
    <th><p>Session</p></th>
    <th>Enter Time Table</th>
  	</h4></u></b>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <?php do { ?>
      <tr>
		<form id="time_tablea" name="time_tablea" method="post" action="classroom.php">
			<input type='hidden' name='sid' value="<?php echo $row_rs_faculty_subject['subject_id'] ?>">
			<input type='hidden' name='sun' value="<?php echo $row_rs_faculty_subject['subject_name'] ?>">
			<input type='hidden' name='bid' value="<?php echo $row_rs_faculty_subject['branch_id'] ?>">
			<input type='hidden' name='cid' value="<?php echo $row_rs_faculty_subject['course_id'] ?>">
			<input type='hidden' name='ses' value="<?php echo $row_rs_faculty_subject['session'] ?>">
			<input type='hidden' name='sem' value="<?php echo $row_rs_faculty_subject['semester'] ?>">

		<td><?php echo $row_rs_faculty_subject['subject_id']; ?></td>
        <td><?php echo $row_rs_faculty_subject['subject_name']; ?></td>
        <td><?php echo $row_rs_faculty_subject['course_name']; ?></td>
        <td><?php echo $row_rs_faculty_subject['branch_name']; ?></td>
        <td><?php echo $row_rs_faculty_subject['semester']; ?></td>
        <td><?php echo strtolower($row_rs_faculty_subject['session']); ?></td>
        <td><input type='submit' value='Enter Time Table' name='submit' /></td>
		</form>
	  </tr>
      <?php } while ($row_rs_faculty_subject = mysql_fetch_assoc($rs_faculty_subject)); ?>
</table>
</div>
</center>
<?php
//to free up $rs_faculty_subject
mysql_free_result($rs_faculty_subject);
}
?>
<?php drawFooter(); ?>
</body>
