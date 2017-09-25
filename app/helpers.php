<?php

function d($data,$var_dump = false)
{
    echo "<pre>";
    if($var_dump)
        var_dump($data);
    else
        print_r($data);
    exit;
}

function get_error_format($error)
{
    return ["error"=>[$error]];
}

function render_json($response)
{

    if(!isset($response['success']))
        $response['success'] =  true;

    if(!isset($response['status']))
        $response['status'] =  200;

    return response()->json($response, 200);
}