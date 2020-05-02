<?php

namespace App\Http\Controllers;

use DB;
use App\Room;
use App\Calendar;
use App\Roomtype;
use Illuminate\Http\Request;
use App\Jobs\BookingEmailJob;
use Illuminate\Support\Facades\Cache;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        return view("booking.index");
    }

    public function directBooking()
    { 
        return view("booking.directBooking");
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createBooking($room_number, $type)
    {
        return "hehe..." . $room_number . $type;
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

    public function addBooking(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',            
            'start' => 'required',
            'end' => 'required',
            'name' => 'required',
            'phoneNo' => 'required',
            'email' => 'email',
            'roomNo' => 'required',
            'members' => 'required'
            //members도 추가 할 것(나중에)
        ]);

        if ($request->start >= $request->end){
            return "날짜 선택이 틀렸습니다...";
        }

        $calendarCount = Calendar::
            select ('*')
            ->where('room_number','=',$request->roomNo)
            ->where('start','=', $request->start)
            ->where('end','=',$request->end)
            ->count();
        if ($calendarCount > 0){
            return "선택하신 룸은 이미 예약이 끝났습니다...";
        }else{
            DB::beginTransaction();
            try{
                $booking = Calendar::create([            
                    'title' => $request->title,
                    'start' => $request->start,
                    'end' => $request->end,
                    'room_number' => $request->roomNo,
                    'customer_name' => $request->name,
                    'customer_email' => $request->email,
                    //'number_members' => $request->members,
                    'customer_phone' => $request->phoneNo
                ]);
                //보낼 메일을 jobs table로 passing 한다.
                //반드시 php artisan queue:work --tries=3 --sleep=3 type 할 것.
                $job = (new BookingEmailJob($booking))
                        ->delay(now()->addSeconds(10));
                $this->dispatch($job);
                DB::commit();
                return "예약이 정상적으로 완료 되었습니다...";
            }
            catch(\Exception $e){
                DB::rollback();
                return response()->json($e->getMessage()); 
            }           
        }        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showAvailableRooms()
    {
        // $rooms = Room::
        //     select ('room_number')
        //     ->get();

        //아래는 cache(redis) example(hours를 사용 할때)
        //phpmyadmin에서 manual로 record를 삭제 혹은 추가시에는 반드시
        //php artisan cache:clear(ubuntu => cd/Code/laravel6x/laravel6x)를 수행 할 것.
        $rooms = Cache::store('redis')->remember('roomNumber', now()->addHours(24), function() {
            $rooms = Room::
                    select('room_number')
                    ->get();
            return $rooms;
        });

        $rooms = collect($rooms);
        $calendars = Calendar::
            select ('room_number')
            ->get();
        $calendars = collect($calendars);
        $selectedRooms = $rooms->diff($calendars);//diff 는 diffAssoc 로 바꾸어야 한다.
        return $selectedRooms;
    }

    public function selectAvailableRoom()
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
       
        //$selectedRooms = $rooms->diffAssoc($calendars);
        $selectedRooms = $rooms->diffAssoc($calendars)->first();
        
        return $selectedRooms;
    }

    public function showRoomType()
    {
        // $roomTypes = Roomtype::
        //     select ('*')
        //     ->get();
        
        //아래는 cache(redis) example(hours를 사용 할때 )
        $roomTypes = Cache::store('redis')->remember('roomTypes', now()->addHours(24), function() {
            return Roomtype::all();
        });

        return $roomTypes;
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
