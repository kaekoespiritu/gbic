<!DOCTYPE html>
<?php
include('directives/db.php');
?>
<html>
	<head>
		<title>Payroll</title>
		<!-- Company Name: Green Built Industrial Corporation -->

		<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
		<link rel="stylesheet" href="css/style.css" type="text/css">
	</head>
	<body style="font-family: Quicksand;">
	<!-- 
	Vertical Navigation Bar
	HOME | EMPLOYEES | PAYROLL | REPORTS | ADMIN OPTIONS | LOGOUT
	After effects: Will minimize width after mouseover
	 -->
	 <div class="container-fluid">

	 	<?php
			require_once("directives/nav.php");
		?>
	 	
	 	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
		<script rel="javascript" src="js/jquery.min.js"></script>
		<script rel="javascript" src="js/bootstrap.min.js"></script>
		<script>
			document.getElementById("adminOptions").setAttribute("class", "active");
		</script>
	 </div>
	</body>
</html>