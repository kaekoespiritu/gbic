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
    WHERE firstname LIKE '%".$search."%' 
    OR lastname LIKE '%".$search."%' ORDER BY lastname ASC LIMIT 5";
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
    $output .= '
      <div class="col-md-6 col-md-offset-3 searchResults text-left" id="'.$row['empid'].'">
           <a href="#"  onclick="sendToModal(\''.$row['empid'].'\')" data-toggle="modal" data-target="#addLoan">'.$row['lastname'].', '.$row['firstname'].'<cite>['.$row['position'].', '.$row['site'].']</cite></a>

           <input type="hidden" id="address" value="'.$row['address'].'">
           <input type="hidden" id="contactnum" value="'.$row['contactnum'].'">
           <input type="hidden" id="firstname" value="'.$row['firstname'].'">
           <input type="hidden" id="lastname" value="'.$row['lastname'].'">
           <input type="hidden" id="position" value="'.$row['position'].'">
           <input type="hidden" id="site" value="'.$row['site'].'">
           <input type="hidden" id="monthly" value="'.$row['salary'].'">
           <input type="hidden" id="rate" value="'.$row['rate'].'">
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