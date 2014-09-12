<?php
	//including layout, authentication and configuration php files 	
	require_once("../Includes/Layout.php");
	require_once("../Includes/Auth.php");
	require_once("../Includes/ConfigSQL.php");
	auth("ft","stu","hod");
 
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
</style>

<div id="heading">
	<h1><u><b><center>Time Table Information System</center></b></u></h1>
</div>

<div class="class_room">
	<center><h3><a href="#" onclick="history.go(-1)">GO BACK</a></h3></center>
	
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
	q = "classroom_select_room.php?b=" + str;
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

$query_rs_building = "SELECT Distinct building FROM timetable_classrooms where status='occupied'";
//perform query
$rs_building = $mysqli->query($query_rs_building) or die(mysql_error());
//each call returns a row from the recordset given by the result of mysql_query() i.e. $rs_building
$row_rs_building = $rs_building->fetch_assoc();

?>
<center>

<form id="form1" name="form1" method="post" action="">
  <label for="select_building2">Select Building</label>
  <select name="select_building" id="select_building2" onChange="showRoom(this.value)">
	<option value="null" selected="selected" disabled="disabled">Select Building</option>
   <?php
//loop to print all the buildings
do {  
?>
    <option value="<?php echo $row_rs_building['building']?>"><?php echo $row_rs_building['building']?></option>
    <?php
} while ($row_rs_building = $rs_building->fetch_assoc());
?>
  </select>
</form>
<div id="div_room"></div>
</div>
</center>
<?php
//to draw Footer
drawFooter(); 
?>