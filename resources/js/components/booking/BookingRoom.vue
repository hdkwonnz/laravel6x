<template>
    <div>
        <form method="POST" action="" @submit.prevent="showAvailableRooms()">
            <div class="form-group row">
                <label for="Type" class="col col-sm-2 col-md-1 col-lg-1 col-form-label text-md-right">Type</label>   
                <div class="col col-sm-4 col-md-3 col-lg-3">
                    <!-- 지우지 말 것... 20/02/2020-->
                    <!-- :value="roomType.id 에 따라 model="roomTypeId 도 값이 바뀐다 -->
                    <!-- <select class="form-control" v-model="roomTypeId">
                        <option disabled value="">Please select one</option>
                        <option v-for="roomType in roomTypes" :key="roomType.id" :value="roomType.id" required>{{ roomType.type }}</option>                       
                    </select> -->
                    <select class="form-control" v-model="type">
                        <option disabled value="">Please select one</option>
                        <option v-for="roomType in roomTypes" :key="roomType.id" :value="roomType.type" required>{{ roomType.type }}</option>                       
                    </select>
                </div>
            </div>
            <div class="form-group row">                          
                <label for="startDate" class="col col-sm-2 col-md-1 col-lg-1 col-form-label text-md-right">Start</label>
                <div class="col-md-3">
                    <input type="date" id="startDate" class="form-control" v-model="startDate" required>                                                                                    
                </div>
                <label for="endDate" class="col col-sm-2 col-md-1 col-lg-1 col-form-label text-md-right">End</label>
                <div class="col-md-3">
                    <input type="date" id="endDate" class="form-control" v-model="endDate" required>
                </div>
            </div>
            <div class="form-group row mb-4">
                <div class="col-md-6 offset-md-1">
                    <button type="submit" class="btn btn-lg btn-primary">
                        Click
                    </button>
                </div>
            </div>
        </form>

        <div class="row">
            <div v-for="room in rooms" :key="room.id" class="col col-sm-4 col-md-2 col-lg-2">
                <div class="btn btn-lg btn-success mb-3">
                    <a href="" class="text-white text-decoration-none" @click.prevent="createBooking(room.room_number,type)">{{room.room_number}}호</a>
                </div>     
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                type: "",               
                startDate: "",
                endDate: "",
                roomTypeId: "",
                rooms: "",
                roomTypes: ""               
            }
        },

        methods: {
            createBooking(room_number,type){
                //아래처럼 하면 새 화면이 열리지 않는다.
                // window.location.href = '/booking/createBooking/' + room_number;
                //아래처럼 하면 새 화면이 열린다.
                // window.open('/booking/createBooking/' + room_number, "_blank", "");
                //alert(room_number + type);
                //아래는 다른 프레임워크에서 사용하는 방법(20/02/2020)
                // window.open('/booking/createBooking?room_number=' + room_number + '&type=' + type, "_blank", "");
                window.open('/booking/createBooking/' + room_number + '/' + type, "_blank", "");
            },
            
            showAvailableRooms(){
                axios.get('/booking/showAvailableRooms')                             
                    .then(response => {
                        this.rooms = response.data;                
                    })              
                    .catch(() => {

                    });
            },

            showRoomType(){
                axios.get('/booking/showRoomType')
                .then(response => {
                    this.roomTypes = response.data
                });        
            }
        },

        created() {            
            this. showRoomType();   
        },

        mounted() {
            
        }
    }
</script>
