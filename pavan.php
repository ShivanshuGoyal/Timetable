<?php
	//including feedbacklayout php file 	
	require_once("../Includes/FeedbackLayout.php");
 
	//to draw header drawHeader() is calling
	drawHeader("Semester Registration System"); 

//initialize the session if it is not initialized
if (!isset($_SESSION)) {
  session_start();
}

//to redirect to logout.php page if user is not logged in
if (!isset($_SESSION['SESS_USERNAME'])) {
	header("Location:../Logout.php");
}
?>


<div>
	<h1><u><b><center>Semester Registration System</center></b></u></h1>
</div>

<div>
	<?php
		//to include conn_time_table.php to make connection with database	
		require_once('Connections/conn_time_table.php'); ?>

<script>
<?php   //function for Ajax   ?>
function showRoom(str){
	var xmlhttp;    
	if (str==""){
		document.getElementById("txtHint").innerHTML="";
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
			document.getElementById("div_room").innerHTML = xmlhttp.responseText;
		}
	}
	q = "select_electives.php?b=" + str;
	xmlhttp.open("GET",q,true);

	xmlhttp.send();
}
</script>


<?php
if(isset($_POST['branch_id']))
$_SESSION['branch_id']=$_POST['branch_id'];
//selecting required database
mysql_select_db($database_conn_time_table, $conn_time_table);
$query_rs_semester = "SELECT semester from table_name where branch_id='".$_POST['branch']."'";
//perform query
$rs_semester = mysql_query($query_rs_semester, $conn_time_table) or die(mysql_error());
//each call returns a row from the recordset given by the result of mysql_query() i.e. $rs_electives
$row_rs_semester = mysql_fetch_assoc($rs_semester);

?>
<center>

<form id="form1" name="form1" method="post" action="">
<table>
	<tr>
		<td>Select Semester</td>
		<td>
			<select name="select_semester" id="select_semester" onChange="showRoom(this.value)">
				<option value="null" selected="selected" disabled="disabled">Select Semester</option>
			<?php
			//loop to print all the semester
			do {  
			?>
				<option value="<?php echo $row_rs_semester['semester']?>"><?php echo $row_rs_semester['semester']?></option>
			<?php
			} while ($row_rs_semester = mysql_fetch_assoc($rs_semester));
			?>
			</select>
		</td>
	</tr>
	</table>
</form>
<div id="div_room"></div>
<?php
//to free up the $rs_building variable
mysql_free_result($rs_semester);
?>

</div>
</center>
<?php
//to draw Footer
drawFooter(); 
?>