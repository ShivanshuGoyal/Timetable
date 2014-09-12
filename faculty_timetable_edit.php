<?php
	//including layout, authentication and configuration php files 	
	require_once("../Includes/Layout.php");
	require_once("../Includes/Auth.php");
	require_once("../Includes/ConfigSQL.php");
	auth("hod");
 
	//to draw header drawHeader() is calling
	drawHeader("TimeTable Info System");  

//initialize the session if it is not initialized
if (!isset($_SESSION)) {
  session_start();
}

//to redirect to logout.php page if user is not logged in
if (!isset($_SESSION['id']) || !isset($_POST['course'])) {
	header("Location:../Logout.php");
}
?>

<style type="text/css">
@import url("template.css");
table tr td {
	padding: 5px;
	margin: 0;
}
table tr th{
	padding:3px;
}
table tr td{
	background-color:#fff;
}
#view_table{
background-color: rgb(238,238,238);
margin:-.7px;
margin-left:22px;
margin-right:22px;
}
</style>

<div id="heading">
	<h1><u><b><center>Time Table Information System</center></b></u></h1>
</div>

<div id="view_table" >
	<center><h3><?php echo 'Timetable for'.$_POST['course'].' '.$_POST['branch'].', Section '.$_POST['section']; ?></h3></center>

	<?php
	
	// a function used to get value from strings
	if (!function_exists("GetSQLValueString")) {
		function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
			if (PHP_VERSION < 6) {
				$theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
			}

			$theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

			switch ($theType) {
				case "text": $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL"; break;    
				case "long":
				case "int": $theValue = ($theValue != "") ? intval($theValue) : "NULL"; break;
				case "double": $theValue = ($theValue != "") ? doubleval($theValue) : "NULL"; break;
				case "date": $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL"; break;
				case "defined": $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue; break;
			}
			return $theValue;
		}
	}
	
	$query_timeslot = "SELECT * from timetable_info and subjects WHERE course_id ='".$_POST['course']."' and branch_id='".$_POST['branch']."' and section='".$_POST['section']."' and semester='".$_POST['semester']."'  and session='".$_POST['session']."' and subjects.id=timetable_info.subject_id";
	$timeslot = $mysqli->query($query_timeslot) or die(mysql_error());
	$totalRows_timeslot = $timeslot->num_rows;
	

	if($totalRows_timeslot<1){
		drawNotification("Error","There is no any timetable to edit for the given details","error");
	}
	else{
		
			// mapping slots and subject ids
			//listing subjects
			$lectures=array();
			$faculty=array();
			while ($row= $timeslot->fetch_assoc()) {
				$lectures[$row['slot_id']]=$row['subject_id'];
				$faculty[$row['slot_id']]=$row['emp_id'];
			}
			?>

			<center>

			<form action="timetable_delete.php" method="post" enctype="multipart/form-data" name="time_table" id="time_table.php">
			<input type="hidden" name="course" value="<?php echo $_POST['course'];?>"/>
			<input type="hidden" name="branch" value="<?php echo $_POST['branch'];?>"/>
			<input type="hidden" name="section" value="<?php echo $_POST['section'];?>"/>
			<input type="hidden" name="semester" value="<?php echo $_POST['semester'];?>"/>
			<input type="hidden" name="session" value="<?php echo $_POST['session'];?>"/>
			<table width="90%" height="304" border="1" class="margin">
				<tr class="heading">
											<th width="8%">&nbsp;</th>
											<th width="6%"><p>1</p><p>8:00-8:50</p></th>
											<th width="6%"><p>2</p><p>8:50-9:40</p></th>
											<th width="6%"><p>3</p><p>9:40-10:30</p></th>
											<th width="6%"><p>4</p><p>10:30-11:20</p></th>
											<th width="6%"><p>5</p><p>11:20-12:10</p></th>
											<td width="2%" rowspan="6">&nbsp;</td>
											<th width="6%"><p>6</p><p>1:30-2:20</p></th>
											<th width="6%"><p>7</p><p>2:20-3:10</p></th>
											<th width="6%"><p>8</p><p>3:10-4:00</p></th>
											<th width="6%"><p>9</p><p>4:00-4:50</p></th>
											<th width="6%"><p>10</p><p>4:50-5:40</p></th>
										</tr>
			<?php    
				//$day, an arrays of days
				$day=array("MONDAY","TUESDAY","WEDNESDAY","THURSDAY","FRIDAY");
				
				//for loop run 5 times,one time for each day
				for ($i=0; $i<5; $i++){
					echo "<tr>";
					echo "<th>". $day[$i] ."</th>";
					for($j=1; $j<=10; $j++){
						echo "<td class='entry'>";
						//echo $i*10+$j;
						if (array_key_exists($i*10+$j,$lectures)){
							echo $lectures[$i*10+$j]."<br>".$faculty[$i*10+$j];
							echo "<br><input type='checkbox' name='time_slot[]' id='time_slot' value='".($i*10+$j)."'/>";
						}
						echo "</td>";
					}
					echo "</tr>";
				}
			?>

			</table>
			<input type='submit' name='submit' value='Delete'>
			</form>
			</div>
			</center>
<?php
}
//to draw footer
drawFooter(); 
?>