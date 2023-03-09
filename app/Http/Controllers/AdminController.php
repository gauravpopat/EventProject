<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Traits\ResponseTrait;
use App\Models\EventBooking;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    use ResponseTrait;
    public function create(Request $request)
    {
        $admin = User::where('id', auth()->user()->id)->first();
        if ($admin->type == 'admin') {
            $validation = Validator::make($request->all(), [
                'name'                  => 'required|max:40|string',
                'type'                  => 'required|max:40|string',
                'start_date'            => 'required|date',
                'end_date'              => 'required|date',
                'description'           => 'required|string',
                'location'              => 'required|max:40|string'
            ]);

            if ($validation->fails())
                return $this->validationErrorsResponse($validation);

            Event::create($request->only(['name', 'type', 'start_date', 'end_date', 'location', 'description']));

            return $this->returnResponse(true, "Event Created Successfully");
        } else {
            return $this->returnResponse(false, "Not Allowed");
        }
    }

    public function showBookings()
    {
        $usersBookings = EventBooking::with('events','users')->get();
        return $this->returnResponse(true, "Bookings", $usersBookings);
    }

    public function showEventUsers()
    {
        $user = User::whereHas('events')->get();

        return $user;

    }
}
