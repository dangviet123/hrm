<template>
    <div>
        <div class="col-md-12">
            <div class="formform-group" >
                <label>Mã <span class="text-danger" >*</span></label> 
                <input disabled type="text"  v-model="listGroup.IDCode" class="form-control">
            </div>
        </div>
        <div class="col-md-12">
            <div class="formform-group" :class="{ 'form-group--error':$v.listGroup.LevelName.$error }">
                <label>Trình độ<span class="text-danger" >*</span></label>
                <input type="text"
                v-model="$v.listGroup.LevelName.$model"
                :class="{'border border-danger': $v.listGroup.LevelName.$error}" 
                :disabled="isDisable" 
                class="form-control">
                <span v-if="!$v.listGroup.LevelName.required" class="text-danger error">Trường không được để trống</span>
                <span v-if="!$v.listGroup.LevelName.minLength" class="text-danger error">Vui lòng nhập tối thiểu {{$v.listGroup.LevelName.$params.minLength.min}} kí tự</span>
                <span v-if="!$v.listGroup.LevelName.maxLength" class="text-danger error">Vui lòng nhập tối da {{$v.listGroup.LevelName.$params.maxLength.max}} kí tự</span>
            </div>
            
        </div>
        <div class="col-md-12">
            <div class="formform-group" >
                <label>Mô tả</label> 
                <textarea :disabled="isDisable" v-model="listGroup.Description"  cols="30" rows="3"    class="form-control"></textarea>
            </div>
        </div>
        <div class="col-md-12">
            <div class="formform-group" >
                <label>Thứ tự</label> 
                <input :disabled="isDisable" type="text"  v-model="listGroup.OrderBy" class="form-control">
            </div>
        </div>
        <div class="col-md-12">
            <div class="formform-group" >
                <p-check :disabled="isDisable" class="p-switch p-fill" color="success_gren" :checked="listGroup.Active" v-model="listGroup.Active" >
                    {{ listGroup.Active==1 ? 'Hoạt động': 'Tạm ngưng' }}
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
            listGroup: {
                LevelName: '',
                Description: '',
                OrderBy: 0,
                IDCode: '',
                Active: true
            }
        }
    },
    validations: {
		listGroup: {
			LevelName: {
				required,
				minLength: minLength(2),
				maxLength: maxLength(225)
            }
		}
    },
    created(){
		const self =this;
		self.$Progress.start();
		axios.get("/staff-information/levels/create")
		.then(res => {
			if (res.data.Status == true) {
                self.listGroup.OrderBy = res.data.OrderBy;
                self.listGroup.IDCode = res.data.IDCode;
				self.$Progress.finish();
			}else if (res.data.Status == 2) {
				window.location.href = 'admincp/login';
			}
		})
		.catch(err => {
			console.error(err); 
		})
	},
    methods: {
        ResetForm(){
            const self = this;
			self.listGroup = {
                LevelName: '',
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
                axios.post("/staff-information/levels",{listGroup: self.listGroup})
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
				  type: 'error',
				  title: 'CHÚ Ý',
				  text: 'Vui Lòng kiểm tra lại thông tin !'
				});
            }
        }
    }
}
</script>