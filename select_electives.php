<?php
	//including feedbacklayout php file 	
	require_once("../Includes/FeedbackLayout.php");

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
	header("Location:pavan.php");
}
//initialize variable b with posted b value
$b=$_GET["b"];

//connecting to the server
$con = mysql_connect('localhost', 'root', '');
//checking whether the conn is made or not
if (!$con){
  die('Could not connect: ' . mysql_error());
}
//selecting required database
mysql_select_db("feedback_mis", $con);

$sql="SELECT * FROM table_name WHERE electives='1' and semester = '".$b."' and branch_id='".$_SESSION['branch_id']."'";
//perform query
$result = mysql_query($sql);
$m=0;
echo ' <div>Select Electives</div>
		<table>
			<tr>
				<td>';
				while ($row= mysql_fetch_assoc($result)) {
					$m++;
					echo '<tr></td>'.$row['subject_name'].' <input type="checkbox" name="subject" id="subject" value="'.$row['subject_id'].'" /></td></tr>';
				}
echo '</table>
	  <div>Minimum No. of Electives to be selected</div>
	  <table><tr><td>
	  <select name="min_electives" id="min_electives">
	  	<option value="null" selected="selected" disabled="disabled">Select Min Electives</option>';
	  
	  for($i=1;$i<$m+1;$i++)
	  	echo	'<option value="'.$i.'">'.$i.'</option>';
		echo '</select></td></tr></table>';
	  
	  
//closing connection
mysql_close($con);

?>