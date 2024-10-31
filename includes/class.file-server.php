<?php

namespace PerryRylance\WordPress\RESTCache;

require_once(__DIR__ . '/class.file-cache.php');

class FileServer extends FileCache
{
	private $rules;
	
	protected function isURIAllowed($uri)
	{
		if(!FileCache::isURIAllowed($uri))
			return false;
		
		$hash	= md5($uri);
		$file	= $this->getRecordFile($hash);
		
		return is_file($file);
	}
	
	public function isServingCurrentURI()
	{
		$uri = $this->getURI();
		return $this->isURIAllowed($uri);
	}
	
	public function serve()
	{
		$uri	= $this->getURI();
		$hash	= md5($uri);
		$file	= $this->getRecordFile($hash);
		
		// Send file
		header("Content-type: application/json; charset=UTF-8");
		header("X-Robots-Tag: noindex");
		header("X-Content-Type-Options: nosniff");
		header("X-Served-By: rest-cache");
		
		// TODO: Send expiry time
		
		// NB: Taken from https://stackoverflow.com/questions/138374/how-do-i-close-a-connection-early with gratitude
		ob_end_clean();
		header("Connection: close");
		ignore_user_abort(true); // just to be safe
		ob_start();
		
		readfile($file);
		
		$size = ob_get_length();
		header("Content-Length: $size");
		
		ob_end_flush(); // Strange behaviour, will not work
		flush(); // Unless both are called !
		
		define("REST_CACHE_HIT_RECORD_HASH", $hash);
		include(dirname(__DIR__) . "/service/public/index.php");
		
		exit;
	}
}
