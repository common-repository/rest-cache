<?php

namespace PerryRylance\WordPress\RESTCache;

class EnvironmentManager
{
	public function __construct(FileStorer $fileStorer)
	{
		global $table_prefix;
		
		$debug = defined("WP_DEBUG") && WP_DEBUG;
		
		$file = REST_CACHE_DIR_PATH . 'service/.env';
		$vars = [
		
			"APP_NAME"		=> "rest-cache",
			"APP_ENV"		=> $debug ? "local" : "production",
			"APP_KEY"		=> "base64:VAITuVabw1cOSenUo00N5xBQVN6296CQ6SUqmhDScSA=",
			"APP_DEBUG"		=> $debug ? "true" : "false",
			"APP_URL"		=> plugin_dir_url(__DIR__) . "service/public",
			
			"LOG_CHANNEL"	=> "stack",
			
			"DB_CONNECTION"	=> "mysql",
			"DB_HOST"		=> DB_HOST,
			"DB_PORT"		=> "3306",
			"DB_DATABASE"	=> DB_NAME,
			"DB_USERNAME"	=> DB_USER,
			"DB_PASSWORD"	=> DB_PASSWORD,
			
			"DB_PREFIX"		=> $table_prefix,
			"DB_CHARSET"	=> DB_CHARSET,
			
			"RECORDS_DIR"	=> $fileStorer->getRecordFolder()
			
		];
		
		$string = "";
		
		foreach($vars as $key => $value)
			$string .= "$key=$value\n";
		
		file_put_contents($file, $string);
	}
}
