  var current = {'academic':{'value':'none','index':0,'title':''}, 'dept':{'value':'none','index':0,'title':''}, 'pi':{'value':'none','index':0,'title':''},'centers':{'value':'none','index':0,'title':''}};//What is currently selected.
  //this is a new comment
  //Function that hides/unchecks a field with an error message that fades away
  //@param field - the field to hide
  //@param msg - the message to display
  function addMessage(field, msg) {
    field=jQuery(field);
    current[field.attr("name")] = {'value':'none','index':0,'title':''};//reset the value in current for this field to the first value
    field.parents('div.filter-row').toggleClass('active');//hide the row div
    var message = jQuery('<span style="color:#c00;font-weight:bold;font-size:14px;">'+msg + ' was not found.</span>').insertAfter(field);//add in the message
    //set the message to fade out
    message.fadeOut(800,function(){
      this.remove();//remove the message from the dom
      field.parents('div.filter-row').toggleClass('active');//reshow the row div briefly, resetField will hide it again, since it toggles.
      resetField(field);//reset the field
    })
  }

  //function that sends the current values to
  function updateFields(field) {
             console.log("test");
    var academic = jQuery('#academic')[0],
      dept = jQuery('#dept')[0],
      pi = jQuery('#pi')[0],
      centers = jQuery('#centers')[0];
      //set what options they are currently looking for
      thedata = {'academic':academic.options[academic.selectedIndex].value,
             'dept':dept.options[dept.selectedIndex].value,
             'pi':pi.options[pi.selectedIndex].value,
             'js':'false', 'change':field.attr("name")};
    //retrieve what options there is for other options given the options they have selected
    jQuery.getJSON("/mtu_resources/php/research/feed/search.php",thedata,function(json){
      if (json.depts) {
          dept.options.length=1;
          jQuery.each(json.depts,function(index,item) {
            dept.options[dept.options.length] = jQuery('<option value = "'+item.value+'" '+((item.value == current.dept.value)?'selected="selected"':'')+' >'+item.title+'</option>')[0];
          });
          if (current.dept.index > 0 && dept.options.selectedIndex == 0){
             addMessage(dept, current.dept.title);
          }
        }
        if (json.pi) {
          var pi = jQuery('#pi')[0];
          pi.options.length=1;
          jQuery.each(json.pi,function(index,item) {
            pi.options[pi.options.length] = jQuery('<option value = "'+item.value+'" '+((item.value == current.pi.value)?'selected="selected"':'')+'>'+item.title+'</option>')[0];
          });
          if (current.pi.index > 0 && pi.options.selectedIndex == 0){
             addMessage(pi, current.pi.title);
          }
        }
                if (json.centers) {
          var centers = jQuery('#centers')[0];
          centers.options.length=1;
          jQuery.each(json.centers,function(index, item) {
            centers.options[centers.options.length] = jQuery('<option value = "'+item.value+'" '+((item.value == current.centers.value)?'selected="selected"':'')+'>'+item.title+'</option>')[0];
          });
          if (current.centers.index > 0 && centers.options.selectedIndex == 0){
             addMessage(centers, current.centers.title);
          }
        }
        
        current[field.attr("name")].value = field[0].options[field[0].options.selectedIndex].value;
        current[field.attr("name")].title = field[0].options[field[0].options.selectedIndex].text;
        current[field.attr("name")].index = field[0].options.selectedIndex;
    });
  }

  function resetField(field) {
    field = jQuery(field);
    var filter = field.parents('div.filter-row');
    var cb = filter.find('input[type=checkbox]');
    cb.attr("checked", !filter.hasClass('active'));
    filter.find('select').prop("selectedIndex",0);
    jQuery.each(jQuery("select"),function(index,select){
    })
    filter.toggleClass('active');
    if (field[0].tagName != "SELECT"){ updateFields(filter.find('select'));}
  }

  //function that removes the loading class and direct slider links
  function submitSuccess() {
    console.log("test");
    //go through all of the sliders
    jQuery.each(jQuery('#research_listing').removeClass('loading').find('.slider .bar'),function(index,item) {
      item = jQuery(item);
      //if a hash is set, and this slider has that hash, we'll open that slider
      if(location.hash && item.parent().attr('id') == location.hash.substr(1)){
        var slider = item.parent().find(".bar");
        slider.click();
        window.scrollTo(0,slider.offset().top - 115);
      }
    }).remove();

    //annoying legacy code for old template
      /*if($ != jQuery){
        jQuery.each(jQuery(".bar"),function(index, item){
              jQuery(this).parent('div').children('div.slider-content').slideUp(0);
              jQuery(this).parent('div').removeClass('opened');
              jQuery(this).find('h2,h4').removeClass('collapse').addClass('expand');
        });
      }*/
   
    }

    function QueryStringToJson() {
        var pairs = location.search.slice(1).split('&');

        var result = {};
        pairs.forEach(function(pair) {
            pair = pair.split('=');
            result[pair[0]] = decodeURIComponent(pair[1] || '');
        });

        return JSON.parse(JSON.stringify(result));
    }

    var initialize = function(){

      //annoying legacy code for old template
      if($ != jQuery){
        jQuery(document).on('click', '.bar', function() {
            if(jQuery(this).parent('div').children('div.slider-content').is(':visible')) {
              jQuery(this).parent('div').children('div.slider-content').slideUp();
              jQuery(this).parent('div').removeClass('opened');
              jQuery(this).find('h2,h4').removeClass('collapse').addClass('expand');
            }else{
              jQuery(this).parent('div').children('div.slider-content').slideDown();
              jQuery(this).parent('div').addClass('opened');
              jQuery(this).parent('div').attr('aria-expanded','true');
              jQuery(this).find('h2,h4').removeClass('expand').addClass('collapse');
            }
          });
      }

            var form_url = '/mtu_resources/php/research/feed/',
              search_url = '/mtu_resources/php/research/feed/search.php',
        query = false,
        search = jQuery('#research-search'),
        filter = jQuery('#research-filter'),
        forms = jQuery('form#research-feed-search, form#research-feed-filter');

      if (query || document.location.search) {
        var doQuery = document.location.search && document.location.hostname == 'www.mtu.edu';
        query = (doQuery ? QueryStringToJson() : query);
        if(query["research-feed-search"]) {
          query.search = query["research-feed-search"];
        }
        if (query.search) {
          //clear reasearch_listing, signify it is loading, and load
          jQuery('#research_listing').empty().addClass('loading').load(form_url+"?"+jQuery.param({search:query.search}),submitSuccess);
        }
        var found = false;
        filter.find('select').each(function(index, selects) {
          if (query[jQuery(selects).attr("name")]) {
            jQuery(selects).parents('div.filter-row').addClass('active').find('input[type=checkbox]').attr("checked",true);
            for (var i=0; i<selects.options.length; i++) {
              if (selects.options[i].value == query[selects.name]) {
                selects.options.selectedIndex = i;
                break;
              }
            }
            found = true;
          }else {
            jQuery(selects).parents('div.filter-row').removeClass('active').find('input[type=checkbox]').attr("checked",false);
            selects.options.selectedIndex = 0;
          }
        });
        if (found && doQuery) {
          search.removeClass('active');
          filter.addClass('active');
        }
      }


      //Add submit event for the search/filter forms
      jQuery.each(forms,function(index,form) {
        jQuery(form).submit( function(e) {
          e.preventDefault();
          var re = /.*?&search=(.*)/;//regex that matches if a string contains &search
          var query = jQuery(form).serialize();//turn the form input into a query string
          re = re.exec(query);

          //does the querystring have a search param in it
          if(re){
            var searchQuery= re[1];
          }
          //if the search was empty, then we just remove it from the querystring, so that an empty search brings up all relevant results
          if(!searchQuery){
            query = query.replace("&search=","");
          }
          //was it a new query, or are they just spamming enter?
          if(jQuery("#research_listing").data("query")!=query){
            //for a new query, we clear the current results, and load in new ones
            jQuery("#research_listing").data("query",query).empty().addClass("loading").load(form_url+"?"+query,submitSuccess).remove();

          }
        });
      });

      //add event for the toggle links
      var searchtoggleLink = jQuery("#research-search a.mode");
        searchtoggleLink.click(function(e) {
        e.preventDefault();
        search.removeClass('active');
        filter.addClass('active');
      });

      var filtertoggleLink = jQuery("#research-filter a.mode");
        filtertoggleLink.click(function(e) {
        e.preventDefault();
        search.addClass('active');
        filter.removeClass('active');
      });

      //go through all the checkboxes and clear buttons
      jQuery.each(jQuery('#research-filter input[type=checkbox],#research-filter span.reset a'),function(index,item) {
        item = jQuery(item);

        //for checkboxes
        if (item[0].tagName == "INPUT") {
          var filter = item.parents('div.filter-row');//go up the dom to the filter-row

          //if the checkbox intially has a checked box and selected option, show the field initialize it being selected
          if (item.attr("checked") && filter.find('select').prop("selectedIndex") > 0) {
            filter.addClass('active');
            updateFields(filter.find('select'));
          }
          //otherwise don't display the field initially
          else {
            item.attr("checked",false);
            filter.removeClass('active');
          }
        }
        //add click events that will reset when checkbox/clear button is hit.
        item.click(function(e) {
          if (jQuery(this)[0].tagName == "A") {
            e.preventDefault();
          }
          resetField(this);
        });
      });

      //for all of the selects, mark what their value is initially, and then update the fields when they change.
      jQuery.each(jQuery('#research-filter select'),function(index, selects) {
        current[selects.name] = {'text': selects.options[selects.options.selectedIndex].text,
                    'value': selects.options[selects.options.selectedIndex].value,
                    'index': selects.options.selectedIndex};
        jQuery(selects).change(function(e){updateFields(jQuery(this));});
      });

      if (current.dept.index > 0 || current.pi.index > 0 || current.academic.index > 0 || current.centers.index > 0 ) {
        var submit_data = {};
        if (current.dept.index > 0) submit_data.dept = current.dept.value;
        if (current.pi.index > 0) submit_data.pi = current.pi.value;
        if (current.academic.index > 0) submit_data.academic = current.academic.value;
        if (current.centers.index > 0) submit_data.centers = current.centers.value;
     
         $.get("https://www.mtu.edu"+form_url+"?"+jQuery.param(submit_data),function(data){
          $("#research_listing").attr("id","remove_me_now");
          $("#remove_me_now").html(data); 
          $("#research_listing").unwrap();
            submitSuccess();
       });
      
      }

  }
/*
*/
function countAccordions(a,b,c){
    console.log("added expand all");
    //Set default aria until we publish all pages; jcv remove after full publish of all pages
    a.children().each(function () {
      if(!$(this).attr('aria-expanded')){
        //console.log('Added ARIA');
        $(this).attr('aria-expanded','false');
      }
    });
    //Check to see if there are at least three accordions
    var totalAccordions = a.children().length;
    if (totalAccordions > 2){
      if(b.prev().is($('h2')) || b.prev().is($('h3')) || b.prev().is($('.top-title'))){
        //Add class to slider group heading
        b.prev().addClass('toggle-align');
        //Add expand/collapse link before group; without wrapper
        b.before($("<p style='display: none;' class='toggle "+c+"' data-state='expandable' tabindex='0' role='button'>Expand All</p>"));
      }
      else{
        //Add expand/collapse link before group with wrapper
        b.before($("<div class='toggle-wrap'><p class='toggle "+c+"' data-state='expandable' tabindex='0' role='button'>Expand All</p></div>"));
      }
    }
  }
/*
*/
  //just in case things this is being ajaxed, if the windows already loaded, we just run the function, otherwise, we latch onto onload.
  if(document.readyState !== 'complete'){

    window.onload = initialize;
    defer(function() {

    initialize();
    });
  }
  else{

    initialize();
  }