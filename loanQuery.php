<?php
	include_once('directives/db.php');

	// mysql_query("UPDATE loans SET date = 'September 16, 2018' WHERE date = 'September 20, 2018'");
	// mysql_query("UPDATE loans SET date = 'September 17, 2018' WHERE date = 'September 21, 2018'");
	// mysql_query("UPDATE loans SET date = 'September 18, 2018' WHERE date = 'September 22, 2018'");

	$employee = mysql_query("SELECT * FROM employee");
	while($empArr = mysql_fetch_assoc($employee))
	{
		$empid = $empArr['empid'];
		$loansLoaned = "SELECT * FROM loans WHERE type = 'oldVale' AND empid = '$empid' AND action = '1' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC, id DESC LIMIT 1";

		$loansLoanedQuery = mysql_query($loansLoaned);
		// Print mysql_num_rows($loansLoanedQuery);
		if(mysql_num_rows($loansLoanedQuery) == 1)
		{
			
			// get the loaned balance
			$loanedArr = mysql_fetch_assoc($loansLoanedQuery);
			$loanedBalance = $loanedArr['balance'];

			// get the Deduction amount

			$loansDeduct = "SELECT * FROM loans WHERE type = 'oldVale' AND empid = '$empid' AND action = '0' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC, id DESC LIMIT 1";
			$loansDeductQuery = mysql_query($loansDeduct);
			$deductArr = mysql_fetch_assoc($loansDeductQuery);

			$deductAmount = $deductArr['amount'];

			$correctAmount = $loanedBalance - $deductAmount;
			$correctAmount = abs($correctAmount);// Absolute the result

			// Update the deduction
			$updateLoan = "UPDATE loans SET balance = '$correctAmount' WHERE empid = '$empid' AND type = 'oldVale' AND action = '0' LIMIT 1";
			mysql_query($updateLoan);
			Print $updateLoan."<br>";
		}
	}

?>