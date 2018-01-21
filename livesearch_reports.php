<?php
//fetch.php
include_once('directives/db.php');

Print "<!-- Table of employees -->
            <div class='row'>
                <div class='col-md-10 col-md-offset-1'>
                    <table class='table table-bordered table-condensed' style='background-color:white;''>

                        <tr>
                            <th class='fixedWidth text-center'>Employee ID</th>
                            <th class='text-center'>Name</th>
                            <th class='text-center'>Position</th>
                            <th class='text-center'>Site</th>
                            <th class='text-center'>Actions</th>
                        </tr>";
if(isset($_POST["search"]))
{
        $search = mysql_real_escape_string($_POST["search"]);
        $period = mysql_real_escape_string($_POST["period"]);
        $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
        $limit = 20; //if you want to dispaly 10 records per page then you have to change here
        $startpoint = ($page * $limit) - $limit;
        $statement = "employee WHERE (firstname LIKE '%".$search."%' 
            OR lastname LIKE '%".$search."%' OR empid LIKE '%".$search."%') AND employment_status = '1' ORDER BY site ASC, position ASC, lastname ASC";

        $res=mysql_query("select * from {$statement} LIMIT {$startpoint} , {$limit}");

        while($empArr = mysql_fetch_assoc($res))
        {
            Print "
                
                <tr>
                    <td style='vertical-align: inherit'>".$empArr['empid']."</td>
                    <td style='vertical-align: inherit'>".$empArr['lastname'].", ".$empArr['firstname']."</td>
                    <td style='vertical-align: inherit'>".$empArr['position']."</td>
                    <td style='vertical-align: inherit'>".$empArr['site']."</td>
                    <td style='vertical-align: inherit'>
                        <button class='btn btn-default' onclick='viewPayrollBtn(\"".$empArr['empid']."\", \"".$period."\")'>
                            Payroll
                        </button>
                        <button class='btn btn-default' onclick='view13thmonthpayBtn(\"".$empArr['empid']."\", \"".$period."\")'>
                            13th Month Pay
                        </button>
                    </td>
                </tr>
            ";
        }
    
    // else
    // {   
    //     echo '
    //         <tr>
    //             <td colspan="6">
    //                 Employee Not Found...
    //             </td>    
    //         </tr>';
    // }
}
Print "              
                    </table>
                </div>
            </div>
            ";

//----------
// if(isset($_POST["search"]))
// {
//     if($_POST["search"] != '')
//     {
//         $search = mysql_real_escape_string($_POST["search"]);
//         $query = "
//         SELECT * FROM employee 
//         WHERE (firstname LIKE '%".$search."%' 
//         OR lastname LIKE '%".$search."%' OR empid LIKE '%".$search."%') AND employment_status = '1' ORDER BY lastname ASC LIMIT 5";
//     }
// }


// if(isset($_POST["search"]))
// {
//     $result = mysql_query($query) or die (mysql_error());
// }
// if(!empty($result))
// {
//     if(mysql_num_rows($result) > 0)
//     {
//     $output = '';
        
//     while($row = mysql_fetch_assoc($result))
//     {
//         $output .= '
//         <div class="searchResults text-left" id="'.$row['empid'].'" style="padding:0.5em">
//         <a href="#"  onclick="searchBox(\''.$row['empid'].'\')">'.$row['lastname'].', '.$row['firstname'].'<cite>['.$row['position'].', '.$row['site'].']</cite></a>
//         </div>
//         ';
//     }
//     echo $output;
//     }
//     else
//     {   
//         echo '<div class="searchResults text-left" style="padding:0.5em">
//          Employee Not Found...
//         </div>';
//     }
// }
// else
//     {   
//         echo '<div class="searchResults text-left" style="padding:0.5em">
//          Employee Not Found...
//         </div>';
//     }


?>