<?php
	//including layout, authentication and configuration php files 	
	require_once("../Includes/Layout.php");
	require_once("../Includes/Auth.php");
	require_once("../Includes/ConfigSQL.php");
	auth("deo");
 

//initialize the secure session if it is not initialized
  session_start_sec();

//to redirect to logout.php page if user is not logged in
if (!isset($_SESSION['id'])) {
	header("Location:../Logout.php");
}			
$year=Date("Y");
//if it gets session value from post variable
if(isset($_POST['session'])){
	$sql_query="insert into subject_faculty values('".$_POST['faculty']."','".$_POST['subject']."','".$_POST['session']."')";
	$sql=$mysqli->query($sql_query);
	drawNotification("Assigned","The given Subject (".$_POST['subject'].") has been assigned successfully to Faculty (".$_POST['faculty'].")","success");
}


//to fetch subjects from subjects table
$subject_query="select * from subjects";
$subject=$mysqli->query($subject_query);

$faculty_query="select * from user_details, emp_basic_details where user_details.id=emp_basic_details.id and emp_basic_details.auth_id='ft'";
$faculty=$mysqli->query($faculty_query);

echo '<form name="form" action="" method="post">
		<table>
		<tr>
		<td>
		Select Session:
		</td>
		<td>
		<select name="session">
		<option value="'.($year-1).'-'.$year.'">'.($year-1).'-'.$year.'</option>
		<option value="'.$year.'-'.($year+1).'">'.$year.'-'.($year+1).'</option>
		</select>
		</td>
		</tr>
		<tr>
		<td>
		Select Subject:
		</td>
		<td>
		<select name="subject">
			<option value="">Select Subject</option>';
			while($row_subject=$subject->fetch_assoc()){
				echo '<option value="'.$row_subject['subject_id'].'">'.$row_subject['name'].'</option>';
			}
	echo'</select>
		</td>
		</tr>
		<tr>
		<td>
		Select Faculty:
		</td>
		<td>
		<select name="faculty">
			<option value="">Select Faculty</option>';
			while($row_faculty=$faculty->fetch_assoc()){
				echo '<option value="'.$row_faculty['id'].'">'.$row_faculty['designation'].' '.$row_faculty['first_name'].' '.$row_faculty['middle_name'].' '.$row_faculty['last_name'].'</option>';
			}
	echo'</select>
		</td>
		</tr>
		</table>
		</form>
';
?>			