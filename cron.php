<?php
require_once(__DIR__ . '/lib/Airac.php');
require_once(__DIR__ . '/lib/Producer.php');
require_once(__DIR__ . '/config.php');

const METAFILE = 'http://aeronav.faa.gov/d-tpp/%s/xml_data/d-TPP_Metafile.xml';

use GetSky\AIRAC\Producer;

$producer = new Producer();

$date = new DateTime();
$date->setTimezone(new DateTimeZone('UTC'));

$nowAirac = $producer->now($date);  

$dbconn = connect();

$cycle = $dbconn->query("SELECT cycle FROM cycle LIMIT 1");
$cycle = $cycle->fetch_assoc()['cycle'];

$new_cycle = $nowAirac->getNumber();

$dbconn->query("UPDATE cycle SET cycle = '$new_cycle'");
$dbconn->query("TRUNCATE TABLE meta");

$xml = simplexml_load_file(sprintf(METAFILE, $new_cycle));


foreach ($xml->xpath('state_code/city_name/airport_name') as $airport) {
	$ident      = $airport['apt_ident'];
	$icao_ident = $airport['icao_ident'];
	$name       = $airport['ID'];
	$state      = $airport->xpath('../..')[0]['ID'];
	$city       = $airport->xpath('..')[0]['ID'];
	$records    = get_records($airport->record);

	$dbconn->query("
		INSERT INTO meta (
			ident,
			icao_ident, 
			name, 
			state,
			city, 
			records
		)
		VALUES (
			'$ident',
			'$icao_ident',
			'$name',
			'$state',
			'$city',
			'$records'
		)
	");
}


function get_record_name($xml_record) {
	return array(
		'chart_name' => (string)$xml_record->chart_name, 
		'pdf_name'   => (string)$xml_record->pdf_name
	);
}

function get_records($xml_records) {
	foreach ($xml_records as $record) {
		$chart_code = $record->chart_code;
		$record = get_record_name($record);

		switch ($chart_code) {
			case 'APD':
				$records['min'][]  = $record;
				break;
			case 'MIN':
				$records['min'][]  = $record;
				break;
			case 'DP':
				$records['dp'][]  = $record;
				break;
			case 'ODP':
				$records['dp'][]  = $record;
				break;
			case 'STAR':
				$records['star'][] = $record;
				break;
			case 'IAP':
				$records['iap'][]  = $record;
				break;
		}
	}

	return serialize($records);
}

?>