# Routes in SprintPHP

Sprint extends the core routing capbilities of CodeIgniter's router to provide much more flexible types of routing. This is heavily based off of Jamie Rumbelow's excellent [Pigeon library](https://github.com/jamierumbelow/pigeon).

All of the standard [CodeIgniter Routing](http://ellislab.com/codeigniter/user-guide/general/routing.html) capabilities still exist. In addition, you can:

- Route to module controllers
- Named routes for easier access throughout your other modules
- HTTP verb-based routing
- Restful resources
- Scoped routes

## Named Routes

Named routes make referring to locations in your app simple and less error-prone. If used well, you could completely restructure your application by simply changing the route names. You may specify a name for a route like:

    Route::with_name('profile', 'users/profile');

The name must be the first part of the URI, and is separated from the uri string by a double colon (::). In this example, the name is <tt>profile</tt> and the uri is <tt>users/edit_profile</tt>.

Now you can use the route's name when generaint URL's or redirects.

    $url = Route::named('profile');

    redirect_to_route('profile');

You can get the full site url for a named route with the following function:

    $url = Route::named_url('profile');

Would return:

    http://mysite.com/users/profile

If no name had been declared for that route in the past, then NULL would be returned by this function.


## HTTP Verb Routing

To make building REST-based routing simpler and more consistent, you can use the

    Route::resources('controller_name');

This function will automatically create RESTful resources for the common HTTP verbs. In this example, <tt>controller_name</tt> is the name of the controller you want to map the resources to. If you controller is named <tt>photos</tt>, you would call it like:

    Route::resources('photos');

If the <tt>photos</tt> controller is part of the <tt>Gallery</tt> module, then you would route it like:

    Route::resources('gallery/photos');

This would map the resources to the <tt>Photos</tt> controller, like:

HTTP Verb   |  Path  			|  action   |  used_for
------------|-------------------|-----------|----------------
GET 		| /photos 			| index 	| display a list of photos
GET 		| /photos/new		| new		| return an HTML form for creating a new photo
POST		| /photos 			| create	| create a new photo
GET 		| /photos/{id} 		| show 		| display a specific photo
GET 		| /photos/{id}/edit | edit 		| return the HTML for editing a single photo
PUT 		| /photos/{id} 		| update	| update a specific photo
DELETE 		| /photos/{id} 		| destroy 	| delete a specific photo


You can also set a single verb-based route with any of the route methods:

    Route::get('from', 'to');
    Route::post('from', 'to');
    Route::put('from', 'to');
    Route::delete('from', 'to');
    Route::head('from', 'to');
    Route::patch('from', 'to');
    Route::options('from', 'to');

These routes will then only be available when the corresponding HTTP verb is used to initiate the call.


## Customizing Resourceful Routes

While the standard naming convention provided by the <tt>resources</tt> Route method will often serve you well, you may find that you need to customize the route
to easily control where your URL's route to.

### Specifying a controller to use

You can pass an array of options into the <tt>resources</tt> method as the second parameter. By specifying a <tt>controller</tt> key, you will tell the router to replace all instances of the original route with the defined controller, like:

    Route::resources('photos', array('controller' => 'images'));

Will recognize incoming paths beginning with <tt>/photos</tt> but will route to the <tt>images</tt> controller:

### Specifying the module to use

You can also specify a module to use in the options array by passing a <tt>module</tt> key. This is helpful when the module and controller share different names.

    Route::resources('photos', array('module' => 'gallery', 'controller' => 'images'));

Will recognize incoming paths beginning with <tt>/photos</tt> but will route to the <tt>gallery/images</tt> module and controller.

### Constraining the {id} format

By default, the {id} used in the routing allows any letter, lower- or upper-case, any digit (0-9), a dash (-) and an underscore(_). If you need to restrict the {id} to another format, you may use the <tt>constraint</tt> option to pass a new, valid, format string:

    Route::resources('photos', array('constraint' => '(:num)'));

 Would restrict the {id} to be only numerals, while:

    Route:resources('photos', array('constraint' => '([A-Z][A-Z][0-9]+)'));

would restrict the {id} to be something like RR27.


## Grouping Routes

Scoped routes allow you to group routes under a specific area of the website. This is commonly done to group certain routes into an admin area of your site.

    Route::group('admin', function (){
    	Route::create('login', 'users/login');
    	Route::resources('users');
    });

Would create the following resources:

    admin/login = users/login

as well as mapping the following resources:

HTTP Verb   |  Path  			        |  action   |  used_for
------------|---------------------------|-----------|----------------
GET 		| /admin/users 				| index 	| display a list of users
GET 		| /admin/users/new			| new		| return an HTML form for creating a new user
POST		| /admin/users 				| create	| create a new user
GET 		| /admin/users/{id} 		| show 		| display a specific user
GET 		| /admin/users/{id}/edit  	| edit 		| return the HTML for editing a single user
PUT 		| /admin/users/{id} 		| update	| update a specific user
DELETE 		| /admin/users/{id} 		| destroy 	| delete a specific user

Grouping routes does not enforce any specific controller naming scheme. However, you might want to separate your grouped methods into a different controller within your module for consistency.

## Routing Areas

When you want to map an entire area of your website to map to a specific controller, you can use the <tt>area</tt> method.

    Route::area('area', 'controller');

For example, to map all /admin routes to the 'admin' controller in any module, you would do something like: 

    Route::area('admin', 'admin');
    
This would create the following routes: 

    admin/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)	=> $1/admin/$2/$3/$4/$5/$6
    admin/(:any)/(:any)/(:any)/(:any)/(:any) 			=> $1/admin/$2/$3/$4/$5
    admin/(:any)/(:any)/(:any)/(:any) 						=> $1/admin/$2/$3/$4
    admin/(:any)/(:any)/(:any)	 								=> $1/admin/$2/$3
    admin/(:any)/(:any) 											=> $1/admin/$2
    admin/(:any) 														=> $1/admin
    admin 																	=> admin/{default_controller}