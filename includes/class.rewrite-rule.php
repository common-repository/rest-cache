<?php

namespace PerryRylance\WordPress\RESTCache;

class RewriteRule
{
	const GUID = "c8922699-19ff-4283-b638-377caf72cd5d";
	private $htaccessFilename;
	
	public function __construct()
	{
		$this->htaccessFilename = ABSPATH . ".htaccess";
		
		if(!$this->isPresent())
			$this->add();
	}
	
	public function isPresent()
	{
		$guid		= RewriteRule::GUID;
		
		return strpos(file_get_contents($this->htaccessFilename), "rest-cache-$guid") !== false;
	}
	
	public function remove()
	{
		if(!$this->isPresent())
			return;
		
		$htaccess	= file_get_contents($this->htaccessFilename);
		$guid		= RewriteRule::GUID;
		$regex		= "/# BEGIN rest-cache-$guid.+# END rest-cache-$guid\s*/ism";
		$htaccess	= preg_replace($regex, '', $htaccess);
		
		return file_put_contents($this->htaccessFilename, $htaccess);
	}
	
	public function add()
	{
		if($this->isPresent())
			return;
		
		$guid		= RewriteRule::GUID;
		$path		= substr(REST_CACHE_DIR_PATH, strlen($_SERVER['DOCUMENT_ROOT']));
		$shim		= "/" . trailingslashit($path) . "shim.php";
		
		// NB: Fix for Windows path \ making redirect rule ineffective
		if(defined('PHP_OS') && strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
			$shim	= str_replace('\\', '/', $shim);
		
		$block		= "# BEGIN rest-cache-$guid
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteCond %{REQUEST_METHOD} ^GET [NC]
RewriteCond %{QUERY_STRING} !skip_cache
RewriteRule ^wp-json $shim [L]
</IfModule>
# END rest-cache-$guid";
		
		$htaccess	= file_get_contents($this->htaccessFilename);
		$htaccess	= $block . "\r\n\r\n" . $htaccess;
		
		file_put_contents($this->htaccessFilename, $htaccess);
	}
}