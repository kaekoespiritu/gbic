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
    WHERE empid LIKE '%".$search."%'
    OR firstname LIKE '%".$search."%' 
    OR lastname LIKE '%".$search."%' 
    OR position LIKE '%".$search."%' 
    OR site LIKE '%".$search."%'
   ";
  }
}

if(isset($_POST["query"]))
{
  $result = mysql_query($query);
}
if(!empty($result))
{
  if(mysql_num_rows($result) > 0)
  {
  $output = '';
  $output .= '
    <div class="table-responsive">
     <table class="table table bordered">
      <tr>
       <th>empid</th>
       <th>name</th>
       <th>position</th>
       <th>site</th>
      </tr>';
   while($row = mysql_fetch_assoc($result))
   {
    $output .= '
     <tr>
      <td>'.$row["empid"].'</td>
      <td>'.$row["lastname"].', '. $row["firstname"] .'</td>
      <td>'.$row["position"].'</td>
      <td>'.$row["site"].'</td>
     </tr>
    ';
   }
   $output .= ' </table>
                </div>
              ';
   echo $output;
  }
  else
  {
   echo 'Data Not Found';
  }
}


?>