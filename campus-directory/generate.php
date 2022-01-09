<?php
header('Access-Control-Allow-Origin: *');
if (isset($_POST["field"])) {
	$field = $_POST["field"];
}
if (isset($_POST["method"])) {
	$method = $_POST["method"];
}
if (isset($_POST["text"])) {
	$text = $_POST["text"];
}
if (isset($_POST["bool"])) {
	$bool = $_POST["bool"];
}
if (isset($_POST["username"])) {
	$username = $_POST["username"];
} else {
	$username = "";
}

if (isset($_GET['p'])) {
	$qs = $_SERVER['QUERY_STRING'];
	$qs = str_replace("function=uiapi&", '', $qs);
	$text[0] = "Alex";
} else {
	$qs = '';
}

if (isset($_POST['sp_field']) && $_POST['sp_field'] != '') {

	?>

<h3 style="text-align: center;">Please Enter Valid Search</h3>

<?php
exit();

}

//if the user did not enter any text display this message
if (($text[0] == "") && ($username == "")) {
	?>
<h3 style="text-align: center;">Please Enter Search Text</h3>
	<?php
exit();
}
function phone($string) {
	$phone = "";
	preg_match_all('!\d+!', $string, $matches);
	$matches = str_split($matches[0][0]);
	for ($i = 0; $i < count($matches); $i++) {

		if (($i == 3) or ($i == 6)) {
			$phone = $phone . "-";

		}
		$phone = $phone . $matches[$i];
	}
	return $phone;
}

function get_data($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_REFERER, $_SERVER['PHP_SELF']);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

	$data = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);
	return $data;
}

//this is the single person view for the search page
if ($username != "") {
	$return = get_data("https://www.mtu.edu/mtuldapweb/ldaprest-api/search.cgi?function=uiapi&p=,user,is,$username");
	$return = json_decode($return, true);
	if (count($return) != 0) {
		?>

<table style="min-width: 100%;" class="auto striped">
	<caption>Results</caption>

	<?php
//load all information about the person that is in the database
		foreach ($return[0] as $key => $person) {
			if ($key == 'Office Phone' && $person != '') {
				$person = phone($person);
			} elseif ($key == 'Email Address' && $person != '') {
				$person = '<a href="mailto:' . $person . '">' . $person . '</a>';
			}
			?>
	<tr>
	<th scope="col"><?php echo $key ?></th>
	<td><?php echo $person ?></td>
	</tr>

	<?php
}
		?>


</table>
<a id="back" tabindex="0" href="javascript:void(0);" style="text-align: center" class="button-grey">Back</a><br>
	<?php
}

} //this is the full results of the sarch
else if ($username == "") {

	if ($qs == '') {
		//generate the query string for the api
		$p = "p=,$field[0],$method[0]," . urlencode($text[0]);
		for ($i = 1; $i < count($field); $i++) {
			$p = $p . "&p=" . $bool[$i - 1] . ",$field[$i],$method[$i]," . urlencode($text[$i]);
		}
	} else {
		$p = $qs;
	}
	$url = "https://www.mtu.edu/mtuldapweb/ldaprest-api/search.cgi?function=uiapi&" . $p;
	$return = get_data($url);
	$return = json_decode($return, true);
	//if it returns nothing just display No Results
	if (count($return) != 0) {
		?>
	<table style="min-width: 100%;" class="auto striped">
	<caption>Results</caption>
	<tr>
	<th scope="col">Name</th>
	<th scope="col">Email</th>
	<th scope="col">Phone</th>
	</tr>
	<?php

		foreach ($return as $person) {
			$phone = "";

			if (isset($person["Office Phone"]) && $person["Office Phone"] != "") {

				$phone = phone($person["Office Phone"]);

			}
			?>
	<tr>
	<th scope="row"><a href="javascript:void(0);"  class="click-name" id="<?php echo $person["Username"]; ?>" data-id="<?php echo $person["Username"]; ?>"><?php echo $person["Name"]; ?></a></th>
		<td><a href="mailto:<?php echo $person["Email Address"]; ?>"><?php echo $person["Email Address"]; ?></a></td>
		<td><?php echo ($phone) ?></td>
	</tr>
	<?php

		}
		?>
	</table>
	<?php
} else {

		?><h3 style="text-align: center;">No Results</h3><?php

	}}
?>
