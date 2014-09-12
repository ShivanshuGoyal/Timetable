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
?>
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
<center>
<div id="heading">
	<h1><u><b><center>Time Table Information System</center></b></u></h1>
</div>
<br/>
<h2>Enter Timetable for a Section (Firstyear)</h2>
<br/>
<?php
  echo '
		<form name="form" action="dataoperator_timetable_enter.php" method="post">
		
		<table>';
		$date=Date("Y");
			echo '
				<tr>
				<td>
				Select Session:
				</td>
				<td>
				<select name="session">
				<option value="">Select Session</option>
				<option value="'.($date-1).'-'.$date.'">'.($date-1).'-'.$date.'</option>
				<option value="'.$date.'-'.($date+1).'">'.$date.'-'.($date+1).'</option>
				</select>
				</td>
				</tr>';
		?>
		<tr>
		<td>
		Select Semester:
		</td>
		<td>
		<select name="semester">
		<option value="">Select Semester</option>
		<option value="1">1</option>
		<option value="2">2</option>
		</select>
		</td>
		</tr>
		<tr>
		<td>
		Select Section:
		</td>
		<td>
		<select name="section">
		<option value="">Select Section</option>
		<option value="A">A</option>
		<option value="B">B</option>
		<option value="C">C</option>
		<option value="D">D</option>
		<option value="E">E</option>
		<option value="F">F</option>
		<option value="G">G</option>
		<option value="H">H</option>
		<option value="I">I</option>
		<option value="J">J</option>
		</select>
		</td>
		</tr>
		</table>
		<div>
		<br/>
		<input type="submit" value="Submit"/>
		</div>
		</center>
		</form>