<template>
    <div>
        <!-- for css test -->
        <div class="test_class">
            <span>hehe...</span>
        </div>
        <!-- for js test -->
        <div class="hehe">

        </div>
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

        <div v-if="messageSw" class="row">           
            <div class="col-md-6 offset-md-1">
                <span class="text-danger">원하시는 날짜에는 이미 예약이 끝났습니다.</span>
            </div>            
        </div>

        <div class="row">
            <div v-for="room in rooms" :key="room.id" class="col col-sm-4 col-md-2 col-lg-2">
                <div class="btn btn-lg btn-success mb-3">
                    <!-- <a href="" class="text-white text-decoration-none">{{room.room_number}}호</a> -->
                    <span class="text-white">{{room.room_number}}호</span>
                </div>     
            </div>
        </div>
    </div>
</template>

<script>  
    export default {
        data(){
            return{                                
                messageSw: false,                                        
                type: "",               
                startDate: "",
                endDate: "",
                //roomTypeId: "",
                rooms: "",
                roomTypes: ""               
            }
        },

        methods: {                        
            showAvailableRooms(){               
                if (this.type == ""){
                    alert("room type을 선택 하십시요...");
                    return false;
                };
                var today = new Date();//get date                             
                today = today.toISOString().slice(0,10); //yyyy-mm-dd format
                
                if (this.startDate < today){
                    alert("시작 날짜는 금일 보다 커야 합니다..." );
                    return false;
                }
                if (this.startDate >= this.endDate ){
                    alert("날짜 선택이 틀렸습니다...");
                    return false;
                }

                this.$Progress.start();
                axios.get('/admin/showAvailableRooms', {
                    params: {
                        type: this.type,
                        start: this.startDate,
                        end: this.endDate
                    }
                })
                    .then(response => {                       
                        this.rooms = response.data;                      
                        if (this.rooms.length == 0){
                            this.messageSw = true;                                                     
                        } else{
                            this.messageSw = false;                            
                        }
                        this.$Progress.finish();
                    })                   
                    .catch(() => {

                    });
            },

            showRoomType(){
                this.$Progress.start();
                axios.get('/booking/showRoomType')
                .then(response => {
                    this.roomTypes = response.data
                    this.$Progress.finish();
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
