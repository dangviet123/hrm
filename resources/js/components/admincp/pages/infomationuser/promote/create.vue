<template>
    <div class="row">
        <div v-if="isLoadingPage">
            <nav aria-label="breadcrumb" style="padding: 0px 10px;">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li> 
                    <li class="breadcrumb-item"><a href="#">Thông tin nhân viên</a></li> 
                    <li aria-current="page" class="breadcrumb-item active">Thưởng thành tích</li>
                </ol>
            </nav>
            <div class="col-md-12 col-sm-12" >
                <div class="x_panel">
                    <div class="x_title">
                        <h2>THÊM MỚI</h2>
                        <div class="clearfix"></div>
                        <hr>
                    </div>
                    <div class="x_content">
                        <div class="row" >

                            <div class="col-md-6">
                                <div class="formform-group ">
                                    <label>Mã KT <span class="text-danger" >*</span></label> 
                                        <input 
                                        disabled
                                        v-model="listGroup.IDCode"
                                        type="text" 
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="formform-group" :class="{ 'form-group--error':$v.listGroup.idCompany.$error }">
                                    <label>Công ty</label>
                                    <v-select :options="DataCompany" 
                                        label="CompanyName" 
                                        :reduce="tag => tag.idCompany" 
                                        v-model="listGroup.idCompany"
                                        :disabled="isDisable"
                                        :class="{'border border-danger': $v.listGroup.idCompany.$error}"
                                        @change="$v.listGroup.idCompany.$touch()"
                                        @blur="$v.listGroup.idCompany.$touch()"
                                        @input="loadListUser"
                                        >
                                    </v-select>
                                    <span v-if="!$v.listGroup.idCompany.required" class="text-danger error">Trường không được để trống</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="formform-group" :class="{ 'form-group--error':$v.listGroup.idDepartment.$error }">
                                    <label>Bộ phận</label>
                                    <v-select :options="DataDepartment" 
                                        label="DepartmentName" 
                                        :reduce="tag => tag.idDepartment" 
                                        v-model="listGroup.idDepartment" 
                                        :disabled="isDisable"
                                        :class="{'border border-danger': $v.listGroup.idDepartment.$error}"
                                        @change="$v.listGroup.idDepartment.$touch()"
                                        @blur="$v.listGroup.idDepartment.$touch()"
                                        @input="loadListUser"
                                    >
                                    </v-select>
                                    <span v-if="!$v.listGroup.idDepartment.required" class="text-danger error">Trường không được để trống</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="formform-group" :class="{ 'form-group--error':$v.listGroup.idStaff.$error }">
                                    <label>Nhân viên <span class="text-danger" >*</span></label>
                                    <v-select :options="DataUsers" 
                                        label="IDCodeFullName" 
                                        :reduce="tag => tag.idUser" 
                                        v-model="listGroup.idStaff" 
                                        :disabled="isDisable"
                                        :class="{'border border-danger': $v.listGroup.idStaff.$error}"
                                        @input="loadInfoUser"
                                        @change="$v.listGroup.idStaff.$touch()"
                                        @blur="$v.listGroup.idStaff.$touch()"
                                        ></v-select>
                                        <span v-if="!$v.listGroup.idStaff.required" class="text-danger error">Trường không được để trống</span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="formform-group" >
                                    <label>Vị trí hiện tại</label>
                                    <v-select :options="DataPosition" 
                                        label="PositionName" 
                                        :reduce="tag => tag.idPosition" 
                                        v-model="listGroup.idPositionOl" 
                                        disabled>
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="formform-group" :class="{ 'form-group--error':$v.listGroup.Promote.$error }">
                                    <label>Tiêu đề<span class="text-danger" >*</span></label>
                                    <input type="text"
                                    v-model="$v.listGroup.Promote.$model"
                                    :class="{'border border-danger': $v.listGroup.Promote.$error}" 
                                    :disabled="isDisable"
                                    class="form-control">
                                    <span v-if="!$v.listGroup.Promote.required" class="text-danger error">Trường không được để trống</span>
                                    <span v-if="!$v.listGroup.Promote.minLength" class="text-danger error">Vui lòng nhập tối thiểu {{$v.listGroup.Promote.$params.minLength.min}} kí tự</span>
                                    <span v-if="!$v.listGroup.Promote.maxLength" class="text-danger error">Vui lòng nhập tối da {{$v.listGroup.Promote.$params.maxLength.max}} kí tự</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="formform-group" :class="{ 'form-group--error':$v.listGroup.idPosition.$error }" >
                                    <label>Vị trí thăng cấp<span class="text-danger" >*</span></label>
                                    <v-select :options="DataPosition" 
                                        label="PositionName" 
                                        :reduce="tag => tag.idPosition" 
                                        v-model="listGroup.idPosition" 
                                        :disabled="isDisable"
                                        :class="{'border border-danger': $v.listGroup.idPosition.$error}"
                                        @change="$v.listGroup.idPosition.$touch()"
                                        @blur="$v.listGroup.idPosition.$touch()"
                                        >
                                    </v-select>
                                    <span v-if="!$v.listGroup.idPosition.required" class="text-danger error">Trường không được để trống</span>
                                </div>
                            </div>
                            

                            <div class="col-md-6">
                                <div class="formform-group" >
                                    <label>Ngày thăng cấp</label>
                                    <date-picker v-model="listGroup.PromoteDay" :disabled="isDisable"  value-type="format" format="DD-MM-YYYY"></date-picker>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="formform-group ">
                                    <label>Ghi chú </label> 
                                        <input 
                                        :disabled="isDisable"
                                        v-model="listGroup.Notes"
                                        type="text" 
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="formform-group ">
                                    <label>Tập tin đính kèm </label><br>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th class="text-center" style="width:5%"><i class="fa fa-long-arrow-down" ></i></th>
                                            <th>Mô tả</th>
                                            <th class="text-center" style="width:5%"><i class="fa fa-upload"></i></th>
                                            <th class="text-center" style="width:5%">Loại</th>
                                            <th class="text-center" style="width:5%"><i class="fa fa-download"></i></th>
                                            <th class="text-center" style="width:10%">Kích thước</th>
                                            
                                            <th class="text-center" style="width:5%">
                                                <a class="badge" title="Thêm" style="font-size: 120%;">
                                                    <i @click="addRowFiles" class="fa fa-plus-square text"></i>
                                                </a>
                                            </th>
                                        </tr>
                                        <tbody class="trunpading">
                                            <tr v-for="(listfile, index) in DataFiles" :key="index">
                                                <td class="text-center">{{ index+1 }}</td>
                                                <td><input type="text" v-model="DataFiles[index].Description" class="form-control" style="border: initial;"></td>
                                                <td class="text-center">
                                                    <label style="margin-bottom: 0px;cursor: pointer;" :for="index"><i class="fa fa-upload" style="font-size: 15px;"></i></label>
                                                    <input type="file" :id="index" @change="getFile($event,index)" hidden>
                                                </td>
                                                <td class="text-center"> 
                                                    {{ DataFiles[index].Type }}
                                                </td>
                                                <td class="text-center"> 
                                                </td>
                                                <td class="text-center"> 
                                                    {{ DataFiles[index].Size | prettyBytes}}
                                                </td>
                                                <td class="text-center">
                                                    <a class="badge" title="Xóa"><i @click="removeRowFiles(index)" class="fa fa-close text-danger "></i></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="clearfix"></div>
                            
                            <div class="col-md-12 col-sm-12">
               
                                <button  :disabled="isDisable"   type="reset" class="btn btn-primary"><i class="fa fa-rotate-left"></i> Làm lại</button> 
                                <button @click="createList"  :disabled="isDisable"   type="submit" class="btn btn-success">
                                    <i :class="{'fa fa-plus': !isSave,'fa fa-spinner fa-spin': isSave}"></i>
                                    {{isSave==true ? 'Đang xử lý' : 'Lưu lại'}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { required, minLength, between,maxLength } from 'vuelidate/lib/validators'
import VueNumeric from 'vue-numeric'

export default {
    data() {
        return {
            isDisable: false,
            isSave: false,
            isLoadingPage: false,
            listGroup: {
                Promote: '',
                Notes: '',
                IDCode: '',
                idStaff: '',
                PromoteDay: '',
                idCompany: '',
                idDepartment: '',
                idPosition: '',
                idPositionOl: '',
                Active: true
            },
            DataUsers:[],
            DataCompany: [],
            DataDepartment: [],
            DataPosition: [],
            DataFiles: [{FileNameBase64: '',Description:'',Type: '',Size: 0}]
        }
    },
    components: {VueNumeric},
    validations: {
		listGroup: {
            Promote: {required,minLength: minLength(3),maxLength: maxLength(225)},
            idStaff: {required},
            idCompany: {required},
            idDepartment: {required},
            idPosition: {required}
        },
        
    },
    beforeRouteEnter(to, from, next) {
        try {
            next(self => {
                self.getInfo();	
            })
        } catch (err) {
            next(false)
        }
    },
    methods: {
        async getInfo(){
			const self =this;
			self.$Progress.start();
			const res = await axios.get("/staff-information/promote/create");
			if (res.data.Status == true) {
                self.listGroup.IDCode  = res.data.IDCode;
                self.DataCompany         = res.data.DataCompany;
                self.DataDepartment         = res.data.DataDepartment;
                self.DataPosition = res.data.DataPosition;
				self.isLoadingPage = true;
				self.$Progress.finish();
				if (res.data.Permission.indexOf(3) ==-1) {self.$router.push({name: 'dashboard'});}
			}else if (res.data.Status == 2) {
				window.location.href = 'admincp/login';
			}

        },
        loadInfoUser(){ // thông tin user
            const self = this;
            self.$Progress.start();
            axios.post("/staff-information/promote/loadInfoUser",{idStaff: self.listGroup.idStaff})
            .then(res => {
                if (res.data.Status == true) {
                    if (res.data.UserInfo != null) {
                        self.listGroup.idPosition  = res.data.UserInfo.idPosition;
                        self.listGroup.idPositionOl  = res.data.UserInfo.idPosition;
                    }else{
                        self.listGroup.idPosition = 0;
                    }
                    self.$Progress.finish();
                }
            })
        },
        async createList(){
            const self = this;
			try{
				self.$v.$touch();
				if (!self.$v.$invalid) {
					self.isDisable = true;
					self.isSave = true;
					self.$Progress.start();
					const res = await axios.post("/staff-information/promote",{listGroup: self.listGroup,DataFiles: self.DataFiles});
					if (res.data.Status == true) {
						self.$Progress.finish();
						self.isDisable = false;
						self.isSave = false;
						self.$notify({
							group: 'infomation',
							type: 'success',
							title: 'THÔNG BÁO',
							text: 'Thêm Mới Thành Công !'
						});
						self.$router.go(-1);
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

				}else{
					self.$notify({
					group: 'infomation',
					type: 'error',
					title: 'LỖI!',
					text: 'Vui Lòng kiểm tra lại thông tin !'
					});
				}
			}catch(error){
				self.$Progress.finish();
				self.isDisable = false;
				self.isSave = false;
				self.$notify({
					group: 'infomation',
					type: 'error',
					title: 'LỖI',
					text: 'Có lỗi trong quá trình !'
				});
			}
        },
        addRowFiles(ev){
			this.DataFiles.push({FileNameBase64: '',Description:'',Type: '',Size: 0});
		},
		removeRowFiles(index){
			if (this.DataFiles.length > 1) {
				this.$swal({
					title: 'THÔNG BÁO?',
					text: "Bạn muốn xóa tập tin ?",
					icon: 'warning'
					}).then((result) => {
					if (result.value) {
						this.DataFiles.splice(index,1);
					}
				})
			}
        },
        getFile(ev,index){ // đọc file
			const self =this;
			var file = ev.target.files[0];
			var name = file.name;
			var parts = name.split('.');
    		var Type = parts[parts.length - 1];
			var reader = new FileReader();
			reader.onloadend = function() {
				self.DataFiles[index].FileNameBase64 = reader.result;
				self.DataFiles[index].Type = Type;
				self.DataFiles[index].Size = file.size;
			}
			reader.readAsDataURL(file);
        },
        loadListUser(){ // lấy danh sách nhân viên theo phòng
            const self = this;
            self.listGroup.idStaff = '';
            self.$Progress.start();
            axios.post("/staff-information/promote/loadListUser",{idCompany: self.listGroup.idCompany,idDepartment: self.listGroup.idDepartment})
            .then(res => {
                if (res.data.Status == true) {
                    self.DataUsers = res.data.DataUsers;
                    self.$Progress.finish();
                }
            })
        },
    }
}
</script>