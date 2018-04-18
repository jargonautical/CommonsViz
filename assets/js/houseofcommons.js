
// Global settings
window.speaker = myconstituencies['parties']['Speaker'][0];
window.government = myconstituencies['government'];
window.government_colours = myconstituencies['government_colours'];
window.opposition = myconstituencies['opposition'];
window.opposition_colours = myconstituencies['opposition_colours'];

function paintDivision(thisdivision, duration, delayfunction) {
  var dataset = [];

  var X_aye = 0;
  var Y_aye = 0;
  var X_noe = 0;
  var Y_noe = 0;
  var X_abs = 0;
  var Y_abs = 0;

  var X_tellersaye = 0;
  var Y_tellersaye = 0;
  var X_tellersno = 0;
  var Y_tellersno = 0;

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
      } else if ((thisdivision.ayestellers).includes(id)) {
        // Aye Tellers
        item['vote'] = 'ayeteller';
        if (X_tellersaye >= 10) {
          X_tellersaye = 0;
          Y_tellersaye++;
        }
        item['x'] = X_tellersaye;
        item['y'] = Y_tellersaye;
        X_tellersaye++;
      } else if ((thisdivision.notellers).includes(id)) {
        // No Tellers
        item['vote'] = 'noteller';
        if (X_tellersno >= 10) {
          X_tellersno = 0;
          Y_tellersno++;
        }
        item['x'] = X_tellersno;
        item['y'] = Y_tellersno;
        X_tellersno++;
      }  else {
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
      } else if ((thisdivision.ayestellers).includes(id)) {
        // Aye Tellers
        item['vote'] = 'ayeteller';
        if (X_tellersaye >= 10) {
          X_tellersaye = 0;
          Y_tellersaye++;
        }
        item['x'] = X_tellersaye;
        item['y'] = Y_tellersaye;
        X_tellersaye++;
      } else if ((thisdivision.notellers).includes(id)) {
        // No Tellers
        item['vote'] = 'noteller';
        if (X_tellersno >= 10) {
          X_tellersno = 0;
          Y_tellersno++;
        }
        item['x'] = X_tellersno;
        item['y'] = Y_tellersno;
        X_tellersno++;
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

  houseDotsGroup.selectAll("rect")
  .data(dataset)
  .transition()
  .duration(duration)
  .delay(delayfunction)
  // .ease("variable")  // Transition easing - default 'variable' (i.e. has acceleration), also: 'circle', 'elastic', 'bounce', 'linear'
  .attr("x", function(d) {
    if (d['vote'] == 'aye') {
      return 10+d['x']*10;
    } else if (d['vote'] == 'noe') {
      return 200+d['x']*10;
    } else if (d['vote'] == 'abs') {
      return 400+d['x']*10;
    } else if (d['vote'] == 'ayeteller') {
      return 570+d['x']*10;
    } else if (d['vote'] == 'noteller') {
      return 570+d['x']*10;
    }
  })
  .attr("y", function(d) {
    if (d['vote'] == 'noteller') {
      return 10+220 + d['y']*10;
    } else {
      return 30+d['y']*10;
    }
  })
  .attr("class", function(d) {
    return d['name']
  });
}

function resetCommons(duration, delayfunction) {
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


  houseDotsGroup.selectAll("rect")
  .data(dataset)  // Update with new data
  .transition()  // Transition from old to new
  .duration(duration)  // Length of animation
  .delay(delayfunction)
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
  // showLabels();
}


function paintCommons() {
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

  var rectangles = houseDotsGroup.selectAll("rect")
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
    div.html("<img src='/assets/img/mps/" + d.id + ".jpg'/><p>" + d.name +"</p>")
    .style("left", (d3.event.pageX) + "px")
    .style("top", (d3.event.pageY - 28) + "px");
  })
  .on("mouseout", function(d) {
    div.transition()
    .duration(500)
    .style("opacity", 0);
  });

  // showLabels();

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
}

function addkey() {
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

function showDivisionLabels() {
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
}
