<?php
// fix for crontab
//set_include_path("/tmp/Onapp-bandwidth-stats-to-grafana/");

$production_state;
 //Show consumption for all CP user,
$customerXML;
$datep;
$dateToString;
$nanocount;

$privateConf="billingSettingsIPO.xml";
if (file_exists($privateConf)) {
    global $customerXML;
    $production_state="IPO";
    $customerXML="billingSettingsIPO.xml";
    include "settings_dev.php";

} else {
    $customerXML="billingSettings.xml";
    include "settings.php";
}




//Include settings
for ($year = 2014; $year <= 2018; $year++) {

	for ($month = 1; $month <= 12; $month++) {

	for ($day = 1; $day <= 31; $day++) {

	for ($hour = 1; $hour <= 24; $hour++) {




  $dateStringStart = $year."-".fixdate($month)."-".fixdate($day)."+".fixdate($hour-1).":00:00";
	$dateStringEnd = $year."-".fixdate($month)."-".fixdate($day)."+".fixdate($hour).":00:00";
	//echo $dateStringStart."\n";
	//echo $dateStringEnd."\n";


  $datep = new DateTime($year."-".fixdate($month)."-".fixdate($day)." ".fixdate($hour).":00:00");



// set HTTP header
$headers = array(
    'Content-Type: application/json',
);
// set url to onapp CP
$service_url = $my_onappURL;
// set date range
#$data = array("period" => array("startdate"=>getStartAndEndDate("start"),"enddate"=>getStartAndEndDate("end")));
$data = array("period" => array("startdate"=>$dateStringStart,"enddate"=>$dateStringEnd));
/// encode to json
$data_string = json_encode($data);
//do some magic and request your CP API
$curl= curl_init($my_onappURL);
  curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  curl_setopt($curl, CURLOPT_USERPWD, $my_email.":".$my_apiKey);
  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Content-Length: ' . strlen($data_string))
  );
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
// save webservice response to a vaibale
$result = curl_exec($curl);
//decode the response to json
$raw_data = json_decode($result);
// setup a array where we will store users id and GB transfer
$userdata = array();

// setup a vaibale for user id's
$user_id = 0;
traverse($raw_data);// access webservice, build a user obkect with data
create_CVS($userdata); // create cvs
$userdata=0;
$raw_data=0;


}
}
}
}

function traverse(&$objOrArray)
{
  // setup global variable so that we can access the values outside the function
  global $user_id;
  global $userdata;

// start the loop
  foreach ($objOrArray as $key => &$value)
	{
    // if value is a array or object. Send it to this fuction again
	if (is_array($value) || is_object($value))
		{
			traverse($value);
		}
	else
  {
    //colled the user id
    if ($key == "user_id")
    {
      $user_id = $value;
      // check if user id exist in userdata array
      if (userExistInArray($value))
      {
        //echo "user is allreday in array";
      }
       else
      {
        // if not, add the user to the array
        array_push($userdata, $value);
      }


    }
    // add cached data to users
    if ($key == "cached")
    {
          $userdata[$user_id] = calc($userdata[$user_id],$value,$user_id);
    }
    // add none cached data to user
    if ($key == "non_cached")
    {
        $userdata[$user_id] = calc($userdata[$user_id],$value,$user_id);
    }

    }
  }
}
// sum data for user
function calc(&$nowGB,&$moreGB,&$userId)
{
  global $datep;
  global $XMLarray;
  global $currentUserID;
  global $cvsString;
  global $traceOutput;
  global $production_state;
  global $csvURL;
  global $dateToString;

  $calcMe = $nowGB + $moreGB;
  // echo "# We have now add ". $calcMe . "to user " . $userId ."\n";

$randnum = rand(111111111,999999999);
echo "/usr/bin/curl -XPOST http://localhost:8086/write?db=telegraf --data-binary \"cdn-monitor-bw,user=".$userId." data=".$moreGB." ".$datep->getTimestamp().$randnum."\""."\n";


  return $calcMe;
}
// check if user exist in Array
function  userExistInArray(&$userId)
{
  global $userdata;
  $user_exist=false;
  foreach ($userdata  as &$value) {
    if ($value == $userId)
    {
      $user_exist=true;
    }
  }
  return $user_exist;
}
// Start creating the CSV

function create_CVS(&$objOrArray)
{
  global $datep;
  global $XMLarray;
  global $currentUserID;
  global $cvsString;
  global $traceOutput;
  global $production_state;
  global $csvURL;
  global $dateToString;

  foreach ($objOrArray as $key => &$value)
  {
    $avrunda=intval($value);
    $currentUserID=false;
  }


  foreach ($objOrArray as $key => &$value)
  {



  }

}



?>
