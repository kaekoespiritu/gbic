<?php
//fetch.php
include_once('directives/db.php');
include("pagination/reports_individual_function.php");//For pagination

$site_page = $_POST['site'];
$position_page = $_POST['position_page'];
$reportType = $_POST['report_type'];
Print "<script>console.log('".$reportType."')</script>";
$pageNum = $_POST['page'];

Print "<!-- Table of employees -->
                    <table class='table table-bordered' style='background-color:white;''>

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
    $page = (int) (!isset($pageNum) ? 1 : $pageNum);
    $limit = 20; //if you want to dispaly 10 records per page then you have to change here    
    $startpoint = ($page * $limit) - $limit;

    if($site_page != "null")
    {
        if($position_page != "null")
        {
            $statement = "employee WHERE (firstname LIKE '%".$search."%' 
        OR lastname LIKE '%".$search."%' OR empid LIKE '%".$search."%') AND position = '$position_page' AND site = '$site_page' AND employment_status = '1' ORDER BY site ASC, position ASC, lastname ASC";
        }
        else
        {
            $statement = "employee WHERE (firstname LIKE '%".$search."%' 
        OR lastname LIKE '%".$search."%' OR empid LIKE '%".$search."%') AND site = '$site_page' AND employment_status = '1' ORDER BY site ASC, position ASC, lastname ASC";
        }
    }
    else if($position_page != "null")
    {
        if($site_page != "null")
        {
             $statement = "employee WHERE (firstname LIKE '%".$search."%' 
        OR lastname LIKE '%".$search."%' OR empid LIKE '%".$search."%') AND position = '$position_page' AND site = '$site_page' AND employment_status = '1' ORDER BY site ASC, position ASC, lastname ASC";
        }
        else
        {
             $statement = "employee WHERE (firstname LIKE '%".$search."%' 
        OR lastname LIKE '%".$search."%' OR empid LIKE '%".$search."%') AND position = '$position_page'  AND employment_status = '1' ORDER BY site ASC, position ASC, lastname ASC";
        }
    }
    else
    {
        $statement = "employee WHERE (firstname LIKE '%".$search."%' 
        OR lastname LIKE '%".$search."%' OR empid LIKE '%".$search."%') AND employment_status = '1' ORDER BY site ASC, position ASC, lastname ASC";
    }
    
        $res=mysql_query("select * from {$statement} LIMIT {$startpoint} , {$limit}");

        while($empArr = mysql_fetch_assoc($res))
        {
            Print "
                
                <tr>
                    <td style='vertical-align: inherit'>".$empArr['empid']."</td>
                    <td style='vertical-align: inherit'>".$empArr['lastname'].", ".$empArr['firstname']."</td>
                    <td style='vertical-align: inherit'>".$empArr['position']."</td>
                    <td style='vertical-align: inherit'>".$empArr['site']."</td>
                    <td style='vertical-align: inherit'>";

            if($reportType == "Earnings")//for Earnings tab
            {
                Print    " 
                            <button class='btn btn-default' onclick='viewPayrollBtn(\"".$empArr['empid']."\")'>
                                Payroll
                            </button>
                            <button class='btn btn-default' onclick='view13thmonthpayBtn(\"".$empArr['empid']."\", \"".$period."\")'>
                                13th Month Pay
                            </button>";
            }
            else if($reportType == "Contributions")// for Contributions tab
            {
                $disableSSS = "";
                $disablePhilhealth = "";
                $disablePagibig = "";
                if($empArr['sss'] == 0)
                {
                    $disableSSS = "disabletotally";
                }
                if($empArr['philhealth']  == 0)
                {
                    $disablePhilhealth = "disabletotally";
                }
                if($empArr['pagibig']  == 0)
                {
                    $disablePagibig = "disabletotally";
                }

                Print "<button class='btn btn-default ".$disableSSS."' onclick='viewSSSBtn(\"".$empArr['empid']."\")'>
                                            SSS
                        </button>
                        <button class='btn btn-default ".$disablePhilhealth."' onclick='viewPhilHealthBtn(\"".$empArr['empid']."\")'>
                            PhilHealth
                        </button>
                        <button class='btn btn-default ".$disablePagibig."' onclick='viewPagIBIGBtn(\"".$empArr['empid']."\")'>
                            PagIBIG
                        </button>";
            }
            else if($reportType == "Loans")// for Contributions tab
            {
                $empid = $empArr['empid'];

                $disableSSS = "";
                $disablePagibig = "";
                $disableOldVale = "";
                $disableNewVale = "";

                $loanChecker = "SELECT DISTINCT type FROM loans WHERE empid = '$empid'";
                $loanQuery = mysql_query($loanChecker);
                while($loanArr = mysql_fetch_assoc($loanQuery))
                {
                    switch($loanArr['type'])
                    {
                        case "SSS": $disableSSS = "disabletotally";break;
                        case "PagIBIG": $disablePagibig = "disabletotally";break;
                        case "oldVale": $disableOldVale = "disabletotally";break;
                        case "newVale": $disableNewVale = "disabletotally";break;
                    }
                }

                Print " <button class='btn btn-default ".$disableSSS."' onclick='viewBtn(\"".$empArr['empid']."\",\"sss\")'>
                            SSS
                        </button>
                        <button class='btn btn-default ".$disablePagibig."' onclick='viewBtn(\"".$empArr['empid']."\",\"pagibig\")'>
                            PagIBIG
                        </button>
                        <button class='btn btn-default ".$disableOldVale."' onclick='viewBtn(\"".$empArr['empid']."\", \"oldvale\")'>
                            Old Vale
                        </button>
                        <button class='btn btn-default ".$disableNewVale."' onclick='viewBtn(\"".$empArr['empid']."\", \"newvale\")'>
                            New Vale
                        </button>";
            }
            else if($reportType == "Attendance")// for Contributions tab
            {
                Print "
                        <a href='reports_individual_empattendance.php?empid=".$empArr['empid']."' class='btn btn-default'>
                                            View Attendance
                                        </a>
                        ";
            }
            Print   " </td>
                </tr>
            ";
        }
    
    
}
Print "              
                    </table>
            ";
echo "<div id='pagingg' >";
                if($statement && $limit && $page && $site_page && $position_page && $reportType && $period)
                    echo pagination($statement,$limit,$page, $site_page, $position_page, $reportType, $period);
                echo "</div>";
?>