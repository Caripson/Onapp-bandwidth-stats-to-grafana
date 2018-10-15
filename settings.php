<?php

$my_email = "your@email.com";
$my_apiKey = "API_KEY_FROM_ONAPP_CP";
$my_onappURL = "https://YOUR.URL.TO.ONAPP.CP";


$traceOutput=true;

function getStartAndEndDate($startorend)
{

//set default variable
$dateStringEnd=00 ;
$dateStringStart=00;

//get current month and year
$getDateMonth=getDateMonth();
$getDateYear=getDateYear();

$getDateHour=getDateHour();
$getDateMinute=getDateMinute();
$getDateDay=getDateDay();


// set last month and year
$getLastMonth=fixdate(getDateMonth()-1);
$getLastYear=fixdate(getDateYear()-1);
$getLastHour=fixdate(getDateYear()-1);



$dateStringEnd = $getDateYear."-".$getDateMonth."-".$getDateDay."+".$getDateHour.":00:00";
$dateStringStart = $getDateYear."-".$getDateMonth."-".$getDateDay."+".$getLastHour.":00:00";



if ($startorend=="start")
{
   $startorend=$dateStringStart;
}
else
{
   $startorend=$dateStringEnd;
}

return $startorend;

}

// get current month
function getDateMonth() {
  $tz_object = new DateTimeZone('Europe/Stockholm');
  date_default_timezone_set('Europe/Stockholm');
           $datetime = new DateTime();
           $datetime->setTimezone($tz_object);
           return $datetime->format('m');
           }
function getDateYear() {
    $tz_object = new DateTimeZone('Europe/Stockholm');
    date_default_timezone_set('Europe/Stockholm');

    $datetime = new DateTime();
    $datetime->setTimezone($tz_object);
    return $datetime->format('Y');
}

function getDateDay() {
    $tz_object = new DateTimeZone('Europe/Stockholm');
    date_default_timezone_set('Europe/Stockholm');

    $datetime = new DateTime();
    $datetime->setTimezone($tz_object);
    return $datetime->format('d');
}


function getDateHour() {
      $tz_object = new DateTimeZone('Europe/Stockholm');
      date_default_timezone_set('Europe/Stockholm');

      $datetime = new DateTime();
      $datetime->setTimezone($tz_object);
      return $datetime->format('H');
}

function getDateMinute() {
      $tz_object = new DateTimeZone('Europe/Stockholm');
      date_default_timezone_set('Europe/Stockholm');

      $datetime = new DateTime();
      $datetime->setTimezone($tz_object);
      return $datetime->format('i');
}
//fix dayt format and add "0" if year/day is less then 10
function fixdate($mdate)
   {
   $len= strlen($mdate);
   if ($len >= 2 )
   {
   //echo "do nothing";
   }else
   {
  $mdate="0".$mdate;
   }
   return $mdate;
}


?>
