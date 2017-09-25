<?php

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

$app->get('/', function () use ($app) {
    echo "nothing to display here";
});



$app->group(['prefix' => 'api/v1','namespace' => '\App\Http\Controllers'], function($app)
{

/*------------------------------------------------------------------------------------------
 * All the inside here are prefix with api/v1 so in future we can change version plus the namespace for controller code.
 *
 *------------------------------------------------------------------------------------------
 */



    // Sports Events Routes

    $app->get('sport-event','SportEventController@index');

    $app->get('sport-event/{id}','SportEventController@getSportEvent');

    $app->post('sport-event','SportEventController@createSportEvent');

    $app->put('sport-event/{id}','SportEventController@updateSportEvent');

    $app->delete('sport-event/{id}','SportEventController@deleteSportEvent');

    // this is service port which will get data from Walls, as my database is meant
        $app->post('athlete-progress','AthleteProgressController@createAthleteProgress');
        $app->post('test-server','AthleteProgressController@createAthleteProgress'); // just created alias to keep think simples



});


