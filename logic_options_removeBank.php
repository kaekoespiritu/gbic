<?php
	include('directives/session.php');
	include_once('directives/db.php');

	$bank = $_GET['bank'];

	$splitBank = explode(',', $bank);
	if(count($splitBank) > 1)
	{
		$loopCount = count($splitBank);
		for($counter = 0 ; $counter < $loopCount ; $counter++)
		{
				$temp = $splitBank[$counter];
				mysql_query("DELETE FROM banks WHERE name ='$temp'");


		}
	}
	else
	{
		mysql_query("DELETE FROM banks WHERE name ='$bank'");
	}

	Print "<script>alert('Successfully removed banks from the list.');
					window.location.assign('options.php')</script>"

?>