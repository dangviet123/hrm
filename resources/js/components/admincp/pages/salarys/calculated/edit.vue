<template>
    <div class="row">
        <loading :active.sync="isLoading" :can-cancel="false" color="#2196F3" :width= "50" :height= "50"></loading>
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
                                    <label>Công ty <span class="text-danger" >*</span></label>
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
                                    <label>Bộ phận <span class="text-danger" >*</span></label>
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
                                <div class="formform-group" :class="{ 'form-group--error':$v.listGroup.idUserSalary.$error }">
                                    <label>Nhân viên <span class="text-danger" >*</span></label>
                                    <v-select :options="DataUsers" 
                                        label="IDCodeFullName" 
                                        :reduce="tag => tag.idUser" 
                                        v-model="listGroup.idUserSalary" 
                                        :disabled="isDisable"
                                        :class="{'border border-danger': $v.listGroup.idUserSalary.$error}"
                                        @input="loadInfoUser"
                                        @change="$v.listGroup.idUserSalary.$touch()"
                                        @blur="$v.listGroup.idUserSalary.$touch()"
                                        ></v-select>
                                        <span v-if="!$v.listGroup.idUserSalary.required" class="text-danger error">Trường không được để trống</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="formform-group ">
                                    <label>Mã nhân viên <span class="text-danger" >*</span></label> 
                                        <input 
                                        disabled
                                        v-model="listGroup.IDCodeUser"
                                        type="text" 
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="formform-group ">
                                    <label>Email <span class="text-danger" >*</span></label> 
                                        <input 
                                        disabled
                                        v-model="listGroup.Email"
                                        type="text" 
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="formform-group ">
                                    <label>Số ngày công <span class="text-danger" >*</span></label> 
                                        <vue-numeric 
                                        :disabled="isDisable" 
                                        v-model="listGroup.NumberWorkdays"
                                        v-bind:precision="1" currency="" separator=","  class="form-control"></vue-numeric>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="formform-group ">
                                    <label>Tháng <span class="text-danger" >*</span></label> 
                                    <date-picker
                                    @change="getIDCodeMonthYear"
                                    v-model="listGroup.MonthlySalary" 
                                    :disabled="true"  
                                    type="month" 
                                    value-type="format" 
                                    format="MM-YYYY"></date-picker>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="formform-group ">
                                    <label>Tiêu đề </label> 
                                        <input 
                                        :disabled="isDisable"
                                        v-model="listGroup.Calculated"
                                        type="text" 
                                        class="form-control">
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
                            
                            <div class="col-md-6">
                                <hr>
                                <table class="table table-bordered">
                                    <tbody class="trunpading">
                                        <tr>
                                            <th colspan="2">
                                                CÁC KHOẢN THU NHẬP (A)
                                            </th>
                                            <th >
                                                <div class="btn-group" >
                                                    <button @click="createSalaryRowA" v-tooltip.top-center="'Thêm'" class="btn text-secondary btn-sm btn-cusize group-permision">
                                                        <i class="fa fa-plus-square text" style="font-size: 120%;"></i>
                                                    </button>
                                                </div>
                                            </th>
                                        </tr>
                                        <tr v-for="(SalaryDataA, index) in DataSalaryDataA" :key="index">
                                            <td>
                                                <input type="text" :disabled="isDisable" v-model="DataSalaryDataA[index].RowName" class="form-control" style="border: initial;">
                                            </td>
                                            <td>
                                                <vue-numeric 
                                                style="border: initial;"
                                                :disabled="isDisable" 
                                                v-model="DataSalaryDataA[index].RowValue"
                                                @input="sumTotalSalaryDataA"
                                                v-bind:precision="0" currency="" separator=","  class="form-control"></vue-numeric>
                                            </td>
                                            <td class="text-center" style="width: 50px;" >
                                                <div class="btn-group" >
                                                    <button @click="deleteSalaryRowA(index)"  v-tooltip.top-center="'Xóa'" class="btn text-secondary btn-sm btn-cusize group-permision"><i class="fa fa-close text-danger "></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Tổng Cộng</th>
                                            <td colspan="2">
                                                &nbsp;&nbsp;
                                                <vue-numeric 
                                                read-only
                                                v-model="listGroup.TotalSalaryDataA"
                                                style="border: initial;"
                                                :disabled="isDisable" 
                                                v-bind:precision="0" currency="" separator=","  class="form-control"></vue-numeric>
                                            </td>
                                            
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <hr>

                                <table class="table table-bordered">
                                    <tbody class="trunpading">
                                        <tr>
                                            <th colspan="2">
                                                CÁC KHOẢN PHẢI NỘP (B)
                                            </th>
                                            <th>
                                                <div class="btn-group" >
                                                    <button @click="createSalaryRowB" v-tooltip.top-center="'Thêm'" class="btn text-secondary btn-sm btn-cusize group-permision">
                                                            <i class="fa fa-plus-square text" style="font-size: 120%;"></i>
                                                    </button>
                                                </div>
                                            </th>
                                        </tr>
                                        <tr v-for="(SalaryDataB, index) in DataSalaryDataB" :key="index">
                                            <td>
                                                <input type="text" :disabled="isDisable" v-model="DataSalaryDataB[index].RowName" class="form-control" style="border: initial;">
                                            </td>
                                            <td>
                                                <vue-numeric 
                                                style="border: initial;"
                                                :disabled="isDisable" 
                                                @input="sumTotalSalaryDataB"
                                                
                                                v-model="DataSalaryDataB[index].RowValue"
                                                v-bind:precision="0" currency="" separator=","  class="form-control"></vue-numeric>
                                            </td>
                                            <td class="text-center" style="width: 50px;" >
                                                <div class="btn-group" >
                                                    <button @click="deleteSalaryRowB(index)" v-tooltip.top-center="'Xóa'" class="btn text-secondary btn-sm btn-cusize group-permision"><i class="fa fa-close text-danger "></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        <th>Tổng Trừ</th>
                                        
                                        <td colspan="2">
                                            &nbsp;&nbsp;
                                            <vue-numeric 
                                            read-only
                                            v-model="listGroup.TotalSalaryDataB"
                                            style="border: initial;"
                                            :disabled="isDisable" 
                                            v-bind:precision="0" currency="" separator=","  class="form-control"></vue-numeric>
                                        </td>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tbody class="trunpading">
                                        <tr>
                                            <th style="width:150px">Thành Tiền (A-B)</th>
                                            <td>
                                                &nbsp;&nbsp;
                                                <vue-numeric 
                                                read-only
                                                v-model="listGroup.TotalSalaryDataAVG"
                                                style="border: initial;"
                                                :disabled="isDisable" 
                                                v-bind:precision="0" currency="" separator=","  class="form-control"></vue-numeric>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width:150px">Bằng Chữ</th>
                                            <th style="text-transform: capitalize;" ref="ReadIntoWord"> {{ listGroup.TotalSalaryDataAVG | ReadNumbersIntoWords}} đồng</th>
                                        </tr>
    
                                    </tbody>
                                </table>
                            </div>
                            <div class="clearfix"></div>

                            <div class="col-md-12 col-sm-12">
                                <hr>
                                <button  :disabled="isDisable"   type="reset" class="btn btn-primary"><i class="fa fa-rotate-left"></i> Làm lại</button> 
                                <button @click="updateList"  :disabled="isDisable"   type="submit" class="btn btn-success">
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
import { required, minLength, between,maxLength } from 'vuelidate/lib/validators';
import VueNumeric from 'vue-numeric';

export default {
	watch: { 
		idCalculated(newVal, oldVal) { // watch it
			this.getInfo(newVal);
		}
    },
    data() {
        return {
            isDisable: false,
            isSave: false,
            isLoadingPage: false,
            isLoading: false,
            listGroup: {
                Calculated: '',
                IDCodeUser: '',
                Email: '',
                Notes: '',
                IDCode: '',
                idUserSalary: '',
                idCompany: '',
                idDepartment: '',
                idPosition: 0,
                TotalSalaryDataA: 0,
                TotalSalaryDataB: 0,
                TotalSalaryDataAVG: 0,
                NumberWorkdays: 0,
                MonthlySalary: '',
                TotalSalaryWords: '',
                Active: true
            },
            idCalculated:this.$route.params.idCalculated,
            DataUsers:[],
            DataCompany: [],
            DataDepartment: [],
            DataPosition: [],
            DataSalaryDataA: [
                {RowName: 'Lương Căn Bản',RowValue: 0},
                {RowName: 'Lương chuyên viên',RowValue: 0},
                {RowName: 'Lương Hiệu Quả 1',RowValue: 0},
                {RowName: 'Lương Hiệu Quả 2',RowValue: 0},
                {RowName: 'PC Hao Mòn',RowValue: 0},
                {RowName: 'PC ĐT, Xăng',RowValue: 0},
                {RowName: 'Thưởng bán hàng',RowValue: 0},
                {RowName: 'Lương Ngoài Giờ',RowValue: 0},
                {RowName: 'Hỗ Trợ Khác',RowValue: 0},
                {RowName: 'Đào Tạo, Kèm Cặp',RowValue: 0},
                {RowName: 'CP Sinh Nhật',RowValue: 0},
                {RowName: 'Chi Phí Quan Hệ Khách Hàng',RowValue: 0}
            ],
            DataSalaryDataB: [
                {RowName: 'BHXH, YT, TN 10.5%',RowValue: 0},
                {RowName: 'KPCD 1%',RowValue: 0},
                {RowName: 'Thuế TNCN',RowValue: 0},
                {RowName: 'Trừ khác',RowValue: 0}
            ],
        }
    },
    
    components: {VueNumeric},
    validations: {
		listGroup: {
            idUserSalary: {required},
            idCompany: {required},
            idDepartment: {required}
        },
        
    },
    beforeRouteEnter(to, from, next) {
        try {
            next(self => {
                self.getInfo(self.idCalculated);
            })
        } catch (err) {
            next(false)
        }
    },
    methods: {
        async getInfo(idCalculated){
            const self =this;
            self.$Progress.start();
            self.isLoading = true;
			const res = await axios.get("/salarys/calculated/"+idCalculated+"/edit");
			if (res.data.Status == true) {
                self.listGroup  = res.data.datas;
                self.DataCompany = res.data.DataCompany;
                self.DataUsers = res.data.DataUsers;
                self.DataDepartment = res.data.DataDepartment;
                self.DataPosition = res.data.DataPosition;
                self.DataSalaryDataA = res.data.datas.SalaryDataA;
                self.DataSalaryDataB = res.data.datas.SalaryDataB;
                self.isLoadingPage = true;
                self.$Progress.finish();
                self.isLoading = false;
                self.isDisable = res.data.datas.Locked == 1 ? true : false;
				if (res.data.Permission.indexOf(4) ==-1) {self.$router.push({name: 'dashboard'});}
			}else if (res.data.Status == 2) {
				window.location.href = 'admincp/login';
			}

        },
        loadInfoUser(){ // thông tin user
            const self = this;
            axios.post("/salarys/calculated/loadInfoUser",{idUserSalary: self.listGroup.idUserSalary,idCompany: self.listGroup.idCompany})
            .then(res => {
                if (res.data.Status == true) {
                    if (res.data.UserInfo != null) {
                        self.listGroup.idPosition  = res.data.UserInfo.idPosition;
                        self.listGroup.Email = res.data.UserInfo.Email;
                        self.listGroup.IDCodeUser = res.data.UserInfo.IDCode
                    }else{
                        self.listGroup.idPosition = '';
                    }
                    
                    self.sumTotalSalaryDataA();
                    self.sumTotalSalaryDataB();
                }
            })
        },
        async updateList(){
            const self = this;
            self.listGroup.TotalSalaryWords = self.$refs.ReadIntoWord.innerHTML;
			try{
				self.$v.$touch();
				if (!self.$v.$invalid) {
					self.isDisable = true;
					self.isSave = true;
                    self.isLoading = true;
					const res = await axios.put("/salarys/calculated/update",{listGroup: self.listGroup,DataSalaryDataA: self.DataSalaryDataA,DataSalaryDataB:self.DataSalaryDataB});
					if (res.data.Status == true) {
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
						self.isDisable = false;
                        self.isSave = false;
                        self.isLoading = false;
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
				self.isDisable = false;
                self.isSave = false;
                self.isLoading = false;
				self.$notify({
					group: 'infomation',
					type: 'error',
					title: 'LỖI',
					text: 'Có lỗi trong quá trình !'
				});
			}
        },

        loadListUser(){ // lấy danh sách nhân viên theo phòng
            const self = this;
            self.listGroup.idUserSalary = '';
            axios.post("/salarys/calculated/loadListUser",{idCompany: self.listGroup.idCompany,idDepartment: self.listGroup.idDepartment})
            .then(res => {
                if (res.data.Status == true) {
                    self.DataUsers = res.data.DataUsers;
                }
            })
        },
        sumTotalSalaryDataA(){
            const self = this;            
            self.listGroup.TotalSalaryDataA = 0;
            for (let index = 0; index < self.DataSalaryDataA.length; index++) {
                self.listGroup.TotalSalaryDataA += parseFloat(self.DataSalaryDataA[index].RowValue);
            }
            self.listGroup.TotalSalaryDataAVG = parseFloat(self.listGroup.TotalSalaryDataA) - parseFloat(self.listGroup.TotalSalaryDataB);

        },
        sumTotalSalaryDataB(){
            const self = this;
            self.listGroup.TotalSalaryDataB = 0;
            for (let index = 0; index < self.DataSalaryDataB.length; index++) {
                self.listGroup.TotalSalaryDataB += parseFloat(self.DataSalaryDataB[index].RowValue);
            }
            self.listGroup.TotalSalaryDataAVG = parseFloat(self.listGroup.TotalSalaryDataA) - parseFloat(self.listGroup.TotalSalaryDataB);

        },
        deleteSalaryRowA(index){
            const self = this;
            if (self.isDisable == false) {
                if (self.DataSalaryDataA.length > 1) {
                    self.DataSalaryDataA.splice(index,1);
                }
                self.sumTotalSalaryDataA();
                self.sumTotalSalaryDataB();
            }
        },
        deleteSalaryRowB(index){
            const self = this;
            if (self.isDisable == false) {
                if (self.DataSalaryDataB.length > 1) {
                    self.DataSalaryDataB.splice(index,1);
                }
                self.sumTotalSalaryDataA();
                self.sumTotalSalaryDataB();
            }
        },
        createSalaryRowA(){
            const self = this;
            if (self.isDisable == false) {
                self.DataSalaryDataA.push({RowName: '',RowValue: 0});
            }
            
        },
        createSalaryRowB(){
            const self = this;
            if (self.isDisable == false) {
                self.DataSalaryDataB.push({RowName: '',RowValue: 0});
            }
        },
        getIDCodeMonthYear(){
            const self = this;
            axios.post("/salarys/calculated/getIDCodeMonthYear",{MonthlySalary: self.listGroup.MonthlySalary})
            .then(res => {
                if (res.data.Status == true) {
                    self.listGroup.IDCode = res.data.IDCode;
                }
            })
        }
    }
}
</script>