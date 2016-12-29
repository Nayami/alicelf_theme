<?php
session_start();

require_once dirname(__FILE__) . "/vendor/autoload.php";

// Theme partials
foreach ( glob( get_template_directory() . "/partials/*.php" ) as $filename )
	require_once( $filename );