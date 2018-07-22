<?php
	if(isset($_POST['submit']))
	{

		Print "<script>alert('".$_POST['textB'][0]."')</script>";
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<form method="POST" >
		<input type="text" name="textB[]" >
		<input type="text" name="textB[]" value="yeah">
		<input type="text" name="textB[]" value="yow">
		<input type="text" name="textB[]" value="yeah">
		<input type="submit" name="submit">
	</form>
</body>
</html>

