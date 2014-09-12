<?php
	//including layout, authentication and configuration php files 	
	require_once("../Includes/Layout.php");
	require_once("../Includes/Auth.php");
	require_once("../Includes/ConfigSQL.php");
	auth("deo");
 
	//to draw header drawHeader() is calling
	drawHeader("TimeTable Info System");  

//initialize the secure session if it is not initialized
  session_start_sec();

//to redirect to logout.php page if user is not logged in
//if (!isset($_SESSION['id']) || !isset($_POST['course'])) {
	//header("Location:../Logout.php");
//}

$classroom_query="select building from timetable_classrooms where all status='available' ";
$classroom_ans=$mysqli->query($classroom_query);
$num_building=$classroom_ans->num_rows;
if($num_building<1)
	drawNotification("Error","There is no any building free to be blocked.","error");
	die();
}
	if(isset($_POST['building'])){
		$sql="update timetable_classrooms set status='blocked' where building='".$_POST['building']."'";
		$sql_ans=$mysqli->query($sql);
		drawNotification("Blocked","The entered building (".$_POST['building'].") has been blocked successfully.","success");
	}
	
?>

<form name="form" action="" method="post">
	<table>
	<tr>
	<td>
	Select Building:
	</td>
	<td>
		<select name="building">
						<option value="">Select Builing</option>
						<?php 
						while($row_classroom=$classroom_ans->fetch_assoc()){
							echo '<option value="'.$row_classroom['builing'].'">'.$row_classroom['builing'].'</option>';
						}
						?>
		</select>
	</td>	
	</tr>
	</table>
	<div>
	<input type="submit" value="Submit"/>
	</div>
</form>