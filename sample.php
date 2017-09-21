<?php

$start_date = 'September 16, 2017';
$end_date = 'September 22, 2017';
if ($end_date >= $start_date)
{
  for ($day = 0; $day < 7; $day++)
  {
    echo "<br />" . date("F j, Y", strtotime("$start_date +$day day"));
    $yea = strtotime($start_date + $day);
    echo $yea;
  }
}
?>
