<script type="text/javascript">
function addNew(){
	document.getElementById("builing_dropdown").style.display="none";
	document.getElementById("add_new").style.display="none";
	document.getElementById("building_text").style.display="block";
	document.getElementById("select_building").style.display="block";
	document.getElementById("capacity_tr").style.display="block";
}

function selectBuilding(){
	document.getElementById("builing_dropdown").style.display="block";
	document.getElementById("add_new").style.display="block";
	document.getElementById("building_text").style.display="none";
	document.getElementById("select_building").style.display="none";
}

</script>

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

$classroom_query="select * from timetable_classrooms";
$classroom_ans=$mysqli->query($classroom_query);

	if(isset($_POST['buildingt'])){
		$numofrooms=$_POST['numberofrooms'];
		for($i=1;i<=$numofrooms;$i++){
			$sql="insert into timetable_classrooms('building','room','capacity') Values('".$_POST['buildingt']."','".$i."','".$_POST['capacity']."')";
			$sql_ans=$mysqli->query($sql);
		}
		drawNotification("Added","The entered building (".$_POST['buildingt'].") has been added successfully for rooms from 1 to ".$numofrooms,"success");
	}
	else if(isset($_POST['buildingd'])){
		$max_room_query="select MAX(room) as max,capacity from timetable_classrooms where building='".$_POST['buildingd']."'";
		$max_room_ans=$mysqli->query($max_room_query);
		$max_room=$max_room_ans-fetch_assoc();
		$max=$max_room['max'];
		$capacity=$max_room['capacity'];
		for($i=$max+1;$i<=$max+$_POST['numberofrooms'];$i++){
				$sql="insert into timetable_classrooms('building','room','capacity') Values('".$_POST['buildingd']."','".$i."','".$_POST['capacity']."')";
				$sql_ans=$mysqli->query($sql);
		}
		drawNotification("Added","The entered building (".$_POST['buildingd'].") has been added successfully for rooms from ".($max+1)." to ".($max+$_POST['numberofrooms']),"success");
	}

?>

<form name="form" action="" method="post">
	<table>
	<tr>
	<td>
	Select Building:
	</td>
	<td>
		<select name="buildingd" id="builing_dropdown">
						<option value="">Select Builing</option>
						<?php 
						while($row_classroom=$classroom_ans->fetch_assoc()){
							echo '<option value="'.$row_classroom['builing'].'">'.$row_classroom['builing'].'</option>';
						}
						?>
		</select>
		<input type="button" value="Add New" id="add_new" onclick="addNew()"/>
		<input type="text" name="builingt"  id="building_text" style="display:none;"/>
		<input type="button" value="Select Building" id="select_building" style="display:none;" onclick="selectBuilding()"/>
	</td>
	</tr>
	<tr>
	<td>
	Enter number of Rooms to be added:
	</td>
	<td>
	<input type="integer" name="numberofrooms" id="numberofrooms"/>
	</td>
	</tr>
	<tr id="capacity_tr" style="display:none;">
	<td>
	Enter Capacity of each room:
	</td>
	<td>
	<input type="integer" name="capacity" id="capacity"/>
	</td>
	</tr>
	</table>
	<div>
	<input type="submit" value="Submit"/>
</form>