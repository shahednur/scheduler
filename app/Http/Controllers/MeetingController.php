<?php

namespace App\Http\Controllers;

use App\Meeting;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $meetings = Meeting::all();
        foreach($meetings as $meeting){
            $meeting->view_meeting = [
                'href'=>'api/v1/meeting'.$meeting->id,
                'method'=>'GET'
            ];
        }
        $response = [
            'msg'=>'List of all Meetings',
            'meeting'=>$meeting
        ];

        return response()->json($response,Response::HTTP_OK);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'title'=>'required',
            'description'=>'required',
            'time'=>'required|date_format:YmdHie',
            'user_id'=>'required'
        ]);

        $title = $request->input('title');
        $description = $request->input('description');
        $time = $request->input('time');
        $user_id = $request->input('user_id');

        $meeting = new Meeting([
            'time'=>Carbon::createFromFormat('YmdHie',$time),
            'title'=>$title,
            'description'=>$description
        ]);
        if($meeting->save()){
            $meeting->users()->attach($user_id);
            $meeting->view_meeting = [
                'href'=>'api/v1/meeting'.$meeting->id,
                'method'=>'GET'
            ];
            $message = [
                'msg'=>'Meeting created',
                'meeting'=>$meeting
            ];
            return response()->json($message,Response::HTTP_CREATED);
        }

        $response = [
            'msg'=>'woops something is wrong!',
        ];
        return response()->json($response,Response::HTTP_NOT_FOUND);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $meeting = Meeting::with('users')->where('id',$id)->firstOrFail();
        $meeting->view_meetings = [
            'href'=>'api/v1/meeting',
            'method'=>'GET'
        ];

        $response = [
            'msg'=>'Meeting information',
            'meeting'=>$meeting
        ];

        return response()->json($response,Response::HTTP_OK);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'title'=>'required',
            'description'=>'required',
            'time'=>'required|date_format:YmdHie',
            'user_id'=>'required'
        ]);

        $title = $request->input('title');
        $description = $request->input('description');
        $time = $request->input('time');
        $user_id = $request->input('user_id');

        $meeting = [
            'title'=>$title,
            'description'=>$description,
            'time'=>$time,
            'user_id'=>$user_id,
            'view_meeting'=>[
                'href'=>'api/v1/meeting/1',
                'method'=>'GET'
            ],
        ];

        $response = [
            'msg'=>'Meeting created',
            'meeting'=>$meeting
        ];
        return response()->json($response,Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = [
            'msg'=>'Meeting deleted',
            'create'=>[
                'href'=>'api/v1/meeting',
                'method'=>'POST',
                'params'=>'title,description,time'
            ]
        ];

        return response()->json($response,Response::HTTP_OK);
    }
}
