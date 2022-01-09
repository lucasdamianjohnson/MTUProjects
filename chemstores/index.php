<?php
function get_data($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_REFERER, $_SERVER['PHP_SELF']);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);
	return $data;
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Michigan Technological University</title>
<meta itemprop="inLanguage" content="en-US" />
<meta itemprop="genre" content="Education" />
<meta itemprop="description" name="description" content="Founded in 1885, Michigan Technological University is a leading public research institution offering more than 120 undergraduate and graduate degree programs in science, technology, engineering, and mathematics." />
<meta property="fb:pages" content="5652694310" />
<meta property="og:title" content="Michigan Technological University">
<meta property="og:description" content="Founded in 1885, Michigan Technological University is a leading public research institution offering more than 120 undergraduate and graduate degree programs in science, technology, engineering, and mathematics.">
<meta property="og:type" content="website">
<meta property="og:url" content="http://www.mtu.edu/">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@michigantech">
<meta name="twitter:creator" content="@michigantech">
<meta name="twitter:title" content="Michigan Technological University">
<meta name="twitter:url" content="http://www.mtu.edu/">
<meta name="twitter:description" content="Founded in 1885, Michigan Technological University is a leading public research institution offering more than 120 undergraduate and graduate degree programs in science, technology, engineering, and mathematics.">

<!--CSS Required-->
<link type="text/css" rel="stylesheet" href="//www.mtu.edu/mtu_resources/styles/n/normalize.css" />
<link type="text/css" rel="stylesheet" href="//www.mtu.edu/mtu_resources/styles/n/base.css" />
<link type="text/css" rel="stylesheet" media="print" href="//www.mtu.edu/mtu_resources/styles/n/print.css" />
<!--[if lte IE 9]>
<script src="//www.mtu.edu/mtu_resources/script/n/html5.js"></script>
<![endif]-->

<!--Your CSS Here-->

<!--[if lte IE 9]>
<script src="//www.mtu.edu/mtu_resources/script/n/html5.js"></script>
<![endif]-->
<script type="application/ld+json">
{
   "@context": "http://schema.org",
   "@type": "WebSite",
   "url": "http://www.mtu.edu/",
   "potentialAction": {
   "@type": "SearchAction",
   "target": "http://www.mtu.edu/search/?q={q}",
   "query-input": "required name=q"
   }
}
</script>
<!--[if (!IE)|(gt IE 8)]><!-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="//www.mtu.edu/mtu_resources/script/n/jquery.js"><\/script>')</script>
<!--<![endif]-->
<!--[if lte IE 8]>
  <script src="//www.mtu.edu/mtu_resources/script/n/jquery-ie.js"></script>
<![endif]-->
<script src="//www.mtu.edu/mtu_resources/script/n/formal.js"></script>
<!-- Your jQuery here -->
</head>
<body itemscope="" itemtype="http://schema.org/CollegeOrUniversity">
<nav id="skip" aria-label="accessibility navigation"><a href="#main">Skip to page content</a> <a href="#site-nav">Skip to site navigation</a> <a href="#footer-fixed">Skip to footer navigation</a></nav>
<div class="stick">
  <?php echo get_data('http://www.mtu.edu/cms-export/3/header.html'); ?>
    <section class="sitetitle">
            <div><span><a href="/chemstores/">Chem Stores</a></span></div>
            <div id="breadcrumbs" role="navigation" aria-label="breadcrumbs">
               <ul>
                  <li><a href="/research/index.html">Research</a> &gt;</li>
                  <li><a href="/chemstores/">Chem Stores</a></li>
                  <li class="active">&gt; <a aria-current="page" href="/chemstores/order-form/">Order Form</a></li>
               </ul>
            </div>
         </section>
  <div id="main" role="main" aria-label="main content">
    <section id="body_section" aria-label="page content">
      <div class="full-width" >
        <!--leftnav location-->
        <div id="content"><!-- Class of rsidebar to shift content appopriately, remove when no sidebar -->
          <article id="content_body">
            <h1>Chemistry Stores Order Form</h1>
                <?php
//required fields on the form.
$requiredFields = array('requestor', 'phone', 'email', 'account', 'dept', 'location', 'justification');

//keep track of which ones they didn't fill out.
$invalidFields = array();

$valid = true;
foreach ($requiredFields as $index => $field) {
	if (!isset($_POST[$field]) || empty($_POST[$field])) {
		$valid = false;
		$invalidFields[] = $field;
	}
}

//the email they entered is invalid. Don't let invalid emails through, since that's where we're going to have to send this.
if (isset($_POST['email']) && !eregi('^[a-zA-Z0-9._-]+@[m][t][u]+\.[e][d][u]$', $_POST['email'])) {
	//Print an error letting them know that the email they entered was not valid.
	echo "<h2>Error: Invalid Email Address</h2>";
	echo "<p>The email address you entered was not valid. You must provide a valid Michigan Tech email address.</p>";
	echo "<p>Please use your browser's back button to return to the form and try again.</p>";
}
//they're missing something
else if ($valid == false) {
	//Print an error letting them know they didn't submit everything they needed.
	echo "<h2>Error: Blank Fields</h2>";
	echo "<p>The following fields were left blank in your form submission</p>";
	echo "<ul>";
	foreach ($invalidFields as $index => $field) {
		echo "<li>$field</li>";
	}
	echo "</ul>";
	echo "<p>These fields must be filled in before you can successfully submit the form.</p>";
	echo "<p>Please use your browser's back button to return to the form and try again.</p>";
}
//They entered all the necessary information to allow submission
else {
	$myDate = date("mdY") . "-" . date("H-i-s"); //date they submitted. Used to generate order number.

	//Develop the html for the confirmation
	echo "<h2>Your order has been sent to Chem Stores for processing</h2>";
	// ---
	$html = "";

	$html .= '<table cellspacing="4" cellpadding="0" border="0" style="border: 1px solid black;border-collapse:seperate;;">
                      <tr>
                        <td>Order #:</td>
                        <td style="text-align: left;">&nbsp;' . $myDate . '</td>
                      </tr>
                      <tr>
                        <td>Vendor:</td>
                        <td style="text-align: left;">&nbsp;' . htmlspecialchars($_POST['vendor']) . '</td>
                      </tr>
            <tr>
                        <td>Vendor Name (manual entry, wasn\'t in list):</td>
                        <td style="text-align: left;">&nbsp;' . htmlspecialchars($_POST['vendorName']) . '</td>
                      </tr>
            <tr>
                        <td>Justification for certain vendor:</td>
                        <td>&nbsp;' . htmlspecialchars($_POST['justification']) . '</td>
                      </tr>
                      <tr>
                        <td>Requestor:</td>
                        <td>&nbsp;' . htmlspecialchars($_POST['requestor']) . '</td>
                      </tr>
                      <tr>
                        <td>Phone:</td>
                        <td>&nbsp;' . htmlspecialchars($_POST['phone']) . '</td>
                      </tr>
                       <tr>
                        <td>Cell Phone:</td>
                        <td>&nbsp;' . htmlspecialchars($_POST['cellphone']) . '</td>
                      </tr>
                      <tr>
                        <td>Email:</td>
                        <td>&nbsp;' . htmlspecialchars($_POST['email']) . '</td>
                      </tr>
            <tr>
                        <td>Secondary contact:</td>
                        <td>&nbsp;' . htmlspecialchars($_POST['secondary']) . '</td>
                      </tr>
            <tr>
            <tr>
                        <td>Secondary phone:</td>
                        <td>&nbsp;' . htmlspecialchars($_POST['secondaryPhone']) . '</td>
                      </tr>
            <tr>
                        <td>Secondary contact email:</td>
                        <td>&nbsp;' . htmlspecialchars($_POST['secondaryEmail']) . '</td>
                      </tr>
                      <tr>
                        <td>Date Needed:</td>
                        <td>&nbsp;' . htmlspecialchars($_POST['dateNeeded']) . '</td>
                      </tr>
            <tr>
                        <td>Rush Order:</td>
                        <td>&nbsp;' . htmlspecialchars(str_replace('rushorder', 'X', $_POST['rush'])) . '</td>
                      </tr>
                      </table>
                    <br><br>

                      <table cellspacing="4" cellpadding="0" border="0" style="border: 1px solid black; width:900;border-collapse:seperate;;">
                      <tr>
                        <th style="text-align:center;border-bottom: 1px solid black;">Qty</th>
                        <th style="text-align:center;border-bottom: 1px solid black;">Unit</th>
                        <th style="text-align:center;border-bottom: 1px solid black;">Catalog #</th>
                        <th style="text-align:center;border-bottom: 1px solid black;">Description</th>
                        <th style="text-align:center;border-bottom: 1px solid black;">Unit Cost</th>
                        <th style="text-align:center;border-bottom: 1px solid black;">Total Cost</th>
                      </tr>';

	for ($i = 0; $i < count($_POST['qty']); $i++) {

		$html .= '<tr>
                    <td style="text-align:center;width:30px;">
                      &nbsp;' . htmlspecialchars($_POST['qty'][$i]) . '
                    </td>
                    <td style="text-align:center;width:30px;">
                      &nbsp;' . htmlspecialchars($_POST['unit'][$i]) . '
                    </td>
                    <td style="text-align:center;width:75px;">
                      &nbsp;' . htmlspecialchars($_POST['cat'][$i]) . '
                    </td>
                    <td style="text-align:center;width:500;">
                      &nbsp;' . htmlspecialchars($_POST['desc'][$i]) . '
                    </td>
                    <td style="text-align:center;width:60px;">
                      &nbsp;' . htmlspecialchars($_POST['ucost'][$i]) . '
                    </td>
                    <td style="text-align:center;width:60px;">
                      &nbsp;' . htmlspecialchars($_POST['tcost'][$i]) . '
                    </td>
                    </tr>';
	}

	$gTotal = 0;
	$temp;
	for ($i = 0; $i < count($_POST['tcost']); $i++) {
		$gTotal += $_POST['tcost'][$i];
	}

	$html .= '<tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td style="text-align:right;font-weight:bold;">Total:</td>
                      <td style="text-align:center;">&nbsp;' . $gTotal . '</td>
                      </tr>
                      </table>

                      <br/><br/>
                      <table cellspacing="4" cellpadding="0" border="0" style="border: 1px solid black;border-collapse:seperate;">
                      <tr>
                        <td>Account:</td>
                        <td>&nbsp;</td><td>' . htmlspecialchars($_POST['account']) . '</td>
                      </tr>
                      <tr>
                        <td>Department:</td>
                        <td>&nbsp;</td><td>' . htmlspecialchars($_POST['dept']) . '</td>
                      </tr>
                      <tr>
                        <td>Date:</td>
                        <td>&nbsp;</td><td>' . date("m/d/Y") . '</td>
                      </tr>
            <tr>
            <td>When your order arrives would you like:</td>
            <td>&nbsp;</td><td>' . htmlspecialchars($_POST['orderArrive']) . '</td>
            </tr>
                      <tr>
                        <td>Building & Room<br/>where used (if Chemical):</td>
                        <td>&nbsp;' . htmlspecialchars($_POST['building']) . '</td>
                        <td>&nbsp;' . htmlspecialchars($_POST['location']) . '</td>
                      </tr>
                      </table>';

	// ------- END HTML GENERATOR----------

	//setup the email so it is ready to send.
	require_once '/docroot/htdocs/mtu_resources/php/phpmailer/class.phpmailer.php';
	$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->Host = "email.mtu.edu"; // SMTP server
	$mail->SMTPDebug = 0; // enables SMTP debug information (for testing)
	$mail->SMTPAuth = true; // enable SMTP authentication
	$mail->SMTPSecure = 'tls';
	$mail->Port = 25; // set the SMTP port for the GMAIL server
	$mail->Username = "XXXXXXXXXXXXXXX"; // SMTP account username
	$mail->Password = "is_A_butterf1y"; // SMTP account password
	$mail->Sender = 'chem_noreply@mtu.edu'; // Envelope address
	$mail->SetFrom('chem_noreply@mtu.edu');
	$mail->Subject = "Chem Stores Web Order: " . $myDate;
	$mail->MsgHTML($html);

	//send to chemistry folks first.
	// $mail->AddAddress("ddwareha@mtu.edu");
	// $mail->AddAddress("joels@mtu.edu");
	$mail->AddAddress("XXXXXXXXXXX");
	//$mail->AddAddress("ldjohnso@mtu.edu");
	//$mail->AddAddress("ajhughes@mtu.edu");
	try {
		$mail->Send();
	} catch (phpmailerException $e) {
		$error = true;
	}
	//if we couldn't send to the chemistry folks, then they're going to have to resubmit, or something is broke.
	if (isset($error) && $error) {
		echo "<p>Unknown error.  Please try again.  If the request fails again, please email us at cmshelp@mtu.edu</p>";
	} else {
		//reset the addresses, so the chemistry folks aren't being sent to anymore
		$mail->ClearAddresses();

		//send a seperate email to the user.
		$mail->AddAddress($_POST["email"]);
		$mail->Send();
	}

	echo $html; // Display their order

}
?>
          </article>
        </div>
        <!-- right sidebar location-->
        <div class="clearer"></div>
      </div>
    </section>
  </div>
<div class="push"></div>
</div>
<?php echo get_data('http://www.mtu.edu/cms-export/3/footer.html'); ?>
<!-- Analytics Code
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-83883600-1', 'auto');
  ga('send', 'pageview');

</script>-->
</body>
</html>