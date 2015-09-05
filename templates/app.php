<?hh

// Load in external libraries
require 'vendor/autoload.php';

// Set the app's timezone to central
date_default_timezone_set('America/Chicago');

if(!file_exists('config.ini')) {
  error_log("Config file does not exist");
  die;
}

$configs = parse_ini_file('config.ini', true);

Config::initialize($configs);

// Prepare the databae
DB::$user = Config::get('DB')['user'];
DB::$password = Config::get('DB')['password'];
DB::$dbName = Config::get('DB')['name'];
DB::$port = Config::get('DB')['port'];

Plugins::initialize($configs);

// Get the user session going
Session::init();

// Call the dispatcher to do its thing
Route::dispatch(
  parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
  $_SERVER['REQUEST_METHOD']
);

