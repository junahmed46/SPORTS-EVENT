<?php
/**
 * Test Function just to display things alternation to dd() of laravel
 * @param  data(to display) , var_dumo to show in print_r or var_dump
 * @return Exit, don't return
 */
function d($data,$var_dump = false)
{
    echo "<pre>";
    if($var_dump)
        var_dump($data);
    else
        print_r($data);
    exit;
}

/**
 * This will convert all supplied to Laravel Error Format.This is just to Keep Error format same for all places
 * @param  error string
 * @return formatted error Array
 */
function get_error_format($error)
{
    return ["error"=>[$error]];
}

/**
 * This is to reder the return JSON, every return is call from here to Keep return format same for all returns
 * @param  reponse Array
 * @return RESONSE
 */
function render_json($response)
{
    if(!isset($response['success']))
        $response['success'] =  true;

    if(!isset($response['status']))
        $response['status'] =  200;

    return response()->json($response);
}


/**
 * Format U.u time to Device foramt
 * @param  U.u time
 * @return device time in format
 */
function get_current_device_time($time_string)
{
    return Carbon\Carbon::createFromFormat('U.u',$time_string)->format(config('myconfig.device_timeformat'));;
}



/**
 * Format U.u time to Disply time fo front end
 * @param  U.u time
 * @return device time in format
 */
function get_event_display_time($time_string)
{
    $date = Carbon\Carbon::createFromFormat('U.u',$time_string)->format(config('myconfig.event_timeformat'));
    return substr($date, 0, -2);
}


/**
 * Call to save Queue data to test Server
 * @param  chip_code, finish type, clock_time further detail Contoller/ AthleteProgressController/createAthleteProgress description
 * @return nothinf
 */
function demo_record($chip_code, $finish_type,$clock_time)
{
    $client = new \GuzzleHttp\Client();

    $data = [
        'chip_code' => $chip_code,
        'finish_type' => $finish_type,
        'clock_time' => $clock_time,
    ];

    $client->post(url('api/v1/test-server'), [
        'form_params' => $data,
    ]);
}
