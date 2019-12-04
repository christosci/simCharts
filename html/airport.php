<?php

class Airport {
	private $dbconn;
	private $cycle;
	private $ident;

	function __construct($ident) {
		global $dbconn;
		$this->dbconn = $dbconn;
		$this->ident = $ident;

		$cycle = $this->dbconn->query("SELECT cycle FROM cycle LIMIT 1");
		$this->cycle = $cycle->fetch_assoc()['cycle'];
	}

	function get_airport_meta() {
		$stmt = $this->dbconn->prepare("
			SELECT ident, icao_ident, name, state, city, records
	 		FROM   meta 
	 		WHERE  ident=? 
	 		OR     icao_ident=?
	 		LIMIT  1
	 	");

		$stmt->bind_param('ss', $this->ident, $this->ident);
		$stmt->execute();
		$stmt->store_result();

		if ($stmt->num_rows > 0) {
			$stmt->bind_result($meta['ident'], $meta['icao_ident'], 
				$meta['name'], $meta['state'], $meta['city'], $meta['records']);
			$stmt->fetch();

			return $meta;
		}

		return null;
	}

	function get_record_link($record) {
		return sprintf('<li><a href="%s/%s/%s" target="_blank">%s</a></li>', 
			TPP_URL, $this->cycle, $record['pdf_name'], $record['chart_name']); 
	}

	function get_direct_url($record) {
		return sprintf('%s/%s/%s', 
			TPP_URL, $this->cycle, $record['pdf_name']); 
	}
}

?>