<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Traits\ResponseTrait;
use App\Models\EventBooking;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ResponseTrait;
    public function list()
    {
        $events = Event::all();
        if ($events) {
            return $this->returnResponse(true, "Events List", $events);
        } else {
            return $this->returnResponse(false, "No Events Found");
        }
    }

    public function book(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'event_id'              => 'required|exists:events,id'
        ]);

        if ($validation->fails())
            return $this->validationErrorsResponse($validation);

        EventBooking::create($request->only(['event_id']) + [
            'user_id'       => auth()->user()->id,
            'booking_date'  => now()
        ]);

        return $this->returnResponse(true, "Event Booking Successfully");
    }

    public function show()
    {
        $events = User::with('events')->where('id',auth()->user()->id)->first();
        if ($events) {
            return $this->returnResponse(true, "Event Booking Information",$events);
        } else {
            return $this->returnResponse(false, "No any event booked by you");
        }

    }

    public function getCurrentEvent()
    {
        //multiple where events
        $events = Event::where([['start_date','<',now()],['end_date','>',now()]])->get();
        if ($events) {
            return $this->returnResponse(true, "Current Event",$events);
        } else {
            return $this->returnResponse(false, "No any current events ");
        }
    }

    public function getEventByDay(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'day'       => 'required|numeric|between:1,31'
        ]);

        if ($validation->fails())
            return $this->validationErrorsResponse($validation);

        $events = Event::whereDay('start_date', $request->day)->orwhereDay('end_date',$request->day)->get();
        if ($events) {
            return $this->returnResponse(true, "Current Event",$events);
        } else {
            return $this->returnResponse(false, "No any events on this month");
        }
    }

    public function getEventByMonth(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'month'              => 'required|numeric|between:1,12'
        ]);

        if ($validation->fails())
            return $this->validationErrorsResponse($validation);

        $events = Event::whereMonth('start_date', $request->month)->orWhereMonth('end_date',$request->month)->get();
        if ($events) {
            return $this->returnResponse(true, "Current Event",$events);
        } else {
            return $this->returnResponse(false, "No any events on this month");
        }
    }

    public function getEventByYear(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'year'              => 'required|numeric'
        ]);

        if ($validation->fails())
            return $this->validationErrorsResponse($validation);

        $events = Event::whereYear('start_date', $request->year)->orwhereYear('end_date',$request->year)->get();
        if ($events) {
            return $this->returnResponse(true, "Current Event",$events);
        } else {
            return $this->returnResponse(false, "No any events on this year");
        }
    }

    /*
    
    public function allExamples()
    {

        //Where Exists
        if (Event::where('start_date', date('Y-m-d'))->exists()) {
            return $this->returnResponse(true, "Today's Starting Date Event is Exists");
        }
        else{
            return $this->returnResponse(true, "Today's Starting Date Event is Not Exists");;
        }
        
        //orWhere
        $event = Event::where('name','musicevent')->orWhere('name','photoevent')->get();

        //multiple where condition
        $event = Event::where([
            ['name','=','musicevent'],
            ['location','=','ahmedabad']
        ])->get();

        //function in where
        $event = Event::where('name','musicevent')->where(function($query){
            $query->where('location','rajkot')->orWhere('location','surat');
        })->get();


        //Laravel Where with Two Column (whereColumn)
        $event = Event::whereColumn('start_date','end_date')->get();
        $event = Event::whereColumn('start_date','!=','end_date')->get();

        $event = Event::whereIn('name', ['musicevent','danceevent'])->get();
        $event = Event::whereNotIn('name', ['musicevent','danceevent'])->get();

        //whereKey / whereId
        $event = Event::whereKey(auth()->user()->id)->first();

        if($event){
            return $this->returnResponse(true, "Event Information",$event);
        }else{
            return $this->returnResponse(false, "No Event Found");
        }
    }

    */


}
