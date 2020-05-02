<template>
  <div class="container">
    <div class="row">
      <div class="col-lg-12 mb-4">
        <h1>이벤트 조회 입력 수정 삭제</h1>
      </div>

      <div class="col-lg-12">        
        <form @submit.prevent>
          <div class="form-group row">
            <label for="event_name" class="col-md-2 col-form-label text-md-right">Event Name</label>
            <div class="col-md-6">
              <input type="text" id="event_name" class="form-control" v-model="newEvent.event_name" required>
            </div>            
          </div>

          <div class="form-group row">                          
            <label for="start_date" class="col-md-2 col-form-label text-md-right">Start Date</label>
            <div class="col-md-3">
              <input type="date" id="start_date" class="form-control" v-model="newEvent.start_date" required>                                                                                    
            </div>
            <label for="end_date" class="col-md-2 col-form-label text-md-right">End Date</label>
            <div class="col-md-3">
              <input type="date" id="end_date" class="form-control" v-model="newEvent.end_date" required>
            </div>
          </div>   
                      
          <div class="col-md-6 mb-4" v-if="addingMode">
            <button class="btn btn-sm btn-primary" @click="addNewEvent">Save Event</button>
          </div>
          <template v-else>
            <div class="col-md-6 mb-4">
              <button class="btn btn-sm btn-success" @click="updateEvent">Update</button>
              <button class="btn btn-sm btn-danger" @click="deleteEvent">Delete</button>
              <!-- <button class="btn btn-sm btn-secondary" @click="addingMode = !addingMode">Cancel</button> -->
              <button class="btn btn-sm btn-secondary" @click="cancelEvent">Cancel</button>
            </div>
          </template>         
        </form>
      </div>

      <div class="col-lg-12">
        <Fullcalendar @eventClick="showEvent" :plugins="calendarPlugins" :events="events"/>
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
  data() {
    return {
      calendarPlugins: [dayGridPlugin, interactionPlugin],     
      events: "",
      newEvent: {
        event_name: "",
        start_date: "",
        end_date: ""
      },
      addingMode: true,
      indexToUpdate: ""
    };
  },
  created() {
    this.getEvents();
  },
  methods: {
    cancelEvent(){
      this.addingMode = !this.addingMode;
      this.resetForm();
    },
    addNewEvent() {
      if (this.newEvent.event_name == ""){
        alert("이벤트 이름을 넣어 주세요!!!");
        return false;
      }
      if (this.newEvent.start_date == ""){
        alert("시작 날짜를 넣어 주세요!!!");
        return false;
      }
      if (this.newEvent.end_date == ""){
        alert("끝 날짜를 넣어 주세요!!!");
        return false;
      }

      //var actualDate = new Date(this.newEvent.start_date);     
      //alert(actualDate);
      //alert(this.newEvent.start_date);
      // alert (new Date().toLocaleString().split(',')[0]);//working
      //alert (actualDate.toLocaleString().split(',')[0]);//working
      // var d = new Date().toISOString().slice(0,10); //working yyyy-mm-dd
      // alert(d);
      // var nextDay = new Date(this.newEvent.start_date);
      // alert (nextDay.setDate(actualDate.getDate() + 1));
      ////stackoverflow.com/questions/23081158/javascript-get-date-of-the-next-day/23081260 => 밑부분
      var today = new Date(this.newEvent.start_date);
      var tomorrow = new Date(today.getTime() - (24 * 60 * 60 * 1000)); //"-" 가 "+" 이면 다음날...
      alert(tomorrow);
      var d = tomorrow.toISOString().slice(0,10);
      alert(d);

      return false;

      axios.post("/calendar/store", {
        ...this.newEvent          
      })
        .then(data => {
          this.getEvents(); // update our list of events
          this.resetForm(); // clear newEvent properties (e.g. title, start_date and end_date)
        })
        .catch(err =>
          console.log("Unable to add new event!", err.response.data)
        );
    },
    showEvent(arg) {
      this.addingMode = false;
      const { id, title, start, end } = this.events.find(
        event => event.id === +arg.event.id
      );
      this.indexToUpdate = id;
      this.newEvent = {
        event_name: title,
        start_date: start,
        end_date: end
      };
    },
    updateEvent() {
      axios       
        .post("/calendar/update", {
          id: this.indexToUpdate,
          event_name: this.newEvent.event_name,
          start_date: this.newEvent.start_date,
          end_date: this.newEvent.end_date
        })
        .then(resp => {
          this.resetForm();
          this.getEvents();
          this.addingMode = !this.addingMode;
        })
        .catch(err =>
          console.log("Unable to update event!", err.response.data)
        );
    },
    deleteEvent() {
      var message = "정말로 취소 하시겠습니까?";
      var result = confirm(message);
      if (result == true){
        axios
        .post("/calendar/delete", {
          id: this.indexToUpdate
        })
        .then(resp => {
          this.resetForm();
          this.getEvents();
          this.addingMode = !this.addingMode;
        })
        .catch(err =>
          console.log("Unable to delete event!", err.response.data)
        );
      }      
    },
    getEvents() {
      axios
        .get("/calendar/show")
        .then(resp => (this.events = resp.data))
        .catch(err => console.log(err.response.data));
    },  
    resetForm() {
      Object.keys(this.newEvent).forEach(key => {
        return (this.newEvent[key] = "");
      });
    }
  },
  watch: {
    indexToUpdate() {
      return this.indexToUpdate;
    }
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

