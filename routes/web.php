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

$app->get('/', function () use ($app) {echo "nothing to display here";});



$app->group(['prefix' => 'api/v1','namespace' => '\App\Http\Controllers'], function($app)
{
 /**********************************************************************************************************************
 * All the inside here are prefix with api/v1 so in future we can change version plus the namespace for controller code.
 *
 ************************************************************************************************************************/
    /*
   |--------------------------------------------------------------------------
   | API Prefixes and Name space
   |--------------------------------------------------------------------------
   |
   | All the inside here are prefix with api/v1.
   | All the controller namespace is also api/v1
   | For next version change prefix
   |
   */


    /*
    |--------------------------------------------------------------------------
    | Sport Event RESTAPI
    |--------------------------------------------------------------------------
    |
    | GET sport-event with index will return all the sports events.
    | GET sport-event/{id} to get the detail of any sport event,
    |     It will also return list of athlets in event
    | POST sport-event will create new event.
          There is no argument for now but can be added later
    | PUT sport-event/{id} should update event but nothing implemneted.
    | DELETE sport-event/{id} should delete event but nothing implemented.
    */

    $app->get('sport-event','SportEventController@index');

    $app->get('sport-event/{id}','SportEventController@getSportEvent');

    $app->post('sport-event','SportEventController@createSportEvent');

    $app->put('sport-event/{id}','SportEventController@updateSportEvent');

    $app->delete('sport-event/{id}','SportEventController@deleteSportEvent');

    /*
    |--------------------------------------------------------------------------
    | Sport Event Athlets
    |--------------------------------------------------------------------------
    |
    | POST sport-event/add-athlets/{id} for now it will assign 10 to 15 athlets to supplied ID event.
    |      NOTE : in above function there is no restriction that if athlets already exists don't add any.
    | GET  sport-event-athletes/{event_id} will return all the athlets of supplied ID including Label who cross corridor or finish line
    */
    $app->post('sport-event/add-athlets/{id}','SportEventAthletesController@addAthlets');
    $app->get('sport-event-athletes/{event_id}','SportEventAthletesController@index');

    //TODO if got time implemnet if athelts already there, add no athelts and return status.


    /*
    |--------------------------------------------------------------------------
    | Athlet Progress
    |--------------------------------------------------------------------------
    | GET 'athlete-progress/data/{event_id}/{step} will return you current progress in the event.
    |   Step might confuse but its a core, this way we can easily fetch results in step for finish events, also we can make history.
        i can also use time in sec here in place of Step but in run time enviroment its better to not do too many calculations.
    | DELETE destroy-dummy-data/{event_id} This will delete all the records of specific event
    |
    */
    $app->get('athlete-progress/data/{event_id}[/{step}]','AthleteProgressController@data');
    $app->delete('destroy-dummy-data/{event_id}','AthleteProgressController@destroy_all_dummy_data');


    /*
    |--------------------------------------------------------------------------
    | Test Server or Machine Call port
    |--------------------------------------------------------------------------
    | 'test-server' will save athlet corridor or finish line
    |
    */
    $app->post('test-server','AthleteProgressController@createAthleteProgress');

    /*
    |--------------------------------------------------------------------------
    | Dummy data insertion and Dummy data deletion
    |--------------------------------------------------------------------------
    | 'dummy-auto-play/{id}' is something will create dummy values, by create it doesn't mean it will call or insert anything in tables
    |     The Values will trigger Queue in Laravel define in App/Jobs/SportEventDemo
    |     The Queue will be added and will call TEST SERVER for all insertion
    |
    */

    $app->get('dummy-auto-play/{id}','AthleteProgressController@insert_all_dummy_data');







});


