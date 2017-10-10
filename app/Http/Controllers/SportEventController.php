<?php

namespace App\Http\Controllers;

use App\Repositories\SportEventRepository;
use App\Repositories\SportEventAthletesRepository;

use Illuminate\Http\Request;

class SportEventController extends Controller
{
    /**
     * Get all the list of events. (for now there is no pagination implemented as not required)
     * @param  nothing
     * @return if there are events return list of events else No event found.
     */
    public function index(Request $request)
    {

        $se_repository = new SportEventRepository();
        $event  = $se_repository->get_all_event();


        if($event)
            return render_json(['events'=>$event]);
        else
            return render_json(get_error_format('No event found'));

    }

    /**
     * Return the detail of event including Athlets in the event
     * @param  event_id
     * @return Event with list of athlets. No event found in case of no event.
     */

    public function getSportEvent(Request $request, $id=false)
    {


        $se_repository = new SportEventRepository();
        $event  = $se_repository->get_event_detail($id);

        if($event)
            return render_json(['event'=>$event]);
        else
            return render_json(get_error_format('No event found'));

    }
   /**
     * Will create Event
     * @param  nothing
     * @return Return created event
     */

    public function createSportEvent(Request $request)
    {
       $se_repository = new SportEventRepository();
       $event  = $se_repository->create_sport_event();
       return render_json(['event'=>$event]);
    }


     /**
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
