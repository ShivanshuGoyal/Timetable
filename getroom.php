<?php
	//including feedbacklayout php file 
	require_once("../Includes/FeedbackLayout.php");

	//to draw header drawHeader() is calling
	//drawHeader("TimeTable Info System"); 

//initialize the session if it is not initialized
if (!isset($_SESSION)) {
  session_start();
}

//to redirect to logout.php page if user is not logged in
if (!isset($_SESSION['SESS_USERNAME'])) {
	header("Location:../Logout.php");
}

//initializing b session value with the b posted value by get method
if(isset($_GET['b'])){
	$_SESSION['b'] = $_GET['b'];
	$b=$_GET["b"];
}

//to make connection with the server
$con = mysql_connect('localhost', 'root', 'p');

//to check whether connection is made or not
if (!$con){
	die('Could not connect: ' . mysql_error());
}

//selecting required database
mysql_select_db("feedback_mis", $con);

$sql="SELECT * FROM classroom WHERE sec ='N' and building = '$b'";

//perform query
$result = mysql_query($sql);

echo "<form action='timetable.php' method = 'post'> 
		<label>Select Room</label><select name='list_classroom_room' id='list_classroom_room'>";

while($row= mysql_fetch_assoc($result)) {  
    //will print all the classrooms in the set one by one as option values
	echo "<option value='" . $row['room'] . "'>" . $row['room'] . "</option>";
} 

echo "</select>
		<input type='submit' name='submit' value='Continue'>
		<input type='hidden' name='building' value='" . $_SESSION['b'] . "'>
		<input type='hidden' name='eid' value='" . $_SESSION['eid'] . "'>
		<input type='hidden' name='sid' value='" . $_SESSION['sid'] . "'>
		<input type='hidden' name='cid' value='" . $_SESSION['cid'] . "'>
		<input type='hidden' name='bid' value='" . $_SESSION['bid'] . "'>
		<input type='hidden' name='sem' value='" . $_SESSION['sem'] . "'>
	  </form>";

//closing connection
mysql_close($con);

//to draw footer calling drawFooter() 
//drawFooter(); 
?>