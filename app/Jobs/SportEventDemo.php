<?php

namespace App\Jobs;
use URL;

class SportEventDemo extends Job
{

    protected $data;
    protected $url;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $url)
    {
        $this->data = $data;
        $this->url = $url; // the URL is passed here for a reason, if not passed Laravel will consider URL::to as localhost as its calling from Localhost not frontend
    }

    /**
     * This is Queue to executes Athlets data of cossing coridor and finish line.
     * point to note here is it will not insert data in table but will POST call server point with actaul values
     *
     * @return void
     */
    public function handle()
    {


        $client = new \GuzzleHttp\Client();


        $response = $client->post($this->url, [
            'form_params' => $this->data,
        ]);

        $rtn =  $response->getBody()->getContents();
        $rtn = json_decode($rtn);

        if(isset($rtn->success) && $rtn->success === true)
            echo $rtn->message."\n\r\n\r";
        else
            echo "failed, triggered called to recover";

        return;

    }
}
