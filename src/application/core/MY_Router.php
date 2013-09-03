<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . 'third_party/HMVC/Router.php';

class MY_Router extends HMVC_Router {

    /**
     * Ensures that the Route class' routes
     * are added to the routes in the parent classes.
     */
    // public function _parse_routes()
    // {
    //     $routes = Route::$routes;

    //     $this->routes = array_merge($this->routes, $routes);

    //     return parent::_parse_routes();
    // }

    // //--------------------------------------------------------------------

}

//--------------------------------------------------------------------

/**
 * Route class provides methods to be used within the routes config file
 * to enable a simpler syntax for some of the non-CI native methods.
 *
 * Thanks to Jamie Rumbelow and his wonderful Pigeon routing class for the
 * ideas for the HTTP Verb-based routing in use here.
 *
 * @package Sprint
 * @since  1.0
 */
class Route {

    // Our routes, ripe for the picking.
    public static $routes   = array();

    // Holds key/value pairs of named routes
    public static $names    = array();

    // Used for grouping routes together.
    public static $group    = null;

    // Holds the 'areas' of the site.
    public static $areas    = array();

    public static $route;

    //--------------------------------------------------------------------

    public function init(&$route=null)
    {
        self::$route = $route;
    }

    //--------------------------------------------------------------------


    /**
     * Attaches the routes in the system to the global $config array.
     * This method should be called in the routes config file after
     * specifying
     * @return [type] [description]
     */
    public function map($route)
    {

        foreach (self::$routes as $from => $to)
        {
            $route[$from] = str_replace('{default_controller}', $route['default_controller'], $to);
        }

        return $route;
    }

    //--------------------------------------------------------------------


    /**
     * A single point to do the actual routing. Can be used in place of
     * the $route array, if desired. Primarily used by our HTTP-verb based
     * routing methods.
     */
    public static function create($from, $to)
    {
        // At the moment all we're doing is storing it
        // for retrieval by the CI_Router class. however, this
        // leaves things in place should we want to do additional
        // processing in the future.

        if (!is_null(static::$group))
        {
            $from = static::$group .'/'. $from;
        }

        self::$routes[$from] = $to;
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Named Routes
    //--------------------------------------------------------------------

    /**
     * Allows for routes to have names that can be referenced
     * later for better code portability.
     *
     * If you want to save a route to the users/profile URL,
     * you could save it with the name 'profile'. Then, in other
     * code you could simply refer to it by name:
     *
     *     $url = Route::named('profile');
     *
     * @param  string $name The name to refer to the route by.
     * @param  string $to   The route itself.
     */
    public static function with_name($name, $to)
    {
        // If we're grouped, prepend the group
        if (!empty(self::$group))
        {
            $to = self::$group .'/'. $to;
        }

        // Store it separately for quick access later
        self::$names[$name] = $to;
    }

    //--------------------------------------------------------------------

    /**
     * Returns the value of a named route.
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public static function named($name)
    {
        if (isset(self::$names[$name]))
        {
            return self::$names[$name];
        }

        return null;
    }

    //--------------------------------------------------------------------

    /**
     * Returns the full URL for a named route.
     *
     * @param  string $name     The name of route to get the URL for.
     * @return string           The URL of the route. If no named route
     *                          exists, returns NULL.
     */
    public static function named_url($name)
    {
        if (!function_exists('site_url'))
        {
            $ci =& get_instance();
            $ci->load->helper('url');
        }

        if (!empty($name) && isset(self::$names[$name]))
        {
            return self::$names[$name];
        }

        return NULL;
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Grouping Routes
    //--------------------------------------------------------------------

    /**
     * Group a series of routes under a single URL segment. This is handy
     * for grouping items into an admin area, like:
     *
     *     Route::group('admin', function() {
     *          Route::resources('users');
     *     });
     *
     * @param  [type]  $name     [description]
     * @param  Closure $callback [description]
     * @return [type]            [description]
     */
    public static function group($name, Closure $callback)
    {
        // To register a route, we'll set a flag so that our router
        // so it will see the groupname.
        static::$group = $name;

        call_user_func($callback);

        // Make sure to clear the group name so we don't accidentally
        // group any ones we didn't want to.
        static::$group = null;
    }

    //--------------------------------------------------------------------


    //--------------------------------------------------------------------
    // HTTP Verb-based routing
    //--------------------------------------------------------------------
    // Routing works here becase, as the routes config file is read in,
    // the various HTTP verb-based routes will only be added to the in-memory
    // routes if it is a call that should respond to that verb.
    //

    public static function get($from, $to)
    {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'GET')
        {
            self::create($from, $to);
        }
    }

    //--------------------------------------------------------------------

    public static function post($from, $to)
    {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST')
        {
            self::create($from, $to);
        }
    }

    //--------------------------------------------------------------------

    public static function put($from, $to)
    {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'PUT')
        {
            self::create($from, $to);
        }
    }

    //--------------------------------------------------------------------

    public static function delete($from, $to)
    {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'DELETE')
        {
            self::create($from, $to);
        }
    }

    //--------------------------------------------------------------------

    public static function head($from, $to)
    {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'HEAD')
        {
            self::create($from, $to);
        }
    }

    //--------------------------------------------------------------------

    public static function patch($from, $to)
    {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'PATCH')
        {
            self::create($from, $to);
        }
    }

    //--------------------------------------------------------------------

    public static function options($from, $to)
    {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            self::create($from, $to);
        }
    }

    //--------------------------------------------------------------------

    /**
     * Creates HTTP-verb based routing for a controller.
     *
     * Generates the following routes, assuming a controller named 'photos':
     *
     *      Route::resources('photos');
     *
     *      Verb    Path            Action      used for
     *      ------------------------------------------------------------------
     *      GET     /photos         index       displaying a list of photos
     *      GET     /photos/new     new         return an HTML form for creating a photo
     *      POST    /photos         create      create a new photo
     *      GET     /photos/{id}    show        display a specific photo
     *      GET     /photos/{id}/edit   edit    return the HTML form for editing a single photo
     *      PUT     /photos/{id}    update      update a specific photo
     *      DELETE  /photos/{id}    delete      delete a specific photo
     *
     * @param  string $name The name of the controller to route to.
     * @param  array $options An list of possible ways to customize the routing.
     */
    public static function resources($name, $options=array())
    {
        if (empty($name))
        {
            return;
        }

        // In order to allow customization of the route the
        // resources are sent to, we need to have a new name
        // to store the values in.
        $new_name = $name;

        // If a new controller is specified, then we replace the
        // $name value with the name of the new controller.
        if (isset($options['controller']))
        {
            $new_name = $options['controller'];
        }

        // If a new module was specified, simply put that path
        // in front of the controller.
        if (isset($options['module']))
        {
            $new_name = $options['module'] .'/'. $new_name;
        }

        // In order to allow customization of allowed id values
        // we need someplace to store them.
        $id = '([a-zA-Z0-9\-_]+)';

        if (isset($options['constraint']))
        {
            $id = $options['constraint'];
        }

        self::get($name, $new_name .'/index');
        self::get($name .'/new', $new_name .'/create');
        self::get($name .'/'. $id .'/edit', $new_name .'/edit/$1');
        self::get($name .'/'. $id, $new_name .'/show/$1');
        self::post($name, $new_name .'/create');
        self::put($name .'/'. $id, $new_name .'/update/$1');
        self::delete($name .'/'. $id, $new_name .'/delete/$1');
    }

    //--------------------------------------------------------------------

    /**
     * Lets the system know about different 'areas' within the site, like
     * the admin area, that maps to certain controllers.
     *
     * Example:
     *      Route::area('admin');
     *
     *      /admin/photos       - Routes to photos modules, admin controller, index method
     *
     * @param  string $area       The name of the area.
     * @param  string $controller The controller name to look for.
     */
    public static function area($area, $controller)
    {
        global $route;

        // Save the area so we can recognize it later.
        self::$areas[$area] = $controller;

        // Create routes for this area.
        self::create($area .'/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)', '$1/'. $controller .'/$2/$3/$4/$5/$6');
        self::create($area .'/(:any)/(:any)/(:any)/(:any)/(:any)', '$1/'. $controller .'/$2/$3/$4/$5');
        self::create($area .'/(:any)/(:any)/(:any)/(:any)', '$1/'. $controller .'/$2/$3/$4');
        self::create($area .'/(:any)/(:any)/(:any)', '$1/'. $controller .'/$2/$3');
        self::create($area .'/(:any)/(:any)', '$1/'. $controller .'/$2');
        self::create($area .'/(:any)', '$1/'. $controller);

        // Setup a home controller with the name of the area and the default controller
        self::create($area, $area .'/{default_controller}');
    }

    //--------------------------------------------------------------------

    /**
     * Returns the name of the area based on the controller name.
     *
     * @param  string $controller The name of the controller
     * @return string             The name of the corresponding area
     */
    public function get_area_name($controller)
    {
        foreach (self::$areas as $area => $cont)
        {
            if ($controller == $cont)
            {
                return $area;
            }
        }

        return NULL;
    }

    //--------------------------------------------------------------------


    /**
     * Empties all named and un-named routes from the system.
     *
     * @return void
     */
    public static function clear()
    {
        self::$routes   = array();
        self::$names    = array();
        self::$group    = null;
        self::$areas    = array();
    }

    //--------------------------------------------------------------------

}
// END Route class