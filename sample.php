<?php
	if(isset($_POST['submit']))
	{

		if(isset($_POST['sss']))
		{
			Print "<script>alert('".$_POST['sss']."')</script>";
		}
		if(isset($_POST['philhealth']))
		{
			Print "<script>alert('".$_POST['philhealth']."')</script>";
		}
		if(isset($_POST['pagibig']))
		{
			Print "<script>alert('".$_POST['pagibig']."')</script>";
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<form method="POST" >
		<input type="checkbox" name="sss" value="sss">
		<input type="checkbox" name="philhealth" value="philhealth">
		<input type="checkbox" name="pagibig" value="pagibig">
		<input type="submit" name="submit">
	</form>
</body>
</html>

