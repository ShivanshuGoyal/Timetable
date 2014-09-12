<script type="text/javascript">
	function getBranchSection(str){
	var xmlhttp;    
	if (str==""){
		document.getElementById("branch_section").innerHTML="";
		return;
	}
  
	if (window.XMLHttpRequest){
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else{
		// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
  
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById("branch_section").innerHTML = xmlhttp.responseText;
		}
	}
	q = "select_branch_section.php?b=" + str;
	xmlhttp.open("GET",q,true);

	xmlhttp.send();
}

</script>
<?php
	//including layout, authentication and configuration php files 	
	require_once("../Includes/Layout.php");
	require_once("../Includes/Auth.php");
	require_once("../Includes/ConfigSQL.php");
	auth("hod");

	//initialize the session if it is not initialized
	if (!isset($_SESSION)) {
		session_start();
	}

	//to redirect to logout.php page if user is not logged in
	if (!isset($_SESSION['id'])) {
		header("Location:../Logout.php");
	}
	
	$dept_query="select dept_id from user_details where id='".$_SESSION['id']."'";
	$dept_ans=$mysqli->query($dept_query);
	$row_dept=$dept_ans->fetch_assoc();
	
	$course_query="select id, name from department_course_branch and courses where dept_id='".$row_dept['dept_id']."' and courses.id= department_course_branch.course_id";
	$course_ans=$mysqli->query($course_query);
	echo '
		<form name="form" action="faculty_timetable_enter.php" method="post">
		<table>';
		$date=Date("Y");
			echo '
				<tr>
				<td>
				<select name="session">
				<option value="">Select Session</option>
				<option value="'.($date-1).'-'.$date.'">'.($date-1).'-'.$date.'</option>
				<option value="'.$date.'-'.($date+1).'">'.$date.'-'.($date+1).'</option>
				</select>
				</td>
				</tr>
				<tr>
				<td><select name="course" onchange="getBranchSection(this.value)">
					<option value="" selected="selected">Select Course</option>';
					while($row=$course_ans->fetch_assoc()){
						echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
					}
					echo '</select></td>
			</tr>
			<tr id="branch_section">
			</tr>
		</table>
	</form>';
?>