<?php

namespace PerryRylance\WordPress\RESTCache;

use PerryRylance\WordPress\RESTCache\FileServer;

require_once('includes/class.file-server.php');

$fileServer = new FileServer();

if($fileServer->isServingCurrentURI())
{
	$fileServer->serve();
	exit;
}

chdir('../../..');
require('index.php');
