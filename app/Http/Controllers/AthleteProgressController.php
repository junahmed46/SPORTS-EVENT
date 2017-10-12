<?php

namespace App\Http\Controllers;

use App\AthleteProgress;
use App\Repositories\SportEventAthletesRepository;
use App\Repositories\SportEventRepository;
use Illuminate\Pagination\Paginator;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Artisan;



class AthleteProgressController extends Controller
{

    /**
     * Main Machine Calling server, Athlets finish corridor or finish line will be only insert from here.
     * @param
     *      chip_code, Unique identifer that will be wear by athlet. (that should be unique)
     *      finish_type should be either corridor or line, Enum is defined in table so it will not accept anything else
     *      clock_time time to cross corridor or line TIMEFORMAT for now is 2012-9-5 23:26:11.123789
     * @return Return created event
     */
    public function createAthleteProgress(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'chip_code' => ['required','exists:sport_event_athletes,code_identifier'],
            'finish_type' => ['required',Rule::in(['corridor', 'line'])],
            'clock_time' => 'required',

        ])->setAttributeNames([
            'chip_code' => 'code of the chip',
            'finish_type'=> 'identifier of the timing point',
            'clock_time'=> 'wall clock time of the timing point'
        ]);

        if ($validator->fails()) {

            $response = [
                'errors'=> $validator->errors(),
                'status' => 400,
                'success' => false,
            ];

        }
        else {

            $clock_time = $request->input('clock_time');

            $dt = Carbon::parse($clock_time);

            /*
             * for now the timeformat should be the one which Carbon accept like 2012-9-5 23:26:11.123789
             * timstamp or microtime can be used to keep things simple but i didn't use as mentioned in doc that wall clock time will be sent.
             */

            if($dt) {

                $clock_time_in_micro = $dt->timestamp + $dt->micro/1000000;

                $sea_repository = new SportEventAthletesRepository();

                $athlete = $sea_repository->get_athlete_by_identifier($request->input('chip_code'));

                $last_step = $sea_repository->get_last_step($athlete->SE_id);

                /*
                 * I have kept step to 200 micro second so below step will change step if previous took more time then it
                 * Steps are important to keep history
                 * if there is no step then save 1 as history
                 */

                if($last_step) {
                    if(($clock_time_in_micro - $last_step->clock_time) > 0.2)
                        $next_step = $last_step->step_history + 1;
                    else
                        $next_step = $last_step->step_history;

                }
                else
                    $next_step = 1;


                $data = [
                    'SEA_id' => $athlete->id,
                    'finish_type' =>  $request->input('finish_type') ,
                    'clock_time' => $clock_time_in_micro,
                    'step_history' => $next_step,
                ];

                /*
                 * In case of Record already exists don't add new one as it will complicate the process
                 */

                $p_saved = AthleteProgress::where('SEA_id','=',$athlete->id)
                    ->where('finish_type','=',$request->input('finish_type'))
                    ->count();

                if($p_saved) {
                    $response = ['message' => "already exists"];
                }
                else {
                    AthleteProgress::insert($data);
                    $response = ['message' => "Recorded Successfully" . $next_step];

                }

            }
            else
            {
                $response = get_error_format('Unable to parse time');
            }

        }
       return render_json($response);
    }

    /**
     * This is Dummy data for whole event, it take all the athlets of specific event shuffle them so we get random athlets, then add time for corridor and finish line
     * Don't confuse yourself with add time, its not going to add any data in tables its just going to add Laravel Queue and that queue will call
     * Test Port or machine port to insert data.
     * @param
     *      Event id
     * @return
     *      Message : done in case all Queues saved
     *      Error : No Athlets found in event in case no athlets found
     *      Error : Event didn't find in case event don't find
     *      Error : Invalid Event Id in case of invalid ID
     */

    public function insert_all_dummy_data(Request $request, $id)
    {
        sleep(5); // intentionally added 5 sec

           if(is_numeric($id))
           {
              $sport_event = new  SportEventRepository();
              $event = $sport_event->get_event_detail($id);

               if($event)
               {
                   if($count = count($event['sport_event_athlets']))
                   {

                       // below two line will determine who is going to finish corridor line first, it will suffle current atlets.
                       $athlet_range = range(0, $count-1);
                       shuffle($athlet_range);


                       /*
                        * PICK 6 athlets
                        * Top 3 will be quick to finish line
                        * Last 3 will be lazy to finish line
                       */

                       $quick_and_lazy_athlets = array_random($athlet_range, 6);
                       $i_counter = 0;

                       $current_time = microtime(true); // starting time for all Athletes

                        $finish_array = [];



                        // foreach will assign finish line and corridor time to athletes
                        foreach($athlet_range as $key => $finish_num)
                        {

                            $chip_identifier = $event['sport_event_athlets'][$finish_num]['code_identifier'];

                            // corridor finish with current time + random micro time from previous athlet 300 to 600 millisecond
                            $corridor_finish = $current_time  + (rand(300,600)/1000); //kept very low intentionally

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


                            $finish_array[$chip_identifier."_corridor"] = $corridor_finish;
                            $finish_array[$chip_identifier."_line"] = $line_finish;

                            $current_time = $corridor_finish; // current time changed for next athlet
                        }

                        natsort($finish_array); // NATSORT was required here because few athlets are crossing finish line first so LARAVEL Queue will disrtub if there is no proper sorting

                        foreach($finish_array as $key=>$fa)
                        {
                            $key_array = explode("_",$key);

                            /*
                             * This is important, here data is not saving in database but going to Queue, Detail can be see in App/jobs/SPORTEVENTDEMO
                             * From There POST calls are made to Server Port to insert values
                             */

                            $job = (new \App\Jobs\SportEventDemo([
                                'chip_code'=> $key_array[0],
                                'finish_type'=> $key_array[1],
                                'clock_time'=>get_current_device_time($fa),
                            ],url('api/v1/test-server')))
                                ->delay( Carbon::createFromTimestamp($fa)); // Delay is time to call it//
                            // i know there is one issue here if timestamp is of previous time then all Queues will be run one by one
                            // but it will not effect anything with Athlet time
                            // as it was demo project so i didn't put more effort, but in real project time diff can be applied here
                            //TODO if got time apply time differnece here
                            dispatch($job);
                        }

                       $response = ['message'=>'done'];
                   }
                   else
                       $response = get_error_format("No Athlets found in event");
               }
               else
                   $response = get_error_format("Event didn't find");
           }
           else
               $response = get_error_format('Invalid Event Id');

        return render_json($response);

    }

    /**
     * that is main port to get the Athlets data of specific event
     * @param
     *      Event id
     *      Step
     * @return
     *      Will return Athlets Name, sur_name, corridor time, finish_line time in order of Steps
     *      Error : Event Don't Exists
     */


    public function data(Request $request, $event_id, $step = false)
    {

        if(is_numeric($event_id))
        {
            $event_exists = DB::table('sport_event_athletes')->where('SE_id','=',$event_id)->count();
            if($event_exists) {

                /**
                 * The below query is tricky and planned with database design
                 * Steps are made while DB design so we can show history to Wathcing Audienace
                 */
                $athlete_progress = DB::table('athlete_progress as ap')
                    ->join('sport_event_athletes as sea','ap.SEA_id','=','sea.id')
                    ->join('athletes as a','a.id','=','sea.A_id')
                    ->where('step_history','<=',$step)
                    ->select('finish_type','clock_time','start_number','first_name','sur_name','step_history')
                    ->where('sea.SE_id','=',$event_id)
                    /**
                     * Order By is very important here, that is tricky part :) and was in mind while desiging database
                     * if we sort finish_type desc it means finish line will come first and corridor will come second
                     *  then there is another sort on clock time so after corridor and finish line sort it will apply clock sort
                     * in any case we will always get line crossed people first and then corridor crossing
                     * if there is no one crossed finish line again it will sort amoung corridor athlets
                     */
                    ->orderby('ap.finish_type','desc')
                    ->orderby('ap.clock_time','asc')
                    ->get();

                $return_data = [];

                $new_data = false; // new data false is to avoid returning of heavy data if there is no data
                $corridor_step = 1;  // this is extra thing just to show who enter corridor first so can be compare with person finishes finish line

                $first_to_cross_corridor = 0; // extra field just to check if someone crossed corridor then add time diff else add clock time
                $first_to_cross_line = 0; // extra field just to check if someone crossed finish line then add time diff else add clock time

                foreach($athlete_progress as $ap)
                {
                    if(!array_key_exists($ap->start_number,$return_data)) {

                        // Values below are left blank intentionally and will be filled below according to logic
                        $return_data[$ap->start_number] = [
                            'first_name' => $ap->first_name,
                            'sur_name' => $ap->sur_name,
                            'start_number' => $ap->start_number,
                            'corridor' => '', // it will show corridor time
                            'corridor_diff' => '', // it will show corridor time diff from previous athlet
                            'corridor_step' => 0,
                            'line' => '', // it will show finish line time
                            'line_diff' => '', // it will show line time diff from previous athlets
                            'current' => false // current fetch so it can be highlighted it display

                        ];
                    }

                    if($ap->finish_type == 'corridor') {
                        if($first_to_cross_corridor == 0) // if it zero in time diff assign time
                        {
                            $first_to_cross_corridor = $ap->clock_time;
                            $return_data[$ap->start_number]['corridor_diff'] = get_event_display_time($ap->clock_time);
                        }
                        else // else assign time diff
                            $return_data[$ap->start_number]['corridor_diff'] = " + " . round($ap->clock_time - $first_to_cross_corridor , 4);



                        $return_data[$ap->start_number]['corridor_step'] = $corridor_step++;
                        $return_data[$ap->start_number]['corridor'] = get_event_display_time($ap->clock_time);

                    }
                    else {

                        if($first_to_cross_line == 0) // if it zero in time diff assign time
                        {
                            $first_to_cross_line = $ap->clock_time;
                            $return_data[$ap->start_number]['line_diff'] = get_event_display_time($ap->clock_time);
                        }
                        else // else assign time diff
                            $return_data[$ap->start_number]['line_diff'] = " + " . round($ap->clock_time - $first_to_cross_line , 4);


                        $return_data[$ap->start_number]['line'] = get_event_display_time($ap->clock_time);
                    }


                    if( $ap->step_history == $step) { // it will show current step
                        $new_data = true;
                        $return_data[$ap->start_number]['current'] = true;

                    }
                }

                // below line is to tell if game is finished or not...
                // by doing that we can stop calling server for more data
                $event_finish = $event_exists == (count($athlete_progress)/2) ? true : false;

                if($new_data)
                    $response = ['data' => array_values($return_data),'new_data'=>true,'event_finish'=>$event_finish];
                else
                    $response = ['data' => [] , 'new_data'=>false, 'event_finish'=>$event_finish];
            }
            else {
                $response = [get_error_format('Event doesn\'t exists')];
            }

        }
        else {
            $response = get_error_format('Invalid Event Id');
        }

        return render_json($response);
    }

    /**
     * This simply destroy all athelts who crosses corridor or finish line of specific event
     * @param
     *      Event id
     * @return
     *      Delete Successfully
     */
    function destroy_all_dummy_data(Request $request, $event_id)
    {
        $athlete_progress = DB::table('athlete_progress as ap')
            ->join('sport_event_athletes as sea','ap.SEA_id','=','sea.id')
            ->join('athletes as a','a.id','=','sea.A_id')
            ->where('sea.SE_id','=',$event_id)
            ->delete();
        $response = ['message' => 'Deleted successfully'];
        return render_json($response);
    }
}
