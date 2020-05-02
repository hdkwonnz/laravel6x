<?php

namespace App\Http\Controllers;

use App\Room;
use App\Calendar;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        //아래처럼 multi middleware를 사용시 순서에 주의 할 것
        // $this->middleware('auth');
        // $this->middleware('role:admin');
        $this->middleware('password.confirm');        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.index");
    }

    public function dateList()
    {
        return view("admin.dateList");
    }

    public function showRooms()
    {
        return view("admin.showRooms");
    }

    public function showAvailableRooms()
    {        
        $type = $_GET['type'];
        $start = $_GET['start'];
        $end = $_GET['end'];
        
        if ($type == ""){
            return "room type을 선택 하십시요...";//추후에 수정 할것.21/02/2020
        }
        $today = date('Y-m-d');//date=>yyyymmdd format
        if ($start < $today){
            return "시작 날짜는 금일 보다 커야 합니다...";//추후에 수정 할것.21/02/2020
        }
        if ($start >= $end){
            return "날짜 선택이 틀렸습니다...";//추후에 수정 할것.21/02/2020
        }

        $rooms = Room::
            select ('room_number')
            ->where('size','=',$type)//조건문이 있어 cache 사용을 못 한다.
            ->get();
        $rooms = collect($rooms);
        $calendars = Calendar::
            select ('room_number')
            ->where('title','=',$type)            
            ->Where('end','>',$start)
            ->where('start','<',$end)           
            ->get();
        $calendars = collect($calendars);
       
        $selectedRooms = $rooms->diffAssoc($calendars);
        //$selectedRooms = $rooms->diffAssoc($calendars)->first();
        
        return $selectedRooms;
    }

    //아래는 pagination을 사용 할때.
    //반드시 한개의 result만 보내야 한다(두개이상 보내는 방법 아직 못 찾음).    
    public function showBookingByDate()
    {    
        //fromDate, toDate를 어떻게 활용 할 것 인지는 추후에 정하자...                  
        $fromDate = $_GET['fromDate'];
        $toDate = $_GET['toDate'];

        //error 처리도 추후에 정하자...

        $bookings = Calendar::
                        select('*')
                        //->where('app_date', '=', $date)
                        //->where('status', '!=', "completed")
                        ->orderBy('start', 'asc')
                        //->orderBy('app_time', 'asc')
                        ->paginate(2);      
        return $bookings;        
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
