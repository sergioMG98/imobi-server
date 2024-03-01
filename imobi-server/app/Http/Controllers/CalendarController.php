<?php

namespace App\Http\Controllers;

use App\Models\Event;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CalendarController extends Controller
{
    // cree un event
    public function addEvents(Request $request){
        
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'startVisit' => 'required', 
            'endVisit' => 'required',
            'adresse' => 'required',
            'title'  => 'required', 
            'lastnameVisitor' => 'required', 
            'firstnameVisitor' => 'required', 
            'phoneVisitor' => 'required', 
            'phoneOwner' => 'required',
        ]);
        
        if($validator->fails()){
            
            return response()->json([
                'status' => 'false',
                'data' => $validator -> Errors($validator),
            ]);
        } else {
            $user_id = Auth::id();
            
            try {
                $event = Event::create([
                    'date' => $request->date,
                    'startVisit' => $request->startVisit, 
                    'endVisit' => $request->endVisit,
                    'adresse' => $request->adresse,
                    'title'  => $request->title, 
                    'lastnameVisitor' => $request->lastnameVisitor, 
                    'firstnameVisitor' => $request->firstnameVisitor, 
                    'phoneVisitor' => $request->phoneVisitor, 
                    'phoneOwner' => $request->phoneOwner,
                    'user_id' => $user_id,
                ]);

                return response()->json([
                    'status' => 'true',
                    'message' => 'event crée',
                ]);
            } catch (\Throwable $th) {

                return response()->json([
                    'status' => 'false',
                    'message' => 'il y a eu un probleme evenement non crée',
                    'date' => $request->date,
                    'startVisit' => $request->startVisit, 
                    'endVisit' => $request->endVisit,
                    'adresse' => $request->adresse,
                    'title'  => $request->title, 
                    'lastnameVisitor' => $request->lastnameVisitor, 
                    'firstnameVisitor' => $request->firstnameVisitor, 
                    'phoneVisitor' => $request->phoneVisitor, 
                    'phoneOwner' => $request->phoneOwner,
                    'user_id' => $user_id,
                ]);
            }



        }

    }
    // va recupere tous les events
    public function getEvents(){

        $user_id = Auth::id();


        $events = DB::table('events')
            ->where('events.user_id', $user_id)
            ->get();

        if (count($events) != 0) {
            return response()->json([
                'status' => 'true',
                'data' => $events
            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'erreur de recuperation des evenements'
            ]);
        }

    } 
    // modifie les events
    public function updateEvents(Request $request){



        try {
            
            $event = Event::find($request->id);

            $event->date = $request->date;
            $event->startVisit = $request->startVisit;
            $event->endVisit = $request->endVisit;
            $event->adresse = $request->adresse;
            $event->title = $request->title;
            $event->lastnameVisitor = $request->lastnameVisitor;
            $event->firstnameVisitor = $request->firstnameVisitor;
            $event->phoneVisitor = $request->phoneVisitor;
            $event->phoneOwner = $request->phoneOwner;

            $event->save();

            return response()->json([
                'status' => 'true',
                'message' => 'modification reussi'
            ]);

        } catch (\Throwable $th) {
            
            return response()->json([
                'status' => 'false',
                'message' => 'modification echoué ! ',
                'error' => $th,
            ]);
        }
    }
    // supprime les events
    public function deleteEvents(Request $request){

        try {
            $event = Event::find($request->id);
            $event ->delete();

            return response()->json([
                'status' => 'true',
                'message' => 'suppression réussie'
            ]);

        } catch (\Throwable $th) {
            
            return response()->json([
                'status' => 'false',
                'message' => 'suppression échouer'
            ]);
        }

    }
}
