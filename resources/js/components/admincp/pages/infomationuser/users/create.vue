<template>
	<div class="row">
		<div v-if="isLoadingPage">
			<nav aria-label="breadcrumb" style="padding: 0px 10px;">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="#">Home</a></li> 
					<li class="breadcrumb-item"><a href="#">Thông tin nhân viên</a></li> 
					<li aria-current="page" class="breadcrumb-item active">Nhân viên</li>
				</ol>
			</nav>
			<div class="col-md-8 col-sm-12" >
				<div class="x_panel">
					<div class="x_title">
						<h2>THÔNG TIN NHÂN VIÊN</h2>
						<div class="clearfix"></div>
						<hr>
					</div>
					<div class="x_content">
						<div class="col-md-6">
							<div class="formform-group " :class="{ 'form-group--error':$v.listGroup.IDCode.$error }">
								<label>Mã NV <span class="text-danger" >*</span></label> 
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
							<div class="formform-group" :class="{ 'form-group--error':$v.listGroup.Email.$error }">
								<label>Email<span class="text-danger" >*</span></label>
								<input type="text"
								
								v-model="$v.listGroup.Email.$model"
								:class="{'border border-danger': $v.listGroup.Email.$error}" 
								:disabled="isDisable"
								class="form-control">
								<span v-if="!$v.listGroup.Email.required" class="text-danger error">Trường không được để trống</span>
								<span v-if="!$v.listGroup.Email.email" class="text-danger error">Định dạng email không đúng</span>
								<span v-if="!$v.listGroup.Email.isUnique" class="text-danger error">Trường đã tồn tại trên hệ thống</span>
								<span v-show="uniqueEmail" class="text-danger">Trường đã tồn tại trên hệ thống</span>
								<span v-if="!$v.listGroup.Email.minLength" class="text-danger error">Vui lòng nhập tối thiểu {{$v.listGroup.Email.$params.minLength.min}} kí tự</span>
								<span v-if="!$v.listGroup.Email.maxLength" class="text-danger error">Vui lòng nhập tối da {{$v.listGroup.Email.$params.maxLength.max}} kí tự</span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="formform-group" >
								<label>Mật khẩu</label>
								<input type="password"  v-model="listGroup.Password" :disabled="isDisable"  class="form-control">
							</div>
						</div>
				
						<div class="col-md-6">
							<div class="formform-group" >
								<label>CMND<span class="text-danger" >*</span></label>
								<input type="text" v-model="listGroup.IDcard" :disabled="isDisable"  class="form-control">
							</div>
						</div>
						<div class="col-md-6">
							<div class="formform-group" >
								<label>Ngày cấp CMND<span class="text-danger" >*</span></label>
								<date-picker v-model="listGroup.IDcardDate" :disabled="isDisable"  value-type="format" format="DD-MM-YYYY"></date-picker>
							</div>
						</div>
						<div class="col-md-6">
							<div class="formform-group" >
								<label>Nơi cấp<span class="text-danger" >*</span></label>
								<v-select :options="DataIDcardAddress" 
									label="Provinces" 
									:reduce="tag => tag.idProvinces" 
									v-model="listGroup.IDcardAddress" 
									:disabled="isDisable">
								</v-select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="formform-group" >
								<label>Giới tính<span class="text-danger" >*</span></label>
								<v-select :options="DataSex" 
									label="NameSex" 
									:reduce="tag => tag.Sex" 
									v-model="listGroup.Sex" 
									:disabled="isDisable">
								</v-select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="formform-group" >
								<label>Hôn nhân<span class="text-danger" >*</span></label>
								<v-select :options="DataMarried" 
									label="NameMarried" 
									:reduce="tag => tag.Married" 
									v-model="listGroup.Married" 
									:disabled="isDisable">
								</v-select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="formform-group" >
								<label>Thường trú<span class="text-danger" >*</span></label>
								<input type="text" v-model="listGroup.Address" :disabled="isDisable"  class="form-control">
							</div>
						</div>
						<div class="col-md-6">
							<div class="formform-group" >
								<label>Tạm trú<span class="text-danger" >*</span></label>
								<input type="text" v-model="listGroup.AddressStaying" :disabled="isDisable"  class="form-control">
							</div>
						</div>
						<div class="col-md-6">
							<div class="formform-group" >
								<label>Trình độ</label>
								<v-select :options="DataLevel" 
									label="LevelName" 
									:reduce="tag => tag.idLevel" 
									v-model="listGroup.idLevel" 
									:disabled="isDisable">
								</v-select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="formform-group" >
								<label>Chuyên môn</label>
								<input type="text" v-model="listGroup.Qualification" :disabled="isDisable"  class="form-control">
							</div>
						</div>
						<div class="col-md-6">
							<div class="formform-group" >
								<label>Phòng ban</label>
								<v-select :options="DataDepartment" 
									label="DepartmentName" 
									:reduce="tag => tag.idDepartment" 
									v-model="listGroup.idDepartment" 
									:disabled="isDisable">
								</v-select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="formform-group" >
								<label>Chức vụ</label>
								<v-select :options="DataPosition" 
									label="PositionName" 
									:reduce="tag => tag.idPosition" 
									v-model="listGroup.idPosition" 
									:disabled="isDisable">
								</v-select>
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="formform-group" >
								<label>Loại hình công việc</label>
								<v-select :options="DataTypeOfWork" 
									label="TypeOfWorkName" 
									:reduce="tag => tag.idTypeOfWork" 
									v-model="listGroup.idTypeOfWork" 
									:disabled="isDisable">
								</v-select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="formform-group" >
								<label>Công ty <span class="text-danger" >*</span></label>
								<v-select :options="DataCompany" 
									label="CompanyName" 
									:reduce="tag => tag.idCompany" 
									v-model="listGroup.idCompany" 
									:disabled="isDisable">
								</v-select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="formform-group" >
								<label>Thời gian thử việc</label>
								<date-picker v-model="listGroup.DayProbationary" range :disabled="isDisable"  value-type="format" format="DD-MM-YYYY"></date-picker>
							</div>
						</div>
						<div class="col-md-6">
							<div class="formform-group" >
								<label>Ngày làm việc chính thức</label>
								<date-picker v-model="listGroup.DayWork" :disabled="isDisable"  value-type="format" format="DD-MM-YYYY"></date-picker>
							</div>
						</div>
						<div class="col-md-6">
							<div class="formform-group" >
								<label>Ngày nghỉ việc</label>
								<date-picker v-model="listGroup.DayWorkOff" :disabled="isDisable"  value-type="format" format="DD-MM-YYYY"></date-picker>
							</div>
						</div>
						<div class="col-md-6">
							<div class="formform-group" >
								<label>Số bảo hiểm</label>
								<input type="text" v-model="listGroup.HealthInsuranceNo" :disabled="isDisable"  class="form-control">
							</div>
						</div>
						<div class="col-md-6">
							<div class="formform-group" >
								<label>Tên ngân hàng</label>
								<input type="text" v-model="listGroup.BankName" :disabled="isDisable"  class="form-control">
							</div>
						</div>
						<div class="col-md-6">
							<div class="formform-group" >
								<label>Số tài khoản</label>
								<input type="text" v-model="listGroup.AccountNumber" :disabled="isDisable"  class="form-control">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-12" >
				<div class="x_panel">
					<div class="x_title">
						<h2>THÔNG TIN</h2>
						<div class="clearfix"></div>
						<hr>
					</div>
					<div class="x_content">
						<div class="col-md-12">

							<div class="profile_img" style="text-align: right;" v-if="listGroup.Image !=''">
								<div id="crop-avatar">
									<img class="img-responsive avatar-view" :src="listGroup.Image" style="width: 200px;height: 200px;">
									<div class="delete-avt">
										<label class="btn btn-primary btn-sm" for="fileImage" v-tooltip.top-center="'Thêm ảnh'"><i class="fa fa-camera"></i>
											<input type="file" @change="getImage($event)" name="Image" id="fileImage" hidden>
										</label>
										<label class=" btn btn-danger btn-sm" v-tooltip.top-center="'Xóa ảnh'" @click="deleteImage"><i class="fa fa-trash-o"></i></label>
									</div>
								</div>
								<br>
							</div>

							<div class="profile_img" style="text-align: right;" v-if="listGroup.Image ==''">
								<div id="crop-avatar">
									<img class="img-responsive avatar-view" src="public/images/users/noav.png" style="width: 200px;height: 200px;">
									<div class="delete-avt">
										<label class="btn btn-primary btn-sm" for="fileImage" v-tooltip.top-center="'Thêm ảnh'"><i class="fa fa-camera"></i>
											<input type="file" @change="getImage($event)" name="Image" id="fileImage" hidden>
										</label>
										<label class=" btn btn-danger btn-sm" v-tooltip.top-center="'Xóa ảnh'" @click="deleteImage"><i class="fa fa-trash-o"></i></label>
									</div>
								</div>
								<br>
							</div>
						</div>
						<div class="col-md-12">
							<div class="formform-group" :class="{ 'form-group--error':$v.listGroup.FullName.$error }">
								<label>Họ Tên<span class="text-danger" >*</span></label>
								<input type="text"
								v-model="$v.listGroup.FullName.$model"
								:class="{'border border-danger': $v.listGroup.FullName.$error}" 
								:disabled="isDisable"
								class="form-control">
								<span v-if="!$v.listGroup.FullName.required" class="text-danger error">Trường không được để trống</span>
								<span v-if="!$v.listGroup.FullName.minLength" class="text-danger error">Vui lòng nhập tối thiểu {{$v.listGroup.FullName.$params.minLength.min}} kí tự</span>
								<span v-if="!$v.listGroup.FullName.maxLength" class="text-danger error">Vui lòng nhập tối da {{$v.listGroup.FullName.$params.maxLength.max}} kí tự</span>
							</div>
						</div>
						<div class="col-md-12">
							<div class="formform-group" >
								<label>Điện thoại</label>
								<input type="text" v-model="listGroup.Phone" :disabled="isDisable"  class="form-control">
							</div>
						</div>
						<div class="col-md-12">
							<div class="formform-group" >
								<label>Ngày sinh<span class="text-danger" >*</span></label>
								<date-picker v-model="listGroup.Birthday" :disabled="isDisable"  value-type="format" format="DD-MM-YYYY"></date-picker>
							</div>
						</div>
						<div class="col-md-12">
							<div class="formform-group" >
								<label>Nơi sinh<span class="text-danger" >*</span></label>
								<v-select :options="DataBirth" 
									label="Provinces" 
									:reduce="tag => tag.idProvinces" 
									v-model="listGroup.Birth" 
									:disabled="isDisable">
								</v-select>
							</div>
						</div>
						<div class="col-md-12">
							<div class="formform-group" >
								<label>Mô tả</label> 
								<textarea :disabled="isDisable" v-model="listGroup.Description"  cols="30" rows="2"    class="form-control"></textarea>
							</div>
						</div>
						<div class="col-md-12">
							<div class="formform-group" >
								<label>Nhóm quyền<span class="text-danger" ></span></label>
								<v-select :options="DataGroup" 
									label="GroupName" 
									:reduce="tag => tag.idGroup" 
									v-model="listGroup.idGroup"
									taggable multiple
									:disabled="isDisable">
								</v-select>
							</div>
						</div>
						
						<div class="col-md-12">
							<table style="width:100%">
								<tr>
									<td>Tình trạng<span class="text-danger" >*</span></td>
									<td style="text-align: right;">
										<p-check class="p-switch p-fill" color="success_gren" :title="listGroup.Active==1 ? 'Hoạt động': 'Tạm ngưng'" :checked="listGroup.Active" v-model="listGroup.Active" >
											{{ listGroup.Active==1 ? 'Hoạt động': 'Tạm ngưng' }}
										</p-check>
									</td>
								</tr>
								<tr>
									<td>Tình trạng làm việc<span class="text-danger" >*</span></td>
									<td style="text-align: right;">
										<p-check class="p-switch p-fill" color="info" :title="listGroup.isWork==1 ? 'Còn làm': 'Đã nghỉ'" :checked="listGroup.isWork" v-model="listGroup.isWork" >
											{{ listGroup.isWork==1 ? 'Còn làm': 'Đã nghỉ' }}
										</p-check>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				
			</div>
			<div style="clear:both" ></div>
			<div class="col-md-12" >
				<div class="x_panel xFile">
					<div class="x_title">
						<h2>FILE ĐÍNH KÈM</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<table class="table table-bordered">
							<tr>
								<th class="text-center" style="width:5%"><i class="fa fa-long-arrow-down" ></i></th>
								<th>Mô tả</th>
								<th class="text-center" style="width:5%"><i class="fa fa-upload"></i></th>
								<th class="text-center" style="width:5%">Loại</th>
								<th class="text-center" style="width:5%"><i class="fa fa-download" ></i></th>
								<th class="text-center" style="width:10%">Kích thước</th>
								
								<th class="text-center" style="width:5%">
									<a class="badge" v-tooltip.top-center="'Thêm'">
										<i @click="addRowFiles" class="fa fa-plus-square text" style="font-size: 120%;"></i>
									</a>
								</th>
							</tr>
							<tbody class="trunpading">
								<tr v-for="(listfile, index) in DataFiles" :key="index">
									<td class="text-center">{{ index+1 }}</td>
									<td><input type="text" v-model="DataFiles[index].Description" class="form-control" style="border: initial;"></td>
									<td class="text-center">
										<label style="margin-bottom: 0px;cursor: pointer;" :for="index" v-tooltip.top-center="'Tải file'"><i class="fa fa-upload" style="font-size: 15px;"></i></label>
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
										<a class="badge" v-tooltip.top-center="'Xóa'"><i @click="removeRowFiles(index)" class="fa fa-close text-danger "></i></a>
									</td>
								</tr>
							</tbody>
						</table>
						<button  :disabled="isDisable"   type="reset" class="btn btn-primary"><i class="fa fa-rotate-left"></i> Làm lại</button> 
						<button @click="createList" :disabled="isDisable"   type="submit" class="btn btn-success">
							<i :class="{'fa fa-plus': !isSave,'fa fa-spinner fa-spin': isSave}"></i>
							{{isSave==true ? 'Đang xử lý' : 'Lưu lại'}}</button>
					</div>
				</div>
			</div>
			<div style="clear:both" ></div>
		</div>
	</div>
</template>

<script>
import { required, minLength, between,maxLength,email  } from 'vuelidate/lib/validators';
export default {
	data(){
		return {
			isDisable: false,
			isSave: false,
			isDisableIDCode: true,
			uniqueIDCode: false,
			uniqueEmail: false,
			listGroup: {
				IDCode:'',
				FullName: '',
				Email: '',
				Password: '',
				Phone: '',
				Birthday: '',
				Birth: 0,
				IDcard: '',
				IDcardDate: '',
				IDcardAddress: 0,
				Sex: 0,
				Married: 0,
				Address: '',
				AddressStaying: '',
				idLevel: 0,
				Qualification:'',
				idDepartment: 0,
				idPosition: 0,
				idTypeOfWork: 0,
				idCompany: 0,
				DayProbationary: '',
				DayWork: '',
				HealthInsuranceNo: '',
				idGroup: '',
				Active: 1,
				isWork: 1,
				Description: '',
				Image: '',
				BankName: '',
				AccountNumber: ''
			},
			DataIDcardAddress: [],
			DataBirth: [],
			DataSex: [{Sex:0,NameSex:'Nam'},{Sex:1,NameSex:'Nữ'},{Sex:2,NameSex:'Khác'}],
			DataMarried: [{Married:0,NameMarried:'Độc thân'},{Married:1,NameMarried:'Kết hôn'}],
			DataLevel:[],
			DataDepartment: [],
			DataPosition: [],
			DataTypeOfWork: [],
			DataCompany: [],
			DataGroup: [],
			DataFiles: [{FileNameBase64: '',Description:'',Type: '',Size: 0}],
			isLoadingPage: false,
			Permission: []
		}
	},
	validations: {
		listGroup: {
			FullName: {required,minLength: minLength(3),maxLength: maxLength(225)},
			Email: {required,email,minLength: minLength(1),maxLength: maxLength(225),
				async isUnique(value){
                    if (value == '') return true
                    const response = await axios.post("/staff-information/users/checkEmail",{Email: value,idUser: 0});
                    return response.data.Status;
                }
			},
            IDCode: {
                required,
                async isUnique(value){
                    if (value == '') return true
                    const response = await axios.post("/staff-information/users/checkIDCode",{IDCode: value,idUser: 0});
                    return response.data.Status;
                },
				minLength: minLength(3),
				maxLength: maxLength(225)
            }
		}
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
			const res = await axios.get("/staff-information/users/create");
			if (res.data.Status == true) {
				self.listGroup.IDCode  = res.data.IDCode;
				self.DataIDcardAddress = res.data.DataIDcardAddress;
				self.DataBirth         = res.data.DataBirth;
				self.DataCompany       = res.data.DataCompany;
				self.DataDepartment    = res.data.DataDepartment;
				self.DataPosition      = res.data.DataPosition;
				self.DataLevel         = res.data.DataLevel;
				self.DataTypeOfWork    = res.data.DataTypeOfWork;
				self.DataGroup         = res.data.DataGroup;
				self.isLoadingPage = true;
				self.$Progress.finish();
				self.Permission = res.data.Permission;
				if (self.Permission.indexOf(3) ==-1) {self.$router.push({name: 'dashboard'});}
			}else if (res.data.Status == 2) {
				window.location.href = 'admincp/login';
			}

		},
		getImage(ev){ // đọc file avt
			const self =this;
			var file = ev.target.files[0];
			var name = file.name;
			var reader = new FileReader();
			reader.onloadend = function() {
				self.listGroup.Image = reader.result;
			}
			reader.readAsDataURL(file);
		},
		addRowFiles(ev){
			this.DataFiles.push({FileNameBase64: '',Description:'',Type: '',Size: 0});
		},
		removeRowFiles(index){
			const self =this;
			if (this.DataFiles.length > 1) {
				self.$dialog.confirm({title: "THÔNG BÁO?",body: "Bạn muốn xóa tập tin ?"})
				.then(function(dialog) {
					dialog.close();
					self.DataFiles.splice(index,1);
				})
				.catch(function() {
					console.log('Clicked on cancel');
				});
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
	
		deleteImage(){
			const self = this;
			self.$dialog.confirm({title: "THÔNG BÁO?",body: "Bạn muốn xóa ảnh ?"})
			.then(function(dialog) {
				dialog.close();
				self.listGroup.Image = '';
			})
			.catch(function() {
				console.log('Clicked on cancel');
			});
			
		},
		async createList(){
			const self = this;
			try{
				self.$v.$touch();
				if (!self.$v.$invalid) {
					self.isDisable = true;
					self.isSave = true;
					self.$Progress.start();
					const res = await axios.post("/staff-information/users",{listGroup: self.listGroup,DataFiles: self.DataFiles});
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
			
		}
	}
}
</script>