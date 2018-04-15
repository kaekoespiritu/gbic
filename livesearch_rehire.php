<?php
//fetch.php
include_once('directives/db.php');
if(isset($_POST["query"]))
{
  if($_POST["query"] != '')
  {
     $search = mysql_real_escape_string($_POST["query"]);
     $query = "
     SELECT * FROM employee 
     WHERE (firstname LIKE '%".$search."%' 
     OR lastname LIKE '%".$search."%' OR empid LIKE '%".$search."%') AND employment_status = '0' ORDER BY lastname ASC LIMIT 5";
 }
}

// <div class="col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3 searchResults">
//           SEARCH RESULTS...
//         </div>
// data-toggle="modal" data-target="#addEmployee"

if(isset($_POST["query"]))
{
  $result = mysql_query($query);
}
if(!empty($result))
{
  if(mysql_num_rows($result) > 0)
  {
      $output = '';
      while($row = mysql_fetch_assoc($result))
      {
    
      $output .= '
      <div class="col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3 searchresults text-left" id="'.$row['empid'].'">
      <a href="#"  onclick="sendToModal(\''.$row['empid'].'\')" data-toggle="modal" data-target="#oldEmployee">'.$row['lastname'].', '.$row['firstname'].'<cite>['.$row['position'].', '.$row['site'].']</cite></a>
      <input type="hidden" class="toModalEmpid" value="'.$row['empid'].'">
      <input type="hidden" class="toModalAddress" value="'.$row['address'].'">
      <input type="hidden" class="toModalContactnum" value="'.$row['contactnum'].'">
      <input type="hidden" class="toModalFirstname" value="'.$row['firstname'].'">
      <input type="hidden" class="toModalLastname" value="'.$row['lastname'].'">
      <input type="hidden" class="toModalPosition" value="'.$row['position'].'">
      <input type="hidden" class="toModalDob" value="'.$row['dob'].'">
      <input type="hidden" class="toModalHired" value="'.$row['datehired'].'">
      <input type="hidden" class="toModalSite" value="'.$row['site'].'">
      <input type="hidden" class="toModalMonthly" value="'.$row['salary'].'">
      <input type="hidden" class="toModalRate" value="'.$row['rate'].'">
      <input type="hidden" class="toModalAllowance" value="'.$row['allowance'].'">
      <input type="hidden" class="toModalCivilStatus" value="'.$row['civilstatus'].'">
      <input type="hidden" class="toModalSss" value="'.$row['sss'].'">
      <input type="hidden" class="toModalPagibig" value="'.$row['pagibig'].'">
      <input type="hidden" class="toModalPhilhealth" value="'.$row['philhealth'].'">
      <input type="hidden" class="toModalEmergency" value="'.$row['emergency'].'">
      <input type="hidden" class="toModalReference" value="'.$row['reference'].'">
      </div>
      ';
    //Pre-requisite is the loan to be able to finish the data display on the modal
  }

  echo $output;
}
else
{   
   echo '<div class="col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3 searchresults text-left">
   Employee Not Found...
   </div>';
}
}


?>