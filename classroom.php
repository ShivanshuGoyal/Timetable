<?php
	//including feedbacklayout php file 	
	require_once("../Includes/FeedbackLayout.php");
 
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
<div id="heading">
	<h1><u><b><center>Time Table Information System</center></b></u></h1>
</div>
<div class="class_room">
	<center><h3><a href="#" onclick="history.go(-1)">GO BACK</a></h3></center>

<head>
<script>
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
			document.getElementById("classroom_rooms").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","getroom.php?b=" + str,true);

	xmlhttp.send();
}
</script>

<style type="text/css">
@import url("template.css");

.class_room table tr .heading {
	font-weight: bolder;
	color: #FFF;
	margin: 1px;
	padding: 1px;
	border: thin solid #003;
}
.class_room table tr .entry {
	font-weight: bold;
	color: #000;
	background-color: #fff;
	margin: 1px;
	padding: 1px;
	border: thin solid #003;
}
.class_room{
background-color: rgb(238,238,238);
margin:-.7px;
margin-left:22px;
margin-right:22px;
}
</style>
</head>

<?php
//to include conn_time_table.php to make connection with database	
require_once('Connections/conn_time_table.php'); 

//to assign posted values to session variables in case of having posted value of sid
if(isset($_POST["sid"]))
{
	$_SESSION['sid']=$_POST["sid"];
	$_SESSION['sun']=$_POST["sun"];
	$_SESSION['cid']=$_POST["cid"];
	$_SESSION['bid']=$_POST["bid"];
	$_SESSION['sem']=$_POST["sem"];
}
?>
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
$query_rs_classroom_building = "SELECT * FROM classroom ORDER BY building ASC";

//perform query
$rs_classroom_building = mysql_query($query_rs_classroom_building, $conn_time_table) or die(mysql_error());

//each call returns a row from the recordset given by the result of mysql_query() i.e. $rs_classroom_building
$row_rs_classroom_building = mysql_fetch_assoc($rs_classroom_building);

//to find the total number of rows in the set
$totalRows_rs_classroom_building = mysql_num_rows($rs_classroom_building);mysql_select_db($database_conn_time_table, $conn_time_table);
$query_rs_classroom_building = "SELECT Distinct building FROM classroom ORDER BY building ASC";
$rs_classroom_building = mysql_query($query_rs_classroom_building, $conn_time_table) or die(mysql_error());
$row_rs_classroom_building = mysql_fetch_assoc($rs_classroom_building);
$totalRows_rs_classroom_building = mysql_num_rows($rs_classroom_building);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<center>


<table width="200" border="1">
  <tr>
    <th class="heading">Subject ID</th>
    <td class="entry"><?php echo $_SESSION['sid']; ?></td>
  </tr>
  <tr>
    <th class="heading">Subject Name</th>
    <td class="entry"><?php echo $_SESSION['sun']; ?></td>
  </tr>
  <tr>
    <th class="heading">Course ID</th>
    <td class="entry"><?php echo $_SESSION['cid']; ?></td>
  </tr>
  <tr>
    <th class="heading">Branch ID</th>
    <td class="entry"><?php echo $_SESSION['bid']; ?></td>
  </tr>
  <tr>
    <th class="heading">Semester</th>
    <td class="entry"><?php echo $_SESSION['sem']; ?></td>
  </tr>
</table>

<p>
<form action=""> 
  <label for="list_classroom_building">Select Building</label>
  <select name="list_classroom_building" id="list_classroom_building" onChange="showRoom(this.value)">
   <option value="null" selected="selected" disabled="disabled">Select Building</option>
	<?php
do {  
?>
    <option value="<?php echo $row_rs_classroom_building['building']?>"><?php echo $row_rs_classroom_building['building']?></option>
    <?php
} while ($row_rs_classroom_building = mysql_fetch_assoc($rs_classroom_building));
  $rows = mysql_num_rows($rs_classroom_building);
  if($rows > 0) {
      mysql_data_seek($rs_classroom_building, 0);
	  $row_rs_classroom_building = mysql_fetch_assoc($rs_classroom_building);
  }
?>
  </select>
</form>
<br>

<div id="classroom_rooms">

</div>

  <br />
</p>
</div>
</center>
</body>
</html>
<?php
//to free up $rs_classroom_building
mysql_free_result($rs_classroom_building);

//to draw footer
drawFooter(); 
?>