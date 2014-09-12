<script type="text/javascript">
	function getRoom(str){
		var xmlhttp;    
	if (str==""){
		document.getElementById("room_tr").innerHTML="";
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
			document.getElementById("branch_tr").innerHTML = xmlhttp.responseText;
		}
	}
	q = "select_branch_section.php?b=" + str;
	xmlhttp.open("GET",q,true);

	xmlhttp.send();
	document.getElementById("course_branch_section_semester").style.display="block";
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

//to fetch course from courses table
$course_query="select * from courses";
$course_ans=$mysqli->query($course_query);

$classroom_query="select building from timetable_classrooms where status='available'";
$classroom_ans=$mysqli->query($classroom_query);
$num_building=$classroom_ans->num_rows;
if($num_building<1)
	drawNotification("Error","There is no any building free to be blocked.","error");
	die();
}
	if(isset($_POST['building'])){
		$sql="update timetable_classrooms set status='occupied' where building='".$_POST['building']."' and room='".$_POST['room']."'";
		$sql_ans=$mysqli->query($sql);
		
		$classroom_id_query="select classroom_id from timetable_classrooms where building='".$_POST['building']."' and room='".$_POST['room']."'";
		$classroom_id_ans=$mysqli->query($classroom_id_query);
		$row_classroom_id=$classroom_id_ans->fetch_assoc();
		$classroom_id=$row_classroom_id['classroom_id'];
		
		$insert_classroom_id_query="insert into timetable_classroom_classes values('".$classroom_id."','".$_POST['course']."','".$_POST['branch']."','".$_POST['semester']."','".$_POST['section']."')";
		$insert_classroom_id_ans=$mysqli->query($insert_classroom_id_query);
		
		drawNotification("Assigned","The entered classroom (".$_POST['building']." ".$_POST['room'].") has been assigned successfully for ".$_POST['course']." ".$_POST['branch'].", Semester".$_POST['semester']." (Section ".$_POST['section'].")","success");
	}
	
?>

<form name="form" action="" method="post">
	<table>
	<tr>
	<td>
	Select Building:
	</td>
	<td>
		<select name="building" onchange="getRoom(this.value)">
						<option value="">Select Builing</option>
						<?php 
						while($row_classroom=$classroom_ans->fetch_assoc()){
							echo '<option value="'.$row_classroom['builing'].'">'.$row_classroom['builing'].'</option>';
						}
						?>
		</select>
	</td>	
	</tr>
	<tr id="room_tr">
	</tr>
	<tr id="course_branch_section_semester" style="display:none;">
	<td>
	Select Course:
	</td>
	<td>
	<select name="course" onchange="getBranch()">
		<?php
		while($row_course=$course_ans->fetch_assoc()){
			echo '<option value="'.$row_course['id'].'">'.$row_course['name'].'</option>';
		}
		?>
	</select>
	</td>
	</tr>
	<tr id="branch_tr">
	</tr>
	</table>
	</form>