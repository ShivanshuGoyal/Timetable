<?php
	//including layout, authentication and configuration php files 	
	require_once("../Includes/Layout.php");
	require_once("../Includes/Auth.php");
	require_once("../Includes/ConfigSQL.php");
	auth("ft","stu");

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
//to redirect to index.php if user is logged in but does not have posted value of classroom_id
else if(isset($_SESSION['id']) && !isset($_POST['classroom_id'])){
	header("Location:index.php");
}
?>
<head>
<style type="text/css">
@import url("main.css");
@import url("template.css");
.margin{
margin-left:22px;
margin-right:22px;	
}
table tr td, table tr th {
	padding: 2px;
	margin: 0;
}

#view_table{
background-color: rgb(238,238,238);
margin:-.7px;
margin-left:22px;
margin-right:22px;
}
</style>

</head>
<body>
<div id="heading">
	<h1><u><b><center>Time Table Information System</center></b></u></h1>
</div>

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
	
	$query_timeslot1 = "SELECT classroom_id from timetable_info WHERE classroom_id ='".$_POST['classroom_id']."'";
	$timeslot1 = $mysqli->query($query_timeslot1) or die(mysql_error());
	$totalRows_timeslot1 = $timeslot1->num_rows;
	
	

	if($totalRows_timeslot1<1){
		$query_timeslot2 = "SELECT classroom_id from timetable_info_firstyear WHERE classroom_id ='".$_POST['classroom_id']."'";
		$timeslot2 = $mysqli->query($query_timeslot2) or die(mysql_error());
		$totalRows_timeslot2 = $timeslot2->num_rows;
		if($totalRows_timeslot2<1){
			drawNotification("Error","There is no any class for the given classroom.","error");
		}
		
	}
	else{
		$colname_rs_croom_info = "-1";
		if (isset($_POST['classroom_id'])) {
			$colname_rs_croom_info = $_POST['classroom_id'];
		}
		//gives a relation having info_id, emp_id, subject_id, course_id, branch_id, slot_id, classroom_id, semester, subject_name, salutation, first_name, middle_name, last_name, design, dept_id
		$query_rs_croom_info = sprintf("SELECT * FROM timetable_info, user_details, subjects WHERE timetable_info.emp_id=user_details.emp_id AND timetable_info.subject_id=subjects.subject_id AND classroom_id = %s", GetSQLValueString($colname_rs_croom_info, "int"));

		//perform query
		$rs_croom_info = $mysqli->query($query_rs_croom_info) or die(mysql_error());

		//returns a row from the record set
		$row_rs_croom_info = $rs_croom_info->fetch_assoc();

		//returns total number of rows in the set
		$totalRows_rs_croom_info = $rs_croom_info->num_rows;
		?>
		<div id="view_table">

		<center>
		<h3><a href="#" onclick="history.go(-1)">GO BACK</a></h3>
		<h3><?php echo "For Class ".$_POST['select_building']." ".$_POST['classroom_id'];?></h3>
		<table width="90%" height="304" border="1" class="margin">
			<tr class="heading">
										<th width="8%">&nbsp;</th>
										<th width="6%"><p>1</p><p>8:00-8:50</p></th>
										<th width="6%"><p>2</p><p>8:50-9:40</p></th>
										<th width="7%"><p>3</p><p>9:40-10:30</p></th>
										<th width="7%"><p>4</p><p>10:30-11:20</p></th>
										<th width="7%"><p>5</p><p>11:20-12:10</p></th>
										<td width="2%" rowspan="6">&nbsp;</td>
										<th width="6%"><p>6</p><p>1:30-2:20</p></th>
										<th width="6%"><p>7</p><p>2:20-3:10</p></th>
										<th width="6%"><p>8</p><p>3:10-4:00</p></th>
										<th width="6%"><p>9</p><p>4:00-4:50</p></th>
										<th width="6%"><p>10</p><p>4:50-5:40</p></th>
									</tr> 
		<?php
			
			$subjects=array();
			$teachers=array();
			$subjectnames=array();
			$branch_courses=array();
			$students=array();
			//loop for each slot_id in the set
			do{
				//assign subject_id to subjects array's element at slot_id index
				$subjects[$row_rs_croom_info['slot_id']]=$row_rs_croom_info['subject_id'];
				//assign (course_id-branch_id-semester) to students array's element at slot_id index 
				$students[$row_rs_croom_info['slot_id']]=$row_rs_croom_info['course_id']. "-" .$row_rs_croom_info['branch_id']. "- Sem " .$row_rs_croom_info['semester'];
				//check for the specified key in $teachers array
				if(array_key_exists($row_rs_croom_info['subject_id'],$teachers)){
					//if key is found and it's value doesn't match with the given expression value then assign it group
					if($teachers[$row_rs_croom_info['subject_id']] != $row_rs_croom_info['salutation'] . " " . $row_rs_croom_info['first_name'] . " " . $row_rs_croom_info['middle_name'] . " " . $row_rs_croom_info['last_name']){
						$teachers[$row_rs_croom_info['subject_id']]="Group";	
					}
				}
				else{
					$teachers[$row_rs_croom_info['subject_id']]=$row_rs_croom_info['salutation'] . " " . $row_rs_croom_info['first_name'] . " " . $row_rs_croom_info['middle_name'] . " " . $row_rs_croom_info['last_name'];
				}
				$subjectnames[$row_rs_croom_info['subject_id']]=$row_rs_croom_info['subject_name'];
				
			} while($row_rs_croom_info = $rs_croom_info->fetch_assoc());
			
			//array of 5 days
			$day=array("MONDAY","TUESDAY","WEDNESDAY","THURSDAY","FRIDAY");
			//for loop run 5 times, one time for each day
			for ($i=0; $i<5; $i++){
				echo "<tr class='heading'>";
				echo "<th>". $day[$i] ."</th>";
				//loop run 10 times, one time for each slot in a day
				for($j=1; $j<=10; $j++){
					echo "<td class='entry' ";
					if(isset($subjects[$i*10+$j])){
						echo "title='Faculty: ".$teachers[$subjects[$i*10+$j]];
						echo " and ";
						echo "Students: ".$students[$i*10+$j];
						echo " and Subject: ".$subjectnames[$subjects[$i*10+$j]];
						}
					echo "'><center>";
					if (array_key_exists($i*10+$j,$subjects)){
						echo $subjects[$i*10+$j];
						echo "<br>";
					}
					echo "</center></td>";
				}
				echo "</tr>";
  	}
?>

</table>
</br>
<center>
<button id="printBtn">Print</button>
</div>
<div class="hidden_div" style="display:none ;color: #009;">
	<?php
		$usubs=array_unique($subjects);
		echo "<center>";
		foreach($usubs as $usub)
		{
			echo $usub ." ". $subjectnames[$usub] ." ( ". $teachers[$usub] .")";
			echo "<br>";
		}
		echo "</center>";
	?>
</center>
</div>
	<style type="text/css" media="print">
			
			.print-content {
				display: block;
				width: parent;
				height: auto;
				margin: auto;
				position: relative;
				top: 0;
				left: 0;
			}
		</style>	
		<script type="text/javascript">
		var pr = document.getElementById("printBtn");
		$(".print").hide();
		$("#printBtn").click(function() {
			$(".-feedback-search-bar, .-feedback-navbar, .-feedback-footer").hide();
			$(".-feedback-content").addClass("print-content");
			$(".hidden_div").show();
			$(this).hide();
			window.print();
			$(".-feedback-search-bar, .-feedback-navbar, .-feedback-footer").show();
			$(".-feedback-content").removeClass("print-content");
			$(".hidden_div").hide();
			$(this).show();

		});
	</script>	
	
<?php }
//to draw footer
drawFooter(); 
?>
</body>