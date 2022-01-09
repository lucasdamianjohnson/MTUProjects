
<?php
echo "<h1>this is a test</h1>";
//Research Listing
include('functions.php');
get_db_conn();

if(isset($_GET['limit'])) {
	$limit = $_GET['limit'];
} else {
	$limit = 10000;	
}
$proposal_depts = array();
$proposal_academics = array();
$prev = '';
$selected_email = '';
$results = mysql_query("SELECT DISTINCT proposal_dept FROM research_feed ORDER BY proposal_dept DESC");
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

$prev = '';
$results = mysql_query("SELECT DISTINCT proposal_academic FROM research_feed ORDER BY proposal_dept DESC");
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

$results = mysql_query("SELECT DISTINCT center FROM research_feed ORDER BY center DESC");
while ($row = mysql_fetch_assoc($results)) {
	//echo substr_compare($row['proposal_dept'],$prev,0);
	//echo $row['proposal_academic'] . '-' . $prev . '(' . similar_text($prev, $row['proposal_academic']) .')<br />';
	$center = $row['center'];
	/*if($row['proposal_academic'] == 'Michigan Tech Transporation Institute') {
		$row['proposal_academic'] = "Michigan Tech Transportation Institute";	
	}*/
	
	//
	//echo similar_text($prev, $row['center']) . " " . $prev. " ". $row['center']."<br />";
	if(similar_text($prev, $row['center']) < 35) {
		$proposal_centers[$center] = $row['center'];
	} else {
		$proposal_centers[$center] = $prev;	
	}
	$prev = $row['center'];
}
//print_r($proposal_centers);

//print_r($p);
if(isset($_GET['thrust'])) {
	echo "<h1>".$_GET['thrust']."</h1>";
}
 if(!isset($_GET['dept']) || !isset($_GET['academic']) || !isset($_GET['pi']) || !isset($_GET['centers']) || !(isset($_GET['thrust']))) {
 	$thrust = "thrust like '%".$_GET['thrust']."%' ";
 	$results = mysql_query("SELECT * FROM research_feed WHERE $thrust ORDER BY ir_number DESC, role DESC, pi_last_name ASC");
 }
elseif(isset($_GET['dept']) || isset($_GET['academic']) || isset($_GET['pi']) || isset($_GET['centers']) || (isset($_GET['thrust']))) {
	if(isset($_GET['dept']) && $_GET['dept'] != 'none') { 
		$dept = "proposal_dept LIKE '" .mysql_real_escape_string(urldecode($_GET['dept'])). "%'";
	} else {
		$dept = 'proposal_dept is not null';
	}
	
	if(isset($_GET['academic']) && $_GET['academic'] != 'none') { 
		$academic = "proposal_academic LIKE '" .mysql_real_escape_string(urldecode($_GET['academic'])). "%'";
	} else {
		$academic = 'proposal_academic is not null';	
	}
	
	if(isset($_GET['centers']) && $_GET['centers'] != 'none') { 
		$center = "center LIKE '" .mysql_real_escape_string(urldecode($_GET['centers'])). "%'";
	} else {
		$center = 'center is not null';	
	}
	
	if(isset($_GET['pi']) && $_GET['pi'] != "none") { 
		$email = "email = '" .mysql_real_escape_string(urldecode($_GET['pi'])). "'";
		$selected_email = mysql_real_escape_string(urldecode($_GET['pi']));		
	} else {
		$email = 'email is not null';
		$selected_email = '';
	}
	if(isset($_GET['thrust']) && $_GET['thrust'] != 'none') {
		$thrust = "thrust like '%".mysql_real_escape_string($_GET['thrust'])."%'";
	} else {
		$thrust = 'thrust is not null';
	}
	if($dept == '' && $academic == '' && $email == '') {
		$results = mysql_query("SELECT * FROM research_feed ORDER BY ir_number ASC, role DESC, pi_last_name ASC");
	} elseif(isset($_GET['search'])) {
		$search = $_GET['search'];
		$results = mysql_query("SELECT * FROM research_feed where $dept && $academic && $email && $center && $thrust && MATCH(title,pi_last_name,pi_first_name,sponsor,proposal_academic,proposal_dept) AGAINST ('".mysql_real_escape_string($search)."')");
		//echo "SELECT * FROM research_feed where $dept && $academic && $email && $center && MATCH(title,pi_last_name,pi_first_name,sponsor,proposal_academic,proposal_dept) AGAINST ('".mysql_real_escape_string($search)."')";
	} else { 
		$results = mysql_query("SELECT * FROM research_feed WHERE $dept && $academic && $email && $center && $thrust ORDER BY ir_number DESC, role DESC, pi_last_name ASC");
		//echo "SELECT * FROM research_feed WHERE $dept && $academic && $email && $center ORDER BY ir_number, role DESC, pi_last_name ASC";
	}
	
} elseif(isset($_GET['search'])) {
		$search = $_GET['search'];
		$results = mysql_query("SELECT * FROM research_feed where MATCH(title,pi_last_name,pi_first_name,sponsor,proposal_academic,proposal_dept) AGAINST ('".mysql_real_escape_string($search)."*' in boolean mode)");
		
} else {
	$ir_numbers = array();
	$results = mysql_query("SELECT * FROM research_feed ORDER BY ir_number DESC, role DESC, pi_last_name ASC");
}




if(isset($results) && mysql_num_rows($results) > 0) {
	while ($row = mysql_fetch_assoc($results)) {
		//print_r($row);
		//retrieve and store the bulk of the research item
		$end_date = $row['end_date'];
		if(date('ymd',strtotime($end_date)) >= date('ymd')) {	
			$select_ir = $row['ir_number'];
			$select_dept = $row['proposal_dept'];
			$select_acad = $row['proposal_academic'];
			$select_mir = substr($select_ir,0,strpos($select_ir,'P')); //create Modified IR number
			$pi_name = $row['pi_first_name'] . " " . $row['pi_last_name'];
			$select_center = $row['center'];	
			$ir_numbers[$select_mir]['full_ir'] = $select_ir;
			$ir_numbers[$select_mir]['name'][] = $pi_name;
			$ir_numbers[$select_mir]['title'] = $row['title'];
			$ir_numbers[$select_mir]['sponsor'] = $row['sponsor'];
			$ir_numbers[$select_mir]['school'] = $proposal_academics[$select_acad];
			$ir_numbers[$select_mir]['dept'] = $proposal_depts[$select_dept];
			$ir_numbers[$select_mir]['center'] = $proposal_centers[$select_center];
			if(isset($row['thrust'])){
			$ir_numbers[$select_mir]['thrust'] = $row['thrust'];
		}
			
		}
	}
} else {
	
	echo "No results found.";	
}
	
	//$ir_numbers = super_unique($ir_numbers);
	//make sure that we are unique by Modified IR Number
if(count($ir_numbers) > 0) {
	if(!isset($_GET['filter'])) {
	
		echo "<div id=\"research_listing\" class=\"sliders\">";
	}
	$count = 0;
foreach($ir_numbers as $key=>$research) {
	
	if($count < $limit) {

	//generate the HTML to display for the research listing
	//$disp = '<div class="item">'; jcv removed 03/05/18
	$disp = "<div tabindex=\"0\" aria-expanded=\"false\" role=\"button\" class=\"slider research-feed\" id=\"research-".$key."\">";
	
	//$disp .= "<div class=\"bar\"><h4 class=\"collapse\">".htmlentities($research['title'])."</h4></div>";
	$disp .= "<div class=\"bar accordion-title\"><h2>".$research['title']."</h2></div>";
	
	$disp .= "<div class=\"slider-content\">";
	$disp .= "<div class=\"meta\">";
	
	$disp .= '<table cellspacing="0" cellpadding="0" border="0"><tbody>';
	
	$disp .= "<th colspan=\"2\">Investigators</th>";
	
	$full_ir = $research['full_ir'];
	
	$results = mysql_query("SELECT DISTINCT pi_last_name, pi_first_name, role, email FROM research_feed WHERE ir_number like '$full_ir%' ORDER BY role DESC, pi_last_name ASC");
	while ($row = mysql_fetch_assoc($results)) {
		//search for all available investigators
		$email = substr($row['email'],0,strpos($row['email'],'@'));
		//$disp .= $row['email'];
		$faculty_link = '';
		if($email != '') {
		$url = mysql_query("SELECT link FROM cms_personnel_ou_pub WHERE userid = '$email' and link not like '%ou-%' and link not like '%senate%' and main_profile = ''
");
			while ($link = mysql_fetch_assoc($url)) {
				$faculty_link = $link['link'];	
			}
		}
		if($faculty_link != '') {
			if($row['email'] == $selected_email) {			
				$disp .= "<tr><td colspan=\"2\">". $row['role'] .": <mark><a href=\"" . $faculty_link . "\">" . $row['pi_first_name']. " " . $row['pi_last_name'] . "</a></mark></td></tr>";		
			} else {
				$disp .= "<tr><td colspan=\"2\">". $row['role'] .": <a href=\"" . $faculty_link . "\">" . $row['pi_first_name']. " " . $row['pi_last_name'] . "</a></td></tr>";
			}
		} else {
			if($row['email'] == $selected_email) {
				$disp .= "<tr><td colspan=\"2\">". $row['role'] .": <mark>" . $row['pi_first_name']. " " . $row['pi_last_name'] . "</mark></td></tr>";
			} else {
				$disp .= "<tr><td colspan=\"2\">". $row['role'] .": " . $row['pi_first_name']. " " . $row['pi_last_name'] . "</td></tr>";	
			}
		}
	}
	
	$disp .= "<tr><td>College/School:&nbsp;</td>";
	$disp .= "<td>" . $research['school'] . "</td></tr>";
	$disp .= "<tr><td>Department(s): </td><td>" . $research['dept'] . "</td></tr>";
	$disp .= '</tbody></table></div>';
	
	$disp .= "<div class=\"meta\"><div class=\"sponsor\"><p class=\"small\"><strong>Sponsor:</strong> " . $research['sponsor'] . "</p></div>";
	if($research['center'] != '') {
		$disp .= "<div class=\"centers\"><p class=\"small\"><strong>Center/Institute:</strong> " . $research['center'] . "</p></div>";
	}
	if(isset($research['thrust'])) {
		$t = str_replace(" | ", ", ", $research['thrust']);
		$disp .= "<div class=\"thrust\"><p class=\"small\"><strong>Research Focus:</strong> " . $t . "</p></div>";
	}
	if(isset($research['title']) && strpos($research['title'],'IRES') !== false) {
		$disp .= '<div class="link"><p class="small"><strong>Additional Information:</strong> <a href="http://iresdenmark.mtu.edu/">http://iresdenmark.mtu.edu/</a></p></div>';
	}
	
	//$disp .= "</div>";
	
	$disp .= "</div></div></div>\n";
	echo $disp;
	$count++;
	}
		
}
	if(!isset($_GET['filter'])) {	
		echo "</div>";
	}
}


close_db_conn();
?>