<?php
/*
 * #### Load controller:
 *
 * Route::set('router_name', 'controller_name');
 *
 *
 * #### Load method:
 *
 * Route::set('router_name', 'controller_name@method');
 *
 *
 * #### Set function:
 *
 * Route::set('router_name', function(){
 *      do your stuff here..
 *      u can also passing var from the url like the in controllers.
 *
 *      if you like to load controller or method you can return it as str like this:
 *      return 'controller_name'; OR 'controller_name@method';
 * });
 *
 *
 * #### block Access to controller:
 *
 * Route::block($controller);
 *
 *
 * #### block Access to specific method in controller
 *
 * Route::block($controller, $method);
 *
 */
