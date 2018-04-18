<?php
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<!--link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png"-->
	<link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon.png">


	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />


	<title>Divisions</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
	<meta name="viewport" content="width=device-width" />
	<link href="assets/css/mine.css" rel="stylesheet"/>
	<link href="assets/css/bootstrap.min.css" rel="stylesheet" />

	<script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
	<script src="assets/js/d3.v3.min.js" charset="utf-8"></script>

	<link href='https://fonts.googleapis.com/css?family=Merriweather:400,300' rel='stylesheet' type='text/css'>



</head>
<body>


	<div class="wrapper">


		<div class="main-panel">



			<div class="content">
				<div class="container-fluid">

					<div class="row">
						<h1>House of Commons: How Members of Parliament vote</h1>
						<h5>Follow me at <a href="http://twitter.com/puntofisso">@puntofisso</a> _ Data by <a href="http://www.data.parliament.uk">data.parliament</a> _ <a href="info.php"><span class="glyphicon glyphicon-info-sign"></span></a></h5>

					</div>

					<div class="row">
						<div class="controls">
							<select class="styled-select yellow rounded" name="divisions" id="divisions"><option disabled selected value> -- select an option -- </option></select>
							<button class="myButton rounded" id="reset" name="reset" value="Reset Chamber">Reset Chamber</button>
						</div>
					</div>


					<div class="row">


						<div class="col-md-6" id="graph"></div>

						<div class="col-md-6 hansard" id="vis">
							<div id="allinfo" name="allinfo">
								<div id="title" name="title"></div>
								<div id="date" name="date"></div>
								<div id="preamble" name="preamble"></div>
								<div id="prevote" name="prevote"></div>
								<div id="ayes_count" name="ayes_count"></div>
								<div id="noes_count" name="noes_count"></div>
							</div>
							<!--script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
              <ins class="adsbygoogle" style="display:inline-block;width:300px;height:250px" data-ad-client="ca-pub-4410734268512605" data-ad-slot="4652812342">
              </ins>
              <script>
              	(adsbygoogle = window.adsbygoogle || []).push({});
              </script-->
						</div>
					</div>
					<script>

					function formatDate(date) {
					  var monthNames = [
					    "January", "February", "March",
					    "April", "May", "June", "July",
					    "August", "September", "October",
					    "November", "December"
					  ];

					  var day = date.substr(8, 2);
					  var monthIndex = date.substr(5, 2);
					  var year = date.substr(0,4);

						var monthname = monthNames[monthIndex*1 -1];

					  return day + ' ' + monthname + ' ' + year;
					}

					// Read files
					var divisions = <?php echo file_get_contents("divisions.json");?>;
					var myconstituencies = <?php echo file_get_contents("mps.json");?>;

					// Create drop down select
					var select = $('#divisions');
					$.each(divisions, function(i, val){
						mydate = formatDate(val.date);
						select.append($('<option>' + mydate +": " + val.title +  ' [' +  val.hansard + ']  </option>' ));
					});

					// SVG initialisation
					var svgContainer = d3.select("#graph").append("svg")
					.attr("width", 1000)
					.attr("height", 600)
					.attr("id",'svgCont')
					.style("border", "0px solid black");

					// Group container for the first Commons Layout
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
						showLabels();
						addkey();
					});


					d3.select("#divisions")
					.on("change", function() {

						var thisdivision = divisions[$("#divisions")[0].selectedIndex-1];
						var mydate = new Date(thisdivision.date);
						var options = {
							weekday: "long", year: "numeric", month: "short",
							day: "numeric", hour: "2-digit", minute: "2-digit"
						};

						d3.select("#title").html(thisdivision.title);
						d3.select("#date").html(mydate.toLocaleTimeString("en-gb",options));
						d3.select("#preamble").html(thisdivision.preamble);
						d3.select("#prevote").html(thisdivision.PreVoteContent);
						d3.select("#ayes_count").html("Ayes: " + thisdivision.ayes.length);
						d3.select("#noes_count").html("Noes: " + thisdivision.noes.length);

						textGroup.selectAll("*").remove();

						textGroup.append("text")
						.attr("x", 50)
						.attr("y", 20)
						.attr("class", "legend")
						.attr( "fill-opacity", 1 ).transition().delay( 300 )
						.style("fill", "black")
						.text("Aye");

						textGroup.append("text")
						.attr("x", 243)
						.attr("y", 20)
						.attr("class", "legend")
						.attr( "fill-opacity", 1 ).transition().delay( 550 )
						.style("fill", "black")
						.text("Noe");

						textGroup.append("text")
						.attr("x", 425)
						.attr("y", 20)
						.attr("class", "legend")
						.attr( "fill-opacity", 1 ).transition().delay( 1000 )
						.style("fill", "black")
						.text("Didn't vote");

						textGroup.append("text")
						.attr("x", 550)
						.attr("y", 20)
						.attr("class", "legend")
						.attr( "fill-opacity", 1 ).transition().delay( 550 )
						.style("fill", "black")
						.text("Tellers Aye");

						textGroup.append("text")
						.attr("x", 550)
						.attr("y", 220)
						.attr("class", "legend")
						.attr( "fill-opacity", 1 ).transition().delay( 550 )
						.style("fill", "black")
						.text("Tellers No");

						paintDivision(thisdivision, 1000, function(d, i) {
					    return i * 7;  // Dynamic delay (i.e. each item delays a little longer)
					  });



					});


					/* Put all nodes back */
					d3.select("#reset")
					.on("click", function() {



						$("#title").empty();
						$("#date").empty();
						$("#preamble").empty();
						$("#prevote").empty();
						$("#ayes_count").empty();
						$("#noes_count").empty();
						textGroup.selectAll("*").remove();


						resetCommons(200, function(d, i) {
					    return i * 3;  // Dynamic delay (i.e. each item delays a little longer)
					  });
						showLabels();
					});




					</script>
				</div>
			</div>
		</div>


		<footer class="footer"></footer>

</div>
</div>


</body>

<!--   Core JS Files   -->

<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/js/bootstrap-checkbox-radio.js"></script>


<script src="assets/js/houseofcommons.js" type="text/javascript"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-109316372-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-109316372-1');
</script>

</html>
