<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//--------------------------------------------------------------------
// Auto Migrate?
//--------------------------------------------------------------------
// We can automatically run any outstanding migrations in the core,
// the application and the modules themselves if this is set to TRUE.
//
    $config['auto_migrate'] = true;

//--------------------------------------------------------------------
// Authentication
//--------------------------------------------------------------------
//
    $config['auth.allowed_drivers'] = array('auth_sprintauth');

    $config['auth.default_driver']  = 'sprintauth';

//--------------------------------------------------------------------
// Modules
//--------------------------------------------------------------------
//
    $config['modules_locations'] = array(
        APPPATH .'modules/'
    );

//--------------------------------------------------------------------
// Caching
//--------------------------------------------------------------------
// Sets the default types of caching used throughout the site. Possible
// choices are:
//      - apc
//      - file
//      - memcached
//      - dummy
//
// If you don't wish to use any caching in your environment, set it
// to dummy.
//
// The cache types can be overriedden as class values within each
// controller.
//
    $config['cache_type']           = 'dummy';
    $config['backup_cache_type']    = 'dummy';

