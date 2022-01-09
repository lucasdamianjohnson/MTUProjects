<?php

//Functions for the Research Listings and Search
$db_ip = 'mysql.ais.mtu.edu';

$db_user = 'umcwebuser';
$db_pass = 'umcpass01';
$db_name = 'umcwebgen';
$conn;

function get_db_conn() {
  global $db_ip, $db_user, $db_pass, $db_name, $conn;

  $conn = mysql_connect($db_ip, $db_user, $db_pass);
  mysql_select_db($db_name, $conn);
  return $conn;
}

function close_db_conn() {
	global $conn;
	mysql_close($conn);
}

function super_unique($array) {
$result = array_map("unserialize", array_unique(array_map("serialize", $array)));

	foreach ($result as $key => $value) {
		if(is_array($value)) {
			$result[$key] = super_unique($value);
		}
	}

  return $result;
}

function disp_depts($audience='pi',$depts='',$academics='',$emails='',$array=false) {

	get_db_conn();
	if(isset($_GET['dept']) && $_GET['dept'] != 'none') {
    $depts = mysql_real_escape_string($depts);
		$dept = "proposal_dept LIKE '" .$depts. "%'";
	} else {
		$dept = 'proposal_dept is not null';
	}

	if(isset($_GET['academic']) && $_GET['academic'] != 'none') {
    $academics = mysql_real_escape_string($academics);
		$academic = "proposal_academic LIKE '" .$academics. "%'";
	} else {
		$academic = 'proposal_academic is not null';
	}

	if(isset($_GET['pi']) && $_GET['pi'] != "none") {
    $emails = mysql_real_escape_string($emails);
		$email = "email = '" .$emails. "'";
	} else {
		$email = 'email is not null';
	}

	if($academics == '') {
		$results = mysql_query("SELECT DISTINCT proposal_dept FROM research_feed ORDER BY proposal_dept DESC");
	} else {
		$results = mysql_query("SELECT DISTINCT proposal_dept FROM research_feed WHERE $academic ORDER BY proposal_dept DESC");
	}

	$prev = '';
	$proposal_depts = array();
	//$results = mysql_query("SELECT DISTINCT proposal_dept FROM research_feed ORDER BY proposal_dept DESC");
	while ($row = mysql_fetch_assoc($results)) {
		//echo substr_compare($row['proposal_dept'],$prev,0);
		//echo $row['proposal_dept'] . '-' . $prev . '(' . similar_text($prev, $row['proposal_dept']) .')<br />';
		$dept = $row['proposal_dept'];
		if(similar_text($prev, $row['proposal_dept']) < 25) {
			$proposal_depts[$dept] = $row['proposal_dept'];
		} else {
			$proposal_depts[$dept] = $prev;
		}
		$prev = $row['proposal_dept'];
	}
	//print_r($proposal_depts);
	if($audience == "pi") {
		$field = "pi_dept";
	} else {
		$field = "proposal_dept";
	}

	$results = mysql_query("SELECT DISTINCT $field FROM research_feed ORDER BY $field ASC");
	if ($array) {
		$return = array();
		while ($row = mysql_fetch_assoc($results)) {
			if (in_array($row[$field], $proposal_depts)) {
				$return[] = array('value' => $row[$field], 'title' => $proposal_depts[$row[$field]], 'selected' => $row[$field] == $depts);
			}
		}
		close_db_conn();
		return $return;
	} else {
		echo "<select name=\"dept\" id=\"dept\">";
		echo "<option value=\"none\">Select Department</option>";
		while ($row = mysql_fetch_assoc($results)) {
		$select_dept = $row[$field];
			if(in_array($row[$field], $proposal_depts)) {
				if($row[$field] == $depts) {
				echo "<option selected=\"selected\" value=\"". $row[$field] . "\">" . $proposal_depts[$select_dept]. "</option>";
				} else {
				echo "<option value=\"". $row[$field] . "\">" . $proposal_depts[$select_dept]. "</option>";
				}
			}
		}
		echo "</select>";
	}
	close_db_conn();
}
function disp_thrust($thrust='',$depts='',$academics='',$emails='') {
	
	get_db_conn();
	$results = mysql_query("SELECT DISTINCT thrust FROM research_feed WHERE thrust NOT LIKE '%|%' && thrust NOT LIKE '' ");
	echo "<select name=\"thrust\" id=\"thrust\">";
	echo "<option value=\"none\">Select Research Focus</option>";
	while ($row = mysql_fetch_assoc($results)) {
			if(($row['thrust'] == $thrust)&& ($thrust != '')) {
			echo "<option selected=\"selected\" value=\"". $row['thrust'] . "\">" . $row['thrust']."</option>";
			} else {
			echo "<option value=\"". $row['thrust'] . "\">" . $row['thrust']."</option>";
			}
		
	}
	echo "</select>";
	close_db_conn();

}
function disp_academic($audience='pi',$depts='',$academics='',$emails='') {

	get_db_conn();

	if(isset($_GET['dept']) && $_GET['dept'] != 'none') {
    $depts = mysql_real_escape_string($depts);
		$dept = "proposal_dept LIKE '" .$depts. "%'";
	} else {
		$dept = 'proposal_dept is not null';
	}

	if(isset($_GET['academic']) && $_GET['academic'] != 'none') {
    $academics = mysql_real_escape_string($academics);
		$academic = "proposal_academic LIKE '" .$academics. "%'";
	} else {
		$academic = 'proposal_academic is not null';
	}

	if(isset($_GET['pi']) && $_GET['pi'] != "none") {
    $emails = mysql_real_escape_string($emails);
		$email = "email = '" .$emails. "'";
	} else {
		$email = 'email is not null';
	}


	//if($depts == '' && $academics == '' && $emails == '') {
		$results = mysql_query("SELECT DISTINCT proposal_academic FROM research_feed ORDER BY proposal_dept DESC");
	/*} else {
		$results = mysql_query("SELECT DISTINCT proposal_academic FROM research_feed WHERE $dept && $academic && $email ORDER BY proposal_dept DESC");
	}*/

	$prev = '';
	$proposal_academics = array();
	//$results = mysql_query("SELECT DISTINCT proposal_academic FROM research_feed ORDER BY proposal_dept DESC");
	while ($row = mysql_fetch_assoc($results)) {
		//echo substr_compare($row['proposal_dept'],$prev,0);
		//echo $row['proposal_academic'] . '-' . $prev . '(' . similar_text($prev, $row['proposal_academic']) .')<br />';
		$acad = $row['proposal_academic'];
		/*if($row['proposal_academic'] == 'Michigan Tech Transporation Institute') {
			$row['proposal_academic'] = "Michigan Tech Transportation Institute";
		}*/
		if(similar_text($prev, $row['proposal_academic']) < 26) {
			$proposal_academics[$acad] = $row['proposal_academic'];
		} else {
			$proposal_academics[$acad] = $prev;
		}
		$prev = $row['proposal_academic'];
	}


	if($audience == "pi") {
		$field = "pi_academic";
	} else {
		$field = "proposal_academic";
	}
	echo "<select name=\"academic\" id=\"academic\">";
	echo "<option value=\"none\">Select Academic Unit</option>";
	$results = mysql_query("SELECT DISTINCT $field FROM research_feed ORDER BY $field ASC");
	while ($row = mysql_fetch_assoc($results)) {
		if(in_array($row[$field],$proposal_academics)) {
			if($row[$field] == $academics) {
			echo "<option selected=\"selected\" value=\"". $row[$field] . "\">" . $row[$field]."</option>";
			} else {
			echo "<option value=\"". $row[$field] . "\">" . $row[$field]."</option>";
			}
		}
	}
	echo "</select>";
	close_db_conn();

}

function disp_pis($depts='',$academics='',$emails='',$array=false) {

	get_db_conn();

	if(isset($_GET['dept']) && $_GET['dept'] != 'none') {
    $depts = mysql_real_escape_string($depts);
		$dept = "proposal_dept LIKE '" .$depts. "%'";
	} else {
		$dept = 'proposal_dept is not null';
	}

	if(isset($_GET['academic']) && $_GET['academic'] != 'none') {
    $academics = mysql_real_escape_string($academics);
		$academic = "proposal_academic LIKE '" .$academics. "%'";
	} else {
		$academic = 'proposal_academic is not null';
	}

	if(isset($_GET['pi']) && $_GET['pi'] != "none") {
    $emails = mysql_real_escape_string($emails);
		$email = "email = '" .$emails. "'";
	} else {
		$email = 'email is not null';
	}

	if($depts == '' && $academics == '' && $emails == '') {
		$results = mysql_query("SELECT DISTINCT email, pi_last_name, pi_first_name FROM research_feed ORDER BY pi_last_name ASC");
	} else {
		$results = mysql_query("SELECT DISTINCT email, pi_last_name, pi_first_name FROM research_feed WHERE $dept && $academic && $email ORDER BY pi_last_name ASC");
	}

	if ($array) {
		$return = array();
		while ($row = mysql_fetch_assoc($results)) {
			$return[] = array('value' => $row['email'], 'title' => $row['pi_first_name']." ".$row['pi_last_name'], 'selected' => $row['email'] == $emails);
		}
		close_db_conn();
		return $return;
	} else {
		echo "<select name=\"pi\" id=\"pi\">";
		echo "<option value=\"none\">Select Investigator</option>";
		//$results = mysql_query("SELECT DISTINCT email, pi_last_name, pi_first_name FROM research_feed ORDER BY pi_last_name ASC");
		while ($row = mysql_fetch_assoc($results)) {
			if($row['email'] == $emails) {
			echo "<option selected=\"selected\" value=\"".$row['email']."\">" .$row['pi_first_name'] . " " . $row['pi_last_name'] . "</option>";
			} else {
			echo "<option value=\"".$row['email']."\">" .$row['pi_first_name'] . " " . $row['pi_last_name'] . "</option>";
			}
		}
		echo "</select>";
		close_db_conn();
	}

}


function disp_centers($audience='center',$depts='',$academics='',$emails='',$centers='',$array=false) {

	get_db_conn();
	//echo $emails;
	if(isset($_GET['dept']) && $_GET['dept'] != 'none') {
    $depts = mysql_real_escape_string($depts);
		$dept = "proposal_dept LIKE '" .$depts. "%'";
	} else {
		$dept = 'proposal_dept is not null';
	}

	if(isset($_GET['academic']) && $_GET['academic'] != 'none') {
    $academics = mysql_real_escape_string($academics);
		$academic = "proposal_academic LIKE '" .$academics. "%'";
	} else {
		$academic = 'proposal_academic is not null';
	}

	if(isset($_GET['pi']) && $_GET['pi'] != "none") {
    $emails = mysql_real_escape_string($emails);
		$email = "email = '" .$emails. "'";
	} else {
		$email = 'email is not null';
	}
	if(isset($_GET['center']) && $_GET['center'] != 'none') {
    $centers = mysql_real_escape_string($centers);
		$center = "center LIKE '" .$centers. "%'";
	} else {
		$center = 'center is not null';
	}

	if($centers == '' && $depts == '' && $academics == '' && $emails == '') {
		$results = mysql_query("SELECT DISTINCT center FROM research_feed ORDER BY proposal_dept DESC");
	} else {
		$results = mysql_query("SELECT DISTINCT center FROM research_feed WHERE $center && $dept && $academic && $email ORDER BY center DESC");
	}

	$prev = '';
	$proposal_centers = array();
	//$results = mysql_query("SELECT DISTINCT proposal_dept FROM research_feed ORDER BY proposal_dept DESC");
	while ($row = mysql_fetch_assoc($results)) {
		//echo substr_compare($row['proposal_dept'],$prev,0);
		//echo $row['proposal_dept'] . '-' . $prev . '(' . similar_text($prev, $row['proposal_dept']) .')<br />';
		$center = $row['center'];
		if(similar_text($prev, $row['center']) < 25) {
			$proposal_centers[$center] = $row['center'];
		} else {
			$proposal_centers[$center] = $prev;
		}
		$prev = $row['center'];
	}
	//print_r($proposal_depts);
	if($audience == "pi") {
		$field = "pi_dept";
	} else {
		$field = "center";
	}

	$results = mysql_query("SELECT DISTINCT $field FROM research_feed where $field != '' ORDER BY $field ASC");
	if ($array) {
		$return = array();
		while ($row = mysql_fetch_assoc($results)) {
			if (in_array($row[$field], $proposal_centers)) {
				$return[] = array('value' => $row[$field], 'title' => $proposal_centers[$row[$field]], 'selected' => $row[$field] == $depts);
			}
		}
		close_db_conn();
		return $return;
	} else {
		echo "<select name=\"centers\" id=\"centers\">";
		echo "<option value=\"none\">Select Center/Institute</option>";
		while ($row = mysql_fetch_assoc($results)) {
		$select_center = $row[$field];
			if(in_array($row[$field], $proposal_centers)) {
				if($row[$field] == $centers && $row[$field] != '') {
				echo "<option selected=\"selected\" value=\"". $row[$field] . "\">" . $proposal_centers[$select_center]. "</option>";
				} else {
					if($row[$field] != '') {
						echo "<option value=\"". $row[$field] . "\">" . $proposal_centers[$select_center]. "</option>";
					}
				}
			}
		}
		echo "</select>";
	}
	close_db_conn();
}

function disp_js2(){
  disp_js(true,false);
}

function disp_js($all=true,$abstract=false) {
	global $sdept, $sacademic, $scenters;
	?>

<script type="text/javascript">
<?php if ($all) { ?>
	var current = {'academic':{'value':'none','index':0,'title':''}, 'dept':{'value':'none','index':0,'title':''}, 'pi':{'value':'none','index':0,'title':''},'centers':{'value':'none','index':0,'title':''}};//What is currently selected.

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
    resetField(field);
	}

  //function that sends the current values to
	function updateFields(field) {
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
    jQuery.getJSON("/mtu_resources/php/research/feed/new/search.php",thedata,function(json){
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
        <?php if(!$abstract){ ?>
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
        <?php } ?>

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
	
	/* Sliders jcv combine slider and faq functions once all new code is published */
	function toggleSlider(a, b){
		if(!b){
			if(a.attr('aria-expanded')=='false'){openSlider(a);}
			else{closeSlider(a);}
		}
		else if (b=="open"){
			if(a.attr('aria-expanded')=='false'){openSlider(a);}
		}
		else{
			if(a.attr('aria-expanded')=='true'){closeSlider(a);}
		}
	}
	
	function openSlider(a){
		a.children('.slider-content').slideToggle();
		a.attr('aria-expanded','true');
		if (window._gaq) _gaq.push(['_trackEvent', 'FAQ/Slider', 'Opened', a.children('.bar').children().text()]);
	}

	function closeSlider(a){
		a.children('.slider-content').slideToggle();
		a.attr('aria-expanded','false');
		if (window._gaq) _gaq.push(['_trackEvent', 'FAQ/Slider', 'Closed', a.children('.bar').children().text()]);
	}

  //function that removes the loading class and direct slider links
	function submitSuccess() {

    //go through all of the sliders
		jQuery.each(jQuery('#research_listing').removeClass('loading').find('.slider .bar'),function(index,item) {
      item = jQuery(item);
      //if a hash is set, and this slider has that hash, we'll open that slider
      if(location.hash && item.parent().attr('id') == location.hash.substr(1)){
        var slider = item.parent().find(".bar");
        slider.click();
        window.scrollTo(0,slider.offset().top - 115);
      }
		});
		//jQuery("#research_listing").replaceWith(jQuery("#research_listing").children(":first"));

	// Expand or collapse sliders when clicked
	$(".research-feed .bar").click(function(event){
		toggleSlider($(this).parent());
	});	

    //annoying legacy code for old template
      if($ != jQuery){
        jQuery.each(jQuery(".bar"),function(index, item){
              jQuery(this).parent('div').children('div.slider-content').slideUp(0);
              jQuery(this).parent('div').removeClass('opened');
              jQuery(this).find('h2,h4').removeClass('collapse').addClass('expand');
        });
      }
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

      <?php if($abstract != true){?>
 
      var form_url = '/mtu_resources/php/research/feed/',
      <?php }else{ ?>
      var form_url = '/mtu_resources/php/research/feed/index-dept.php',
      <?php } ?>
        search_url = '/mtu_resources/php/research/feed/search.php',
        query = <?php echo isset($sdept, $sacademic,$scenter) ? json_encode(array('dept' => urlencode($sdept), 'academic' => urlencode($sacademic), 'centers' => urlencode($scenter) ) ) : 'false'; ?>,
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
     	      jQuery("#research_listing").empty().addClass('loading');

       $.get("https://www.mtu.edu"+form_url+"?"+jQuery.param({search:query.search}),function(data){
       		$("#research_listing").attr("id","remove_me_now");
       		$("#remove_me_now").html(data);	
       		$("#research_listing").unwrap();
       		 		submitSuccess();
       });
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
	
       
            jQuery("#research_listing").data("query",query).empty().addClass("loading").load(form_url+"?"+query,submitSuccess);
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

      if (current.dept.index > 0 || current.pi.index > 0 || current.academic.index > 0 <?php if(!$abstract){ ?>|| current.centers.index > 0 <?php }?>) {
        var submit_data = {};
        if (current.dept.index > 0) submit_data.dept = current.dept.value;
        if (current.pi.index > 0) submit_data.pi = current.pi.value;
        if (current.academic.index > 0) submit_data.academic = current.academic.value;
        <?php if(!$abstract){ ?>if (current.centers.index > 0) submit_data.centers = current.centers.value; <?php } ?>
		
		//jQuery("#research_listing").empty().addClass('loading').load(form_url+"?"+jQuery.param(submit_data),submitSuccess);
       jQuery("#research_listing").empty().addClass('loading');
   	
       $.get("https://www.mtu.edu"+form_url+"?"+jQuery.param(submit_data),function(data){
       		$("#research_listing").attr("id","remove_me_now");
       		$("#remove_me_now").html(data);	
       		$("#research_listing").unwrap();
       			submitSuccess();
       })
	   	.done(function() {

	   		if(typeof countAccordions !== "function"){
	   			 	   	//	console.log("not loaded"); 	
	   			 console.log(typeof countAccordions);
	   			setTimeout(wait,100);
	   		} else {
	   		//console.log("loaded");
	   			expandAll();
	   		}
	 

	   });

      }
    
  }
  function wait(){
  	if(typeof countAccordions !== "function"){
  			
  			  
	   			setTimeout(wait,100);

	   } else {
	  
	   	expandAll();
	   }
  }
  function expandAll(){

  
  	  		if(jQuery(".toggle-wrap").length >= 1){
  	  		} else
	   		if(jQuery(".toggle-wrap").length < 1){
	   	
			 jQuery('.sliders').each(function() {
				countAccordions($(this),$(this),'slider-group');
		   });
	
		} 
		if(jQuery(".toggle-wrap").length > 1){ 
				
		jQuery(".toggle-wrap:first").remove();
		}
		

		//if( typeof toggleSliderGroup === "function"){
			jQuery(".slider-group").unbind('click');
			jQuery("#content").off().on('click','.slider-group',function(){
		
			if( typeof toggleSliderGroup === "function"){
			
				} else {
		
		   togglecustom(jQuery(this));
		}
	
	});


  }
  	function togglecustom(a){
  		console.log("custrom toggle");
		if(a.data('state') == 'expandable'){
			a.attr('data-state','collapsable');
			a.removeData('state');
			a.text('Collapse All');
			a.parent().next('.sliders').children('.slider').each(function () {
				toggleSlider($(this), "open");
				//if (window._gaq) _gaq.push(['_trackEvent', 'FAQ/Slider', 'Opened All', 'Slider Group']);
			});
		}
		else{
			a.attr('data-state','expandable');
			a.removeData('state');
			a.text('Expand All');
			a.parent().next('.sliders').children('.slider').each(function () {
				toggleSlider($(this), "close");
				//if (window._gaq) _gaq.push(['_trackEvent', 'FAQ/Slider', 'Closed All', 'Slider Group']);
			});
		}
	}
 function wait2(){
 	  	if(typeof toggleSliderGroup !== "function"){
  			   
  		
	   			setTimeout(wait2,100);

	   } else {
	   			jQuery("#content").on('click','.slider-group',function(){
		   toggleSliderGroup(jQuery(this));
	});
	   }
 }

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
<?php } ?>

</script>

<?php
}

?>
