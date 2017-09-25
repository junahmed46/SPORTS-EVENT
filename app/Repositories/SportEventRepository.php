<?php
namespace App\Repositories;
use App\SportEvent;

class SportEventRepository {
    /**
     * Get a new Sport Event
     *
     * @param  Nothing for now might be added later once we have name etc..
     * @return collection
     */
    public function create_sport_event()
    {
        $sport_event = new SportEvent();
        $sport_event->event_start = microtime(true);
        $sport_event->save();

        return $sport_event->toArray();
    }

    /**
     * Get Complete detail of Sport Event With Athletes
     *
     * @param  id of sport_events table
     * @return collection or FALSE in case sport event not found
     */
    public function get_event_detail($id)
    {
        $sport_event = SportEvent::with([
            'sport_event_athlets' => function($q) {
                $q->join('athletes as a', 'a.id', '=', 'sport_event_athletes.A_id')
                    ->select('sport_event_athletes.*', 'a.first_name','a.sur_name');
            }
        ])->find($id);

        if($sport_event)
            return $sport_event->toArray();
        else
            return false;


    }
    /**
     * Get all Sport Event With Athletes
     *
     * @param  no param right now but we can add things like limit, sort etc paginate etc
     * @return collections of events or FALSE in case sport event not found
     */
    public function get_all_event()
    {
        $sport_event = SportEvent::with([
            'sport_event_athlets' => function($q) {
                $q->join('athletes as a', 'a.id', '=', 'sport_event_athletes.A_id')
                    ->select('sport_event_athletes.*', 'a.first_name','a.sur_name');
            }
        ])->orderby('id','desc')->get();

        if($sport_event)
            return $sport_event->toArray();
        else
            return false;
    }

}