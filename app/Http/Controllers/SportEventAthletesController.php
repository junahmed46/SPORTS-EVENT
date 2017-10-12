<?php

namespace App\Http\Controllers;

use DB;
use App\Repositories\SportEventAthletesRepository;
use App\Repositories\SportEventRepository;

use Illuminate\Http\Request;

class SportEventAthletesController extends Controller
{

    /**
     * Get all Athlets of Specific Event, There Corridor and finish line crossing as well, if they didn't then return empty for finish line and corridor
     * @param  Event ID
     * @return Athlets List
     */
    public function index(Request $request, $event_id)
    {
        $athletes = DB::table('athletes as a')
            ->join('sport_event_athletes as sea','sea.A_id','=','a.id')
            ->leftjoin('athlete_progress as ap','ap.SEA_id','=','sea.id')
            ->select(['first_name','sur_name','code_identifier','finish_type'])
            ->where('sea.SE_id','=',$event_id)
            ->orderby('sea.start_number','asc')
            ->get();

        $return_array = [];
        foreach($athletes as $a) {
            if( !array_key_exists($a->code_identifier,$return_array)) {
                $return_array[$a->code_identifier] = [
                    'first_name' => $a->first_name,
                    'sur_name' => $a->sur_name,
                    'code_identifier' => $a->code_identifier,
                    'corridor_done' => false ,
                    'line_done' => false ,
                ];
            }

            if($a->finish_type == 'corridor')
                $return_array[$a->code_identifier]['corridor_done'] = true;
            if($a->finish_type == 'line')
                $return_array[$a->code_identifier]['line_done'] = true;

        }
        // array_values are applied intentionally because it was associate Array and angular will not iterate directly, just to save time there
        return render_json(['athlets'=>array_values($return_array)]);
    }

    /**
     * Assgin 10 to 15 random athlets to Event.
     * @param  Event ID
     * @return Event with Athlets
     */
    public function addAthlets(Request $request, $id )
    {
        $sea_repository = new SportEventAthletesRepository();
        $sea_repository->get_and_assign_rand_athlete($id);

        $se_repository = new SportEventRepository();
        $event  = $se_repository->get_event_detail($id);
        if($event)
            return render_json(['event'=>$event]);
        else
            return render_json(['event'=>$event,'status'=>404]);
    }

}
