<?php
#http://hansard.services.digiminster.com/Divisions/list.json?startdate=2017-06-08
#http://hansard.services.digiminster.com/Divisions/Division/102.json

#TODO replace with
# http://lda.data.parliament.uk/commonsdivisions.json?_pageSize=500&minEx-date=2017-06-08
# http://api.data.parliament.uk/resources/files/800480.xml]

# info at http://explore.data.parliament.uk/?learnmore=Commons%20Divisions

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://hansard.services.digiminster.com/Divisions/list.json?startdate=2017-06-08");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$text = curl_exec ($ch);

curl_close ($ch);

$json = json_decode($text);
$out = array();


// Instead of $json, use DOM, but still spit out JSON
foreach($json->Results as $division) {
  if ($division->House === "Commons") {
      $thisdiv = array();
      $thisdiv['id'] = $division->Id;
      $thisdiv['date'] = $division->Date;
      $thisdiv['title'] = trim(preg_replace('/\s+/', ' ', $division->DebateSection));
      $thisdiv['hansard'] = $division->HansardIdentifier;

      // Get full count
      $ch = curl_init();
      $divid = $division->Id;
      curl_setopt($ch, CURLOPT_URL,"http://hansard.services.digiminster.com/Divisions/Division/$divid.json");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $text = curl_exec ($ch);
      curl_close ($ch);

      $json_div = json_decode($text);

      $thisdiv['preamble'] =$json_div[0]->Preamble;
      $thisdiv['ayes'] = array();
      $thisdiv['noes'] = array();
      $thisdiv['ayestellers'] = array();
      $thisdiv['noestellers'] = array();
      // TODO <DivisionTitle>Nuclear Safeguards Bill: Report Stage Amdt 5</DivisionTitle>

      $thisdiv['PreVoteContent'] = $json_div[0]->PreVoteContent;
      // AyeCount
      foreach ($json_div[0]->AyeMembers as $ayemember) {
          $thisdiv['ayes'][] = "$ayemember->Id";
      }
      // NoeCount
      foreach ($json_div[0]->NoeMembers as $noemember) {
          $thisdiv['noes'][] = "$noemember->Id";
      }
      // TODO tellers (where will colour be?)


      $out[] = $thisdiv;

    }
  }

echo json_encode($out);


?>
