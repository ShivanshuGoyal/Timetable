<script type="text/javascript">
	function setPrintBtn(){
		var pr = document.getElementById("printBtn");
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
	}
</script>
<?php
	//including feedbacklayout php file 	
	require_once("../Includes/FeedbackLayout.php");
 
	require_once("../Includes/AuthDo.php");
	
	//to draw header drawHeader() is calling
	drawHeader("TimeTable Info System"); 

//initialize the session if it is not initialized
if (!isset($_SESSION)) {
  session_start();
}

//to redirect to logout.php page if user is not logged in
if (!isset($_SESSION['SESS_USERNAME'])) {
	header("Location:../Logout.php");
}
?>

<style type="text/css">
@import url("main.css");
@import url("template.css");
.class_room{
margin:0px;
margin-left:22px;
margin-right:22px;
background-color:rgb(238,238,238);
padding:5px;
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
<script type="text/javascript">
function func1(){
	$("div.enter_div").hide();
	$("div.view_div").show();
}			
function func2(){
		$("div.view_div").hide();
	$("div.enter_div").show();
	
}			


</script>
<div class="class_room">
	<center><h3><a href="#" onclick="func1()">View TimeTable For A Section</a> | <a href="#" onclick="func2()">Enter TimeTable For A Section</a></h3></center>
	<?php
		//to include conn_time_table.php to make connection with database	
		require_once('Connections/conn_time_table.php'); ?>

<script>
<?php   //function for Ajax   ?>
function showRoom(str){
	var xmlhttp;    
	if (str==""){
		document.getElementById("view_table").innerHTML="";
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
			document.getElementById("view_table").innerHTML = xmlhttp.responseText;
			setPrintBtn();
		}
	}
	q = "select_sec.php?b=" + str;
	xmlhttp.open("GET",q,true);

	xmlhttp.send();
}

function showfaculty(str){
	var xmlhttp;    
	if (str==""){
		document.getElementById("select_faculty").innerHTML="";
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
			document.getElementById("select_faculty").innerHTML = xmlhttp.responseText;
		}
	}
	q = "get_faculty.php?b=" + str;
	xmlhttp.open("GET",q,true);

	xmlhttp.send();
}
</script>


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

//selecting required database
mysql_select_db($database_conn_time_table, $conn_time_table);

?>
<center>
<div class="view_div" style="display:none">
<form id="form" name="form" method="post" action="">
  <label for="select_section1">Select Section</label>
  <select name="select_section" id="select_section" onChange="showRoom(this.value)">
	<option value="null" selected="selected" disabled="disabled">Select Section</option>
	<option value="A" >A</option>
	<option value="B" >B</option>
	<option value="C" >C</option>
	<option value="D" >D</option>
	<option value="E" >E</option>
	<option value="F" >F</option>
	<option value="G" >G</option>
	<option value="H" >H</option>
	<option value="I" >I</option>
	<option value="J" >J</option>
  </select>
</form>

<div id="view_table" style="margin:0%"></div>

</div>
<div class="enter_div" style="display:none">
<form id="form1" name="form1" method="post" action="dataoperator_entertimetable.php">
<center><table>
  <tr><td><label for="select_section1">Select Section</label></td>
  <td>
  <select name="select_sections" id="select_sections">
	<option value="null" selected="selected" disabled="disabled">Select Section</option>
	<option value="A" >A</option>
	<option value="B" >B</option>
	<option value="C" >C</option>
	<option value="D" >D</option>
	<option value="E" >E</option>
	<option value="F" >F</option>
	<option value="G" >G</option>
	<option value="H" >H</option>
	<option value="I" >I</option>
	<option value="J" >J</option>
	</select>
  </td>
  </tr><tr>
  <td><label for="sem_type">Semester Type</label></td>
  <td>
  <select name="semtype"><option value="EASY">EASY</option><option value="HARD">HARD</option></select></td>
  </tr>
  <tr>
  <td><label for="semeter">Semester</label></td><td>
  <select name="sem"><option value="1">1</option><option value="2">2</option></select></td>
  </tr>
  <?php
  $query_subjects="SELECT * FROM feedback_subject WHERE subject_id IN ( SELECT DISTINCT subject_id FROM `feedback_subjectdetails` WHERE semester IN ( 1, 2 ))";
  $result_subjects=mysql_query($query_subjects) or die(mysql_error());
  $row_subjects=mysql_fetch_assoc($result_subjects);
    $row_subjects=mysql_fetch_assoc($result_subjects);
  //$query_faculty="SELECT * FROM feedback_faculty WHERE emp_id IN(SELECT DISTINCT emp_id FROM feedback_subjectfaculty WHERE subject_id IN ( SELECT DISTINCT subject_id FROM `feedback_subjectdetails` WHERE semester IN ( 1, 2 )))";
  //$result_faculty=mysql_query($query_faculty) or die(mysql_error());
  //$row_faculty=mysql_fetch_assoc($result_faculty);
  ?>
  <tr><td><label for="select_sub">Select Subject</label></td>
  <td><select name="subject" id="subject" onchange="showfaculty(this.value)">
	<option value="null" selected="selected" disabled="disabled">Select Subject</option>
   <?php
do {  
?>
    <option value="<?php echo $row_subjects['subject_id']?>"><?php echo $row_subjects['subject_name']?></option>
    <?php
} while ($row_subjects = mysql_fetch_assoc($result_subjects));
?>
  </select></td></tr>
  <tr id="select_faculty"></tr>
  </table>
  <input type="submit" value="Submit" name="entersubmit"/></center>
</form>

</div>
</div>
</center>
<?php
//to draw Footer
drawFooter(); 
?>