// 현재까지 파악한 내용을 정리해 보면...(08/03/2010)
// style(css file)은 public/myCss/test.css로 부터 이곳에 import 할 수 있다.
// class 이름이 같으면 모든 view에서 동일하게 적용 된다...해결 방법 찾는 중...
// 그러나 script(js file)은 public/myJs/test.js로 부터 import 할 수 없다.
// 그래서 resource/js/components/booking/test.js을 만들고 아래 하단의
// script 란에서 [require ("./test.js")] 혹은 [import "./test.js]"하여 사용한다.
// 두개의 test.js에서 사용하는 class 이름이 같을 경우 양쪽의 내용이 모두 반영 된다.
// 아직 이문제 해결 법은 찾지 못 했음......
// 외부에 JS FILE을 만들지 말고 그냥 내부에서 inline 코딩하는 방법을 생각 중...
// inline coding 해도 문제 해결 않됨....
// 현재까지 내린 결론은 class 이름을 모두 다르게 주는 수 밖에 없는 것 같다....
// 마침내 해결책을 찾았다. css, js file을 이곳 components에서 주지말고 views에서
// 주면 해결 된다.이거 찾는데 꼬박 24시간 걸렸음...(09/03/2020)

//아래처럼 css file을 이곳에서 불러 사용하면 절대 않됨....
//이것 알아 내는데 꼬박 24시간 걸렸음.(09/03/2020)
<style>
    /* @import "/myCss/test.css"; */
</style>

<template>
    <div>
        <!-- for css test -->
        <div class="test_class">
            <span>hehe...</span>
        </div>
        <!-- for js test -->
        <div class="hehe">

        </div>
        <form v-if="form1Sw" method="POST" action="" @submit.prevent="selectAvailableRoom()">
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

        <!-- <div class="row">
            <div v-for="room in rooms" :key="room.id" class="col col-sm-4 col-md-2 col-lg-2">
                <div class="btn btn-lg btn-success mb-3">
                    <a href="" class="text-white text-decoration-none">{{room.room_number}}호</a>
                </div>     
            </div>
        </div> -->

        <form v-if="form2Sw" method="POST" action="" @submit.prevent="addBooking()">
            <div class="form-group row">
                <label for="typ" class="col col-sm-2 col-md-1 col-lg-1 col-form-label text-md-right">type</label>   
                <div class="col col-sm-4 col-md-3 col-lg-3">
                    <input type="text" id="type" class="form-control" v-model="type" required readonly>
                </div>
            </div>
            <div class="form-group row">                          
                <label for="startDate" class="col col-sm-2 col-md-1 col-lg-1 col-form-label text-md-right">Start</label>
                <div class="col-md-3">
                    <input type="date" id="startDate" class="form-control" v-model="startDate" required readonly>                                                                                    
                </div>
                <label for="endDate" class="col col-sm-2 col-md-1 col-lg-1 col-form-label text-md-right">End</label>
                <div class="col-md-3">
                    <input type="date" id="endDate" class="form-control" v-model="endDate" required readonly>
                </div>                
            </div>
            <div class="form-group row">                          
                <label for="startDate" class="col col-sm-2 col-md-1 col-lg-1 col-form-label text-md-right">Name</label>
                <div class="col-md-3">
                    <input type="text" id="name" class="form-control" v-model="name" required>                                                                                    
                </div>
                <label for="endDate" class="col col-sm-2 col-md-1 col-lg-1 col-form-label text-md-right">Phone</label>
                <div class="col-md-3">
                    <input type="text" id="phoneNo" class="form-control" v-model="phoneNo" required>
                </div>                
            </div>
            <div class="form-group row">                          
                <label for="Email" class="col col-sm-2 col-md-1 col-lg-1 col-form-label text-md-right">Email</label>
                <div class="col-md-3">
                    <input type="email" id="email" class="form-control" v-model="email" required>                                                                                    
                </div>
                <label for="Members" class="col col-sm-2 col-md-1 col-lg-1 col-form-label text-md-right">Members</label>
                <div class="col-md-3">
                    <input type="number" id="members" class="form-control" v-model="members" min="1" max="5" required>                                                                                    
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

        <!-- <div class="row">
            <span class="text-success">{{serverMsg}}</span>
        </div> -->

    </div>
</template>


<script>
    //아래처럼 js file을 이곳에서 불러 사용하면 절대 않됨....
    //이것 알아 내는데 꼬박 24시간 걸렸음.(09/03/2020)
    //require ("./test.js")//이렇게 해도 되고
    //import "./test.js" //이렇게 해도 된다.
  
    export default {
        data(){
            return{
                members: "",
                serverMsg: "",
                messageSw: false,
                name: "",
                phoneNo: "",
                email: "",
                form1Sw: true,
                form2Sw: false,
                type: "",               
                startDate: "",
                endDate: "",
                //roomTypeId: "",
                rooms: "",
                roomTypes: ""               
            }
        },

        methods: {
            addBooking(){
                this.$Progress.start();
                axios.post('/booking/addBooking',{
                    title: this.type,
                    start: this.startDate,
                    end: this.endDate,
                    name: this.name,
                    phoneNo: this.phoneNo,
                    email: this.email,
                    roomNo: this.rooms.room_number,
                    members: this.members
                })
                .then(response => {
                    this.serverMsg = response.data;
                    alert(this.serverMsg);
                    this.members = "";
                    this.serverMsg = "";
                    this.name = "";
                    this.phoneNo = "";
                    this.email = "";
                    this.type = "";
                    this.startDate = "";
                    this.endDate = "";
                    this.form1Sw = true;
                    this.form2Sw = false;
                    this.rooms = "";
                    this.$Progress.finish();
                })
                .catch(error => {

                })
            },
            
            selectAvailableRoom(){               
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
                axios.get('/booking/selectAvailableRoom', {
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
                            this.form1Sw = true;
                            this.form2Sw = false;
                        } else{
                            this.messageSw = false;
                            this.form1Sw = false;
                            this.form2Sw = true;
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
