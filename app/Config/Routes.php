<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

// Authentication routes
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::attemptRegister');
$routes->get('logout', 'Auth::logout');

// Post routes
$routes->get('post/create', 'Post::create', ['filter' => 'auth']);
$routes->post('post/store', 'Post::store', ['filter' => 'auth']);
$routes->get('post/my-posts', 'Post::myPosts', ['filter' => 'auth']);
$routes->get('post/(:num)', 'Post::show/$1');
$routes->get('post/edit/(:num)', 'Post::edit/$1', ['filter' => 'auth']);
$routes->post('post/update/(:num)', 'Post::update/$1', ['filter' => 'auth']);
$routes->get('post/delete/(:num)', 'Post::delete/$1', ['filter' => 'auth']);

// Tag routes
$routes->get('tag/(:num)', 'Tag::posts/$1');
$routes->get('tags', 'Tag::index');

// Vote routes
$routes->post('vote', 'Vote::vote', ['filter' => 'auth']);

// Comment routes
$routes->post('comment/add', 'Comment::add', ['filter' => 'auth']);
$routes->get('comment/delete/(:num)', 'Comment::delete/$1', ['filter' => 'auth']);
//$routes->get('comment/approve/(:num)', 'Comment::approve/$1', ['filter' => 'admin']);
//$routes->get('comment/reject/(:num)', 'Comment::reject/$1', ['filter' => 'admin']);
//$routes->get('admin/pending-comments', 'Comment::pendingComments', ['filter' => 'admin']);

// User verification routes
$routes->get('verify-users', 'User::verifyUsers');
$routes->get('verify-user/(:num)', 'User::verifyUser/$1', ['filter' => 'admin']);
$routes->get('unverify-user/(:num)', 'User::unverifyUser/$1', ['filter' => 'admin']);
$routes->get('delete-user/(:num)', 'User::deleteUser/$1', ['filter' => 'admin']);
$routes->get('verification-history/(:num)', 'User::verificationHistory/$1', ['filter' => 'admin']);

// Profile routes
$routes->get('profile', 'Profile::index', ['filter' => 'auth']);
$routes->post('profile/update', 'Profile::update', ['filter' => 'auth']);
$routes->post('profile/change-password', 'Profile::changePassword', ['filter' => 'auth']);

// Admin routes
$routes->group('admin', ['filter' => 'admin'], function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('pending-posts', 'Admin::pendingPosts');
    $routes->get('approved-posts', 'Admin::approvedPosts');
    $routes->get('approve-post/(:num)', 'Admin::approvePost/$1');
    $routes->get('reject-post/(:num)', 'Admin::rejectPost/$1');
    $routes->get('delete-post/(:num)', 'Admin::deletePost/$1');
    $routes->get('manage-tags', 'Admin::manageTags');
    $routes->post('add-tag', 'Admin::addTag');
    $routes->get('edit-tag/(:num)', 'Admin::editTag/$1');
    $routes->post('update-tag/(:num)', 'Admin::updateTag/$1');
    $routes->get('delete-tag/(:num)', 'Admin::deleteTag/$1');
    $routes->get('announcements', 'Admin::announcements');
    $routes->get('create-announcement', 'Admin::createAnnouncement');
    $routes->post('store-announcement', 'Admin::storeAnnouncement');
    $routes->get('edit-announcement/(:num)', 'Admin::editAnnouncement/$1');
    $routes->post('update-announcement/(:num)', 'Admin::updateAnnouncement/$1');
    $routes->get('delete-announcement/(:num)', 'Admin::deleteAnnouncement/$1');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
