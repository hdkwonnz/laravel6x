<template>
    <div>
        <div class="row">
            <div class="col col-lg-12">
               <Fullcalendar :plugins="calendarPlugins" :events="events"/> 
            </div>            
        </div>
    </div>
</template>

<script>
    import Fullcalendar from "@fullcalendar/vue";
    import dayGridPlugin from "@fullcalendar/daygrid";
    import interactionPlugin from "@fullcalendar/interaction";

    export default {
        components: {
            Fullcalendar // make the <FullCalendar> tag available
        },
        data(){
            return{
                calendarPlugins: [dayGridPlugin, interactionPlugin],
                events: ""                               
            }
        },

        methods: {
            getEvents() {
                //아래는 비디오 원본                
                // axios.get("/calendar/show")
                // .then(resp => (this.events = resp.data))               
                // .catch(err => console.log(err.response.data))

                //아래는 내 방식으로 수정했다(progress bar 사용을 위해...)
                this.$Progress.start();
                axios.get("/calendar/show")
                .then(resp => {
                    this.events = resp.data
                    this.$Progress.finish()
                    })            
                .catch(err => {console.log(err.response.data)})
            },                    
        },
       
        created() {            
            this.getEvents();            
        },

        mounted() {
            
        }
    };
</script>

<style lang="css">
    @import "~@fullcalendar/core/main.css";
    @import "~@fullcalendar/daygrid/main.css";

    .fc-title {
    color: #fff;
    }

    .fc-title:hover {
    cursor: pointer;
    }
</style>
