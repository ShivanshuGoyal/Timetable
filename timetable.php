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

//to redirect to index.php page if user is logged in but it doesn't have posted value of list_classroom_room
else if(isset($_SESSION['SESS_USERNAME']) && !isset($_POST['list_classroom_room'])){
	header("Location:index.php");
}

if(isset($_POST['list_classroom_room'])){
	$_SESSION['list_classroom_room'] = $_POST['list_classroom_room'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
@import url("main.css");
@import url("template.css");
.info{
margin-left:22px;
margin-right:22px;
}
table tr td, table tr th {
	padding: 2px;
	margin: 0;
}
.info table tr td {
text-align:center;
}
.entry tr td{
	background-color:#fff;
}

</style>
</head>

<body>
<div id="heading">
	<h1><u><b><center>Time Table Information System</center></b></u></h1>
</div>
<div class="info">
<center><h3><a href="#" onclick="history.go(-1)">GO BACK</a></h3></center>

<center>
<table width="783" border="1">
  <tr class="heading">
    <th width="95">Emp ID</th>
    <th width="80">Subject ID</th>
    <th width="129">Course ID</th>
    <th width="133">Branch ID</th>
    <th width="125">Building</th>
    <th width="181">Room</th>
    <th width="125">Semester</th>
  </tr>
  <tr class="entry">
    <td><?php echo $_SESSION['SESS_USERNAME'] ?></td>
    <td><?php echo $_SESSION['sid'] ?></td>
    <td><?php echo $_SESSION['cid'] ?></td>
    <td><?php echo $_SESSION['bid'] ?></td>
    <td><?php echo $_SESSION['b'] ?></td>
    <td><?php echo $_SESSION['list_classroom_room']?></td>
    <td><?php echo $_SESSION['sem'] ?></td>
  </tr>
</table>
</center>


<br />
<center>
<form id="time_table" name="time_table" method="post" action="timetable_insert.php">
<input type='hidden' name='building' value="<?php echo $_SESSION['b'] ?>">
<input type='hidden' name='room' value="<?php echo $_SESSION['list_classroom_room'] ?>">
<input type='hidden' name='eid' value="<?php echo $_SESSION['SESS_USERNAME'] ?>">
<input type='hidden' name='sid' value="<?php echo $_SESSION['sid'] ?>">
<input type='hidden' name='cid' value="<?php echo $_SESSION['cid'] ?>">
<input type='hidden' name='bid' value="<?php echo $_SESSION['bid'] ?>">
<input type='hidden' name='sem' value="<?php echo $_SESSION['sem'] ?>">

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
  <tr>
    <th>Monday</th>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="1" /></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="2"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="3"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="4"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="5"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="6"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="7"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="8"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="9"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="10"/></td>
    
  </tr>
  <tr>
    <th>Tuesday</th>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="11" /></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="12"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="13"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="14"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="15"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="16"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="17"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="18"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="19"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="20"/></td>
  </tr>
  <tr>
    <th>Wednesday</th>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="21" /></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="22"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="23"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="24"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="25"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="26"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="27"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="28"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="29"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="30"/></td>
  </tr>
  <tr>
    <th>Thursday</th>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="31" /></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="32"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="33"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="34"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="35"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="36"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="37"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="38"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="39"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="40"/></td>
  </tr>
  <tr>
    <th>Friday</th>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="41"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="42"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="43"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="44"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="45"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="46"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="47"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="48"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="49"/></td>
    <td><input type="checkbox" name="time_slot[]" id="time_slot" value="50"/></td>
  </tr>
</table>
<br />
<input type='submit' name='submit' value='Submit'>
</form>
</center>
</div>
<?php
//to draw footer
drawFooter(); 
?>
</body>
</html>