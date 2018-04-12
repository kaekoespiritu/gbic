<?php
	include('directives/db.php');

	$employee = "SELECT * FROM payroll";

	$employeeQuery = mysql_query($employee);

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<?php
		while($empArr = mysql_fetch_assoc($employeeQuery))
		{
			$empid = $empArr['empid'];
			$monthly = $empArr['rate'] * 25;
			$sssContribution = 0;
			$sssEmployer = 0;
			if($empArr['sss'] != 0)
			{
				if($monthly >= 1000 && $monthly <= 1249.9){
					$sssContribution = 36.30;
					$sssEmployer = 83.70;
				}
				//1250 ~ 1749.9 = 54.50
				else if($monthly >= 1250 && $monthly <= 1749.9) {
					$sssContribution = 54.50;
					$sssEmployer = 120.50;
				}
				//1750 ~ 2249.9 = 72.70
				else if($monthly >= 1750 && $monthly <= 2249.9) {
					$sssContribution = 72.70;	
					$sssEmployer = 157.30;
				}
				//2250 ~ 2749.9 = 90.80
				else if($monthly >= 2250 && $monthly <= 2749.9) {
					$sssContribution = 90.80;
					$sssEmployer = 194.20;
				}
				//2750 ~ 3249.9 = 109.0
				else if($monthly >= 2750 && $monthly <= 3249.9){
					$sssContribution = 109.00;
					$sssEmployer = 231.00;
				}
				//3250 ~ 3749.9 = 127.20
				else if($monthly >= 3250 && $monthly <= 3749.9){
					$sssContribution = 127.20;
					$sssEmployer = 267.80;
				}
				//3750 ~ 4249.9 = 145.30
				else if($monthly >= 3750 && $monthly <= 4249.9){
					$sssContribution = 145.30;
					$sssEmployer = 304.70;
				}
				//4250 ~ 4749.9 = 163.50
				else if($monthly >= 4250 && $monthly <= 4749.9 ){
					$sssContribution = 163.50;
					$sssEmployer = 341.50;
				}
				//4750 ~ 5249.9 = 181.70
				else if($monthly >= 4750 && $monthly <= 5249.9){
					$sssContribution = 181.70;
					$sssEmployer = 378.30;
				}
				//5250 ~ 5749.9 = 199.80
				else if($monthly >= 5250 && $monthly <= 5749.9){
					$sssContribution = 199.80;
					$sssEmployer = 415.20;
				}
				//5750 ~ 6249.9 = 218.0
				else if($monthly >= 5750 && $monthly <= 6249.9){
					$sssContribution = 218.00;
					$sssEmployer = 452.00;
				}
				//6250 ~ 6749.9 = 236.20
				else if($monthly >= 6250 && $monthly <= 6749.9){
					$sssContribution = 236.20;
					$sssEmployer = 488.80;
				}
				//6750 ~ 7249.9 = 254.30
				else if($monthly >= 6750 && $monthly <= 7249.9){
					$sssContribution = 254.30;
					$sssEmployer = 525.70;
				}
				//7250 ~ 7749.9 = 272.50
				else if($monthly >= 7250 && $monthly <= 7749.9){
					$sssContribution = 272.50;
					$sssEmployer = 562.50;
				}
				//7750 ~ 8249.9 = 290.70
				else if($monthly >= 7750 && $monthly <=  8249.9 ){
					$sssContribution = 290.70;
					$sssEmployer = 599.30;
				}
				//8250 ~ 8749.9 = 308.80
				else if($monthly >= 8250 && $monthly <= 8749.9){
					$sssContribution = 308.80;
					$sssEmployer = 636.20;
				}
				//8750 ~ 9249.9 = 327.0
				else if($monthly >= 8750 && $monthly <= 9249.9){
					$sssContribution = 327.00;
					$sssEmployer = 673.00;
				}
				//9250 ~ 9749.9 = 345.20
				else if($monthly >= 9250 && $monthly <= 9749.9){
					$sssContribution = 345.20;
					$sssEmployer = 709.80;
				}
				//9750 ~ 10249.9 = 363.30
				else if($monthly >= 9750 && $monthly <= 10249.9){
					$sssContribution = 363.30;
					$sssEmployer = 746.70;
				}
				//10250 ~ 10749.9 = 381.50
				else if($monthly >= 10250 && $monthly <=  10749.9){
					$sssContribution = 381.50;
					$sssEmployer = 783.50;
				}
				//10750 ~ 11249.9 = 399.70
				else if($monthly >= 10750 && $monthly <= 11249.9){
					$sssContribution = 399.70;
					$sssEmployer = 820.30;
				}
				//11250 ~ 11749.9 = 417.80
				else if($monthly >= 11250 && $monthly <= 11749.9){
					$sssContribution = 417.80;
					$sssEmployer = 857.20;
				}
				//11750 ~ 12249.9 = 436.0
				else if($monthly >= 11750 && $monthly <= 12249.9){
					$sssContribution = 436.00;
					$sssEmployer = 894.00;
				}
				//12250 ~ 12749.9 = 454.20
				else if($monthly >= 12250 && $monthly <= 12749.9){
					$sssContribution = 454.20;
					$sssEmployer = 930.80;
				}
				//12750 ~ 13249.9 = 472.30
				else if($monthly >= 12750 && $monthly <= 13249.9){
					$sssContribution = 472.30;
					$sssEmployer = 967.70;
				}
				//13250 ~ 13749.9 = 490.50
				else if($monthly >= 13250 && $monthly <= 13749.9){
					$sssContribution = 490.50;
					$sssEmployer = 1004.5;
				}
				//13750 ~ 14249.9 = 508.70
				else if($monthly >= 13750 && $monthly <= 14249.9){
					$sssContribution = 508.70;
					$sssEmployer = 1041.30;
				}
				//14250 ~ 14749.9 = 526.80
				else if($monthly >= 14250 && $monthly <= 14749.9){
					$sssContribution = 526.80;
					$sssEmployer = 1070.20;
				}
				//14750 ~ 15249.9 = 545.0
				else if($monthly >= 14750 && $monthly <= 15249.9){
					$sssContribution = 545.00;
					$sssEmployer = 1135.00;
				}
				//15250 ~ 15749.9 = 563.20
				else if($monthly >= 15250 && $monthly <= 15749.9){
					$sssContribution = 563.20;
					$sssEmployer = 1171.80;
				}
				//15750 ~ higher = 581.30
				else if($monthly >= 15750){
					$sssContribution = 581.30;
					$sssEmployer = 1208.70;
				}
					$sssContribution = $sssContribution / 4;
					$sssEmployer = $sssEmployer / 4;
				
				

				mysql_query("UPDATE payroll SET sss = '$sssContribution', sss_er = '$sssEmployer' WHERE empid ='$empid'");
			}

			if($empArr['philhealth'] != 0)
			{
				$philhealthER = $empArr['philhealth'];
				mysql_query("UPDATE payroll SET philhealth_er = '$philhealthER' WHERE empid ='$empid'");
			}
			if($empArr['pagibig'] != 0)
			{
				$pagibigER = $empArr['pagibig'];
				mysql_query("UPDATE payroll SET pagibig_er = '$pagibigER' WHERE empid ='$empid'");
			}
		}
	?>
	
</body>
</html>

</script>