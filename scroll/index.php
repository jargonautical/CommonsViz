<?php
?>
<!DOCTYPE html>
<html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<head>
<link href="style.css" rel="stylesheet" />
<script src="../assets/js/jquery-1.10.2.js" type="text/javascript"></script>
<link href="../assets/css/mine.css" rel="stylesheet"/>
<link href='https://fonts.googleapis.com/css?family=Merriweather:400,300' rel='stylesheet' type='text/css'>
</head>
<body>
<div id='container' class='container-1'>
  <h1>Navigating the Brexit votes</h1>
  <h5>by <a href="http://twitter.com/puntofisso">Giuseppe Sollazzo</a></h5>
  <div id='graph1' ></div>

  <div>
    <h3>Election 2017</h3>
    On May 8th, 2018, the General Election produced a hung parlament. Subsequent votes show difficulties in achieving a majority.
  </div>

  <div id='sections'>


    <div>
      <h3>Vote 1</h3>
      Corbyn's amendment
      <iframe src="https://videoplayback.parliamentlive.tv/Player/Index/80fc2f9b-cda5-45da-8193-48f508169696?audioOnly=False&amp;autoStart=False&amp;statsEnabled=True" id="UKPPlayer" name="UKPPlayer" title="UK Parliament Player" seamless="seamless" frameborder="0" allowfullscreen style="width:100%;height:100%;"></iframe>
    </div>

    <div>
      <h3>Vote 2</h3>
      Another one
    </div>

    <div>
      <h3>Vote 3</h3>
      Brexit was not voted by Ken Clarke.
    </div>

  </div>

</div>




</div>

<script src="d3v4+jetpack.js"></script>
<script src="graph-scroll.js"></script>
<script>

function formatDate(date) {
  var monthNames = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
  var day = date.substr(8, 2);
  var monthIndex = date.substr(5, 2);
  var year = date.substr(0,4);
  var monthname = monthNames[monthIndex*1 -1];
  return day + ' ' + monthname + ' ' + year;
}

// Read files
var divis1 = <?php echo file_get_contents("divis1.json");?>;
var myconstituencies = <?php echo file_get_contents("mps.json");?>;
var oldWidth = 0

function render(tagid='#graph1', onresize=123){
  if (oldWidth == innerWidth) return
  oldWidth = innerWidth

  var width = height = d3.select(tagid).node().offsetWidth
  var r = 40


  if (innerWidth <= 925){
    width = innerWidth
    height = innerHeight*.7
  }


  if (onresize == 0) {
    paintCommons();
    //  resetCommons(100, function(d) {return 1;});
    return;
  }

  var svg = d3.select('.container-1 ' + tagid).html('')
    .append('svg')
      .attrs({width: width, height: height, id: 'svgCont'});

  var gs = d3.graphScroll()
      .container(d3.select('.container-1'))
      .graph(d3.selectAll('container-1 ' + tagid))
      .eventId('uniqueId1')  // namespace for scroll and resize events
      .sections(d3.selectAll('.container-1 #sections > div'))
      // .offset(innerWidth < 900 ? innerHeight - 30 : 200)
      .on('active', function(i){
        console.log(i);
        if (i == 0) {
          // quick hack to avoid scrolling on first
          resetCommons(100, function(d, i) {
            return i/30;
          });
        } else {
          var numberofdivisions = divis1.length;
          var thisdivision = divis1[i-1];
          paintDivision(thisdivision, 100, function(d, i) {
            return i;
          });
        }
      });






}
render('#graph1', 200);
d3.select(window).on('resize', render)

</script>


<script>


// Group container for the Commons Layout
var houseDotsGroup = d3.select('#svgCont').append("g")
.attr("id", "houseSvgGroup");

// Group container for the textual elements
var textGroup = d3.select('#svgCont').append("g")
.attr("id","textGroup");

// The div for the tooltip
var div = d3.select("body").append("div")
.attr("class", "tooltip")
.style("opacity", 0);



$(document).ready( function () {
  paintCommons();

});




</script>
<script src="../assets/js/houseofcommons.js" type="text/javascript"></script>
</body>
</html>
