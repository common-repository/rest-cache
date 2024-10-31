=== REST Cache ===
Contributors: PerryRylance
Donate link: https://perryrylance.com/rest-cache
Tags: REST cache, REST, cache
Requires at least: 4.4.0
Tested up to: 5.5.1
Requires PHP: 5.4
Stable tag: trunk
License: Apache License 2.0

A very fast REST cache for WordPress, powered by Laravel. This project is more of a learning exercise for myself, rather than a production plugin, however it should work well. Very good compliment to WP Google Maps and any other plugins which use the REST API.

== Description ==

This REST cache uses a file-based caching system which is extremely fast compared to other similar plugins and will serve up records usually in a matter of milliseconds.

You can manage expiry times, clear records manually, and add rules to include and exclude specific REST URL's, with support for regex pattern matching in the rule set.

A rest_cache_clear action is provided for plugin developers, the action accepts a regular expression and can be used to clear the cache for your plugins REST endpoints.