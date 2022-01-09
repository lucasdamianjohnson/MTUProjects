<?php

if(isset($_POST['data_set_1'])) {
		$data_1 = $_POST['data_set_1'];
	} else {
		$data_1 = '';
	}
if(isset($_POST['data_set_2'])) {
		$data_2 = $_POST['data_set_2'];
	} else {
		$data_2 = '';
	}
	$result = array_diff($data_set_2,$data_set_1);
	for ($i=0; $i < sizeof($result); $i++) { 
		//echo "test";
		echo $result[$i];
	}
/*
$html = "<h1>this is a test</h1>".$data_1[0];
$file = fopen("list.html","w") or die("something went wrong");
fwrite($file,$html);
chmod("list.html",0777);
*/
?>
