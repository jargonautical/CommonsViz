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
						<h5>Follow me at <a href="http://twitter.com/puntofisso">@puntofisso</a> _ Data by <a href="http://www.data.parliament.uk">data.parliament</a> _ <a href=""><span class="glyphicon glyphicon-info-sign"></span></a></h5>

					</div>




					<div class="row">


						<div class="col-md-6" id="graph">
							<h3>What is this?</h3>
								<p class="description">The UK General Election 2017 resulted in a hung parliament. The Conservatives led by Theresa May, who had been Prime Minister since July 2016, had to strike a deal with the Democratic Unionist Party to reach a majority. The Government relies on the combined 327 votes of the Conservative and Democratic Unionists, which is slightly above the required majority of 323.</p>
								<p class="description">This project is an interactive, visual exploration of each vote&mdash;called "division" in the UK Parliament&mdash;and the resulting majority.</p>
								<p class="description"><b>Disclaimer:</b> this visualization should be considered a proof-of-concept beta. There are some discrepancies in the data; for example, the divisions available through the API seem to be a subset of the full dataset.</p>
								<p class="description">For more data driven news, <a href="http://puntofisso.net/newsletter">subscribe to my weekly newsletter</a>.</p>
							<h3>Data</h3>
								<p class="description">The list of <b>MPs and their photos</b> is tricky. I had to cross-reference two datasets, including data from TheyWorkForYou, as
								I couldn't find a full, exhaustive list of current MPs together with their so-called MNID, on the Parliament website. </p>
								<p class="description">As far as divisions are concerned, there are two endpoints
									I've used: the <b>list of divisions</b> at </p><pre>http://hansard.services.digiminster.com/Divisions/list.json</pre> <p class="description">and
									the <b>detail of each division</b>, for example </p><pre>http://hansard.services.digiminster.com/Divisions/Division/102.json</pre>
								<p class="description">One <b>issue</b> that I've found is that the division list API doesn't release the full list of divisions as listed on <a href="https://hansard.parliament.uk/search/Divisions?startDate=2017-06-08&searchTerm=finance%20bill&house=Commons&includeCommitteeDivisions=True">Hansard</a>. I'm not sure why this is the case, but I'm investigating.</p>
								<p class="description">All the issues, whether they are incorrect data, missing API calls, or simple <i>desiderata</i>, have been reported to the (excellent) teams working at Parliament on data and business systems, and we're working together to improve the quality of the data.</p>
								<p class="description"></p>
								<p class="description"></p>
							<h3>Code</h3>
								<p class="description">The code I've used is available on <a href="http://github.com/puntofisso/CommonsViz">Github</a>.</p>
						</div>

						<div class="col-md-6 hansard" id="vis">


					</div>


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




<!--script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-99388198-1', 'auto');
ga('send', 'pageview');

</script-->


</html>
