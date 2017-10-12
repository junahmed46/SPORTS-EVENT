# Code Explanation

Trying to Explain few important code here 

## 1. Timing point the Information sent to server 

*Function* AthleteProgressController/createAthleteProgress

*API Port* POST api/vi/test-server

After Validation ... 

The code will try to parse the date, if was unable to then will throw error back
```php
$clock_time = $request->input('clock_time');
$dt = Carbon::parse($clock_time);
if($dt)
.
.
else
{
    $response = get_error_format('Unable to parse time');
}
```

Once date parsed and i got the microtime, then i am checking what was the last step of athlete who either completed corridor or finish line.
and if the time differnece is above 200 ms then i increment the step, if there is no step or first athlete to finish the step will be first
The data will be saved to tables. 

```php
  $clock_time_in_micro = $dt->timestamp + $dt->micro/1000000; // i can also use concatation here with . between but better to get float value 

  $sea_repository = new SportEventAthletesRepository();

  $athlete = $sea_repository->get_athlete_by_identifier($request->input('chip_code'));

  $last_step = $sea_repository->get_last_step($athlete->SE_id);

  if($last_step) {
      if(($clock_time_in_micro - $last_step->clock_time) > 0.2)
          $next_step = $last_step->step_history + 1;
      else
          $next_step = $last_step->step_history;

  }
  else
      $next_step = 1;

```



## 2. Create a test-client that sends some dummy data instead.

*Function* AthleteProgressController/insert_all_dummy_data

*API Port* POST api/vi/dummy-auto-play/{id}

**Once Validation and 5 second delay, 5 second delay is added so values insert in Queue after 5 sec

With Below line all the athletes from the event will shuffle, so suffling from this point will determine who is going to finish corridor first
it can be now in any order. 
```php
   // below two line will determine who is going to finish corridor line first, it will suffle current atlets.
   $athlet_range = range(0, $count-1);
   shuffle($athlet_range);
```

Amoung all athlets now 6 will be randomly pick
1. Top three will become quick to cross finish line
2. last three will become lazy to cross finish line

```php
 $quick_and_lazy_athlets = array_random($athlet_range, 6);
 $i_counter = 0;
```

Iterate through all the shuffled athletes, assigning them timestamp with differnece between 300 to 600 ms 

```php
  foreach($athlet_range as $key => $finish_num)
  {

      $chip_identifier = $event['sport_event_athlets'][$finish_num]['code_identifier'];

      // corridor finish with current time + random micro time from previous athlet 300 to 600 millisecond
      $corridor_finish = $current_time  + (rand(300,600)/1000); //kept very low intentionally
  .
  .
      $current_time = $corridor_finish; // next athlete current time now
  .
  }
```


Here come the time for **Lazy and Quick athletes**
1. Quick Athlets will finish the finish line in Corridor time + rand(300,700)/1000 //300 to 700 ms
2. Lazy athlets will finish the finish line in Corridor time + rand(4000,6000)/1000 // 4000 to 6000 ms
3. Normal Athlets will finish the finish line in Corridor time + rand(2000,3000)/1000 //2000 to 3000 ms

```php
  if(in_array($key, $quick_and_lazy_athlets))
  {
      if($i_counter < 3) // Quick Crosser will cross finish line in 300 to 700 millisecond
      {
          $line_finish = $corridor_finish + (rand(300,700)/1000);
      }
      else // Lazy Crosser will cross finish line in 4000 to 6000 millisecond
      {
          $line_finish = $corridor_finish + (rand(4000,6000)/1000);
      }
      $i_counter++;

  } else  // Normal Crosser will cross finish line in 2000 to 3000 millisecond
  {
      $line_finish = $corridor_finish + (rand(2000,3000)/1000);
  }
```

NATSORT was required here because few athlets are crossing finish line first so LARAVEL Queue will disrtub if there is no proper sorting

```php
natsort($finish_array); 
```

Once time generated Queues are dispatched to Point 1. and then queues are calling Point 1. via POST request with actual data
***Queue need php artisan queue listen in backend running***
```php
      $job = (new \App\Jobs\SportEventDemo([
          'chip_code'=> $key_array[0],
          'finish_type'=> $key_array[1],
          'clock_time'=>get_current_device_time($fa),
      ],url('api/v1/test-server')))
          ->delay( Carbon::createFromTimestamp($fa)); 
      dispatch($job);
```


## 3. How Front end is getting data,

*Function* AthleteProgressController/data

*API Port* POST api/vi/athlete-progress/data/{event_id}[/{step}

Once race finished and to see history and active and inactive of browser we need step argument for this

After Validation etc 

That is the only Query i am using to get All required data 
1. ->where('step_history','<=',$step) due to this it will send all data whose step is either equal or less. In this way in case of active and inactive
we will not lost or directly get all the.data due to this i don't need to do complex calculations. Due to this i can navigate check previous history data
2. ***When i was designing database*** ordering was the most important thing so i 
kept it in this way to keep records of corridor and finish line in seprate table and in two rows. Now i can easily do ordering ->orderby('ap.finish_type','desc')->orderby('ap.clock_time','asc')
**It will always return me athlets with finish line first and then corridor finishes**

```php
$athlete_progress = DB::table('athlete_progress as ap')
    ->join('sport_event_athletes as sea','ap.SEA_id','=','sea.id')
    ->join('athletes as a','a.id','=','sea.A_id')
    ->where('step_history','<=',$step)
    ->select('finish_type','clock_time','start_number','first_name','sur_name','step_history')
    ->where('sea.SE_id','=',$event_id)
    ->orderby('ap.finish_type','desc')
    ->orderby('ap.clock_time','asc')
    ->get();
```

With new data i am able to tell front end that there is no new data so it don't need to refresh front end. Also with new data current step is send
so it can be highlighted. 
```php
$new_data = false;
.
.
.
if( $ap->step_history == $step) { // it will show current step
    $new_data = true;
    $return_data[$ap->start_number]['current'] = true;
}
```
Additional thing so we can give time to first crosser in both end **used in time differnece display**
```php
$first_to_cross_corridor = 0;
$first_to_cross_line = 0;

```

**All the rest is Self explanined i wrote comment there**




