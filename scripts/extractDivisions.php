<?php

$input='../divisions.json';
//$idlist=['CD:2018-01-17:363','CD:2018-01-17:364','CD:2018-01-17:365','CD:2018-01-17:366','CD:2018-01-17:367','CD:2018-01-17:369','CD:2018-01-17:368','CD:2018-01-17:370','CD:2018-01-17:371','CD:2018-01-17:372','CD:2018-01-16:358','CD:2018-01-16:360','CD:2018-01-16:359','CD:2018-01-16:361','CD:2018-01-16:362','CD:2018-01-09:356','CD:2018-01-09:357','CD:2018-01-08:354','CD:2018-01-08:355','CD:2017-12-20:348','CD:2017-12-20:349','CD:2017-12-20:350','CD:2017-12-20:351','CD:2017-12-20:352','CD:2017-12-20:353','CD:2017-12-13:335','CD:2017-12-13:336','CD:2017-12-13:337','CD:2017-12-13:338','CD:2017-12-13:339','CD:2017-12-13:340','CD:2017-12-12:330','CD:2017-12-12:331','CD:2017-12-12:332','CD:2017-12-12:333','CD:2017-12-12:334','CD:2017-12-06:324','CD:2017-12-06:325','CD:2017-12-06:326','CD:2017-12-06:327','CD:2017-12-04:320','CD:2017-12-04:321','CD:2017-12-04:322','CD:2017-12-04:323','CD:2017-11-21:311','CD:2017-11-21:312','CD:2017-11-21:313','CD:2017-11-21:314','CD:2017-11-21:315','CD:2017-11-20:310','CD:2017-11-15:305','CD:2017-11-15:306','CD:2017-11-15:307','CD:2017-11-15:308','CD:2017-11-15:309','CD:2017-11-14:300','CD:2017-11-14:301','CD:2017-11-14:302','CD:2017-11-14:303','CD:2017-11-14:304','CD:2017-09-11:280','CD:2017-09-11:281','CD:2017-09-11:282'];

// CD:2017-11-15:305,European Union (Withdrawal) Bill: Committee of the whole House New Clause 25
// CD:2017-11-15:306,European Union (Withdrawal) Bill: Committee of the whole House New Clause 58
// CD:2017-11-15:307,European Union (Withdrawal) Bill: Committee of the whole House New Clause 30
// CD:2017-11-15:308,European Union (Withdrawal) Bill: Committee of the whole House New Clause 67
// CD:2017-11-15:309,European Union (Withdrawal) Bill: Committee of the whole House Amdt 70

// day2
// $idlist = ['CD:2017-11-14:300','CD:2017-11-14:301','CD:2017-11-14:302','CD:2017-11-14:303','CD:2017-11-14:304'];


// day 1
$idlist = ['CD:2017-09-11:280','CD:2017-09-11:281','CD:2017-09-11:282'];

$extracted = array();

$divisions = json_decode(file_get_contents($input));

foreach($divisions as $division) {

  //$id=$division->id;
  //$title=$division->title;
  //echo "$id,$title\n";
  if (in_array($division->id, $idlist)) {
    $extracted[] = $division;
  }
}

echo json_encode($extracted);

?>
