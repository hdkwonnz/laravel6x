<template>
    <div>
        <!-- <div class="row">       
            <label for="From" class="col col-sm-1 col-md-1 col-lg-1 col-form-label text-md-right">From</label>
            <div class="col col-sm-3 col-md-3 col-lg-3">                    
                <input type="date" name="date" v-model="fromDate" class="form-control" required>
            </div>
            <label for="To" class="col col-sm-1 col-md-1 col-lg-1 col-form-label text-md-right">To</label>
            <div class="col col-sm-3 col-md-3 col-lg-3">                    
                <input type="date" name="date" v-model="toDate" class="form-control" required>
            </div>
            <button class="btn btn-sm btn-secondary" @click="showByDate">Click</button>      
        </div> -->

        <form method="POST" action="" @submit.prevent="showByDate()">
            <div class="form-group row">
                <label for="From" class="col col-sm-1 col-md-1 col-lg-1 col-form-label text-md-right">From</label>
                <div class="col col-sm-3 col-md-3 col-lg-3">                    
                    <input type="date" name="date" v-model="fromDate" class="form-control" required>
                </div>
                <label for="To" class="col col-sm-1 col-md-1 col-lg-1 col-form-label text-md-right">To</label>
                <div class="col col-sm-3 col-md-3 col-lg-3">                    
                    <input type="date" name="date" v-model="toDate" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-sm btn-secondary">Click</button>
            </div>                             
        </form>
        
        <div class="table-responsive mt-3">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Start</th>
                        <th>End</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Room</th>
                        <th>members</th>                        
                        <th>Status</th>                       
                    </tr>
                </thead>
                <tbody>
                    <!-- 아래는 pagination을 위해 일반적인 것과 조금 다르다.-->
                    <tr v-for="booking in bookings.data" :key="booking.id" >
                        <td >
                            {{ booking.start }}
                        </td>
                        <td >
                            {{ booking.end }}
                        </td>
                        <td>{{ booking.customer_name }}</td>
                        <td>{{ booking.customer_phone }}</td>
                        <td>{{ booking.customer_email }}</td>
                        <td>{{ booking.title }}</td>
                        <td>{{booking.room_number}}</td>
                        <td>3</td>
                        <td>check-in</td>                                                                        
                    </tr>
                </tbody>
            </table>           
        </div> <!-- end table-responsive -->

        <!-- for pagination -->
        <!-- <pagination :data="appointments" @pagination-change-page="getResults"></pagination> -->
        <!-- <pagination :data="bookings" align="center" :limit="2" @pagination-change-page="getResults"></pagination> -->
        <pagination :data="bookings" align="center" :limit="2" @pagination-change-page="getResults">
	        <span slot="prev-nav">&lt; Previous</span>
	        <span slot="next-nav">Next &gt;</span>
        </pagination>
    </div>   
</template>

<script>
    export default {
        data(){
            return{
                 fromDate: "",
                 toDate: "",                             
                 //bookings: []
                 bookings: {}//중요
            }
        },

        methods: {
            //아래는 한개 이상의 parameter 처리가 가능
            getResults(page = 1) { //for pagination
                this.$Progress.start();////
                axios.get('/admin/showBookingByDate', {
                    params: {page: page, fromDate: this.fromDate, toDate: this.toDate}
                })
                    .then(response => {
                        this.bookings = response.data;
                        this.$Progress.finish();////
                    });
                    //this.$Progress.finish();////
            },                        
           
            //아래는 한개 이상의 parameter 처리가 가능
            showByDate(){                
                this.$Progress.start();//               
                axios.get('/admin/showBookingByDate', {
                    params:{fromDate: this.fromDate, toDate: this.toDate}
                    })                            
                    .then(({data}) =>        //pagination을 위해.... "{}" 사용했음...
                      (this.bookings = data))//pagination을 위해....                                                                 
                    // .then(data => {
                    //     this.bookings = data;
                    //     this.$Progress.finish();//
                    // })
                    .catch(() => {

                    })
            }             
        },

        created() {           
            
        },

        mounted() {
            
        }
    }
</script>
