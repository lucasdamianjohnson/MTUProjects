
<?php 
include('functions.php');

$mode = 'search';
if(isset($_GET['filter']))  $mode = 'filter';

if(isset($_GET['main'])) {
	$main = urldecode($_GET['main']);
	//if ($_GET['dept'] != 'none') $dept = "proposal_dept LIKE '" .urldecode($_GET['dept']). "%'";
	//else $dept = 'proposal_dept is not null';
} else {
	$main = '';	
}

if(isset($_GET['dept'])) {
	$sdept = urldecode($_GET['dept']);
	//if ($_GET['dept'] != 'none') $dept = "proposal_dept LIKE '" .urldecode($_GET['dept']). "%'";
	//else $dept = 'proposal_dept is not null';
} else {
	$sdept = '';	
}

if(isset($_GET['academic'])) {
	$sacademic = urldecode($_GET['academic']);
	//if ($_GET['academic'] != 'none') $academic = "proposal_academic LIKE '" .urldecode($_GET['academic']). "%'";
	//else $academic = 'proposal_academic is not null';
} else {
	$sacademic = '';	
}
	
if(isset($_GET['pi'])) {
	$semail = urldecode($_GET['pi']);
	//if ($_GET['pi'] != "none") $email = "email = '" .urldecode($_GET['pi']). "'";
	//else $email = 'email is not null';
} else {
	$semail = '';	
}

if(isset($_GET['centers'])) {
	$scenter = urldecode($_GET['centers']);
	//if ($_GET['pi'] != "none") $email = "email = '" .urldecode($_GET['pi']). "'";
	//else $email = 'email is not null';
} else {
	$scenter = '';	
}

if(isset($_GET['abstract'])) {
	$abstract = $_GET['abstract'];	
}
if(isset($_GET['thrust'])) {
	$sthrust = $_GET['thrust'];
} 
if(!isset($_GET['js'])) {
	if(isset($_GET['abstract'])) {
	disp_js2();	
	} else {
	disp_js();
	}
}

if(isset($_GET['change'])) {
	$change = $_GET['change'];
	if ($change == 'academic') {
		echo json_encode(array('depts' => disp_depts('proposal',$sdept,$sacademic,$semail,true),
							   'pi' => disp_pis($sdept,$sacademic,$semail,true),
							   'centers' => disp_centers('proposal',$sdept,$sacademic,$semail,$scenters,true)));
	} else if ($change == 'dept') {
		echo json_encode(array('pi' => disp_pis($sdept,$sacademic,$semail,true),
							  'centers' => disp_centers('proposal',$sdept,$sacademic,$semail,$scenters,true)));
	} else if ($change == 'pi') { 
		//echo $semail;
		echo json_encode(array('centers' => disp_centers('proposal',$sdept,$sacademic,$semail,$scenters,true)));	
	} else {
		echo json_encode(array());
	}
} 

else {
if(isset($_GET['type']) && $_GET['type'] == 'research-main') {?>
	<div class="item">
        <div class="search-box">
            <form action="//www.mtu.edu/research/about/current/" method="get">
                <label for="research-feed-search" class="label_in_textfield">Search Michigan Tech Research Projects</label>
                <input type="text" class="search-field" name="research-feed-search" id="research-feed-search">
                <input type="submit" class="search-submit" value="Search">
            </form>
        </div>
    </div>

<?php } else {
?>	
<div id="research-feed" style="float: left;margin-bottom: 30px;">
	<div id="research-search" class="<?php echo $mode=='search' ? 'active' : ''; ?>">
		<h3>Search the Michigan Tech Research Feed</h3>
        <?php if(isset($abstract)) { ?>
		<form action="//www.mtu.edu/mtu_resources/php/research/feed/ndex-dept.php" method="get" id="research-feed-search">
        <?php } else { ?>
        <form action="//www.mtu.edu/mtu_resources/php/research/feed/" method="get" id="research-feed-search">
        <?php } ?>
			<?php if (isset($sdept)) { ?>
			<input type="hidden" name="dept" value="<?php echo $sdept ?>" />
			<?php } if (isset($sacademic)) { ?>
			<input type="hidden" name="academic" value="<?php echo $sacademic ?>" />
			<?php } if (isset($scenters)) { ?>
			<input type="hidden" name="centers" value="<?php echo $scenters ?>" />
			<?php } ?>
			<input type="hidden" name="filter" value="true" />
			<input type="text" name="search" id="search" />
			<input id ="search-button" alt="Search" type="submit" value="Search" />
		</form>
		<a class="mode" style="float: right; margin-top:4px; font-size: 15px;" href="<?php if (isset($sdept, $sacademic)) { echo "?academic=$sacademic&dept=$sdept"; } else { echo '#'; } ?>">Not finding what you're looking for? Try advanced filtering.</a>
	</div>
	<?php //if (!isset($sdept, $sacademic)) { ?>
	<div id="research-filter" class="<?php echo $mode == 'filter' ? 'active' : ''; ?>">
		<p style="margin-bottom: 5px;"><a class="mode" href="#" style="float:right;">Return to Search.</a> You may filter the feed by selecting a single option or multiple options.</p>
<div id="banner_image"><div class="shadow">
		<form action="//www.mtu.edu/mtu_resources/php/research/feed/new/" method="get" id="research-feed-filter">
		<h3 style="float: left;">Filter by:</h3>
		
		<div class="filter-row <?php echo isset($sacademic) ? 'active' : ''; ?>" >
			<span class="reset">[<a href="javascript:null;">clear</a>]</span>
			<input type="checkbox" value="academic" name="radioAcademic" id="radioAcademic" <?php echo isset($sacademic) ? 'checked="checked"' : ''; ?> />
			<label for="radioAcademic">Academic</label>
			<?php disp_academic('proposal',$sdept,$sacademic,$semail); ?>
		</div>
		<div class="filter-row <?php echo isset($sdept) ? 'active' : ''; ?>">
			<span class="reset">[<a href="javascript:null;">clear</a>]</span>
			<input type="checkbox" value="dept" name="radioDept" id="radioDept" <?php echo isset($sdept) ? 'checked="checked"' : ''; ?> />
			<label for="radioDept">Departments</label>
			<?php disp_depts('proposal',$sdept,$sacademic,$semail); ?>
		</div>

		<div class="filter-row <?php echo isset($semail) ? 'active' : ''; ?>">
			<span class="reset">[<a href="javascript:null;">clear</a>]</span>
			<input type="checkbox" value="pi" name="radioPi" id="radioPi" <?php echo isset($semail) ? 'checked="checked"' : ''; ?> />
			<label for="radioPi">Investigators</label>
			<?php disp_pis($sdept,$sacademic,$semail); ?>
		</div>

       <div class="filter-row <?php echo isset($scenter) ? 'active' : ''; ?>">
	<?php ?>		<span class="reset">[<a href="javascript:null;">clear</a>]</span>
			<input type="checkbox" value="center" name="radioCenter" id="radioCenter" <?php echo isset($scenter) ? 'checked="checked"' : ''; ?> />
			<label for="radioCenter">Centers/Institute</label>
			<?php disp_centers($sdept,$sacademic,$semail,$scenters); ?>
		</div>
	<div class="filter-row <?php echo isset($sthrust) ? 'active' : ''; ?>">
		<span class="reset">[<a href="javascript:null;">clear</a>]</span>
			<input type="checkbox" value="thrust" name="radioThrust" id="radioThrust" <?php echo isset($sthrust) ? 'checked="checked"' : ''; ?> />
			<label for="radioThrust">Research Focus</label>
			<?php disp_thrust($sthrust,$sdept,$sacademic,$semail); ?>
		</div>
	


		<input type="hidden" name="filter" value="true" />
		<p class="right" style="margin: 0 40px 0 0;"><input type="submit" value="Filter" /></p>
		</form>
		</div></div>
	</div>
	<?php //} ?>
</div>

<?php } 
}
?>