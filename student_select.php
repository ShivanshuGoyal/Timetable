<?php
//including layout, authentication and configuration php files 	
	require_once("../Includes/Layout.php");
	require_once("../Includes/Auth.php");
	require_once("../Includes/ConfigSQL.php");
	auth("stu","ft","hod");
	 
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

<script language="javascript" type="text/javascript">
window.onload = function(){
	document.getElementById("select_course").focus();
}
function getXMLHTTP() { //fuction to return the xml http object
       var xmlhttp=false;  
       try{
           xmlhttp=new XMLHttpRequest();
       }
       catch(e){
            try{            
               xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
            }
           catch(e){
               try{
               xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
               }
               catch(e1){
                   xmlhttp=false;
               }
           }
       }
        return xmlhttp;
   }



function getBranch()
{
	
	var course_id = document.getElementById("select_course").value;
	var temp;
	//document.write(temp);
	var req = getXMLHTTP();
	var strURL="branch.php?course_id=" + escape(course_id);
		//alert(i);
        if (req) {
			req.onreadystatechange = function() {
                if (req.readyState == 4) {
                    if (req.status == 200) {
//	alert(req.responseText);
						temp=req.responseText;
						document.getElementById("selection").innerHTML=temp;
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                }               
            }           
            req.open("GET", strURL, true);
            req.send(null);
        }
}



</script>
<style type="text/css">
@import url("main.css");
@import url("template.css");
.branch-course {
	color: #000;
	background-color: rgb(238,238,238);
	margin: 0px;
	margin-left:22px;
	margin-right:22px;
	padding: 2px;
}
</style>
<div id="heading">
	<h1><u><b><center>Time Table Information System</center></b></u></h1>	
</div>
<div class="branch-course">
<center><h3><a href="#" onclick="history.go(-1)">GO BACK</a></h3></center>

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
//gives a relation having course_id and course_name
$query_rs_course = "SELECT * FROM courses";
//perform query
$rs_course = $mysqli->query($query_rs_course) or die(mysql_error());
//gives a relation having branch_id and branch_name
$query_rs_branch = "SELECT * FROM branches";
//perform query
$rs_branch = $mysqli->query($query_rs_branch) or die(mysql_error());
?>

<center>

<form name="form1" method="POST" action="view_student.php">
  <p>
    <label>Select Course:</label>
    <select name="course_id" id="select_course" onchange="getBranch()">
	<option value="null" disabled="disabled" selected="selected">Select Course</option>
      <?php
//while loop to print all the course names
while ($row_rs_course = $rs_course->fetch_assoc()) {  
?>
      <option value="<?php echo $row_rs_course['id']?>"><?php echo $row_rs_course['name']?></option>
      <?php
}
  //gives number of rows as much as we have courses
  $rows = $rs_course->num_rows;
  if($rows > 0) {
      //moves the internal row pointer of the MySQL result associated with the specified rs_course identifier to point to the specified row(0) 
	  mysql_data_seek($rs_course, 0);
	  //fetches first row as we have pointer at 0 index
	  $row_rs_course = $rs_course->fetch_assoc();
  }
?>
    </select>
  </p>
  <div id="selection">
  </div>
  <p>
    <input type="submit" name="button_submit" id="button_submit" value="Continue">
  </p>
</form>


<?php
//to free up $rs_course and $rs_branch
mysql_free_result($rs_course);

mysql_free_result($rs_branch);
?>
</div>

</center>
<?php 
//to draw footer
drawFooter(); 
?>