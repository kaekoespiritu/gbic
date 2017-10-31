<?php
//fetch.php
include('directives/db.php');
if(isset($_POST["query"]))
{
  if($_POST["query"] != '')
  {
     $search = mysql_real_escape_string($_POST["query"]);
     $query = "
     SELECT * FROM employee 
     WHERE (firstname LIKE '%".$search."%' 
     OR lastname LIKE '%".$search."%') AND employment_status = '1' ORDER BY lastname ASC LIMIT 5";
 }
}

// <div class="col-md-6 col-md-offset-3 searchResults">
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
    //Pre set values of loans
        $employeeSSS = 0;
        $employeePAGIBIG = 0;
        $employeeOldVale = 0;
        $employeeNewVale = 0;
        $empid = $row['empid'];
        $sss = "SELECT * FROM loans WHERE type = 'SSS' AND empid = '$empid' ORDER BY date DESC, time DESC LIMIT 1";
        $pagibig = "SELECT * FROM loans WHERE type = 'PagIBIG' AND empid = '$empid' ORDER BY date DESC, time DESC LIMIT 1";
        $oldVale = "SELECT * FROM loans WHERE type = 'oldVale' AND empid = '$empid' ORDER BY date DESC, time DESC LIMIT 1";
        $newVale = "SELECT * FROM loans WHERE type = 'newVale' AND empid = '$empid' ORDER BY date DESC, time DESC LIMIT 1";
    //Query the statement above
        $sssQuery = mysql_query($sss);
        $pagibigQuery = mysql_query($pagibig);
        $oldValeQuery = mysql_query($oldVale);
        $newValeQuery = mysql_query($newVale);
    //check if it has values
        $sssNum = mysql_num_rows($sssQuery);
        $pagibigNum = mysql_num_rows($pagibigQuery);
        $oldValeNum = mysql_num_rows($oldValeQuery);
        $newValeNum = mysql_num_rows($newValeQuery);

        if(!empty($sssNum))
        {
            $sssRow = mysql_fetch_assoc($sssQuery);
            $employeeSSS = $sssRow['balance'];
        }
        if(!empty($pagibigNum))
        {
            $pagibigRow = mysql_fetch_assoc($pagibigQuery);
            $employeePAGIBIG = $pagibigRow['balance'];
        }
        if(!empty($oldValeNum))
        {
            $oldValeRow = mysql_fetch_assoc($oldValeQuery);
            $employeeOldVale = $oldValeRow['balance'];
        }
        if(!empty($newValeNum))
        { 
            $newValeRow = mysql_fetch_assoc($newValeQuery);
            $employeeNewVale = $newValeRow['balance'];
        }



      $output .= '
      <div class="col-md-6 col-md-offset-3 searchResults text-left" id="'.$row['empid'].'">
      <a href="#"  onclick="sendToModal(\''.$row['empid'].'\')" data-toggle="modal" data-target="#addLoan">'.$row['lastname'].', '.$row['firstname'].'<cite>['.$row['position'].', '.$row['site'].']</cite></a>
      <input type="hidden" id="empid" value="'.$row['empid'].'">
      <input type="hidden" id="address" value="'.$row['address'].'">
      <input type="hidden" id="contactnum" value="'.$row['contactnum'].'">
      <input type="hidden" id="firstname" value="'.$row['firstname'].'">
      <input type="hidden" id="lastname" value="'.$row['lastname'].'">
      <input type="hidden" id="position" value="'.$row['position'].'">
      <input type="hidden" id="site" value="'.$row['site'].'">
      <input type="hidden" id="monthly" value="'.$row['salary'].'">
      <input type="hidden" id="rate" value="'.$row['rate'].'">
      <input type="hidden" id="sss" value="'.$employeeSSS.'">
      <input type="hidden" id="pagibig" value="'.$employeePAGIBIG.'">
      <input type="hidden" id="oldvale" value="'.$employeeOldVale.'">
      <input type="hidden" id="newvale" value="'.$employeeNewVale.'">
      </div>
      ';
    //Pre-requisite is the loan to be able to finish the data display on the modal
  }

  echo $output;
}
else
{   
   echo '<div class="col-md-6 col-md-offset-3 searchResults text-left">
   Employee Not Found...
   </div>';
}
}


?>