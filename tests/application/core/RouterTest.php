<?php


class RouterTest extends CI_UnitTestCase {

	public function __construct()
	{
		parent::__construct();
	}

	//--------------------------------------------------------------------

/*
	public function setUp()
	{
		// Ensure Consistent Routes
		$routes = array(
			'default_controller'	=> 'home',
			'404_override'			=> '',
		);

		$this->router->routes = $routes;

		// Ensure consisten Module Locations
		$locations = array(
			APP_DIR .'modules/',
			BF_DIR .'modules/'
		);
		$this->config->set_item('modules_locations', $locations);
	}

	//--------------------------------------------------------------------
*/
	public function tearDown()
	{
		Route::clear();
	}

	//--------------------------------------------------------------------

	public function test_is_loaded()
	{
		$this->assertTrue(class_exists('CI_Router'));
		$this->assertTrue(get_class($this->router) == 'CI_Router', 'Object is a '. get_class($this->router));
	}

	//--------------------------------------------------------------------

	/*
		ROUTE Tests

		These tests are primarily geared toward the Route methods, since the built-in
		CI Router has way too many dependencies to be able to easily test. Not even
		sure how I'd do it at the moment....

		CI's one huge downfall right there.
	 */

	public function test_route_works_like_ci_routes()
	{
		Route::create('posts/(:any)', 'posts/show/$1');
		Route::create('books', 'books/index');

		$this->assertEqual(array( 'posts/(:any)' => 'posts/show/$1', 'books' => 'books/index' ), Route::$routes);
	}

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// Verb Routes
	//--------------------------------------------------------------------

	public function test_get()
	{
		$_SERVER['REQUEST_METHOD'] = 'GET';

		Route::create('posts/(:any)', 'posts/show/$1');
		Route::get('books', 'books/index');

		$this->assertEqual(array('posts/(:any)' => 'posts/show/$1', 'books' => 'books/index'), Route::$routes);
	}

	//--------------------------------------------------------------------

	public function test_post()
	{
		$_SERVER['REQUEST_METHOD'] = 'POST';

		Route::create('posts/(:any)', 'posts/show/$1');
		Route::post('books', 'books/index');

		$this->assertEqual(array('posts/(:any)' => 'posts/show/$1', 'books' => 'books/index'), Route::$routes);
	}

	//--------------------------------------------------------------------

	public function test_put()
	{
		$_SERVER['REQUEST_METHOD'] = 'PUT';

		Route::create('posts/(:any)', 'posts/show/$1');
		Route::put('books', 'books/index');

		$this->assertEqual(array('posts/(:any)' => 'posts/show/$1', 'books' => 'books/index'), Route::$routes);
	}

	//--------------------------------------------------------------------

	public function test_delete()
	{
		$_SERVER['REQUEST_METHOD'] = 'DELETE';

		Route::create('posts/(:any)', 'posts/show/$1');
		Route::delete('books', 'books/index');

		$this->assertEqual(array('posts/(:any)' => 'posts/show/$1', 'books' => 'books/index'), Route::$routes);
	}

	//--------------------------------------------------------------------

	public function test_patch()
	{
		$_SERVER['REQUEST_METHOD'] = 'PATCH';

		Route::create('posts/(:any)', 'posts/show/$1');
		Route::patch('books', 'books/index');

		$this->assertEqual(array('posts/(:any)' => 'posts/show/$1', 'books' => 'books/index'), Route::$routes);
	}

	//--------------------------------------------------------------------

	public function test_head()
	{
		$_SERVER['REQUEST_METHOD'] = 'HEAD';

		Route::create('posts/(:any)', 'posts/show/$1');
		Route::head('books', 'books/index');

		$this->assertEqual(array('posts/(:any)' => 'posts/show/$1', 'books' => 'books/index'), Route::$routes);
	}

	//--------------------------------------------------------------------

	public function test_options()
	{
		$_SERVER['REQUEST_METHOD'] = 'OPTIONS';

		Route::create('posts/(:any)', 'posts/show/$1');
		Route::options('books', 'books/index');

		$this->assertEqual(array('posts/(:any)' => 'posts/show/$1', 'books' => 'books/index'), Route::$routes);
	}

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// Resourceful Routes
	//--------------------------------------------------------------------

	public function test_resources()
	{
		$_SERVER['REQUEST_METHOD'] = 'GET';

		Route::resources('books');
		$this->assertEqual(
				array(
					'books'		=> 'books/index',
					'books/new'	=> 'books/create',
					'books/([a-zA-Z0-9\-_]+)/edit' => 'books/edit/$1',
					'books/([a-zA-Z0-9\-_]+)' => 'books/show/$1',
				),
				Route::$routes
			);

		Route::clear();
		$_SERVER['REQUEST_METHOD'] = 'POST';

		Route::resources('books');
		$this->assertEqual(
				array(
					'books'	=> 'books/create'
				),
				Route::$routes
			);

		Route::clear();
		$_SERVER['REQUEST_METHOD'] = 'PUT';

		Route::resources('books');
		$this->assertEqual(
				array(
					'books/([a-zA-Z0-9\-_]+)' => 'books/update/$1',
				),
				Route::$routes
			);

		Route::clear();
		$_SERVER['REQUEST_METHOD'] = 'DELETE';

		Route::resources('books');
		$this->assertEqual(
				array(
					'books/([a-zA-Z0-9\-_]+)' => 'books/delete/$1',
				),
				Route::$routes
			);
	}

	//--------------------------------------------------------------------

	public function test_custom_controllers_in_resources()
	{
		$_SERVER['REQUEST_METHOD'] = 'POST';

		Route::resources('books', array('controller' => 'tomes'));
		$this->assertEqual(
				array(
					'books'	=> 'tomes/create'
				),
				Route::$routes
			);
	}

	//--------------------------------------------------------------------

	public function test_custom_module_in_resources()
	{
		$_SERVER['REQUEST_METHOD'] = 'POST';

		Route::resources('books', array('module' => 'tomes'));
		$this->assertEqual(
				array(
					'books'	=> 'tomes/books/create'
				),
				Route::$routes
			);
	}

	//--------------------------------------------------------------------

	public function test_custom_id_in_resources()
	{
		$_SERVER['REQUEST_METHOD'] = 'DELETE';

		Route::resources('books', array('constraint' => '(:num)'));
		$this->assertEqual(
				array(
					'books/(:num)' => 'books/delete/$1',
				),
				Route::$routes
			);
	}

	//--------------------------------------------------------------------


	//--------------------------------------------------------------------
	// Grouped Resources
	//--------------------------------------------------------------------

	public function test_grouped_resources()
	{
		$_SERVER['REQUEST_METHOD'] = 'GET';

		Route::group('admin', function(){
			Route::resources('books');
		});
		$this->assertEqual(
				array(
					'admin/books'		=> 'books/index',
					'admin/books/new'	=> 'books/create',
					'admin/books/([a-zA-Z0-9\-_]+)/edit' => 'books/edit/$1',
					'admin/books/([a-zA-Z0-9\-_]+)' => 'books/show/$1',
				),
				Route::$routes
			);
	}

	//--------------------------------------------------------------------

	public function test_grouped_works_with_singular_resources()
	{
		Route::group('admin', function() {
			Route::create('books', 'books/index');
		});

		$this->assertEqual(array('admin/books' => 'books/index'), Route::$routes);
	}

	//--------------------------------------------------------------------

	public function test_areas()
	{
		Route::area('admin', 'adminer');

		$this->assertEqual(
				array(
					'admin/(:any)/(:any)' => '$1/adminer/$2',
					'admin/(:any)'	=> '$1/adminer'
				),
				Route::$routes
			);
	}

	//--------------------------------------------------------------------
	//
	//--------------------------------------------------------------------
	// Named Routes
	//--------------------------------------------------------------------

	public function test_name_route()
	{
		Route::with_name('profile', 'users/profile');

		$this->assertTrue(isset(Route::$names['profile']));
		$this->assertEqual(Route::$names['profile'], 'users/profile');
	}

	//--------------------------------------------------------------------

	public function test_named()
	{
		Route::with_name('profile', 'users/profile');

		$this->assertEqual(Route::named('profile'), 'users/profile');
	}

	//--------------------------------------------------------------------

	public function test_named_url()
	{
		Route::with_name('profile', 'users/profile');

		$this->assertEqual(Route::named_url('profile'), 'users/profile');
	}

	//--------------------------------------------------------------------

	public function test_named_works_in_groups()
	{
		Route::group('admin', function() {
			Route::with_name('profile', 'users/profile');
		});

		$this->assertEqual(Route::named('profile'), 'admin/users/profile');
	}

	//--------------------------------------------------------------------


	//--------------------------------------------------------------------
	// Utility Functions
	//--------------------------------------------------------------------

	public function test_clear()
	{
		Route::$routes = array('books' => 'books/index');

		Route::clear();
		$this->assertEqual(array(), Route::$routes);
	}

	//--------------------------------------------------------------------

}