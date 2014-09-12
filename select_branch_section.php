<?php
	//including layout, authentication and configuration php files 	
	require_once("../Includes/Layout.php");
	require_once("../Includes/Auth.php");
	require_once("../Includes/ConfigSQL.php");
	auth("hod","deo");

	//initialize the session if it is not initialized
	if (!isset($_SESSION)) {
		session_start();
	}

	//to redirect to logout.php page if user is not logged in
	if (!isset($_SESSION['id']) || !isset($_GET['b'])) {
		header("Location:../Logout.php");
	}
	
	$branch_section_query="select branch_id,section,name from course_branch_section and branches where  where course_id='".$_GET['b']."' and course_branch_section.branch_id=branches.id";
	$branch_ans=$mysqli->query($branch_section_query);
	$section_ans=$mysqli->query($branch_section_query);
	
	echo '<td>
			<select name="branch">
				<option value="" selected="selected">Select Branch</option>';
				while($row_branch=$branch_ans->fetch_assoc()){
					echo '<option value="'.$row_branch['branch_id'].'">'.$row_branch['name'].'</option>';
				}
				echo '</select>
		</td>
		</tr>
		';
		
		$semester_query="select semester from course_semester where course_id='".$_GET['b']."'";
	    $semester_ans=$mysqli->query($semester_query);
		$row_semester=$semester_ans->fetch_assoc();
		$max_sem=$row_semester['semester'];
		
echo	'<tr>
			<td>
				<select name="semester">
				<option value="" selected="selected">Select Semester</option>';
				for($i=1;$i<=$max_sem;$i++)
					echo '<option value="'.$i.'">'.$i.'</option>';
		echo		
				'</select>
			</td>
		</tr>
		<tr>
		<td>
		<select name="section">
			<option value="" selected="selected">Select Section</option>';
			while($row_section=$section_ans->fetch_assoc()){
					echo '<option value="'.$row_section['section'].'">'.$row_branch['section'].'</option>';
				}
			echo '</select>
		</td>
		</tr>
		
		<tr>
		<td>
			<input type="submit" value="submit"/>
		</td>
		';
	