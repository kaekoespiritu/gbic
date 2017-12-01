<?php
//fetch.php
include_once('directives/db.php');
if(isset($_POST["search"]))
{
    if($_POST["search"] != '')
    {
        $search = mysql_real_escape_string($_POST["search"]);
        $query = "
        SELECT * FROM employee 
        WHERE (firstname LIKE '%".$search."%' 
        OR lastname LIKE '%".$search."%' OR empid LIKE '%".$search."%') AND employment_status = '1' ORDER BY lastname ASC LIMIT 5";
    }
}


if(isset($_POST["search"]))
{
    $result = mysql_query($query) or die (mysql_error());
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
        <a href="#"  onclick="searchBox(\''.$row['empid'].'\')">'.$row['lastname'].', '.$row['firstname'].'<cite>['.$row['position'].', '.$row['site'].']</cite></a>
        </div>
        ';
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
else
    {   
        echo '<div class="col-md-6 col-md-offset-3 searchResults text-left">
         Employee Not Found...
        </div>';
    }


?>