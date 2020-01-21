<template>
    <div>

        <div class="col-md-12">
            <div class="formform-group" :class="{ 'form-group--error':$v.listper.ListName.$error }">
                <label>Tên quyền<span class="text-danger" >*</span></label>
                <input type="text"
                v-model="$v.listper.ListName.$model"
                :class="{'border border-danger': $v.listper.ListName.$error}" 
                :disabled="isDisable" 
                class="form-control">
                <span v-if="!$v.listper.ListName.required" class="text-danger error">Trường không được để trống</span>
                <span v-if="!$v.listper.ListName.minLength" class="text-danger error">Vui lòng nhập tối thiểu {{$v.listper.ListName.$params.minLength.min}} kí tự</span>
                <span v-if="!$v.listper.ListName.maxLength" class="text-danger error">Vui lòng nhập tối da {{$v.listper.ListName.$params.maxLength.max}} kí tự</span>
            </div>
            
        </div>
        <div class="col-md-12">
            <div class="formform-group">
                <label>Biểu tượng<span class="text-danger" >*</span></label>
                <input type="text" v-model="listper.Icon" :disabled="isDisable" class="form-control">
            </div>
        </div>
        <div class="col-md-12">
            <div class="formform-group" >
                <label>Mô tả</label> 
                <textarea :disabled="isDisable" v-model="listper.Description"  cols="30" rows="3"    class="form-control"></textarea>
            </div>
        </div>
        <div class="col-md-12">
            <div class="formform-group" >
                <p-check :disabled="isDisable" class="p-switch p-fill" color="success_gren" :checked="listper.Active" v-model="listper.Active" >
                    {{ listper.Active==1 ? 'Hoạt động': 'Tạm ngưng' }}
                </p-check>
            </div>
        </div>
        <div class="clearfix"></div>
        <hr>
        <div class="form-group row">
            <div class="col-md-12 col-sm-12">
                <button  :disabled="isDisable" @click="ResetForm"  type="reset" class="btn btn-primary"><i class="fa fa-rotate-left"></i> Làm lại</button> 
                <button :disabled="isDisable" @click="createList"  type="submit" class="btn btn-success">
                    <i :class="{'fa fa-plus': !isSave,'fa fa-spinner fa-spin': isSave}"></i>
                    {{isSave==true ? 'Đang xử lý' : 'Lưu lại'}}</button>
            </div>
        </div>
    </div>
</template>
<script>
import { required, minLength, between,maxLength } from 'vuelidate/lib/validators'
export default {
    data() {
        return {
            isDisable: false,
            isSave: false,
            listper: {
                ListName: '',
                Icon: '',
                Description: '',
                Active: true
            }
        }
    },
    validations: {
		listper: {
			ListName: {
				required,
				minLength: minLength(2),
				maxLength: maxLength(225)
            }
		}
    },
    methods: {
        ResetForm(){
            const self = this;
			self.listper = {
                ListName: '',
                Icon: '',
                Description: '',
                Active: true
			};
		},
        createList(){
            const self = this;
            self.$v.$touch();
            if (!self.$v.$invalid) {
                self.isDisable = true;
				self.isSave = true;
				self.$Progress.start();
                axios.post("/bgdata/listpermission",{listper: self.listper})
				.then(res => {
					if (res.data.Status == true) {
						this.$Progress.finish();
						self.isDisable = false;
						self.isSave = false;
                        self.$emit('listenisSaveCheck',true);	
						this.$notify({
						  group: 'infomation',
						  type: 'success',
						  title: 'THÔNG BÁO',
						  text: 'Thêm Mới Thành Công !'
						});
						$('#exampleModal').modal('hide');
					}else if (res.data.Status == 2) {
                        window.location.href = 'admincp/login';
                    }
				})
				.catch(err => {
					this.$Progress.finish();
					self.isDisable = false;
					self.isSave = false;
					this.$notify({
						group: 'infomation',
						type: 'error',
						title: 'LỖI',
						text: 'Có lỗi trong quá trình !'
					});
				})
            }else{
                self.$notify({
				  group: 'infomation',
				  type: 'warn',
				  title: 'CHÚ Ý',
				  text: 'Vui Lòng kiểm tra lại thông tin !'
				});
            }
        }
    }
}
</script>