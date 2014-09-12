<?php
//including layout, authentication and configuration php files 	
	require_once("../Includes/Layout.php");
	require_once("../Includes/Auth.php");
	require_once("../Includes/ConfigSQL.php");
	auth("stu","ft","hod");

//initialize the session if it is not initialized
if (!isset($_SESSION)) {
  session_start();
}

//to redirect to logout.php page if user is not logged in
if (!isset($_SESSION['id'])) {
	header("Location:../Logout.php");
}

//gives a relation having branch_id and branch_name
$query_rs_section = "SELECT section FROM course_branch_section WHERE branch_id = '".$_GET['branch_id']."'";
//perform query
$rs_section = $mysqli->query($query_rs_section) or die(mysql_error());
?>
<p>
    <label>Select Section:</label>
    <select name="section" id="section">
      <option value="null" selected="selected" disabled="disabled">Select Section</option>
		<?php
			while ($row_rs_section = $rs_section->fetch_assoc()) {  
			//to print branch names
		?>
			<option value="<?php echo $row_rs_section['section']?>"><?php echo $row_rs_section['section']?></option>
			<?php 
		}
			//returns number of rows
			$rows = $rs_section->num_rows;
			if($rows > 0) {
				mysql_data_seek($rs_section, 0);
				$row_rs_section = $rs_section->fetch_assoc();
			}
		?> 
    
	</select>
  </p>
