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
//if (!isset($_SESSION['id']) || !isset($_GET['b'])) {
	//header("Location:../Logout.php");
//}

$room_query="select room from timetable_classrooms where building='".$_GET['b']."' and status='available'";
$room_ans=$mysqli->query($room_query);
$num_room=$classroom_ans->num_rows;
if($num_room<1)
	drawNotification("Error","There is no any room free to be blocked.","error");
	die();
}
echo '<td>
		Select Room:
      </td>
	  <td>
	  <select name="room">
		<option value="">Select Room</option>';
		while($row_room=$room_ans->fetch_assoc()){
				echo '<option value="'.$row_room['room'].'">'.$row_room['room'].'</option>';
		}
		echo'</select>
	  </td>
';	
?>