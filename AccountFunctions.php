<?php
	$timetable=array();
	$timetable["Manage Timetable"]=array();
	//var_dump($_SESSION['auth']);
	if(is_auth("hod")){
		$timetable["Manage Timetable"]["View Timetable"]=array();
		$timetable["Manage Timetable"]["Edit Timetable"]="faculty_time_table_edit.php";
		$timetable["Manage Timetable"]["Enter Timetable"]="faculty_time_table_enter.php";			$timetable["Manage Timetable"]["View Timetable"]["For Firstyears"]="view_firstyr_faculty.php";
		$timetable["Manage Timetable"]["View Timetable"]["For Secondyear onwards"]="view_faculty.php";
		$timetable["Manage Timetable"]["View Timetable"]["For a Classroom"]="classroom_select.php";
		$timetable["Manage Timetable"]["View Timetable"]["For a Branch"]="student_select.php";			$timetable["Manage Timetable"]["View Timetable"]["For a Section (Firstyear)"]="section_select.php";
	}
	else if(is_auth("stu") || is_auth("ft")){
		$timetable["Manage Timetable"]["View Timetable"]=array();
		if(is_auth("stu"))
			$timetable["Manage Timetable"]["View Timetable"]["For You"]="student.php";
		else{
			$timetable["Manage Timetable"]["View Timetable"]["For Firstyears"]="view_firstyr_faculty.php";
			$timetable["Manage Timetable"]["View Timetable"]["For Secondyear onwards"]="view_faculty.php";
		}
		$timetable["Manage Timetable"]["View Timetable"]["For a Classroom"]="classroom_select.php";
		$timetable["Manage Timetable"]["View Timetable"]["For a Branch"]="student_select.php";
		$timetable["Manage Timetable"]["View Timetable"]["For a Section (firstyear)"]="section_select.php";
	}
	else if(is_auth("deo")){
		$timetable["Manage Timetable"]["View Timetable"]=array();
		$timetable["Manage Timetable"]["View Timetable"]["For a Classroom"]="classroom_select.php";
		$timetable["Manage Timetable"]["View Timetable"]["For a Branch"]="student_select.php";
		$timetable["Manage Timetable"]["View Timetable"]["For a Section (firstyear)"]="section_select.php";
		$timetable["Manage Timetable"]["Enter Timetable for a Section (firstyear)"]="dataoperator_enter_timetable.php";
		$timetable["Manage Timetable"]["Assign Classroom to Class"]=array();
		$timetable["Manage Timetable"]["Assign Classroom to Class"]["For Firstyears"]="assign_classroom_firstyear.php";
		$timetable["Manage Timetable"]["Assign Classroom to Class"]["For Secondyear onwards"]="assign_classroom.php";
		$timetable["Manage Timetable"]["Assign Subject to Faculty"]="assign_subject.php";
		//$timetable["Manage Timetable"]["Set Timelength to Period"]="set_time.php";
		$timetable["Manage Timetable"]["Manage Classrooms"]=array();
		$timetable["Manage Timetable"]["Manage Classrooms"]["Add classrooms"]="adding_classrooms.php";
		$timetable["Manage Timetable"]["Manage Classrooms"]["Block classrooms"]=array();
		$timetable["Manage Timetable"]["Manage Classrooms"]["Block classrooms"]["Block Building"]="block_building.php";
		$timetable["Manage Timetable"]["Manage Classrooms"]["Block classrooms"]["Block Room"]="block_room.php";
	}
	else
		$timetable["Manage Timetable"]="#";
?>
