<?php

namespace PerryRylance\WordPress\RESTCache;

class FileCache
{
	private $rules;
	
	public function __construct()
	{
		$file = dirname(__DIR__) . '/service/public/rules.json';
		
		if(file_exists($file))
			$this->rules = json_decode( file_get_contents($file) );
	}
	
	protected function array_map_recursive($function, &$data)
	{
		foreach ($data as $i => $item)
			$data[$i] = is_array($item) ? $this->array_map_recursive($function, $item) : $function($item);
		
		return $data;
	}
	
	protected function getURI()
	{
		$uri	= $_SERVER['REQUEST_URI'];
		
		if(!empty($_GET))
		{
			$arr = array_merge(array(), $_GET);
			
			// NB: Sanitization added at the request of the WordPress.org plugin team. I don't anticipate the need to include HTML or XML in a GET request however doing so may lead to odd behaviour. This may also lead to URL's which are different, but equivalent after sanitization yielding the same cache record. Please keep an eye on this.
			// NB: The WordPress sanitization functions are not necessarily available at this point (when the record is being served), so PHP functions are used instead.
			$arr = $this->array_map_recursive(function($input) {
				
				$output		= stripslashes($input);
				
				// NB: Sanitize tags by converting special characters, sanitize invalid UTF-8 by substituting characters
				$ouput		= htmlspecialchars($input, ENT_NOQUOTES | ENT_SUBSTITUTE | ENT_DISALLOWED);
				
				return $output;
				
			}, $arr);
			
			$uri .= "?" . http_build_query($arr);
		}
		
		return $uri;
	}
	
	public function getRecordFolder()
	{
		if(!defined('ABSPATH'))
			$uploads_dir 	= '../../uploads';
		else
			$uploads_dir	= wp_upload_dir()['basedir'];
		
		$cache_dir			= "$uploads_dir/rest-cache";
		
		return $cache_dir;
	}
	
	protected function getRecordFile($hash)
	{
		if(!(strlen($hash) == 32 && ctype_xdigit($hash)))
			throw new \Exception("Argument must be an MD5 hash");
		
		return $this->getRecordFolder() . "/$hash.json";
	}
	
	protected function getExpiryInterval()
	{
		global $restCachePlugin;
		
		$amount		= $restCachePlugin->settings->{"record-expiry-interval-amount"};
		$type		= $restCachePlugin->settings->{"record-expiry-interval-type"};
		
		if(empty($amount) || empty($type))
			return "1 DAY";
		
		return "$amount $type";
	}
	
	protected function isURIAllowed($uri)
	{
		$allowed = true;
		
		foreach($this->rules as $obj)
		{
			if($obj->regex)
				$uriMatchesPattern = preg_match("@{$obj->pattern}@", $uri);
			else
				$uriMatchesPattern = stripos($uri, $obj->pattern) !== false;
			
			if($obj->behaviour == "exclude" && $uriMatchesPattern)
				$allowed = false;
			else if($obj->behaviour == "include" && $uriMatchesPattern)
				$allowed = true;
		}
		
		if(!$allowed)
			return false;
		
		return true;
	}
}