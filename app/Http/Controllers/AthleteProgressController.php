<?php

namespace App\Http\Controllers;

use App\AthleteProgress;
use App\Repositories\SportEventAthletesRepository;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;


class AthleteProgressController extends Controller
{

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
             * timstamp or microtime can be used to keep things simple but i didn;t use as mentioned in doc that wall clock time will be sent.
             */

            if($dt) {

                $clock_time_in_micro = $dt->timestamp.".".$dt->micro;
                $sea_repository = new SportEventAthletesRepository();
                $sea_id = $sea_repository->get_id_from_identifier($request->input('chip_code'));
                $last_step = $sea_repository->get_last_step($sea_id);

                if($last_step) {
                    if(($dt->timestamp - $last_step->clock_time) > 1)
                        $next_step = $last_step->step_history + 1;
                    else
                        $next_step = $last_step->step_history;

                }
                else
                    $next_step = 1;


                $data = [
                    'SEA_id' => $sea_id,
                    'finish_type' =>  $request->input('finish_type') ,
                    'clock_time' => $clock_time_in_micro,
                    'step_history' => $next_step,
                ];

                AthleteProgress::insert($data);


                $response = [
                    'message' => "Recorded Successfully"
                ];

            }
            else
            {
                $response = get_error_format('Unable to parse time');
            }

        }

       return render_json($response);

    }
}
