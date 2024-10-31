<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckForWordPressUser
{
	private function getWordPressAbsolutePath()
	{
		$dir = '../';
		$iter = 0;
		
		while(!file_exists("{$dir}wp-config.php"))
		{
			$dir .= '../';
			
			if(++$iter > 64)
				throw new \Exception("Maximum depth exceeded");
		}
		
		return $dir;
	}
	
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		$table		= env("DB_PREFIX") . "usermeta";
		
		if(!isset($_COOKIE['rest-cache-admin-key']))
			throw new \Exception("No key provided");
		
		// NB: Even though prepared statements are used below, the WordPress.org plugin team has requested sanitization here. This code strips any non-hex characters, so $key can be considered totally safe.
		$key		= preg_replace("/[^0-9A-F]/i", "", $_COOKIE['rest-cache-admin-key']);
		
		$result		= DB::table("usermeta")
			->select(["user_id"])
			->where("meta_key", "=", "rest-cache-admin-hash")
			->whereRaw("meta_value = sha2(CONCAT((
				SELECT meta_value FROM $table AS temp
				WHERE temp.user_id = $table.user_id
				AND meta_key = 'rest-cache-admin-salt'
			), ?), 256)", $key)
			->limit(1)
			->first();
		
		if(empty($result))
		{
			if(!$request->wantsJson())
			{
				if(!function_exists("wp_login_url"))
					return abort(400, "Web requests need to be made from within the WordPress framework");
				
				return redirect( wp_login_url() );
			}
			
			return abort(403, "Access denied");
		}
		
        return $next($request);
    }
}
