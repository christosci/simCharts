<?php
require_once('airport.php');
$ident = strtoupper($_GET['search']);
$ident = explode('.', $ident);

$apt = new Airport($ident[0]);

// if airport metadata does not exist, return to frontpage
$meta = $apt->get_airport_meta();
if ($meta == null)
	 header('Location: index.php');

$records = unserialize($meta['records']);

if (!empty($ident[1])) {
	foreach ($records as $record_type) {
		foreach ($record_type as $i=>$record) {
			if (strpos($record['pdf_name'], $ident[1]) !== false) {
				$r = $record;
				if (!empty($ident[2]))
					$r = $record_type[$i+$ident[2]-1];
				if (isset($_GET['direct']))
					header(sprintf('Location: %s', $apt->get_direct_url($r)));
				else
					echo sprintf('<script>window.open("%s");</script>', $apt->get_direct_url($r));
				break 2;
			}
		}
	}
}

$record_keys = ['min', 'dp', 'star', 'iap'];
$available_record_keys = [];
foreach ($record_keys as $key) {
	if (array_key_exists($key, $records)) {
		array_push($available_record_keys, $key);
	}
}

?>
