<?php
	//including layout, authentication and configuration php files 	
	require_once("../Includes/Layout.php");
	require_once("../Includes/Auth.php");
	require_once("../Includes/ConfigSQL.php");
 
	//to draw header drawHeader() is calling
	drawHeader("TimeTable Info System"); 

//initialize the session if it is not initialized
if (!isset($_SESSION)) {
  session_start();
}

//to redirect to logout.php page if user is not logged in
if (!isset($_SESSION['id'])) {
	header("Location:../Logout.php");
}
	if(isset($_GET['notification'])){
		drawNotification($_GET['notification'],$_GET['content'],$_GET['type']);
	}
	if(is_auth("stu"))
			header("Location:student.php" );
	else if(is_auth("deo"))
			header("Location:dataoperator_timetable.php");
	else if(is_auth("ft") || is_auth("hod")){
			header("Location:view_faculty.php");
			}
	else
		drawNotification("Error","Sorry you are not allowed to access the page.","error");
 drawFooter(); 
 ?>
</body>
</html>