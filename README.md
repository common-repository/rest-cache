# rest-cache
A very fast REST cache for WordPress. Very good compliment to WP Google Maps and any other plugins which use the REST API.

## Installation
Install through the WordPress plugins menu (when this plugin becomes available there).

Alternatively download the ZIP from this repo (or clone into your plugins folder) and then activate the plugin.

## Development
In order to work on the plugins JavaScript, you'll need to run `npm install`, and run `gulp` in order to watch for changes to the plugins source files.

You'll also need to run `composer install` in _both_ the plugin directory and inside the `/service` directory, as there are development dependencies which aren't included in the release. Use `--no-dev` for production builds.