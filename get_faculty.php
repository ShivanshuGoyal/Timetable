<?php
	//including feedbacklayout php file 	
	require_once("../Includes/FeedbackLayout.php");
	require_once("../Includes/AuthDo.php");

//initialize the session if it is not initialized
if (!isset($_SESSION)) {
  session_start();
}

//to redirect to logout.php page if user is not logged in
if (!isset($_SESSION['SESS_USERNAME'])) {
	header("Location:../Logout.php");
}
//to redirect to classroom_select.php if user is logged in but does not have posted value of b
else if(isset($_SESSION['SESS_USERNAME']) && !isset($_GET["b"])) {
	header("Location:dataoperator_timetable.php");
}
//initialize variable b with posted b value
$b=$_GET["b"];

//connecting to the server
$con = mysql_connect('localhost', 'root', 'p');
//checking whether the conn is made or not
if (!$con){
  die('Could not connect: ' . mysql_error());
}
//selecting required database
mysql_select_db("feedback_mis", $con);
$query_faculty="select distinct feedback_faculty.emp_id,salutation,first_name,middle_name,last_name from feedback_faculty INNER JOIN feedback_subjectfaculty on  feedback_subjectfaculty.emp_id=feedback_faculty.emp_id where subject_id='".$b."'";

$result_faculty=mysql_query($query_faculty);
$row_faculty = mysql_fetch_assoc($result_faculty);
echo '
<td><label for="select_fac">Select Faculty</label></td>
  <td><select name="facultyi" id="faculty">
	<option value="null" selected="selected" disabled="disabled">Select faculty</option>';

do {  
?>
    <option value="<?php echo $row_faculty['emp_id']?>"><?php echo $row_faculty['salutation']." ".$row_faculty['first_name']." ".$row_faculty['middle_name']." ".$row_faculty['last_name']?></option>
    <?php
} while ($row_faculty = mysql_fetch_assoc($result_faculty));
?>
  </select>
  </td>