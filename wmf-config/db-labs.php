<?php
# WARNING: This file is publicly viewable on the web. Do not put private data here.

if ( $wmfRealm == 'labs' ) { # safe guard
	# Database configuration files for the beta labs

	$wgDBTableOptions = "ENGINE=InnoDB, DEFAULT CHARSET=binary";

	$wgLBFactoryConf = [

		# Requires 'sectionsByDB', 'sectionLoads', 'serverTemplate'

		'class' => 'LBFactoryMulti',

		# Everyone to DEFAULT
		'sectionsByDB' => [],

		'sectionLoads' => [
			'DEFAULT' => [
				'deployment-db03' => 0,
				'deployment-db04' => 400,
			],
		],

		'serverTemplate' => [
			'dbname'	  => $wgDBname,
			'user'		  => $wgDBuser,
			'password'	  => $wgDBpassword,
			'type'		  => 'mysql',
			'flags'		  => DBO_DEFAULT,
			'max lag'	  => 300, // 5 minutes
		],

		'hostsByName' => [
			'deployment-db03' => '172.16.5.23:3306', # deployment-db03.eqiad.wmflabs
			'deployment-db04' => '172.16.5.5:3306', # deployment-db04.eqiad.wmflabs
		],

		'externalLoads' => [
			'cluster1' => [
				'172.16.5.23:3306' => 1,  # deployment-db03.eqiad.wmflabs, master
				'172.16.5.5:3306' => 3 , # deployment-db04.eqiad.wmflabs
			],

			'flow_cluster1' => [
				'172.16.5.23:3306' => 1,  # deployment-db03.eqiad.wmflabs, master
				'172.16.5.5:3306' => 3 , # deployment-db04.eqiad.wmflabs
			],

			'extension1' => [
				'172.16.5.23:3306' => 1,  # deployment-db03.eqiad.wmflabs, master
				'172.16.5.5:3306' => 3 , # deployment-db04.eqiad.wmflabs
			],
		],

		'templateOverridesByCluster' => [
			'cluster1' => [ 'blobs table' => 'blobs1' ],
			'flow_cluster1' => [ 'blobs table' => 'blobs_flow1' ],
		],
	];

	$wgDefaultExternalStore = [
		'DB://cluster1',
	];

	# No parser cache in beta yet
	$wmgParserCacheDBs = [];

} # end safe guard
