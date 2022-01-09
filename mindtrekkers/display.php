<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 */
if (isset($_POST['title'])) {
	$titleq = $_POST['title'];
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
//This is getting the list of majors from this google spread sheet.
//!!! should maybe cahce this so it does not have to pull every single time
$key = "11hQr0uT1EeuiVqBe1RMyrE4fGPmzHUb_C-CWxvRWKWg";
$id = "0";
$json = get_data("https://www.mtu.edu/mtu_resources/php/google/getsheet/?key=$key&sheet=$id");
$json = json_decode($json, true);
$act = $json['Sheet1'];
$i = 0;
$titleq = $titleq;
if (isset($titleq)) {
	$titleq = explode(" ", $titleq);
}
if (isset($titleq) && (implode("", $titleq) != "")) {
	$ids = Array();
	foreach ($act as $a) {
		$i++;
		$search_score = -1;
		$title = $a['Title'];
		$desc = $a['Description'];
		$stand = $a['Education Standards'];
		$keywords = $a['Search Keywords'];
		$desct = explode(" ", $desc);
		$standt = explode(" ", $stand);
		$titlet = explode(" ", $title);
		$keywords = explode(" ", $keywords);
		if (isset($titleq) && (implode("", $titleq) != "")) {
			foreach ($titleq as $v1) {
				$v1 = strtolower($v1);
				$length = strlen($v1);
				if ($length <= 3) {
					$lev = 0;
				}
				if ($length > 3 && $length <= 6) {
					$lev = 1;
				}
				if ($length > 6 && $length <= 9) {
					$lev = 2;
				}
				if ($length > 10) {
					$lev = 3;
				}

				foreach ($titlet as $v2) {
					$v2 = strtolower($v2);
					if ($v1 == $v2) {
						$search_score += 5;
					}
					if (strpos($v2, $v1) !== false) {
						$search_score += 2;
					}

					if (levenshtein($v1, $v2) <= $lev) {
						$search_score += 1;
					}
				}
				foreach ($desct as $v2) {
					$v2 = trim(strtolower($v2));
					if ($v1 == $v2) {
						$search_score += 5;
					}
					if (strpos($v2, $v1) !== false) {
						$search_score += 2;
					}

					if (levenshtein($v1, $v2) <= $lev) {

						$search_score += 1;
					}
				}
				foreach ($standt as $v2) {
					$v2 = strtolower($v2);
					if ($v1 == $v2) {
						$search_score += 5;
					}
					if (strpos($v2, $v1) !== false) {
						$search_score += 2;
					}

					if (levenshtein($v1, $v2) <= $lev) {
						$search_score += 1;
					}
				}
				foreach ($keywords as $v2) {
					$v2 = strtolower($v2);
					if ($v1 == $v2) {
						$search_score += 5;
					}
					if (strpos($v2, $v1) !== false) {
						$search_score += 2;
					}

					if (levenshtein($v1, $v2) <= $lev) {
						$search_score += 1;
					}
				}

				if (strpos(strtolower($title), $v1) !== false) {
					$search_score += 3;
				}
			}
			if ($search_score > -1) {
				$ids[] = "slider-$i";
			}
		}
	}

	echo json_encode($ids);
	exit();
}
?>
	<div id="display">
		<div class="sliders">
			<?php
foreach ($act as $a) {

	$i++;
	$title = $a['Title'];
	$desc = $a['Description'];
	$plan = $a['Lesson Plan URL'];
	$time = $a['Time to Complete'];
	$cat = $a['STEM Categories'];
	$stand = $a['Education Standards'];
	$home = $a['Try Our Demonstrations'];
	$image = $a['Image URL'];
	$cat = explode(",", $cat);
	$stand = explode(",", $stand);
	foreach ($cat as $key => $v) {
		if ($v == "") {
			unset($cat[$key]);
		}
	}
	foreach ($stand as $key => $v) {
		if ($v == "") {
			unset($stand[$key]);
		}
	}

	?>
				<div class="slider" tabindex="0" aria-expanded="false" id="slider-<?php echo $i; ?>" role="button" data-stem="<?php echo implode(",", $cat); ?>" data-home="<?php echo $home; ?>" data-select="true">
					<div class="bar accordion-title">
						<h2><?php echo $title; ?></h2>
					</div>
					<div class="slider-content accordion" style="display: none;">
						<div class="item">

							<p> <?php
if ($image != "") {
		?>
								<img src="<?php echo $image; ?>" alt="<?php echo $title ?> demo" class="right">
							<?php }?>
							<strong class="time-icon">Demo: <?php echo $time; ?></strong></p>

							<p><?php echo $desc ?></p>

							<h3>STEM Categories</h3>

							<ul>
								<?php if (count($cat) > 0) {
		?>
									<?php
foreach ($cat as $c) {
			echo "<li><p>$c</p></li>";
		}

		?>

								</ul>
							<?php }?>
							<?php if (count($stand) > 0) {
		?>
								<h3>Education Standards</h3>

								<ul>

									<?php
foreach ($stand as $s) {
			echo "<li><p>$s</p></li>";
		}

		?>

								</ul>
							<?php }?>
							<h3>Try Our Demonstrations</h3>

							<ul>

								<li><?php echo $home; ?></li>

							</ul>
							<?php if ($plan != "") {?>
								<p><a class="button" href="<?php echo $plan; ?>" target="_blank">View Lesson Plan</a></p>
							<?php }?>
						</div>
					</div>
				</div>

				<?php

}
?>
		</div>
	</div>
<div id="results" style="display: none;">
<h4>No Results</h4>
</div>