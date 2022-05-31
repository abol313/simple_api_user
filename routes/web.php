<?php

/** @var \Laravel\Lumen\Routing\Router $router */

//use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$router->get('/', function () use ($router) {
    return $router->app->version();
});


//handle the users C.R.U.D.
//using of routings

// create (put)    : http://localhost:8000/user
// read (get)      : http://localhost:8000/user
// update (put)    : http://localhost:8000/user/{id}
// delete (delete) : http://localhost:8000/user/{id}
$router->group(['prefix' => '/user' ], function () use ($router) {
    //Sign up user
    $router->post('/sign_up','UserController@signUp');

    //Sign in user
    $router->post('/sign_in','UserController@signIn');

    //C Create
    //request method: put
    //create a user and add that
    // i.e. cURL
    // curl -X PUT http://localhost:8080/user -d "name=Ali" -d "city=Quchan" -d "phone=09365473854"
    $router->put('/', 'UserController@addUser');


    //R Read
    //request method: get
    $router->get('/', ['as' => 'routeGetUsers', 'middleware' => 'middlewareGetUsers', 'uses' => 'UserController@getUsers']);

    //R Read
    //request method: get
    //just one user information
    $router->get('/{id}', ['as' => 'routeGetUser', 'uses' => 'UserController@getUser']);


    //U Update
    //request method: put
    //update a user and add that
    // i.e. cURL
    // curl -X PUT http://localhost:8000/user/10 -d "city=Quchan"
    $router->put('/{id}', 'UserController@setUser');

    //D Delete
    //request method: delete
    //update a user and add that
    // i.e. cURL
    // curl -X DELETE http://localhost:8000/user/6
    $router->delete('/{id}', 'UserController@deleteUser');
});


// //(test+practice)ing
// // use Psr\Http\Message\ServerRequestInterface;
// $router->get('/myurl', function (\Illuminate\Http\Request $request) {
//     return $obj ?? 'falsey :\\';
// });

