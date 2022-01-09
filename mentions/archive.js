window.onload = function(){
      //copy this part
      var thisMonth = new Date();
      thisYear = thisMonth.getFullYear();
      thisMonth = thisMonth.getMonth();
      var page;
      var params = {
        "author": "",
        "category": "",
        "researcher": "",
        "academics": "",
        "page": "",
        "month": "",
        "year": ""
      }; //what the current params are. We carry these around while were ajaxing so that we don't need to be doing nasty url parsing constantly (we limit it to only having to do that on initial load).
      //When you add to the params list, be sure to set them in the filters change function, otherwise they won't end up changing

      //function for loading results on click events.
      var loadResults = function(event) {
        //only looking for left clicks. Keep default behavior for everything else.
        if (event.which != 1) {
          return;
        } else {
          event.preventDefault();
        }
        params["page"] = $(this).data("page"); //Grab this so that the page param in the hash can be updated
        loadStories(buildQuery(params, "&"));
      }

      

      //Function for loading the stories list given a query
      function loadStories(query) {
        $("#results").addClass("loading");
        window.history.replaceState({}, "", location.href.split(/\?|#/)[0] + (query != "" ? ("?" + query) : "")); //update history
        $.ajax("//www.mtu.edu/mtu_resources/php/ou/news/in-the-news/archives.php?" + query, {
          success: function(response) {
            $("#results").html(response);
            $("#results").removeClass("loading");
            $("#pagination a").click(loadResults);
            window.scrollTo(0,0); 
          },
          error: function() {
            $("#results").html("<p>Unable to retrieve results at this time. Please try again later.</p>");
            $("#results").removeClass("loading");
          }
        });
      }

      //build querystring from an object. Iterates over object and uses key/value for each param
      function buildQuery(object, glue) {
        var first = true; //is it the first in the query? Then we don't want the glue
        var result = "";

        //iterate over noninherited object properties
        for (key in object) {
          if (object.hasOwnProperty(key)) {
            if (object[key] != "") {
              if (first) {
                result = "" + key + "=" + (object[key] + "").replace(/\s+/g, "+");
                first = false;
              } else {
                result += glue + key + "=" + (object[key] + "").replace(/\s+/g, "+");
              }
            }
          }
        }
        return result;
      }


      $("#pagination a").click(loadResults);

      $("#reset").click(function() {
        var initialParams = buildQuery(params, "&");

        for (key in params) {
          if (params.hasOwnProperty(key)) {
            params[key] = "";
          }
        }

        //don't constantly ajax new stuff if there's nothing to reset
        if (initialParams != buildQuery(params, "&")) {
          loadStories(buildQuery(params, "&"));
        }
      });

      //code for if they have some hash variables set on load (i.e. want to load a specific set of results).
      if (location.hash != "") {
        var hashVars = (location.hash).replace(/#/, "").replace(/\+/g, " ").split("&"); //array of the params in param=value format
      } else {
        var hashVars = (window.location.search).replace(/\?/, "").replace(/\+/g, " ").split("&"); //array of the params in param=value format
      }
      var re = /(.*)=(.*)/; //regex to capture the param index/value
      var specific = false; //whether or not they are looking for a specific set of results.

      for (var i = 0; i < hashVars.length; i++) { //go through all of these params
        var groups = re.exec(hashVars[i]);
        if (groups) { //regex captured something
          var index = groups[1];
          var val = groups[2];
          if (typeof params[index] !== "undefined") { //only use the params that we're looking for; no funny business						
            params[index] = val; //set the param
            var option = $("option[value=\"" + val + "\"]"); //set the option to be selected (assuming it is an avialable option)
            if (option.length > 0) {
              option[0].selected = true;
              specific = true;
            }
            //pages don't have an option tag.
            else if (index == "page") {
              specific = true;
            }
          }
        }
      }
      if (specific) {
        loadStories(buildQuery(params, "&"));
      }
      /*
      //when they change the filters we need to update the params
      $("#filters select").change(function() {
        params["author"] = $("#authors").val() == "Not Set" ? "" : $("#authors").val();
        params["category"] = $("#categories").val() == "Not Set" ? "" : $("#categories").val();
        params["researcher"] = $("#researchers").val() == "Not Set" ? "" : $("#researchers").val();
        params["academics"] = $("#academics").val() == "Not Set" ? "" : $("#academics").val();
        params["year"] = $("#years").val() == "Not Set" ? "" : $("#years").val();
        params["month"] = $("#months").val() == "Not Set" ? "" : $("#months").val();
        params["page"] = ""; //reset page to zero when filtering changes
       
        //run the query
        loadStories(buildQuery(params, "&"));
      });*/
      //don't copy after this part
}
/*defer(function(){
	$(document).ready(function(){
		var count = $("#story-count").text();
		var start = 0;
		console.log(count);
		$("#previous").attr("style","display:none;");
		$("#next").attr("style","display:block;");
		$("#previous").click( function(e){
			e.preventDefault();
			$("#next").attr("style","display:block;");
			if(start != 0){
			start -= 15;
			}
			if(start == 0){
			$("#previous").attr("style","display:none;");
			$.get("//www.mtu.edu/mtu_resources/php/ou/news/in-the-news/archives.php", function(data, status){
			$("#js-content").html(data);
			});
			if(start > 0) {
	       $.get("//www.mtu.edu/mtu_resources/php/ou/news/in-the-news/archives.php?index="+start, function(data, status){
			$("#js-content").html(data);
			});
			}
			}
		});
		$("#next").click( function(e){
			e.preventDefault();
			if(start < count){
			$("#previous").attr("style","display:block;");	
			start += 15;
			console.log(start);
			$.get("//www.mtu.edu/mtu_resources/php/ou/news/in-the-news/archives.php?index="+start, function(data, status){
			$("#js-content").html(data);
			});
			if(start + 15 > count ) {
			$("#next").attr("style","display:none;");
			console.log("start is greater");
			}
		   } 
		});
	});
})*/