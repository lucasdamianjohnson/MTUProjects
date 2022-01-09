var crit = 1;
var previous_data = "";
//console.log('loading');
defer(function() {
	console.log("this is a test!!!!!!");
	//console.log('loaded');
	var qs = document.location.search;
	var selected = "";
	
	if(qs) {
		$('#results').html('<div class="center"><img src="/mtu_resources/images/pre-loader.gif" alt="loading" /></div>');
		document.body.scrollTop = document.documentElement.scrollTop = 0;
		
		$.get('https://www.mtu.edu/search/includes/generate.php'+qs, function(data) {
			$("#results").html(data);
			previous_data = data;
			$("html, body").animate({ scrollTop: $('#results').offset().top - 120 }, 1000);
			
			console.log("testing");
		});
	}

	

	//This is what happens when the form is submitted
	$("#submit").click( function(e) {
		//$('#results table tbody').html('');
		$('#results').html('<div class="center"><img src="/mtu_resources/images/pre-loader.gif" alt="loading" /></div>');
		document.body.scrollTop = document.documentElement.scrollTop = 0;
		e.preventDefault();
		
		$.post('https://www.mtu.edu/search/includes/generate.php', $('#search-form').serialize(), function(data) {
			$("#results").html(data);
			previous_data = data;
			$("html, body").animate({ scrollTop: $('#results').offset().top - 120 }, 1000);

			console.log("testing");
				//$("#results a").first().blur();
		});
		
	});
	
	$("#search-form").submit( function(e) {
		//$('#results table tbody').html('');
		$('#results').html('<div class="center"><img src="/mtu_resources/images/pre-loader.gif" alt="loading" /></div>');
		document.body.scrollTop = document.documentElement.scrollTop = 0;
		e.preventDefault();
		$.post('https://www.mtu.edu/search/includes/generate.php', $('#search-form').serialize(), function(data) {
			$("#results").html(data);
			previous_data = data;
			$("html, body").animate({ scrollTop: $('#results').offset().top - 120 }, 1000);

			console.log("testing");
		});
	});

	//This is when a criteria is added
	$('#add').click( function(e) {
		e.preventDefault();
		addFields();
	});

	
	function addFields() {
		console.log("crit added!");
		if(crit != 5){
			//we are not allowing any more than five search crtieria 
			crit++;
			var tempcrit = crit - 1;
			var $paste = $("#copy-"+tempcrit); //this is where we will append the clone to after
			var $clone = $("#copy-1").clone().prop("id","copy-"+crit); //copy the search
			$paste.after($clone); //insert it

			$("#copy-"+crit+" div:first").css("display","block"); //reveal the relation option
			$("#copy-"+crit+" input:first").val(""); //clear the input
			$("#copy-"+crit).append('<div class="form-group search_remove"><a href="javascript:void(0);" tabindex="0" aria-label="Remove Criteria '+crit+'" role="button" class="' 
				+"copy-"+crit+' button-grey" id="remove">X</a></div>');//add the remove button
				$("#copy-"+crit + " label").each(function() {
				$(this).attr('for',$(this).attr('for') + crit);
			});
			 $("#copy-"+crit + " input").each(function() {
				$(this).attr('id',$(this).attr('id') + crit);
			});
			  $("#copy-"+crit + " select").each(function() {
				$(this).attr('id',$(this).attr('id') + crit);
			});
			  $("#search_bool" + crit).focus();
		}
	}
	
	//this is the New Search function
	$("#reset").click( function(e) {
		resetFields();
	});
	

	
	
	function resetFields() {
		$("#results").html("");
		crit = 1;
		//deletes all input groups but the first one
		$(".input-group").each(function(i, el) {
			if(el.id=="copy-1") {
				$("#copy-1 input:first").val('');
				$("html, body").animate({ scrollTop: 0 }, 500);
			} else {
				el.remove();
			}
		});
	}
	//when the user is viewing a sigle users data and pressed the back button
	$("#results").on( "click",'#back',function(e) {
		//load the previous results
		$("#results").html(previous_data);


	});


	$("#search-form").on("keydown",".button-grey, .button-blank, .button", function(e){
		     if(e.which == 32){
	     	$(this).click();
	  		return false;
	     }

	});
 	
	$("#search-form").on("keydown",".gettext", function(e){
		     if(e.which == 13){
	     	$("#submit").click();
	     	$("#submit").focus();
	  		return false;
	     }

	});
	$("#results").on("keydown",".button-grey, .button-blank, .button", function(e){
		     if(e.which == 32){
	     	$(this).click();
	     	if($(this).attr("id")=="back"){
	     				$("#"+selected).focus();
	     	}
	   	return false;
	     }

	});
	
	//when the user clicks a person's names 
	$("#results").on( "click",'.click-name',function(e) {
		selected = $(this).attr("data-id");
		//this loads the single user view of the generate script
		$.post( 'https://www.mtu.edu/search/includes/generate.php',
		{
			"username":$(this).attr("data-id")
		}, function(data) {
			$("#results").html(data);
		});
	});

	//when the X button is clicked to remove a search criteria 
	$("#search-form").on( "click",'#remove',function(e) {
		e.preventDefault();
		var id = this.className.split(' ')[0];
		crit--;
		console.log(id);
		//we use the class attr of the remove button to decided which input group to remove
		$("#"+id).remove();
		this.remove();
		//we need to update the input-groups and the remove buttons
		$(".input-group").each(function(i, el) {
			i++;

			var newID = "copy-" + i;
			el.id = newID;
			$('a:first', this).attr("class","copy-" + i +" button-grey");

		});
	});
	
}
);