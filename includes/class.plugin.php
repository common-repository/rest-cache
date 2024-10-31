<?php

namespace PerryRylance\WordPress\RESTCache;

use PerryRylance\WordPress\Plugin as Base;
use PerryRylance\WordPress\JsonOption;
use PerryRylance\DataTable;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

require_once(REST_CACHE_DIR_PATH . 'includes/class.rewrite-rule.php');
require_once(REST_CACHE_DIR_PATH . 'includes/class.file-cache.php');
require_once(REST_CACHE_DIR_PATH . 'includes/class.file-server.php');
require_once(REST_CACHE_DIR_PATH . 'includes/class.file-storer.php');
require_once(REST_CACHE_DIR_PATH . 'includes/class.environment-manager.php');

class Plugin extends Base
{
	private $_settings;
	private $_rewriteRule;
	
	public function __construct()
	{
		$this->_rewriteRule			= new RewriteRule();
		$this->_fileStorer			= new FileStorer();
		$this->_environmentManager	= new EnvironmentManager($this->_fileStorer);
		
		Base::__construct();
		
		add_action('init', array($this, 'onInit'));
		
		if(!$this->_rewriteRule->isPresent())
			add_action('admin_notices', array($this, 'onRewriteRuleNotice'));
		else
			add_filter('rest_post_dispatch', array($this, 'onRESTPostDispatch'), 10, 3);
		
		// Deprecated: Legacy, the prefix should come first
		add_action('clear_rest_cache', array($this, 'onClearRESTCache'), 10, 1);
		
		// NB: The actual hook
		add_action('rest_cache_clear', array($this, 'onClearRESTCache'), 10, 1);
	}
	
	public function __get($name)
	{
		switch($name)
		{
			case "settings":
				return $this->{"_$name"};
				break;
		}
	}
	
	public function getPluginSlug()
	{
		return "rest-cache";
	}
	
	public function getPluginDirPath()
	{
		return plugin_dir_path(__DIR__);
	}
	
	public function isAdminPage()
	{
		return isset($_GET['page']) && $_GET['page'] == 'rest-cache';
	}
	
	public static function sanitizeAuthKey($key)
	{
		return preg_replace("/[^0-9A-F]/i", "", $key);
	}
	
	public function onActivate()
	{
		Parent::onActivate();
		
		$this->setAuthCookie();
		
		$url	= REST_CACHE_DIR_URL . "service/public/api/install";
		
		$client	= new Client([
			'timeout' => 30
		]);
		
		// NB: Sanitize cookie by removing any non-hex characters, as per WordPress.org plugin teams request
		$key	= Plugin::sanitizeAuthKey($_COOKIE["rest-cache-admin-key"]);
		
		$jar	= CookieJar::fromArray([
			'rest-cache-admin-key' => $key
		], $_SERVER['SERVER_NAME']);
		
		$response = $client->request('GET', $url, ['cookies' => $jar]);
		
		$json = json_decode( $response->getBody()->getContents() );
		
		if($json->success != true)
			throw new \Exception("Failed to install the service");
	}
	
	public function onInit()
	{
		$this->purgeExpiredRecords();
		$this->settings = new JsonOption("rest-cache-settings");
		
		if(!$this->isAdminPage())
			return;
		
		$this->setAuthCookie();
	}
	
	public function onAdminMenu()
	{
		add_menu_page(
			"REST Cache", 
			__("REST Cache", 'rest-cache'), 
			"manage_options", 
			"rest-cache", 
			array($this, 'onAdminPage'),
			'dashicons-performance'
		);
	}
	
	public function onAdminPage()
	{
		include(REST_CACHE_DIR_PATH . 'service/public/index.php');
		
		wp_enqueue_script('jquery-ui');
		wp_enqueue_script('jquery-ui-tabs');
		
		wp_enqueue_style('jquery-ui', REST_CACHE_DIR_URL . 'lib/jquery-ui.css');
		wp_enqueue_style('jquery-ui-redmond', REST_CACHE_DIR_URL . 'lib/theme.css');
		
		wp_enqueue_script('datatables', REST_CACHE_DIR_URL . 'node_modules/@perry-rylance/data-table/lib/jquery.dataTables.min.js');
		wp_enqueue_style('datatables', REST_CACHE_DIR_URL . 'node_modules/@perry-rylance/data-table/lib/jquery.dataTables.min.css');
		
		wp_enqueue_style('fontawesome5', REST_CACHE_DIR_URL . "lib/fontawesome/css/all.css");
		
		wp_enqueue_style('rest-cache-admin', REST_CACHE_DIR_URL . 'css/admin.css');
		wp_enqueue_script('rest-cache-admin', REST_CACHE_DIR_URL . 'js/dist/entry.js', array('jquery-ui-tabs'));
	}
	
	private function setAuthCookie()
	{
		$user		= get_current_user_id();
		$salt		= bin2hex( openssl_random_pseudo_bytes(64) );
		$key		= bin2hex( openssl_random_pseudo_bytes(64) );
		$hash		= hash('sha256', $salt . $key);
		
		update_user_meta(
			get_current_user_id(),
			'rest-cache-admin-salt',
			$salt
		);
		
		update_user_meta(
			get_current_user_id(),
			'rest-cache-admin-hash',
			$hash
		);
		
		setcookie("rest-cache-admin-key", $key, time() + 60 * 60 * 24, "/");
		
		// NB: Sanitize cookie by removing any non-hex characters, as per WordPress.org plugin teams request
		$_COOKIE["rest-cache-admin-key"] = Plugin::sanitizeAuthKey($key);
	}
	
	// NB: Quick and dirty - this should be done in Laravel, but the release is overdue. Consider refactoring this.
	private function purgeExpiredRecords()
	{
		global $wpdb;
		
		$table		= $wpdb->prefix . "rest_cache_records";
		$qstr		= "SELECT id, filename FROM $table WHERE expires < NOW()";
		$records	= $wpdb->get_results($qstr);
		$ids		= [];
		
		foreach($records as $obj)
		{
			if(!unlink($obj->filename))
			{
				add_action('admin_notices', function() use ($filename) {
					?>
					<div class="notice notice-warning">
						<p>
							<strong>
								REST Cache:
							</strong>
							Failed to delete the expired record stored at <?php esc_html_e($filename); ?>
						</p>
					</div>
					<?php
				});
				
				continue;
			}
			
			$ids []= $obj->id;
		}
		
		if(empty($ids))
			return;
		
		$ids		= implode(",", $ids);
		
		$wpdb->query("DELETE FROM $table WHERE id IN ($ids)");
	}
	
	public function onDeactivate()
	{
		$this->_rewriteRule->remove();
	}
	
	public function onRewriteRuleNotice()
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
				_e('We couldn\'t find the REST Cache redirect rules in your .htaccess file. Please try de-activating and re-activating the plugin', 'rest-cache');
				?>
			</p>
		</div>
		<?php
	}
	
	public function onRESTPostDispatch(\WP_REST_Response $result, \WP_REST_Server $server, \WP_REST_Request $request)
	{
		if(defined('WP_DEBUG') && function_exists('xdebug_disable'))
			xdebug_disable();
		
		$fileStorer = new FileStorer();
		
		if($fileStorer->isStoringRequest($request))
			$fileStorer->store($result);
		
		return $result;
	}
	
	public function onClearRESTCache($regex)
	{
		if(!is_string($regex) || @preg_match("@$regex@", '') === false)
			throw new \Exception('Argument is not a valid regular expression');
		
		global $wpdb;
		
		$table		= $wpdb->prefix . "rest_cache_records";
		$qstr		= "SELECT id, filename FROM $table WHERE url REGEXP ?";
		$stmt		= $wpdb->prepare($qstr, [$regex]);
		$records	= $wpdb->get_results($qstr);
		$ids		= [];
		
		foreach($records as $obj)
		{
			if(!unlink($obj->filename))
				trigger_error("Failed to remove {$obj->filename} upon action");
			
			$ids []= $obj->id;
		}
		
		if(empty($ids))
			return;
		
		$ids		= implode(",", $ids);
		
		$wpdb->query("DELETE FROM $table WHERE id IN ($ids)");
	}
}

$restCachePlugin = new Plugin();
