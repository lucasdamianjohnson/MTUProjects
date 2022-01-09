var home = "Any"; //ty at home
var stem = new Array();//stem catagories
var stflag = false; //is there search text?
function results(){
	var i = 0;
	$(".sliders > div").each(function() {
		if($(this).attr("class") == "slider"){
			i++;
		}
	});
	if (i == 0) {
		$("#results").css("display","block");
	} else {
		$("#results").css("display","none");
	}
}
/*
Used to make sure all proper sliders are showing
*/
function refilter(){
	$(".sliders > div").each(function(){
		var st = $(this).attr("data-stem");
		var h = $(this).attr("data-home");
		var select = $(this).attr("data-select");
		var test = true;
		if(stem.length > 0) {
			stem.forEach(function(entry) {
				if(st.includes(entry)){
					test = false;
				}
			});
			if(!test){
				if(h.includes(home) || home == "Any") {
					if((select == "true") || (stflag == false)) {
						$(this).removeClass("slider-hide");
			            $(this).addClass("slider");
					}
				}
			}
		} else {
			if(h.includes(home) || home == "Any") {
				if((select == "true") || (stflag == false)) {
					$(this).removeClass("slider-hide");
			          $(this).addClass("slider");
				}
			}
		}
	});
}
/*
This is for the search text bar. Calls the php file and gets a list of ids that display the sliders.
*/
function form() {
	console.log("this is a test");
	var serializedData = $("#trekkerform").serialize();
	if(serializedData.trim() != "title="){
		stflag = true;
		$.post('https://www.mtu.edu/mtu_resources/php/ou/trekkers/display.php', serializedData,
			function(response) {
				console.log(response);
				var a = jQuery.parseJSON(response);
//hide all sliders than show the ones with the ids that were returnred
$(".sliders > div").each(function(){
	$(this).removeClass("slider");
    $(this).addClass("slider-hide");
	$(this).attr("data-select","false");
});
a.forEach(function(entry) {
	var st = $("#"+entry).attr("data-stem");
	var h = $("#"+entry).attr("data-home");
	var test = true;
	$("#"+entry).attr("data-select","true");
	if(stem.length > 0) {
		stem.forEach(function(entry) {
			if(st.includes(entry)){
				test = false;
			}
		});
		if(!test){
			if(h.includes(home) || home == "Any") {
				$("#"+entry).removeClass("slider-hide");
				$("#"+entry).addClass("slider");
			}
		}
	} else {
		if(h.includes(home) || home == "Any") {
			$("#"+entry).removeClass("slider-hide");
			$("#"+entry).addClass("slider");
		}
	}

});
results();
});
	} else {
//it the user searches a blank string show all the sliders
stflag = false;
$(".sliders > div").each(function(){
	var st = $(this).attr("data-stem");
	var h = $(this).attr("data-home");
	var test = true;
	if(stem.length > 0) {
		stem.forEach(function(entry) {
			if(st.includes(entry)){
				test = false;
			}
		});
		if(!test){
			if(h.includes(home) || home == "Any") {
			$(this).removeClass("slider-hide");
			$(this).addClass("slider");
			}
		}
	} else {
		if(h.includes(home) || home == "Any") {
			$(this).removeClass("slider-hide");
			$(this).addClass("slider");
		}
	}
});
}
}
/*
A function to show hide sliders based on the form filters.
*/
function filter(filter,type){
	if(type=="check"){
		$(".sliders > div").each(function(){
			var st = $(this).attr("data-stem");
			var h = $(this).attr("data-home");
			var test = true;
			stem.forEach(function(entry) {
				if(st.includes(entry)){
					test = false;
				}
			});
			if(test){
			$(this).removeClass("slider");
			$(this).addClass("slider-hide");
			} else {
				var select = $(this).attr("data-select");
				if((select == "true") || (stflag == false)) {
					if(h.includes(home) || home == "Any") {
			$(this).removeClass("slider-hide");
			$(this).addClass("slider");
					}
				}
			}
		});
	}
	if(type=="all") {
		$(".sliders > div").each(function(){
			var h = $(this).attr("data-home");
			var select = $(this).attr("data-select");
			if((select == "true") || (stflag == false)) {
				if(h.includes(home) || home == "Any") {
			    $(this).removeClass("slider-hide");
			    $(this).addClass("slider");
				}
			}
		});
	}
	if(type=="uncheck") {
		$(".sliders > div").each(function(){
			var ss = $(this).attr("data-stem");
			var stuff = ss.split(",");
			var other = stem.toString();
			var test = true;
			stuff.forEach(function(entry) {
				if(other.includes(entry)) {
					test = false;
				}
			});
			if(test){
				if((ss.includes(filter)) ){
				$(this).removeClass("slider");
			    $(this).addClass("slider-hide");
				}
			}
		});
	}
	if(type=="radio"){
		if(home != "Any"){
			$(".sliders > div").each(function(){
				var h = $(this).attr("data-home");
				var st = $(this).attr("data-stem");
				var select = $(this).attr("data-select");
				var test = true;
				stem.forEach(function(entry) {
					if(st.includes(entry)){
						test = false;
					}
				});
				if(test){
					if(!h.includes(filter)) {
				    $(this).removeClass("slider");
			         $(this).addClass("slider-hide");
					}
				} else {
					if(h.includes(filter)) {
						if((select == "true") || (stflag == false)) {
						$(this).removeClass("slider-hide");
			            $(this).addClass("slider");
						}
					} else {
					$(this).removeClass("slider");
			        $(this).addClass("slider-hide");
					}
				}
			});
		} else {
			$(".sliders > div").each(function(){
				var select = $(this).attr("data-select");
				var st = $(this).attr("data-stem");
				var test = true;
				stem.forEach(function(entry) {
					if(st.includes(entry)){
						test = false;
					}
				});
				if(test){
					$(this).removeClass("slider");
			        $(this).addClass("slider-hide");
				} else {
					if((select == "true") || (stflag == false)) {
						$(this).removeClass("slider-hide");
			            $(this).addClass("slider");
					}
				}
			});
		}
	}
	refilter();
	results();
}
defer(function() {
	$('input:checkbox').change(function(){
		var value = $(this).val();
		var unchecked = 0;
		$("input:checkbox").each(function(){
			if(!$(this).is(":checked")){
				unchecked -= 1;
			} else {
				unchecked += 1;
			}
		});
		if( unchecked == 4) {
//all check boxes are checked
stem.push(value);
filter("","all",home,stem);
} else if (unchecked == -4 ) {
//all check boxes are unchecked
stem = new Array();
filter("","all",home,stem);
}
else {
	if ($(this).is(':checked')) {
		stem.push(value);
		filter(value,"check",home,stem);
	}
	if (!($(this).is(':checked'))) {
		var index = stem.indexOf(value);
		stem.splice(index,1);
		filter(value,"uncheck",home,stem);
	}
}
});
	$('input:radio').change(function(){
		var value = $(this).val();
		home = value;
		filter(value,"radio",home,stem);
	});
	$("#trekkerform").submit(function(event){
		event.preventDefault();
		form();
	});
	$("#formbutton").click(function(event) {
		form();
		
		var sp = $('.sliders').offset();
		$('html, body').stop().animate({ scrollTop: sp.top-150 }, 500);

	});
	
	$("#resetButton").click(function(event) {
		setTimeout(function(){
			form();	}, 300); });});