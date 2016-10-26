<?php
function convertEuroToUSDate($original_date) {
	//error_log("convertEuroToUSDate->from: " . $original_date);
	$converted_date=null;
	if ($original_date!=null) {
		$date_array = explode("/", $original_date);
		$converted_date = $date_array[2] . "-" . $date_array[1] . "-" . $date_array[0];
	}
	//error_log("convertEuroToUSDate->to: " . $converted_date);
	return $converted_date;
}

function convertUSToEuroDate($original_date) {
	//error_log("convertUSToEuroDate->from: " . $original_date);
	$converted_date=null;
	if($original_date!=null) {
		$date_array = explode("-", $original_date);
		$converted_date = $date_array[2] . "/" . $date_array[1] . "/" . $date_array[0];
	}
	//error_log("convertUSToEuroDate->to: " . $converted_date);
	return $converted_date;
}

function convertEuroToUSDateTime($original_date) {
	//error_log("convertEuroToUSDateTime->from: " . $original_date);
	$converted_datetime=null;
	if($original_date!=null) {
		$datetime_array = explode(" ", $original_date);
		$date_array = explode("/", $datetime_array[0]);
		$converted_date = $date_array[2] . "-" . $date_array[1] . "-" . $date_array[0];
		$converted_datetime = $converted_date . " " . $datetime_array[1];
	}
	//error_log("convertEuroToUSDateTime->to: " . $converted_datetime);
	return $converted_datetime;
}

function convertUSToEuroDateTime($original_date) {
	//error_log("convertUSToEuroDateTime->from: " . $original_date);
	$converted_datetime=null;
	if($original_date!=null) {
		$datetime_array = explode(" ", $original_date);
		$date_array = explode("-", $datetime_array[0]);
		$converted_date = $date_array[2] . "/" . $date_array[1] . "/" . $date_array[0];
		$converted_datetime = $converted_date . " " . $datetime_array[1];
	}
	//error_log("convertUSToEuroDateTime->to: " . $converted_datetime);
	return $converted_datetime;
}

