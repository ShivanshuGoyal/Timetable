<script type="text/javascript">
function getSection()
{
	
	var branch_id = document.getElementById("select_branch").value;
	var temp;
	//document.write(temp);
	var req = getXMLHTTP();
	var strURL="section.php?branch_id=" + escape(branch_id);
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
$query_rs_branch = "SELECT DISTINCT branches.name as name, branches.id as id FROM branches,course_branch_section WHERE course_branch_section.branch_id=branches.id and course_branch_section.course_id = '".$_GET['course_id']."'";
//perform query
$rs_branch = $mysqli->query($query_rs_branch) or die(mysql_error());
?>
<p>
    <label>Select Branch:</label>
    <select name="branch_id" id="select_branch" onchange="get_section()">
      <option value="null" selected="selected" disabled="disabled">Select Branch</option>
		<?php
			while ($row_rs_branch = $rs_branch->fetch_assoc()) {  
			//to print branch names
		?>
			<option value="<?php echo $row_rs_branch['id']?>"><?php echo $row_rs_branch['name']?></option>
			<?php 
		}
			//returns number of rows
			$rows = $rs_branch->num_rows;
			if($rows > 0) {
				mysql_data_seek($rs_branch, 0);
				$row_rs_branch = $rs_branch->fetch_assoc();
			}
		?> 
    
	</select>
  </p>
  <p>
    <label>Select Semester</label>
    <select name="semester" id="select_semester">
      <option value="null">Select Semester</option>
	  <?php
		$query_rs_sem = "SELECT semester FROM course_semester WHERE course_id = '".$_GET['course_id']."'";
		//perform query
		$rs_sem = $mysqli->query($query_rs_sem) or die(mysql_error());
			while ($row_rs_sem = $rs_sem->fetch_assoc()) {  
			//to print branch names
				$noOfSem = $row_rs_sem['semester']+0;
					echo $noOfSem;
					for($i=1; $i<=$noOfSem; $i++)
						echo '<option value="'.$i.'">'.$i.'</option>';
			}	
		?>
    </select>
  </p>

  <div id="selection">
  </div>
  