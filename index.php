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

							<div id="title" name="title"></div>
							<div id="date" name="date"></div>
							<div id="preamble" name="preamble"></div>
							<div id="prevote" name="prevote"></div>
							<div id="ayes_count" name="ayes_count"></div>
							<div id="noes_count" name="noes_count"></div>

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

					  return day + ' ' + monthNames[monthIndex*1] + ' ' + year;
					}


					var myconstituencies = <?php echo file_get_contents("mps.json");?>;
					window.speaker = myconstituencies['parties']['Speaker'][0];
					var divisions = <?php echo file_get_contents("divisions.json");?>;
					var select = $('#divisions');

					$.each(divisions, function(i, val){
						mydate = formatDate(val.date);
						select.append($('<option>' + val.title +  ' [' +  val.hansard + '] ' + mydate + '  </option>' ));
					});

					// Settings
					window.government = myconstituencies['government'];
					window.government_colours = myconstituencies['government_colours'];
					window.opposition = myconstituencies['opposition'];
					window.opposition_colours = myconstituencies['opposition_colours'];

					// SVG initialisation
					var svgContainer = d3.select("#graph").append("svg")
					.attr("width", 1000)
					.attr("height", 600)
					.attr("id",'svgCont')
					.style("border", "0px solid black");

					// Group containers for the Commons Layout
					var govGroup = d3.select('#svgCont').append("g")
					.attr("id", "govSvgGroup");

					var textGroup = d3.select('#svgCont').append("g")
					.attr("id","textGroup");

					// The div for the tooltip
					var div = d3.select("body").append("div")
					.attr("class", "tooltip")
					.style("opacity", 0);


					paint();


					function paint() {
						var dataset = [];

						// Government Round
						var X = 0;
						var Y = 0;

						for (var i = 0, ilen = government.length; i < ilen; i++) {
							partyname = government[i];
							partycolour = government_colours[i];
							this_party_mps = myconstituencies['parties'][partyname];

							for (var j = 0, jlen = this_party_mps.length; j < jlen; j ++) {
								name = this_party_mps[j]['name'];
								var item = [];
								item['name'] = name;
								item['id'] = this_party_mps[j]['id'];
								item['party'] = partyname;
								item['colour'] = partycolour;
								item['government'] = true;

								if (Y > 5) {
									X++;
									Y = 0;
								}
								item['x'] = X;
								item['y'] = Y;
								Y++;
								dataset.push(item);
							}
						}

						// Opposition Round
						var X = 0;
						var Y = 0;

						for (var i = 0, ilen = opposition.length; i < ilen; i++) {
							partyname = opposition[i];
							partycolour = opposition_colours[i];
							this_party_mps = myconstituencies['parties'][partyname];

							for (var j = 0, jlen = this_party_mps.length; j < jlen; j ++) {
								name = this_party_mps[j]['name'];
								var item = [];
								item['name'] = name;
								item['id'] = this_party_mps[j]['id'];
								item['party'] = partyname;
								item['colour'] = partycolour;
								item['government'] = false;

								if (Y > 5) {
									X++;
									Y = 0;
								}
								item['x'] = X;
								item['y'] = Y;
								Y++;
								dataset.push(item);
							}
						}


						//var rectangles = svgContainer.selectAll("rect")
						var rectangles = govGroup.selectAll("rect")
						.data(dataset)
						.enter()
						.append("rect");



						// Create the actual markers via function with data
						rectangles
						.attr("x", function (d) {
							return 30 + (d['x'] * 10);
						})
						.attr("y", function (d) {
							if (d['government']) {
								return 200 + (d['y'] *10 );
							} else {
								return 50 + (d['y'] *10 );
							}
						})
						.attr("width", 8)
						.attr("height", 8)
						.style("fill", function(d) {
							return d['colour'];
						})
						.on("mouseover", function(d) {
							div.transition()
							.duration(200)
							.style("opacity", .9);
							div.html("<img src='assets/img/mps/" + d.id + ".jpg'/><p>" + d.name +"</p>")
							.style("left", (d3.event.pageX) + "px")
							.style("top", (d3.event.pageY - 28) + "px");
						})
						.on("mouseout", function(d) {
							div.transition()
							.duration(500)
							.style("opacity", 0);
						});

						showLabels();



					}

					function showLabels(){
						textGroup.selectAll("*").remove();

						textGroup.append("text")
						.attr("x", 0)
						.attr("y", 150)
						.attr("class", "legend")
						.attr( "fill-opacity", 1 ).transition().delay( 1000 )
						.style("fill", "black")
						.text("Speaker");

						textGroup.append("text")
						.attr("x", 60)
						.attr("y", 120)
						.attr("class", "legend")
						.attr( "fill-opacity", 1 ).transition().delay( 1000 )
						.style("fill", "black")
						.text("Opposition");

						textGroup.append("text")
						.attr("x", 60)
						.attr("y", 197)
						.attr("class", "legend")
						.attr( "fill-opacity", 1 ).transition().delay( 1000 )
						.style("fill", "black")
						.text("Government");

						textGroup.append("rect")
						.attr("x",0 )
						.attr("y", 153)
						.attr("width", 8)
						.attr("height", 8)
						.style("fill", function() {
							return '#dddddd';
						})
						.on("mouseover", function() {
							div.transition()
							.duration(200)
							.style("opacity", .9);
							div.html("<img src='assets/img/mps/" + window.speaker.id + ".jpg'/><p>" + window.speaker.name +"</p>" )
							.style("left", (d3.event.pageX) + "px")
							.style("top", (d3.event.pageY - 28) + "px");
						})
						.on("mouseout", function(d) {
							div.transition()
							.duration(500)
							.style("opacity", 0);
						});

						// Add Keys
						$('#svgKeyCont').remove();
						var svgContainer2 = d3.select("#vis").append("svg")
						.attr("width", 200)
						.attr("height", 600)
						.attr("id",'svgKeyCont')
						.style("border", "0px solid black");


						var x = 10;
						var y = 10;
						svgContainer2.append("text")
						.attr("x", x)
						.attr("y", y)
						.text("Government");
						x = 25;
						for (var i = 0, ilen = government.length; i < ilen; i++) {

							colour = government_colours[i];
							y = y + 12;

							svgContainer2.append("text")
							.attr("x", x)
							.attr("y", y)
							.text(government[i]);

							svgContainer2.append("rect")
							.attr("x",10 )
							.attr("y", y-8)
							.attr("width", 8)
							.attr("height", 8)
							.style("fill", function() {
								return colour;
							});

						}

						x = 10;
						y = 50;
						svgContainer2.append("text")
						.attr("x", x)
						.attr("y", y)
						.text("Opposition");
						x = 25;
						for (var i = 0, ilen = opposition.length; i < ilen; i++) {
							y = y + 12;
							colour = opposition_colours[i];
							svgContainer2.append("text")
							.attr("x", x)
							.attr("y", y)
							.text(opposition[i]);

							svgContainer2.append("rect")
							.attr("x",10 )
							.attr("y", y-8)
							.attr("width", 8)
							.attr("height", 8)
							.style("fill", function() {
								return colour;
							});
						}





					}

					d3.select("#divisions")
					.on("change", function() {

						var thisdivision = divisions[$("#divisions")[0].selectedIndex-1];
						var dataset = [];

						var X_aye = 0;
						var Y_aye = 0;
						var X_noe = 0;
						var Y_noe = 0;
						var X_abs = 0;
						var Y_abs = 0;

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

						for (var i = 0, ilen = government.length; i < ilen; i++) {
							partyname = government[i];
							partycolour = government_colours[i];
							this_party_mps = myconstituencies['parties'][partyname];

							for (var j = 0, jlen = this_party_mps.length; j < jlen; j ++) {
								name = this_party_mps[j]['name'];
								id = this_party_mps[j]['id'];

								var item = [];
								item['id'] = id;
								item['name'] = name;
								item['party'] = partyname;
								item['colour'] = partycolour;



								if ((thisdivision.ayes).includes(id)) {
									item['vote'] = 'aye';
									if (X_aye >= 10) {
										X_aye = 0;
										Y_aye++;
									}
									item['x'] = X_aye;
									item['y'] = Y_aye;
									X_aye++;
								} else if ((thisdivision.noes).includes(id)) {
									item['vote'] = 'noe';
									if (X_noe >= 10) {
										Y_noe++;
										X_noe = 0;
									}
									item['x'] = X_noe;
									item['y'] = Y_noe;
									X_noe++;
								} else {
									item['vote'] = 'abs';
									if (X_abs >= 10) {
										Y_abs++;
										X_abs = 0;
									}
									item['x'] = X_abs;
									item['y'] = Y_abs;
									X_abs++;
								}
								dataset.push(item);
							}


						}
						for (var i = 0, ilen = opposition.length; i < ilen; i++) {
							partyname = opposition[i];
							partycolour = opposition_colours[i];
							this_party_mps = myconstituencies['parties'][partyname];

							for (var j = 0, jlen = this_party_mps.length; j < jlen; j ++) {
								name = this_party_mps[j]['name'];
								id = this_party_mps[j]['id'];

								var item = [];
								item['id'] = id;
								item['name'] = name;
								item['party'] = partyname;
								item['colour'] = partycolour;
								item['x'] = -1;
								item['y'] = -1;



								if ((thisdivision.ayes).includes(id)) {
									item['vote'] = 'aye';
									if (X_aye >= 10) {
										X_aye = 0;
										Y_aye++;
									}
									item['x'] = X_aye;
									item['y'] = Y_aye;
									X_aye++;
								} else if ((thisdivision.noes).includes(id)) {
									item['vote'] = 'noe';
									if (X_noe >= 10) {
										Y_noe++;
										X_noe = 0;
									}
									item['x'] = X_noe;
									item['y'] = Y_noe;
									X_noe++;
								} else {
									item['vote'] = 'abs';
									if (X_abs >= 10) {
										Y_abs++;
										X_abs = 0;
									}
									item['x'] = X_abs;
									item['y'] = Y_abs;
									X_abs++;
								}
								dataset.push(item);
							}


						}

						govGroup.selectAll("rect")
						.data(dataset)
						.transition()
						.duration(1000)

						.delay(function(d, i) {
							return i * 7;  // Dynamic delay (i.e. each item delays a little longer)
						})
						//.ease("variable")  // Transition easing - default 'variable' (i.e. has acceleration), also: 'circle', 'elastic', 'bounce', 'linear'
						.attr("x", function(d) {
							if (d['vote'] == 'aye') {
								return 10+d['x']*10;
							} else if (d['vote'] == 'noe') {
								return 200+d['x']*10;
							} else if (d['vote'] == 'abs') {
								return 400+d['x']*10;
							}
						})
						.attr("y", function(d) {
							return 30+d['y']*10;
						})
						.attr("class", function(d) {
							return d['name']
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


						var dataset = [];

						// Government Round
						var X = 0;
						var Y = 0;

						textGroup.selectAll("*").remove();

						for (var i = 0, ilen = government.length; i < ilen; i++) {
							partyname = government[i];
							partycolour = government_colours[i];
							this_party_mps = myconstituencies['parties'][partyname];

							for (var j = 0, jlen = this_party_mps.length; j < jlen; j ++) {
								name = this_party_mps[j]['name'];
								var item = [];
								item['name'] = name;
								item['id'] = this_party_mps[j]['id'];
								item['party'] = partyname;
								item['colour'] = partycolour;
								item['government'] = true;

								if (Y > 5) {
									X++;
									Y = 0;
								}
								item['x'] = X;
								item['y'] = Y;
								Y++;
								dataset.push(item);
							}
						}

						// Opposition Round
						var X = 0;
						var Y = 0;

						for (var i = 0, ilen = opposition.length; i < ilen; i++) {
							partyname = opposition[i];
							partycolour = opposition_colours[i];
							this_party_mps = myconstituencies['parties'][partyname];

							for (var j = 0, jlen = this_party_mps.length; j < jlen; j ++) {
								name = this_party_mps[j]['name'];
								var item = [];
								item['name'] = name;
								item['id'] = this_party_mps[j]['id'];
								item['party'] = partyname;
								item['colour'] = partycolour;
								item['government'] = false;

								if (Y > 5) {
									X++;
									Y = 0;
								}
								item['x'] = X;
								item['y'] = Y;
								Y++;
								dataset.push(item);
							}
						}


						govGroup.selectAll("rect")
						.data(dataset)  // Update with new data
						.transition()  // Transition from old to new
						.duration(200)  // Length of animation
						.delay(function(d, i) {
							return i * 3;  // Dynamic delay (i.e. each item delays a little longer)
						})
						//.ease("linear")  // Transition easing - default 'variable' (i.e. has acceleration), also: 'circle', 'elastic', 'bounce', 'linear'
						.attr("x", function (d) {
							return 30 + (d['x'] * 10);
						})
						.attr("y", function (d) {
							if (d['government']) {
								return 200 + (d['y'] *10 );
							} else {
								return 50 + (d['y'] *10 );
							}
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



<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-109316372-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-109316372-1');
</script>

</html>
