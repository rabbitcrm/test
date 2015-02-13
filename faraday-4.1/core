<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

// Format Date
function formatDate($date, $format = '') {
	$bczUser = $_SESSION['bcz_user'];
	
	if (!$format) {
		$format = $bczUser->settings->date_format ? $bczUser->settings->date_format : ($bczUser->organization->date_format ? $bczUser->organization->date_format : 'd/m/Y');
	}
	return date($format, strtotime($date));
}

// Convert timezone
function convertDateTime($dateStr, $format = '') {
	$bczUser = $_SESSION['bcz_user'];

	if (!$dateStr || in_array($dateStr, array('0000-00-00 00:00:00', '0000-00-00'))) {
		return '-';
	}

	if (!$format) $format = $bczUser->settings->date_format ? $bczUser->settings->date_format : ($bczUser->organization->date_format ? $bczUser->organization->date_format : 'd/m/Y');

	$timezone = $bczUser->settings->timezone ? $bczUser->settings->timezone : 'UTC';
	$dateObj = new DateTime($dateStr);
	$dateObj->setTimezone(new DateTimeZone($timezone));
	return $dateObj->format($format);
}