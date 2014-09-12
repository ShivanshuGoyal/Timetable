<?php
	//including layout, authentication and configuration php files 	
	require_once("../Includes/Layout.php");
	require_once("../Includes/Auth.php");
	require_once("../Includes/ConfigSQL.php");
	auth("ft","stu");
	
//initialize the session if it is not initialized
if (!isset($_SESSION)) {
  session_start();
}

//to redirect to logout.php page if user is not logged in
if (!isset($_SESSION['id'])) {
	header("Location:../Logout.php");
}
//to redirect to classroom_select.php if user is logged in but does not have posted value of b
else if(isset($_SESSION['id']) && !isset($_GET["b"])) {
	header("Location:classroom_select.php");
}
//initialize variable b with posted b value
$b=$_GET["b"];

$sql="SELECT * FROM timetable_classrooms WHERE building = '".$b."'";
//perform query
$result = $mysqli->query($sql);

echo "<form method='post' action='view_croom.php'> 
		<input type='hidden' value='".$b."' name='select_building'/>
		<label>Select Room</label><select name='classroom_id' id='list_classroom_room'>";
		while ($row= $result->fetch_assoc()) {  
			echo "<option value='" . $row['classroom_id'] . "'>" . $row['room'] . "</option>";
		} 
		echo "</select>
		<input type='submit'>
	  </form>";

?>