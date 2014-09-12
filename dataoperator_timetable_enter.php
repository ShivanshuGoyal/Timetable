<script type="text/javascript">
	function getFaculty(str,str2){
		var xmlhttp;    
	if (str==""){
		document.getElementById("faculty_div").innerHTML="";
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
			document.getElementById("faculty_div").innerHTML = xmlhttp.responseText;
		}
	}
	q = "select_faculty.php?b=" + str + "&session=" + str2;
	xmlhttp.open("GET",q,true);

	xmlhttp.send();		
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
<script>
$(document).ready(function(){
	$("td.entry").click(function(){
		window.currentEntry = this;
		$("select[name='subject']").parent().show();
		$("select[name='subject'], select[name='faculty']").val(null);
		$("select[name='subject']").change(function(){
			$($(currentEntry).find("input")[0]).val(this.value);
		});
		$("select[name='faculty']").change(function(){
			$($(currentEntry).find("input")[1]).val(this.value);
		});
		
	});
});
</script>
<div id="heading">
	<h1><u><b><center>Time Table Information System</center></b></u></h1>
</div>

<div id="view_table" >
	<center><h3><?php echo 'Timetable for Semester '.$_POST['semester'].', Section '.$_POST['section'].' in Session ('.$_POST['session'].')'; ?></h3>

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
?>	
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
				echo '<form name="form" action="dtimetable_enter.php" method="post">';?>
						<input type="hidden" name="section" value="<?php echo $_POST['section'];?>"/>
						<input type="hidden" name="semester" value="<?php echo $_POST['semester'];?>"/>
						<input type="hidden" name="session" value="<?php echo $_POST['session'];?>"/>
						
			
			<?php	
				$free_slot_query="select slot_id from timetable_info_firstyear where section='".$_POST['section']."' and semester='".$_POST['semester']."' and session='".$_POST['session']."'";
				
				$array_slot_id=array();
				$free_slot_ans=$mysqli->query($free_slot_query);
				while($row_slot_id=$free_slot_ans->fetch_assoc()){
					$array_slot_id=$row_slot_id['slot_id'];
				}
				//for loop run 5 times,one time for each day
				for ($i=0; $i<5; $i++){
					echo "<tr>";
					echo "<th>". $day[$i] ."</th>";
					for($j=1; $j<=10; $j++){
						if(in_array(($i*10+$j),$array_slot_id)){
							echo "<td style='backgroundColor:red'>";
						}
						else{
							echo "<td class='entry' id='".($i*10+$j)."' value='".($i*10+$j)."' style='cursor:hand;' onclick='getSubjectFaculty(this.value)'>";
						}
						echo"<input type='hidden' value='' id='subject".($i*10+$j)."' name='subject".($i*10+$j)."'/>
						<input type='hidden' value='' id='faculty".($i*10+$j)."' name='faculty".($i*10+$j)."'/>
						</td>";
					}
					echo "</tr>";
				}
			$semester=$_POST['semester']+0;
			if($semester%2==0)
				$year=Date("Y")-($semester/2);
			else
				$year= Date("Y")-(($semester+1)/2) + 1;
			
			$subject_id_query="select subject_id as id,name from course_structure, course_branch_section, subjects where subjects.subject_id= course_structure.subject_id and  course_structure.course_structure_id=course_branch_section.course_structure_id and semester='".$_POST['semester']."' and course_id='CMN' and branch_id='CMN' and section='".$_POST['section']."' and year='".$year."' ";
			
			$subject_id_ans=$mysqli->query($subject_id_query);
				
			
			?>

			</table>
			<input type='submit' name='submit' value='Submit'/>
			</form>
			</div>
			<div id="hiddendiv" style="display: none;" align="center">
				<select name="subject" onchange="getFaculty(this.value,document.form.form['session'].value)">
					<option>Select Subject</option>
					<?php
					while($row_subject_id=$subject_id_ans->fetch_assoc()){
						echo '<option value="'.$row_subject_id['id'].'">'.$row_subject_id['name'].'</option>';
					}
					?>
				</select>
				<div id="faculty_div">
				
				</div>
				<br />
				<button onclick="(function(){document.getElementById('hiddendiv').style.display='none';
					window.currentEntry.style.backgroundColor='red'; $(window.currentEntry).unbind('click'); delete window.currentEntry;})()">Add</button>
			</div>
			</center>
<?php	
//to draw footer
drawFooter(); 
?>