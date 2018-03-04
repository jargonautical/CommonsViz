<?php
?>
<!DOCTYPE html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<head>
<link href="style.css" rel="stylesheet" />
<script src="../assets/js/jquery-1.10.2.js" type="text/javascript"></script>
<link href="../assets/css/mine.css" rel="stylesheet"/>
</head>

<div id='container' class='container-1'>

  <div id='graph'></div>

  <div id='sections'>
    <div>
      <h3>Election 2017 - what it looks like</h3>
    </div>

    <div>
      <h3>Vote 1</h3>
    </div>

    <div>
      <h3>Vote 2</h3>
    </div>

    <div>
      <h3>Vote 3</h3>
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
var divisions = <?php echo file_get_contents("../divisions.json");?>;
var myconstituencies = <?php echo file_get_contents("../mps.json");?>;



var oldWidth = 0
function render(){
  if (oldWidth == innerWidth) return
  oldWidth = innerWidth

  var width = height = d3.select('#graph').node().offsetWidth
  var r = 40


  if (innerWidth <= 925){
    width = innerWidth
    height = innerHeight*.7
  }

  var svg = d3.select('#graph').html('')
    .append('svg')
      .attrs({width: width, height: height, id: 'svgCont'});

  var gs = d3.graphScroll()
      .container(d3.select('.container-1'))
      .graph(d3.selectAll('container-1 #graph'))
      .eventId('uniqueId1')  // namespace for scroll and resize events
      .sections(d3.selectAll('.container-1 #sections > div'))
      // .offset(innerWidth < 900 ? innerHeight - 30 : 200)
      .on('active', function(i){
        if (i == 0) {
          // quick hack to avoid scrolling on first
          resetCommons();
        } else {
          var thisdivision = divisions[i];
          paintDivision(thisdivision, 100, function(d, i) {
            return i;
          });
        }
      })

}
render()
d3.select(window).on('resize', render)

</script>


<script>



// SVG initialisation
// var svgContainer = d3.select("#graph").append("svg")
// .attr("width", 1000)
// .attr("height", 600)
// .attr("id",'svgCont')
// .style("border", "0px solid black");

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


// d3.select("#divisions")
// .on("change", function() {
//
//   var thisdivision = divisions[$("#divisions")[0].selectedIndex-1];
//   var mydate = new Date(thisdivision.date);
//   var options = {
//     weekday: "long", year: "numeric", month: "short",
//     day: "numeric", hour: "2-digit", minute: "2-digit"
//   };
//
//   d3.select("#title").html(thisdivision.title);
//   d3.select("#date").html(mydate.toLocaleTimeString("en-gb",options));
//   d3.select("#preamble").html(thisdivision.preamble);
//   d3.select("#prevote").html(thisdivision.PreVoteContent);
//   d3.select("#ayes_count").html("Ayes: " + thisdivision.ayes.length);
//   d3.select("#noes_count").html("Noes: " + thisdivision.noes.length);
//
//
//
//   paintDivision(thisdivision);
//   showDivisionLabels();
//
//
// });

//
// /* Put all nodes back */
// d3.select("#reset")
// .on("click", function() {
//
//
//
//   $("#title").empty();
//   $("#date").empty();
//   $("#preamble").empty();
//   $("#prevote").empty();
//   $("#ayes_count").empty();
//   $("#noes_count").empty();
//   textGroup.selectAll("*").remove();
//
//
//   resetCommons();
//
//
//
// });

</script>
<script src="../assets/js/houseofcommons.js" type="text/javascript"></script>
