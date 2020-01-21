<template>
    <div class="login_wrapper" style="max-width: 380px;">
        <div class="right_col" role="main" >
            <div class="animate form login_form" style="padding: 30px;border: 1px solid #c8c8c8;" >
                <section class="login_content" style="text-shadow: initial;">
                    <form>
                        <h1>ĐĂNG NHẬP</h1>
                        <div v-if="isError" class="alert alert-danger "style="text-align: left;" >
                            <i class="glyphicon glyphicon-warning-sign" ></i> {{ MassageError }}
                        </div>
                        
                        <div class="formform-group" :class="{ 'form-group--error':$v.listUser.Email.$error }">
                            <input 
                            type="text" 
                            :disabled="isDisable" 
                            class="form-control" placeholder="Email" 
                            :class="{'border border-danger': $v.listUser.Email.$error}" 
                            v-model="$v.listUser.Email.$model" />
                            
                        </div>
                        <div class="formform-group" :class="{ 'form-group--error':$v.listUser.Password.$error }">
                            <input type="password" 
                            :disabled="isDisable" 
                            class="form-control" 
                            placeholder="Mật khẩu" 
                            :class="{'border border-danger': $v.listUser.Password.$error}" 
                            v-model="$v.listUser.Password.$model" />
                        </div>
                        <div>
                            <button :disabled="isDisable" type="button" @click="checkLogin" class="btn btn-primary">
                                <i :class="{'fa fa-user': !isSave,'fa fa-spinner fa-spin': isSave}"></i>
                                {{isSave==true ? 'Đang xử lý' : 'Đăng nhập'}}
                            </button>
                            <button :disabled="isDisable" type="button" @click="resetForm" class="btn btn-info"><i class="fa fa-refresh" ></i> Làm Mới</button>
                        </div>
        
                        <div class="clearfix"></div>
        
                        <div class="separator">
                            <div class="clearfix"></div>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</template>
<script>
import { required, minLength, between,maxLength } from 'vuelidate/lib/validators'
export default {
    data() {
        return {
            listUser:{
                'Email': 'vietdq@phanam.com.vn',
                'Password': '123456'
            },
            isDisable: false,
            isSave: false,
            isError: false,
            MassageError: ''
        }
    },
    validations: {
		listUser: {
			Email: {
				required,
				minLength: minLength(5),
				maxLength: maxLength(225)
            },
            Password: {
                required,
				minLength: minLength(5),
				maxLength: maxLength(225)
            }
		}
    },
    methods:{
        checkLogin(){
            this.$v.$touch();
            if (!this.$v.$invalid) {
                this.isDisable = true;
                this.isSave = true;
                axios.post("/login/checkLogin",{listUser: this.listUser})
                .then(res => {
                    if (res.data.Status == true) {
                        this.isDisable = false;
                        this.isSave = false;
                        this.isError = false;
                        window.location.href= "./admincp";
                    }else{
                        this.isDisable = false;
                        this.isSave = false;
                        this.isError = true;
                        this.MassageError = "Tài khoản hoặc mật khẩu không đúng ";
                    }
                }).catch(err => {

                })
            }else{
                this.isError = true;
                this.MassageError = "Vui lòng nhập đầy đủ thông tin";
            }

        },
        resetForm(){
            this.listUser ={
                'Email': '',
                'Password': ''
            };
            this.$v.$touch();
        }
    }
}
</script>