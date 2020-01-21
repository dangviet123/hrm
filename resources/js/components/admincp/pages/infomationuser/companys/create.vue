<template>
    <div>

        <div class="col-md-6">
            <div class="formform-group " :class="{ 'form-group--error':$v.listGroup.IDCode.$error }">
                <label>Mã <span class="text-danger" >*</span></label> 
                <div class="input-group" style="margin-bottom: initial;">
                    <input 
                    :disabled="isDisableIDCode"
                    type="text" 
                    @input="uniqueIDCode=false"
                    v-model="$v.listGroup.IDCode.$model" 
                    :class="[{'border border-danger': $v.listGroup.IDCode.$error},{'border border-danger':uniqueIDCode}]" 
                    class="form-control">
                    <button class="btn btn-defaults btn-sm" @click="isDisableIDCode=!isDisableIDCode" style="margin-bottom: initial;margin-left: 5px;margin-right:initial;">
                        <i :class="isDisableIDCode == true ? 'fa fa-bars' : 'fa fa-pencil'" ></i>
                    </button>
                </div>
                <span v-if="!$v.listGroup.IDCode.required" class="text-danger error">Trường không được để trống</span>
                <span v-if="!$v.listGroup.IDCode.isUnique" class="text-danger error">Trường đã tồn tại trên hệ thống</span>
                <span v-show="uniqueIDCode" class="text-danger">Trường đã tồn tại trên hệ thống</span>
                <span v-if="!$v.listGroup.IDCode.minLength" class="text-danger error">Vui lòng nhập tối thiểu {{$v.listGroup.IDCode.$params.minLength.min}} kí tự</span>
                <span v-if="!$v.listGroup.IDCode.maxLength" class="text-danger error">Vui lòng nhập tối da {{$v.listGroup.IDCode.$params.maxLength.max}} kí tự</span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="formform-group" :class="{ 'form-group--error':$v.listGroup.CompanyName.$error }">
                <label>Tên công ty/ Chi nhánh<span class="text-danger" >*</span></label>
                <input type="text"
                v-model="$v.listGroup.CompanyName.$model"
                :class="{'border border-danger': $v.listGroup.CompanyName.$error}" 
                :disabled="isDisable"
                class="form-control">
                <span v-if="!$v.listGroup.CompanyName.required" class="text-danger error">Trường không được để trống</span>
                <span v-if="!$v.listGroup.CompanyName.minLength" class="text-danger error">Vui lòng nhập tối thiểu {{$v.listGroup.CompanyName.$params.minLength.min}} kí tự</span>
                <span v-if="!$v.listGroup.CompanyName.maxLength" class="text-danger error">Vui lòng nhập tối da {{$v.listGroup.CompanyName.$params.maxLength.max}} kí tự</span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="formform-group" >
                <label>Điện thoại</label> 
                <input :disabled="isDisable"  type="text"  v-model="listGroup.Phone" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="formform-group" >
                <label>Mã số thuế</label> 
                <input :disabled="isDisable"  type="text"  v-model="listGroup.TaxCode" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="formform-group" >
                <label>Giám đốc</label> 
                <input :disabled="isDisable"  type="text"  v-model="listGroup.Director" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="formform-group" >
                <label>Quản lý</label> 
                <input :disabled="isDisable"  type="text"  v-model="listGroup.Manager" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="formform-group" >
                <label>Tài khoản ngân hàng</label> 
                <input :disabled="isDisable"  type="text"  v-model="listGroup.BankAccount" class="form-control">
            </div>
        </div>

        <div class="col-md-6">
            <div class="formform-group" >
                <label>Địa chỉ</label> 
                <input :disabled="isDisable"  type="text"  v-model="listGroup.Address" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="formform-group" >
                <label>Mô tả</label> 
                <textarea :disabled="isDisable" v-model="listGroup.Description"  cols="30" rows="2"    class="form-control"></textarea>
            </div>
        </div>
        <div class="col-md-6">
            <div class="formform-group" >
                <label>Ghi chú</label> 
                <textarea :disabled="isDisable" v-model="listGroup.Notes"  cols="30" rows="2"    class="form-control"></textarea>
            </div>
        </div>
        <div class="col-md-6">
            <div class="formform-group" >
                <label>Thứ tự</label> 
                <input :disabled="isDisable" type="text"  v-model="listGroup.OrderBy" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
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
            isDisableIDCode: true,
            uniqueIDCode: false,
            listGroup: {
                CompanyName: '',
                Description: '',
                OrderBy: 0,
                IDCode: '',
                Address: '',
                Notes: '',
                Phone: '',
                TaxCode: '',
                Director: '',
                Manager: '',
                BankAccount: '',
                Active: true
            }
        }
    },
    validations: {
		listGroup: {
			CompanyName: {
				required,
				minLength: minLength(2),
				maxLength: maxLength(225)
            },
            IDCode: {
                required,
                isUnique(value){
                    if (value == '') return true
                    // const response = await axios.post("/staff-information/companys/checkIDCode",{IDCode: value,idCompany: 0});
                    // return response.Status;
                    return new Promise((resolve, reject) => {
                        axios.post("/staff-information/companys/checkIDCode",{IDCode: value,idCompany: 0})
                        .then(res => {
                            resolve(res.data.Status);
                        })
                    });
                },
				minLength: minLength(2),
				maxLength: maxLength(10)
            }
		}
    },
    created(){
		const self =this;
		self.$Progress.start();
		axios.get("/staff-information/companys/create")
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
                CompanyName: '',
                Description: '',
                Address: '',
                Notes: '',
                Phone: '',
                TaxCode: '',
                Director: '',
                Manager: '',
                BankAccount: '',
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
                axios.post("/staff-information/companys",{listGroup: self.listGroup})
				.then(res => {
					if (res.data.Status == true) {
						self.$Progress.finish();
						self.isDisable = false;
						self.isSave = false;
                        self.$emit('listenisSaveCheck',true);	
						self.$notify({
						  group: 'infomation',
						  type: 'success',
						  title: 'THÔNG BÁO',
						  text: 'Thêm Mới Thành Công !'
						});
						$('#myLargeModal').modal('hide');
					}else if(res.data.Status == false){
                        self.$Progress.finish();
                        self.isDisable = false;
                        self.isSave = false;
                        self.uniqueIDCode = true;
                        self.$notify({
                            group: 'infomation',
                            type: 'error',
                            title: 'CHÚ Ý',
                            text: res.data.Massages
                        });
                    }else if (res.data.Status == 2) {
                        window.location.href = 'admincp/login';
                    }
                    else{
                        self.$Progress.fail();
						self.isDisable = false;
                        self.isSave = false;
                        self.$notify({
                            group: 'infomation',
                            type: 'error',
                            title: 'LỖI',
                            text: 'Có lỗi trong quá trình !'
                        });
                    }
				})
				.catch(err => {
					self.$Progress.finish();
					self.isDisable = false;
					self.isSave = false;
					self.$notify({
						group: 'infomation',
						type: 'error',
						title: 'LỖI',
						text: 'Có lỗi trong quá trình !'
					});
				})
            }
            else{
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