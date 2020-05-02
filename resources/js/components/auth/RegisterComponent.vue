<template>
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Register</div>

                <div class="card-body">
                    <form method="POST" action="" @submit.prevent="register()">                       
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" v-model="form.name" required autocomplete="name" autofocus>                                
                                <span v-if="serverErrorMsgs.name" class="error text-danger">{{ serverErrorMsgs.name[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" v-model="form.email" required autocomplete="email">
                                <span v-if="serverErrorMsgs.email" class="error text-danger">{{ serverErrorMsgs.email[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                            <!-- 아래 pattern은 내가 추가 minimum length = 8 와 동일 -->
                            <!-- stackoverflow.com/questions/45929862/why-doesnt-input-minlength-check-work-with-initial-value -->
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" v-model="form.password" pattern=".{8,}" required autocomplete="new-password">
                                <span v-if="serverErrorMsgs.password" class="error text-danger">{{ serverErrorMsgs.password[0] }}</span>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" v-model="form.password_confirmation" required autocomplete="new-password">
                                <span v-if="frontEndError" class="error text-danger">{{ frontEndError }}</span>
                            </div>                            
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        data(){
            return{
                frontEndError: "",               
                serverMsg: "",
                serverErrorMsgs: [],
                form: {
                    name: "",
                    email: "",
                    password: "",
                    password_confirmation: ""
                }                
            }
        },

        methods: {
            register(){
                // if (document.getElementById("password") !=  document.getElementById("password-confirm")){
                //     alert("The password confirmation does not match!!!");
                //     return false;
                // }
                if (this.form.password != this.form.password_confirmation){
                    this.frontEndError = "The password confirmation does not match!!!";
                    return false;
                }else{
                    this.frontEndError = "";
                }

                this.$Progress.start();                
                axios.post('/register',{
                    ...this.form
                })
                .then(response => {
                    this.serverMsg = response.data;
                    this.$Progress.finish();
                    if (this.serverMsg == "done")
                    {
                        window.location.href = '/showVerificationMsg';
                    }else{                   
                       //
                    }                                                          
                })
                .catch(error => {
                    this.serverErrorMsgs = error.response.data.errors;//laradoctor 참고 할 것
                })
            }
            
        },
        
        mounted() {
            
        }
    }
</script>
