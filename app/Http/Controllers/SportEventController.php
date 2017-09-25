<?php

namespace App\Http\Controllers;

use App\Repositories\SportEventRepository;
use App\Repositories\SportEventAthletesRepository;

use Illuminate\Http\Request;

class SportEventController extends Controller
{

    public function index(Request $request)
    {

        $se_repository = new SportEventRepository();
        $event  = $se_repository->get_all_event();

        if($event)
            return render_json(['events'=>$event]);
        else
            return render_json(get_error_format('No event found'));

    }

    public function getSportEvent(Request $request, $id=false)
    {


        $se_repository = new SportEventRepository();
        $event  = $se_repository->get_event_detail($id);

        if($event)
            return render_json(['event'=>$event]);
        else
            return render_json(get_error_format('No event found'));

    }

    public function createSportEvent(Request $request)
    {
        // Create New Event
       $se_repository = new SportEventRepository();
       $event  = $se_repository->create_sport_event();

        // Assign 10 to 15 Random Players
       $sea_repository = new SportEventAthletesRepository();
       $sea_repository->get_and_assign_rand_athlete($event['id']);


       return render_json(['event'=>$event]);

    }

/*
 * Below Function we don't need as not required in requirement
 *
 */
    public function updateSportEvent()
    {
        // In case we need update sport event, in my case we don't

    }

    public function deleteSportEvent()
    {
        // In case we need delete sport event, in my case we don't
    }

}
