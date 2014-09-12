<?php
	//including feedbacklayout php file 	
	require_once("../Includes/FeedbackLayout.php");

	//to draw header drawHeader() is calling
	drawHeader(); 

//to include conn_time_table.php to make connection with database		
require_once('Connections/conn_time_table.php');

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

//initializing some variables
$maxRows_rs_faculty = 25;
$pageNum_rs_faculty = 0;

//assign the posted value to the variable
if (isset($_GET['pageNum_rs_faculty'])) {
  $pageNum_rs_faculty = $_GET['pageNum_rs_faculty'];
}

$startRow_rs_faculty = $pageNum_rs_faculty * $maxRows_rs_faculty;

//selecting required database
mysql_select_db($database_conn_time_table, $conn_time_table);

$query_rs_faculty = "SELECT emp_id, salutation, first_name, middle_name, last_name, design, dept_id FROM feedback_faculty";
$query_limit_rs_faculty = sprintf("%s LIMIT %d, %d", $query_rs_faculty, $startRow_rs_faculty, $maxRows_rs_faculty);
//perform query
$rs_faculty = mysql_query($query_limit_rs_faculty, $conn_time_table) or die(mysql_error());
//each call returns a row from the database pointed by $rs_faculty
$row_rs_faculty = mysql_fetch_assoc($rs_faculty);

if (isset($_GET['totalRows_rs_faculty'])) {
  $totalRows_rs_faculty = $_GET['totalRows_rs_faculty'];
} 
else {
  $all_rs_faculty = mysql_query($query_rs_faculty);
  $totalRows_rs_faculty = mysql_num_rows($all_rs_faculty);
}
$totalPages_rs_faculty = ceil($totalRows_rs_faculty/$maxRows_rs_faculty)-1;

$queryString_rs_faculty = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rs_faculty") == false && 
        stristr($param, "totalRows_rs_faculty") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rs_faculty = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rs_faculty = sprintf("&totalRows_rs_faculty=%d%s", $totalRows_rs_faculty, $queryString_rs_faculty);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<p><a href="student_select.php">Check Student's Time Table</a><br />
</p>
<p><a href="classroom_select.php">Check ClassRoom's Time Table</a></p>
<p><a href="<?php printf("%s?pageNum_rs_faculty=%d%s", $currentPage, 0, $queryString_rs_faculty); ?>">First</a> | <a href="<?php printf("%s?pageNum_rs_faculty=%d%s", $currentPage, max(0, $pageNum_rs_faculty - 1), $queryString_rs_faculty); ?>">Previous</a> | <a href="<?php printf("%s?pageNum_rs_faculty=%d%s", $currentPage, min($totalPages_rs_faculty, $pageNum_rs_faculty + 1), $queryString_rs_faculty); ?>">Next</a> | <a href="<?php printf("%s?pageNum_rs_faculty=%d%s", $currentPage, $totalPages_rs_faculty, $queryString_rs_faculty); ?>">Last</a></p>
<table width="1018" border="1">
  <tr>
    <td width="122">Emp_id</td>
    <td width="133"><p>Salutation</p></td>
    <td width="139">First Name</td>
    <td width="157">Middle Name</td>
    <td width="137">Last Name</td>
    <td width="115">Designation</td>
    <td width="123">Dept_id</td>
    <td width="44">Check Time Table</td>
  </tr>
  <tr>
    <td colspan="8">&nbsp;</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="faculty_subject.php?eid=<?php echo $row_rs_faculty['emp_id']; ?>"><?php echo $row_rs_faculty['emp_id']; ?></a></td>
      <td><?php echo $row_rs_faculty['salutation']; ?></td>
      <td><?php echo $row_rs_faculty['first_name']; ?></td>
      <td><?php echo $row_rs_faculty['middle_name']; ?></td>
      <td><?php echo $row_rs_faculty['last_name']; ?></td>
      <td><?php echo $row_rs_faculty['design']; ?></td>
      <td><?php echo $row_rs_faculty['dept_id']; ?></td>
      <td><a href="view_faculty.php?emp_id=<?php echo $row_rs_faculty['emp_id']; ?>">See Time Table</a></td>
    </tr>
    <?php } while ($row_rs_faculty = mysql_fetch_assoc($rs_faculty)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($rs_faculty);
?>
<?php drawFooter(); ?>