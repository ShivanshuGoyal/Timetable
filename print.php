<?php
	//including feedbacklayout php file 	
	require_once("../Includes/FeedbackLayout.php");
 
	//to draw header drawHeader() is calling
	drawHeader("TimeTable Info System");  
?>
	<style type="text/css">
		.print-content {
			display: block;
			width: parent;
			height: auto;
			margin: auto;
			position: relative;
			top: 0;
			left: 0;
		}
	</style>

		sfjda
		d
		afds
		af
		ds
		fds
		af
		<a href="#" id="printBtn">Print This Page</a>
	
		<script type="text/javascript">
		var pr = document.getElementById("printBtn");
		$(".print").hide();
		$("#printBtn").click(function() {
			$(".-feedback-search-bar, .-feedback-navbar, .-feedback-footer").hide();
			$(".-feedback-content").addClass("print-content");
			window.print();
			$(".-feedback-search-bar, .-feedback-navbar, .-feedback-footer").show();
			$(".-feedback-content").removeClass("print-content");

		});
	</script>
<?php
	drawFooter();
?>