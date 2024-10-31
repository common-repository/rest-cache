<?php

namespace PerryRylance\WordPress\RESTCache;

require_once(__DIR__ . '/class.file-cache.php');

class FileStorer extends FileCache
{
	private static $createDirectoryNoticeBound = false;
	
	public function __construct()
	{
		FileCache::__construct();
		
		$this->createStorageDirectory();
	}
	
	private function createStorageDirectory()
	{
		if(is_dir($this->getRecordFolder()))
			return;
		
		if(!mkdir($this->getRecordFolder()) && !FileStorer::$createDirectoryNoticeBound)
		{
			add_action('admin_notices', array($this, 'onCreateDirectoryNotice'));
			FileStorer::$createDirectoryNoticeBound = true;
		}
	}
	
	public function isStoringRequest(\WP_REST_Request $request)
	{
		if(!is_dir($this->getRecordFolder()))
			return false;
			
		// TODO: Figure out why this is even necessary, .htaccess should catch this
		if(!preg_match('/GET/i', $request->get_method()))
			return false;
		
		if(!$this->isURIAllowed($this->getURI()))
			return false;
		
		$attributes = $request->get_attributes();
		
		// NB: As of WordPress 5.5, a permission callback is requred
		if(!isset($attributes['permission_callback']))
			return true;
		
		if($attributes['permission_callback'] == '__return_true')
			return true;
		
		return false;
	}
	
	public function store(\WP_REST_Response $result)
	{
		global $wpdb;
		
		$uri		= $this->getURI();
		
		// TODO: Check URI is allowed by rules
		
		$output		= wp_json_encode($result->jsonSerialize());
		
		$hash		= md5($uri);
		$file		= $this->getRecordFile($hash);
		
		// TODO: Store record in the database (via Laravel?)
		
		// NB: Temporary code
		global $wpdb;
		
		$stmt	= $wpdb->prepare("INSERT INTO {$wpdb->prefix}rest_cache_records 
			(uri, hash, filename, size, created, expires)
			VALUES 
			(%s, %s, %s, %d, NOW(), DATE_ADD(NOW(), INTERVAL " . $this->getExpiryInterval() . "))
		", array(
			$uri,
			$hash,
			$file,
			strlen($output)
		));
			
		if(!$wpdb->query($stmt))
			throw new \Exception('Failed to store record');
		
		file_put_contents($file, $output);
	}
	
	public function onCreateDirectoryNotice()
	{
		?>
		<div class="notice notice-error">
			<p>
				<i class="fas fa-hand-paper"></i>
			
				<strong>
					<?php
					_e('REST Cache:', 'rest-cache');
					?>
				</strong>
				<?php
				echo sprintf(
					__('We couldn\'t create a storage directory for cache records. Please try manually creating the folder %s.', 'rest-cache'),
					$this->getRecordFolder()
				);
				?>
			</p>
		</div>
		<?php
	}
}
