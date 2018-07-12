<?php

include('directives/db.php');
include('directives/session.php');
include('directives/admin_historical.php');

//employee History
$position = $_POST['job'];

$output = "
			<form method='POST' action='logic_options_removePosition.php'>
			    <div class='modal-body'>
		     		
		     		<table class='table table-bordered'>
		     			<tr>
		     				<td>
		     					Position
		     				</td>
		     				<td>
		     					Number of employees in position
		     				</td>
		     			</tr>";

$positionArr = explode("+", $position);

$removeBool = false;// true if there is ZERO employees in position
$onceBool = true;

$positionList = "";
foreach($positionArr as $pos)
{
	$positionCheck = "SELECT * FROM employee WHERE position = '$pos' AND employment_status = '1'";
	
	$posQuery = mysql_query($positionCheck) or die (mysql_error());
	$empNum = mysql_num_rows($posQuery);
	
	$output .= "<script>console.log('empNum: ".$empNum."')</script>";

	if($empNum != 0 && $onceBool)
	{
		$removeBool = true;//enable remove position
		$onceBool = false;//no repeat of this condition
	}

	

	$output .= "
				<tr>
     				<td>
     					".$pos."
     				</td>
     				<td>
     					".$empNum."
     				</td>
     			</tr>
				";

	//Accumulates all the selected position then transfer it to logic
	if($positionList != "")
		$positionList .= "+";
	$positionList .= $pos;
}

$addClass = "";
if($removeBool == true)
	$addClass = "disabletotally";

	$output .=	"
	     		</table>

	     		<div>
	     			NOTE: Be sure to remove all employees from selected position before removing the position.
	     		</div>
	     	</div>
     		<div class='modal-footer'>
	        	<center>
	        		<button type='submit' class='btn btn-primary $addClass'>Remove</button>
	        	</center>
	      	</div>
	      	<input type='hidden' name='positionList' value='".$positionList."'>
	  	</form>
	  	

			";//this is the object that will be fetch to the removePosition.php

	
echo $output;

?>

