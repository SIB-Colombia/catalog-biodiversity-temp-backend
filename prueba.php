<?php
	$connectionStringConstruct = 'pgsql:host='.getenv('CATALOG_BACKEND_DBHOST').';port=5432;dbname='.getenv('CATALOG_BACKEND_DBNAME');
	echo $connectionStringConstruct;
	phpinfo();
?>