<template>
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Passport Login</div>

                <div class="card-body">
                    <form method="POST" action="" @submit.prevent="login()">                       
                        <div class="form-group row">
                            <!-- <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label> -->
                            <label for="username" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                            <div class="col-md-6">
                                <!-- <input id="email" type="email" class="form-control" name="email" v-model="email" required autocomplete="email" autofocus> -->
                                <input id="username" type="email" class="form-control" name="username" v-model="username" required autofocus>
                                <!-- <span v-if="serverErrorMsgs.email" class="error text-danger">{{ serverErrorMsgs.email[0] }}</span> -->
                                <span v-if="serverErrorMsgs.username" class="error text-danger">{{ serverErrorMsgs.username[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" v-model="password" required autocomplete="current-password">                
                                <span v-if="serverErrorMsgs.password" class="error text-danger">{{ serverErrorMsgs.password[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" v-model="remember" id="remember">
                                    <label class="form-check-label" for="remember">
                                         Remember Me                                     
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                   Login
                                </button>
                                <a class="btn btn-link" href="/password/reset">
                                        Forgot Your Password
                                </a>
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
                serverMsg: "",
                serverErrorMsgs: [],
                //email: "",
                username: "",
                password: "",
                remember: "",
                token: localStorage.getItem('access_token') || null
            }
        },

        methods: {
            login(){
                this.$Progress.start();
                if (this.remember == false){ ////
                    this.remember = "";
                }                
                axios.post('/api/login',{
                    //email: this.email,
                    username:this.username,
                    password: this.password,
                    //remember: this.remember
                })
                .then(response => {
                    //console.log(response.data)
                    this.serverMsg = response.data
                    // this.$Progress.finish();
                    // if (this.serverMsg == "admin")
                    // {
                    //     window.location.href = '/admin/index';
                    // }else if (this.serverMsg == "noAdmin"){
                    //     window.location.href = '/home';
                    // }else{
                    //     //
                    // }
                    this.token = response.data.access_token
                    localStorage.setItem('access_token', this.token)
                    //window.location.href = '/booking/directBooking';
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
