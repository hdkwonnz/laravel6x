<?php

namespace App\Http\Controllers;

use App\Calendar;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('calendar.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request->all();
        $this->validate($request, [
            'event_name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'           
        ]);

        $calendar = Calendar::create([            
            'title' => $request->event_name,
            'start' => $request->start_date,
            'end' => $request->end_date,            
        ]);

        return $calendar;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {   
        //아래 처럼 하면 전체를 모두 보여준다.    
        //$calendars = Calendar::all();

        //groupBy를 사용하기 위해 다음과 같이 하였음.17/02/2020
        //Open config/database.php
        //Find strict key inside mysql connection settings
        //Set the value to false

        $today = date('Y-m-d');//date=>yyyymmdd format

        $calendars = Calendar::
            select('id','title','start','end',\DB::raw('count(*) as subCount'))
            ->where('start','>=',$today)
            ->orWhere('end','>=',$today)
            ->groupBy('title','start','end')
            ->get();

        //title 과 subCount를 동시에 보여 주기 위해...
        foreach ($calendars as $calendar){
            $calendar->title = $calendar->title . "(" . $calendar->subCount . ")";
        }

        return $calendars;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)   
    {        
        $this->validate($request, [
            'id' => 'required',
            'event_name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'           
        ]);

        $calendar = Calendar::findOrFail($request->id); 

        $calendar->title = $request->event_name;
        $calendar->start = $request->start_date;
        $calendar->end = $request->end_date;            
         
        $calendar->save();

        return $calendar;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'                
        ]);

        $calendar = Calendar::findOrFail($request->id);
        
        $calendar->delete();
    }
}
