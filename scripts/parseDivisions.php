<?php
# Originally:
#http://hansard.services.digiminster.com/Divisions/list.json?startdate=2017-06-08
#http://hansard.services.digiminster.com/Divisions/Division/102.json


# http://lda.data.parliament.uk/commonsdivisions.json?_pageSize=500&minEx-date=2017-06-08
# http://lda.data.parliament.uk/commonsdivisions/id/845870.json/xml less accurate->WHY?!
# but can get info about member at: http://lda.data.parliament.uk/members/392.json
# using this is easier: http://api.data.parliament.uk/resources/files/800480.xml

# info at http://explore.data.parliament.uk/?learnmore=Commons%20Divisions

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://lda.data.parliament.uk/commonsdivisions.json?_pageSize=25000&minEx-date=2017-06-08");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$text = curl_exec ($ch);
curl_close ($ch);
$json = json_decode($text);

$out = array();

foreach($json->result->items as $division) {

      $thisdiv = array();
      $thisdiv['id'] = $division->uin;
      $thisdiv['date'] = $division->date->_value;
      $thisdiv['title'] = $division->title;
      $thisdiv['url'] = $division->_about;
      $thisdiv['hansard'] = $division->uin;
      $thisdiv['preamble'] = "";
      $thisdiv['PreVoteContent'] = "";

      // Initialise arrays
      $thisdiv['ayes'] = array();
      $thisdiv['noes'] = array();
      $thisdiv['ayestellers'] = array();
      $thisdiv['noestellers'] = array();

      // Build URL as it's not in the right format
      $urlarr = explode("/",$division->_about);

      $myurl = $urlarr[4];



      // Get full data about this division
      $ch = curl_init();
      $divid = $division->Id;
      curl_setopt($ch, CURLOPT_URL,"http://lda.data.parliament.uk/commonsdivisions/id/$myurl.json");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $text = curl_exec ($ch);
      curl_close ($ch);

      $json_div = json_decode($text);



      // Votes
      foreach ($json_div->result->primaryTopic->vote as $ayemember) {
          $idarr = $ayemember->member[0]->_about;
          $mid = explode("/",$idarr)[4];
          $type = $ayemember->type;

          if ($type == "http://data.parliament.uk/schema/parl#AyeVote") {
            // AYE
            $thisdiv['ayes'][] = $mid;
          } elseif ($type == "http://data.parliament.uk/schema/parl#NoVote") {
            $thisdiv['noes'][] = $mid;
          } else {
            echo "ERROR IN VOTE TYPE ".$type;
          }
      }


      // TODO need to add info about party to display colour
      // TODO replace use of csv file
      // AyeTellers
      // NoeTellers


      $out[] = $thisdiv;


  }

echo json_encode($out);


?>
