<?php
//fetch.php
include_once('directives/db.php');

$fromDate = $_POST['fromDate'];
$toDate = $_POST['toDate'];
$empid = $_POST['empid'];

if(isset($_POST["fromDate"]) && isset($_POST["toDate"]))
{
     $employeeChecker = "SELECT * FROM employee WHERE empid = '$empid'";
     $employeeCheckerQuery = mysql_query($employeeChecker);

     if(mysql_num_rows($employeeCheckerQuery))
          $empArr = mysql_fetch_assoc($employeeCheckerQuery);

     $attendance = "SELECT date, workhours, attendance FROM attendance WHERE  
            empid = '$empid' AND (STR_TO_DATE(date, '%M %e, %Y') BETWEEN STR_TO_DATE('$fromDate', '%M %e, %Y') AND STR_TO_DATE('$toDate', '%M %e, %Y')) ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";

     $attChecker = mysql_query($attendance);
     $attQuery = mysql_query($attendance);

     $daysAttended = 0;//counter for days attended
     $daysCompleted = 0; // counter for days completed
     $overallDaysAttended = 0;
     $overallPayment = 0;
     
     $arrayChecker = array();

     // Adds attendance array to array checker
     while($attArray = mysql_fetch_assoc($attChecker))
     {
          //exclude Holidays and Sundays

          //Check if holiday
          $holidayDateCheck = $attArray['date'];
          $holidayChecker = "SELECT * FROM holiday WHERE date = '$holidayDateCheck' LIMIT 1";
          $holidayCheckQuery = mysql_query($holidayChecker) or die (mysql_error());
          
          //Check if Sunday
          $date = $attArray['date'];
          $day = date('l', strtotime($date));// check what day of the week

          
          if(mysql_num_rows($holidayCheckQuery) > 0)
          {
               $checkHoliday = mysql_fetch_assoc($holidayCheckQuery);
               $regHolidayCheckBool = ($checkHoliday['type'] != "special" ? true : false);
          }
          else
          {
               $regHolidayCheckBool = true;
          }

          
          if($regHolidayCheckBool && $day != "Sunday")
               array_push($arrayChecker, $attArray);
     }

     // Removes duplicates from array checker
     $secondArrayChecker = array_unique($arrayChecker, SORT_REGULAR);

     //Computes the 13th month
     $overallCounter = 100;
     // $overallCounter = count(array_filter($secondArrayChecker));

     for($count = 0; $count < $overallCounter; $count++ )
     {

          if(!empty($secondArrayChecker[$count]['date']))
          {
               $date = $secondArrayChecker[$count]['date'];

               $workHrs = $secondArrayChecker[$count]['workhours'];

               $holidayChecker = "SELECT * FROM holiday WHERE date = '$date' LIMIT 1";
               $holidayCheckQuery = mysql_query($holidayChecker) or die (mysql_error());

               if(mysql_num_rows($holidayCheckQuery) > 0)
               {
                    $checkHoliday = mysql_fetch_assoc($holidayCheckQuery);
                    $regHolidayCheckBool = ($checkHoliday['type'] != "special" ? true : false);
                    $attendedHoliday = ($checkHoliday['type'] == "regular" ? true : false);
               }
               else
               {
                    $attendedHoliday = false;
                    $regHolidayCheckBool = true;
               }

               if($regHolidayCheckBool)// Include regular holidays. dont proceed if special holiday
               {
                    // check if days are not duplicated
                    if(isset($secondArrayChecker[$count]['attendance']) && $secondArrayChecker[$count]['attendance'] == '2')//check if employee is present
                    {
                         if($secondArrayChecker[$count]['workhours'] >= 8 || $attendedHoliday)//check if employee attended 8hours || regardless how many hours the employee rendered for that regular holiday
                         {
                              $daysCompleted++;
                         }
                         else
                         {
                              $daysCompleted += ($secondArrayChecker[$count]['workhours'] / 8);
                         }
                    }
               }
          }
     }
     $thirteenthMonth = ($daysCompleted * $empArr['rate']) / 12; 
     
     $overallDaysAttended = $daysCompleted;
     $overallPayment = $thirteenthMonth;     

    $output = '
    <table class="table table-bordered pull-down">
          <tr>
            <td>
              From - To Date
            </td>
            <td>
              Amount
            </td>
            <td>
              Days Completed
            </td>
          </tr>';

          $output .='
                    <tr>
                      <td>'.$fromDate." - ".$toDate.'
                      </td>
                      <td>
                        '.numberExactFormat($overallPayment, 2, '.', true).'
                      <input type="hidden" id="custompayment" value="'.numberExactFormat($overallPayment, 2, '.', false).'">
                      </td>
                      <td>
                        '.$daysCompleted.'
                      </td>
                    </tr>
                  </table>
              ';

          // if() {
          //       $output .'
          //           <tr>
          //             <td>'.$fromDate." - ".$toDate.'
          //             </td>
          //             <td>
          //               '.numberExactFormat($overallPayment, 2, '.', true).'
          //             <input type="hidden" id="custompayment" value="'.numberExactFormat($overallPayment, 2, '.', true).'">
          //             </td>
          //             <td>
          //               '.$daysCompleted.'
          //             </td>
          //           </tr>
          //         </table>
          //     ';
          // }
          // else {
          //       $output .'
          //       <tr>
          //       <td></td>
          //       </tr>
          //           <tr>
          //             <td>'.$fromDate." - ".$toDate.'
          //             </td>
          //             <td>
          //               '.numberExactFormat($overallPayment, 2, '.', true).'
          //             <input type="hidden" id="custompayment" value="'.numberExactFormat($overallPayment, 2, '.', true).'">
          //             </td>
          //             <td>
          //               '.$daysCompleted.'
          //             </td>
          //           </tr>
          //         </table>
          //     ';
          // }

  echo $output;
  }
  else
  {   
    // may never appear
     echo 'No dates selected.';
  }


?>