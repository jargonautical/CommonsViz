<?php


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://data.parliament.uk/membersdataplatform/services/mnis/members/query/House=Commons%7CIsEligible=true/");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$headers = [
    'Content-Type: application/json'
];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$server_output = curl_exec ($ch);

curl_close ($ch);

/* Fix BOM */
$bom = pack('H*','EFBBBF');
$text = preg_replace("/^$bom/", '', $server_output);
$json = json_decode($text);

$out = array();

$parties = array();
foreach($json->Members->Member as $member) {
  $mid = $member->{'@Member_Id'};
  $name = $member->DisplayAs;
  $party = $member->Party->{'#text'};
  $constituency = $member->MemberFrom;

  $mp = array();
  $mp['id'] = $mid;
  $mp['name'] = $name;
  $mp['constituency'] = $constituency;

  ${$party}[] = $mp;
  $parties[] = $party;
}


$out['count'] = array_count_values($parties);
$out['parties'] = array();

$out['government'] = ['Conservative', 'Democratic Unionist Party'];
$out['government_colours'] = ['#0000ff', '#aa2222'];
$out['opposition'] = ['Labour', 'Labour (Co-op)', 'Scottish National Party', 'Liberal Democrat', 'Plaid Cymru','Green Party','Independent', 'Sinn Féin'];
$out['opposition_colours'] = ['#ff0000', '#ff0000', '#FFF95D', '#FDBB30', '#3F8428', '#00ff00', '#000000', '#008066'];

foreach ($out['count'] as $name =>$count) {
  $out['parties'][$name] = ${$name};
}

echo json_encode($out);


?>
