<?php
	//including layout, authentication and configuration php files 	
	require_once("../Includes/Layout.php");
	require_once("../Includes/Auth.php");
	require_once("../Includes/ConfigSQL.php");
	//auth("hod");
 

//initialize the secure session if it is not initialized
  session_start_sec();

//to redirect to logout.php page if user is not logged in
if (!isset($_SESSION['id']) || !isset($_GET['b'])) {
	header("Location:../Logout.php");
}			

			$faculty_query="select emp_id,first_name,middle_name,last_name,designation from user_details and subject_faculty where subject_faculty.emp_id=user_details.id and session='".$_GET['session']."' and subject_id='".$_GET['b']."' ";
			
			$faculty_ans=$mysqli->query($faculty_query);
			echo '
				<select name="faculty">
				<option value="">Select Faculty</option>';
				while($row_faculty=$faculty_ans->fetch_assoc()){
					echo '<option value="'.$row_faculty['emp_id'].'">'.$row_faculty['designation'].' '.$row_faculty['first_name'].' '.$row_faculty['middle_name'].' '.$row_faculty['last_name'].' </option>';
				}
				
			echo 	'</select>
			';
		