<?php
/**
 * Voter Guide project application and api routes.
 *
 * @author rbrisita Robert Brisita <robert.brisita@gmail.com>
 */

/**
 * Application Route Patterns
 * name = regex pattern
 */
\Laravel\Routing\Router::$patterns['(:yyyy)'] = '([0-9]{4})';
\Laravel\Routing\Router::$patterns['(:mm-dd)'] = '([0-9]{2}-[0-9]{2})';
\Laravel\Routing\Router::$patterns['(:region)'] = '(bronx|brooklyn|connecticut|hudson-valley|long-island|new-jersey|westchester)';

// Application
Route::get('/', 'app@index');
Route::get('/test', 'app@test');
Route::get('/(:yyyy)', 'app@year');	// YYYY
Route::get('/(:yyyy)/(:mm-dd)', 'app@date'); // YYYY/MM-DD
Route::get('(:region)/vg/(:yyyy)', 'app@region');
Route::get('(:region)/vg/(:yyyy)/(:mm-dd)', 'app@region');

// Candidates
Route::get('/candidate/(:num)', 'app@candidate');
Route::get('/candidate/(:any?)', 'app@candidate'); // W.I.P.

// API
Route::get('/api', 'api@index');
Route::get('/api/(:yyyy)/all', 'api@year');	// YYYY
Route::get('/api/(:yyyy)', 'api@date');	// YYYY
Route::get('/api/(:yyyy)/(:mm-dd)', 'api@date'); // YYYY/MM-DD
Route::get('(:region)/vg/api/(:yyyy)', 'api@region');
Route::get('(:region)/vg/api/(:yyyy)/(:mm-dd)', 'api@region');

/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application.
|
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});


// Filters
Route::filter('before', function()
{
	// Only all news organization URLs
	// Check domain
	$allowedDomains = array('new-site-1.com', 'new-site-2.com', 'new-site-3.com');
	if(!SiteLayoutManager::allowedDomains($allowedDomains))
	{
		return Response::error('404');
	}

	/**
	 * For further restrictions one could also use:
	 * SiteLayoutManager::allowedSubDomains($allowedSubDomainsArr)
	 * SiteLayoutManager::allowedRegions($allowedRegionsArr)
	 *
	 * Example:
	 * $allowedRegions = array('long-island');
	 * if(!SiteLayoutManager::allowedRegions($allowedRegions))
	 * {
	 *	 if(!SiteLayoutManager::supportCall())
	 *	 {
	 *		 return Response::error('404');
	 *	 }
	 * }
	 */
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to('login');
});
