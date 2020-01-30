
<template>
	<div class="row" style="display: block;">
		<loading :active.sync="isLoading" :can-cancel="false" color="#2196F3" :width= "50" :height= "50"></loading>
		<div class="col-md-12 col-sm-12" v-if="isLoadingPage">

			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="#">Home</a></li>
			    <li class="breadcrumb-item"><a href="#">Thông tin nhân viên</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Nhân viên</li>
			  </ol>
			</nav>
			<div class="x_content_button" >
				<a v-tooltip.top-center="DataInfoPermission[2].ListName" v-if="Permission.indexOf(2) !=-1" class="btn btn-defaults" @click="Refresh"> 
					<i :class="DataInfoPermission[2].Icon"></i>
				</a>
				<a v-tooltip.top-center="DataInfoPermission[3].ListName" v-if="Permission.indexOf(3) !=-1" class="btn btn-defaults" @click="createdNew" >
					<i :class="DataInfoPermission[3].Icon" ></i>
				</a>
				<a v-tooltip.top-center="DataInfoPermission[4].ListName" v-if="Permission.indexOf(4) !=-1" class="btn btn-defaults" @click="update">
					<i :class="DataInfoPermission[4].Icon"></i>
				</a>
				<a v-tooltip.top-center="DataInfoPermission[5].ListName" v-if="Permission.indexOf(5) !=-1" class="btn btn-defaults" @click="activeStatusAll(1)">
					<i :class="DataInfoPermission[5].Icon"></i>
				</a>
				<a v-tooltip.top-center="DataInfoPermission[6].ListName" v-if="Permission.indexOf(6) !=-1" class="btn btn-defaults" @click="activeStatusAll(0)">
					<i :class="DataInfoPermission[6].Icon"></i>
				</a>
				<a v-tooltip.top-center="DataInfoPermission[7].ListName" v-if="Permission.indexOf(7) !=-1" class="btn btn-defaults" @click="deleteAll">
					<i :class="DataInfoPermission[7].Icon"></i>
				</a>
				<a v-tooltip.top-center="DataInfoPermission[15].ListName" v-if="Permission.indexOf(15) !=-1" class="btn btn-defaults" @click="exportExcel">
					<i :class="DataInfoPermission[15].Icon" ></i>
				</a>
				<a v-tooltip.top-center="DataInfoPermission[8].ListName" v-if="Permission.indexOf(8) !=-1" class="btn btn-defaults" @click="isFilter =! isFilter">
					<i :class="DataInfoPermission[8].Icon" ></i>
				</a>
			</div>
				<div class="x_panel isFilter" v-show="isFilter" >
					
					<div class="x_content" >
						<div class="col-md-4">
							<label>Mã NV</label>
							<input type="text"  v-model="filedSearch.IDCode" class="form-control" >
						</div>
						<div class="col-md-4">
							<label>Tên NV</label>
							<input type="text"  v-model="filedSearch.FullName" class="form-control" >
						</div>
						<div class="col-md-4">
							<label>Email</label>
							<input type="text"  v-model="filedSearch.Email" class="form-control" >
						</div>
						<div class="col-md-4">
							<label >Mô tả</label>
							<input type="text"  v-model="filedSearch.Description" class="form-control" >
						</div>
						<div class="col-md-4">
							<label >Điện thoại</label>
							<input type="text"  v-model="filedSearch.Phone" class="form-control" >
						</div>
						<div class="col-md-4">
							<label>Người lập</label>
							<input type="text"  v-model="filedSearch.FullNameAdd" class="form-control" >
						</div>

						<div class="col-md-4">
							<label>Nơi sinh</label>
							<v-select :options="DataBirth" 
								label="Provinces" 
								:reduce="tag => tag.idProvinces" 
								v-model="filedSearch.Birth" 
								taggable multiple
								>
							</v-select>
						</div>
						<div class="col-md-4">
							<label>Trình độ</label>
							<v-select :options="DataLevel" 
								label="LevelName" 
								:reduce="tag => tag.idLevel" 
								v-model="filedSearch.idLevel"
								taggable multiple 
								>
							</v-select>
						</div>
						<div class="col-md-4">
							<label>Phòng ban</label>
							<v-select :options="DataDepartment" 
								label="DepartmentName" 
								:reduce="tag => tag.idDepartment" 
								v-model="filedSearch.idDepartment" 
								taggable multiple 
								>
							</v-select>
						</div>
						<div class="col-md-4">
							<label>Chức vụ</label>
							<v-select :options="DataPosition" 
								label="PositionName" 
								:reduce="tag => tag.idPosition" 
								v-model="filedSearch.idPosition"
								taggable multiple  
								>
							</v-select>
						</div>
						
						<div class="col-md-4">
							<label>Loại hình công việc</label>
							<v-select :options="DataTypeOfWork" 
								label="TypeOfWorkName" 
								:reduce="tag => tag.idTypeOfWork" 
								v-model="filedSearch.idTypeOfWork" 
								taggable multiple 
								>
							</v-select>
						</div>
						<div class="col-md-4">
							<label>Công ty</label>
							<v-select :options="DataCompany" 
								label="CompanyName" 
								:reduce="tag => tag.idCompany" 
								v-model="filedSearch.idCompany"
								taggable multiple>
							</v-select>
						</div>
						<div class="col-md-4">
							<label>Nhóm quyền</label>
							<v-select :options="DataGroup" 
								label="GroupName" 
								:reduce="tag => tag.idGroup" 
								v-model="filedSearch.idGroup"
								taggable multiple>
							</v-select>
						</div>
						<div class="col-md-4">
							<label>Giới tính</label>
							<v-select   :options="ListSex" label="Name" :reduce="tag => tag.Sex" v-model="filedSearch.Sex" ></v-select>
						</div>
						<div class="col-md-4">
							<label>Tình trạng làm việc</label>
							<v-select :options="ListisWork" label="Name" :reduce="tag => tag.isWork" v-model="filedSearch.isWork" ></v-select>
						</div>
						<div class="col-md-4">
							<label>Tình trạng</label>
							<v-select  :options="ListActive" label="Name" :reduce="tag => tag.Active" v-model="filedSearch.Active" ></v-select>
						</div>
						
						<div class="col-md-4">
							<label >Ngày lập từ</label>
							<date-picker v-model="filedSearch.date_from"  value-type="format" format="DD-MM-YYYY"></date-picker>
						</div>
						<div class="col-md-4">
							<label >Ngày lập đến</label>
							<date-picker v-model="filedSearch.date_to"  value-type="format" format="DD-MM-YYYY"></date-picker>
						</div>
						
					</div>
					<div class="clearfix"></div>
					<hr>
					<div class="form-group row">
						<div class="col-md-6 col-sm-6  offset-md-5">
							<button @click="resetSearch" class="btn btn-primary" type="reset"><i class="fa fa-rotate-left" ></i> Làm lại</button>
							<button @click="filterSearch" type="submit" class="btn btn-success">
								<i :class="{'fa fa-filter': !isLoadingSearch,'fa fa-spinner fa-spin': isLoadingSearch}"></i>
								{{isLoadingSearch==true ? 'Đang xử lý' : 'Tìm kiếm'}}
							</button>
						</div>
					</div>
				</div>

			<div class="x_panel">
              <div class="x_title">
                <h2>NHÂN VIÊN</h2>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
				<div class="table-responsive">
					<table class="table table-hover table-bordered">
						<thead>
							<tr class="headings">
								<th class="text-center" style="width:50px">
									<p-check class="p-icon p-smooth" v-model="checkedAll" @change="checkedAlls">
										<i class="icon fa fa-check-square" slot="extra"></i>
									</p-check>
								</th>
								<th  class="text-center" style="width:50px" title="Ảnh đại điện"><i class="fa fa-image" ></i></th>
								<th @click="orderByColum('IDCode')"  class="text-center" style="width:100px">Mã NV
									<span class="icon_sort" v-html="showIconOrderBy('IDCode',this.orderby)"></span>
								</th>
								<th @click="orderByColum('FullName')" >Tên NV
									<span class="icon_sort" v-html="showIconOrderBy('FullName',this.orderby)"></span>
								</th>
								<th @click="orderByColum('Email')" >Email
									<span class="icon_sort" v-html="showIconOrderBy('Email',this.orderby)"></span>
								</th>
								<th @click="orderByColum('Birthday')"  class="text-center" style="width:100px">Ngày sinh
									<span class="icon_sort" v-html="showIconOrderBy('Birthday',this.orderby)"></span>
								</th>
								<th @click="orderByColum('Sex')"  class="text-center" style="width:100px">
									Giới tính
									<span class="icon_sort" v-html="showIconOrderBy('Sex',this.orderby)"></span>
								</th>
								<th @click="orderByColum('idDepartment')"  class="text-center" style="width:100px">Bộ phận
									<span class="icon_sort" v-html="showIconOrderBy('idDepartment',this.orderby)"></span>
								</th>
								
								<th @click="orderByColum('isWork')" class="text-center" style="width:100px">TTNV
									<span class="icon_sort" v-html="showIconOrderBy('isWork',this.orderby)"></span>
								</th>
								<th @click="orderByColum('Active')" class="text-center" style="width:100px">Tình trạng
									<span class="icon_sort" v-html="showIconOrderBy('Active',this.orderby)"></span>
								</th>
								
							</tr>
						</thead>
						<tbody>
							<tr v-for="(data, index) in datas" :key="index" :class="{'tr-active': data.checked}" @click="datas[index].checked=!datas[index].checked">
								<th scope="row" class="text-center">
									<p-check class="p-icon p-smooth" color="primary-o" :checked="datas[index].checked" v-bind:id="index" @click="datas[index].checked=!datas[index].checked">
										<i class="icon fa fa-check" slot="extra"></i>
									</p-check>
								</th>
								<td class="text-center">
									<img v-if="data.Image==''" src="images/users/noav.png" style="width: 29px;height: 29px;border-radius: 50%;">
									<img v-else  :src="(data.Image != '') ? 'images/users/'+data.Image : data.Image " style="width: 29px;height: 29px;border-radius: 50%;">
								</td>
								<td class="text-center" @click="datas[index].checked=!datas[index].checked">
									<v-popover  placement="right">
										<span class="tooltip-target b3" @click="loadingDataUser(index,data.idUser)">{{ data.IDCode }}</span>
										<template slot="popover">
											<div class="popover-body">
												<div v-if="datas[index].data_detail == ''" style="line-height: 295px;text-align: center;width: 200px;">
													<i class="fa fa-spinner fa-spin" style="font-size: 20px;"></i>
												</div>	
												<div v-if="datas[index].data_detail != ''" >
													<div><strong class="title-infobb">Công ty: </strong> {{ datas[index].data_detail.CompanyName }}</div>
													<div><strong class="title-infobb">Điện thoại: </strong> {{ datas[index].data_detail.Phone }}</div>
													<div><strong class="title-infobb">CMND: </strong> {{ datas[index].data_detail.IDcard }}</div>
													<div><strong class="title-infobb">Ngày cấp: </strong> {{ datas[index].data_detail.IDcardDate |  fomatDate}}</div>
													<div><strong class="title-infobb">Thường trú: </strong> {{ datas[index].data_detail.Address }}</div>
													<div><strong class="title-infobb">Tạm trú: </strong> {{ datas[index].data_detail.AddressStaying }}</div>
													<div><strong class="title-infobb">Trình độ: </strong> {{ datas[index].data_detail.LevelName }}</div>
													<div><strong class="title-infobb">Vị trí: </strong> {{ datas[index].data_detail.PositionName }}</div>
													<div><strong class="title-infobb">Loại hình CV: </strong> {{ datas[index].data_detail.TypeOfWorkName }}</div>
													<div><strong class="title-infobb">Chuyên môn: </strong> {{ datas[index].data_detail.Qualification }}</div>
													<div><strong class="title-infobb">Hôn nhân: </strong> {{ datas[index].data_detail.Married == 0 ? 'Độc thân' : 'Đã kết hôn' }}</div>
													<div><strong class="title-infobb">Thời gian thử việc: </strong> <span v-if="datas[index].data_detail.DayProbationaryFrom"> {{ datas[index].data_detail.DayProbationaryFrom |  fomatDate}} <i class="fa fa-long-arrow-right" ></i> {{ datas[index].data_detail.DayProbationaryTo |  fomatDate}}</span></div>
													<div><strong class="title-infobb">Ngày làm việc chính thức: </strong> <span v-if="datas[index].data_detail.DayWork"> {{ datas[index].data_detail.DayWork |  fomatDate}}</span></div>
													<div v-if="datas[index].data_detail.DayWorkOff" ><strong class="title-infobb">Ngày nghỉ việc: </strong> <span> {{ datas[index].data_detail.DayWorkOff |  fomatDate}}</span></div>
													<div><strong class="title-infobb">Số bảo hiểm: </strong> {{ datas[index].data_detail.HealthInsuranceNo }}</div>
													<div><strong class="title-infobb">Tên ngân hàng: </strong> {{ datas[index].data_detail.BankName }}</div>
													<div><strong class="title-infobb">Số tài khoản: </strong> {{ datas[index].data_detail.AccountNumber }}</div>
													<div><strong class="title-infobb">Nhóm quyền: </strong> {{ datas[index].data_detail.GroupName }}</div>
													<div><strong class="title-infobb">Người lập: </strong> {{ data.FullNameAdd }}</div>
													<div><strong class="title-infobb">Ngày lập: </strong> {{ data.created_at | fomatDate }}</div>
												</div>
											</div>
										</template>
									</v-popover>
								</td>
								<td>{{ data.FullName }}</td>
								<td>{{ data.Email }}</td>
								<td class="text-center">{{ data.Birthday | fomatDate }}</td>
								<td class="text-center">
									<span v-if="data.Sex==0">Nam</span>
									<span v-if="data.Sex==1">Nữ</span>
									<span v-if="data.Sex==2">Khác</span>
								</td>
								<td class="text-center">{{ data.DepartmentName}}</td>
								
								<td class="text-center" @click="datas[index].checked=!datas[index].checked">
									<a class="badge" v-if="Permission.indexOf(11) != -1" >
										<i @click="activeSisWorkOne(data.idUser,data.isWork)" v-tooltip.top-center="data.isWork==1 ? 'Còn làm' : 'Đã nghỉ'" :class="data.isWork==1 ? 'fa fa-check text text-primary' : 'fa fa-close text text-secondary'" ></i>
									</a>
									<a class="badge"  v-if="Permission.indexOf(11) == -1" >
										<i v-tooltip.top-center="data.isWork==1 ? 'Còn làm' : 'Đã nghỉ'" :class="data.isWork==1 ? 'fa fa-check' : 'fa fa-close text text-secondary'" ></i>
									</a>
								</td>
								<td class="text-center" @click="datas[index].checked=!datas[index].checked">
									<a class="badge" v-if="data.Active==1 && Permission.indexOf(6) !=-1">
										<i @click="activeStatusOne(data.idUser,1)" v-tooltip.top-center="'Hoạt động'" class="fa fa-check text text-success" ></i>
									</a>
									<a class="badge" v-if="data.Active==0 && Permission.indexOf(5) !=-1">
										<i @click="activeStatusOne(data.idUser,0)" v-tooltip.top-center="'Tạm ngưng'" class="fa fa-close text text-danger" ></i>
									</a>
									<a class="badge" v-if="data.Active==0 && Permission.indexOf(5) == -1">
										<i v-tooltip.top-center="data.Active==1 ? 'Hoạt động' : 'Tạm ngưng'" :class="data.Active==1 ? 'fa fa-check' : 'fa fa-close'" ></i>
									</a>
									<a class="badge" v-if="data.Active==1 && Permission.indexOf(6) == -1">
										<i v-tooltip.top-center="data.Active==1 ? 'Hoạt động' : 'Tạm ngưng'" :class="data.Active==1 ? 'fa fa-check' : 'fa fa-close'" ></i>
									</a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
              </div>
			  
              <div class="row" style="display:block">
              		<div class="col-sm-7" >
						<paginate v-if="pagination.last_page>1"
							:value="parseInt(page)"
							:page-count="parseInt(pagination.last_page)"
							:page-range="3"
							:margin-pages="2"
							:click-handler="clickCallback"
							:prev-text="'«'"
							:next-text="'»'"
							:container-class="'pagination'"
							:page-class="'page-item'">
						</paginate>
              		</div>
              		<div class="col-sm-5 text-right">
              			<div id="datatable_info" role="status" aria-live="polite">Xem {{pagination.from}} <i class="fa fa-long-arrow-right"></i> {{pagination.to}} <i class="fa fa-ellipsis-v" ></i> Tổng số {{pagination.total}}
							<i class="fa fa-ellipsis-v" ></i>
              				Xem <input class="input-showrows" type="text" v-model="limit" @change="getLimit" > dòng
              			</div>
              		</div>
                </div>
            </div>
			
		</div>
		<div style="clear:both" ></div>
	</div>
	
</template>
<style>

</style>
<script>
	import { isNullOrUndefined, isArray } from 'util';
	import { saveAs } from 'file-saver';
	import * as XLSXStyle from 'xlsx-style';
	import XLSX from 'xlsx';
	export default {
		watch: {
			'$route.query': {
				handler(query) {
					this.page = this.$route.query.page;
					this.showIndex();
				},
			}
		},
		data () {
	      return {
			datas: [],
			isFilter: false,
			isComponent: '',
			orderby: '',
			isLoadingSearch: false,
			orderbyLoading: false,
			filed: '',
			filedSearch: {
				date_from: this.$route.query.date_from,
				date_to: this.$route.query.date_to,
				FullName: this.$route.query.FullName,
				Email: this.$route.query.Email,
				Description: this.$route.query.Description,
				IDCode: this.$route.query.IDCode,
				Phone: this.$route.query.Phone,
				FullNameAdd: this.$route.query.FullNameAdd,
				Sex: this.$route.query.Sex,
				Active: this.$route.query.Active,
				isWork: this.$route.query.isWork,
				Birth: (this.$route.query.Birth) ? this.$route.query.Birth.split(',') : [],
				idLevel: (this.$route.query.idLevel) ? this.$route.query.idLevel.split(',') : [],
				idDepartment: (this.$route.query.idDepartment) ? this.$route.query.idDepartment.split(',') : [],
				idPosition: (this.$route.query.idPosition) ? this.$route.query.idPosition.split(',') : [],
				idTypeOfWork: (this.$route.query.idTypeOfWork) ? this.$route.query.idTypeOfWork.split(',') : [],
				idGroup: (this.$route.query.idGroup) ? this.$route.query.idGroup.split(',') : [],
				idCompany: (this.$route.query.idCompany) ? this.$route.query.idCompany.split(',') : [],
				idGroup: (this.$route.query.idGroup) ? this.$route.query.idGroup.split(',') : []
			},
			pagination: {},
			limit: 0,
			page: this.$route.query.page,
			ListActive: [{Active: '1',Name: 'Hoạt động'},{Active: '0',Name: 'Ngưng hoạt động'}],
			ListisWork: [{isWork: '1',Name: 'Còn làm'},{isWork: '0',Name: 'Đã nghỉ'}],
			ListSex: [{Sex: '0',Name: 'Nam'},{Sex: '1',Name: 'Nữ'},{Sex: '2',Name: 'Khác'}],
			checkedAll: false,
			title_cp: '',
			idUser: 0,
			DataBirth: [],
			DataLevel:[],
			DataDepartment: [],
			DataPosition: [],
			DataTypeOfWork: [],
			DataCompany: [],
			DataGroup: [],
			isLoading: false,
			isLoadingPage: false,
			Permission: [],
			DataInfoPermission: []
	      }
		},
		
		beforeRouteEnter(to, from, next) {

			next(self => {
				if (self.filedSearch.Birth.length > 0) {
					for (let i = 0; i < self.filedSearch.Birth.length; i++) 
					self.filedSearch.Birth[i] = parseInt(self.filedSearch.Birth[i]);
				}
				if (self.filedSearch.idLevel.length > 0) {
					for (let i = 0; i < self.filedSearch.idLevel.length; i++) 
					self.filedSearch.idLevel[i] = parseInt(self.filedSearch.idLevel[i]);
				}
				if (self.filedSearch.idDepartment.length > 0) {
					for (let i = 0; i < self.filedSearch.idDepartment.length; i++) 
					self.filedSearch.idDepartment[i] = parseInt(self.filedSearch.idDepartment[i]);
				}
				if (self.filedSearch.idPosition.length > 0) {
					for (let i = 0; i < self.filedSearch.idPosition.length; i++) 
					self.filedSearch.idPosition[i] = parseInt(self.filedSearch.idPosition[i]);
				}
				if (self.filedSearch.idTypeOfWork.length > 0) {
					for (let i = 0; i < self.filedSearch.idTypeOfWork.length; i++) 
					self.filedSearch.idTypeOfWork[i] = parseInt(self.filedSearch.idTypeOfWork[i]);
				}
				if (self.filedSearch.idGroup.length > 0) {
					for (let i = 0; i < self.filedSearch.idGroup.length; i++) 
					self.filedSearch.idGroup[i] = parseInt(self.filedSearch.idGroup[i]);
				}
				if (self.filedSearch.idCompany.length > 0) {
					for (let i = 0; i < self.filedSearch.idCompany.length; i++) 
					self.filedSearch.idCompany[i] = parseInt(self.filedSearch.idCompany[i]);
				}
				if (self.filedSearch.idGroup.length > 0) {
					for (let i = 0; i < self.filedSearch.idGroup.length; i++) 
					self.filedSearch.idGroup[i] = parseInt(self.filedSearch.idGroup[i]);
				}
				self.showIndex();
			})
			
		},

	    methods: {
	    	createdNew(){
				const self = this;
				self.$router.push({name: 'users.create'});
			},
			update(){
				const self = this;
				if (self.datas.length > 0) {
				var data_active = [];	
				for (let i = 0; i < self.datas.length; i++) 
					if (self.datas[i].checked == true) {
						data_active.push(self.datas[i].idUser);
					}
				}
				if (data_active.length == 1) {
					let idUser = data_active[0];
					self.idUser = idUser;
					self.$router.push({name: 'users.edit',params: {idUser: idUser}});
				}else if (data_active.length > 1 || data_active.length == 0) {
					self.$notify({
						group: 'infomation',
						type: 'error',
						title: 'CHÚ Ý',
						text: 'Vui lòng chọn 1 dòng !'
					});
				}
			},
			listenisSave(data){
				const self = this;
				if (data == true) {
					self.isComponent = '';
					self.showIndex();
				}
			},
			async showIndex(){
				const self = this;
				self.$Progress.start();
				self.orderbyLoading = true;
				self.isLoading = true;
				const res = await axios.get("/staff-information/users",{params: self.$route.query});
				if (res.data.Status == true) {
					self.datas = res.data.datas.data;
					self.Provinces = res.data.Provinces;
					self.District = res.data.District;
					self.filed = res.data.filed;
					self.orderby = res.data.orderby;
					self.limit = res.data.limit;
					self.DataBirth = res.data.DataBirth;
					self.DataLevel = res.data.DataLevel;
					self.DataDepartment = res.data.DataDepartment;
					self.DataPosition = res.data.DataPosition;
					self.DataTypeOfWork = res.data.DataTypeOfWork;
					self.DataCompany = res.data.DataCompany;
					self.DataGroup = res.data.DataGroup;
					self.isLoadingSearch = false;
					self.isLoading = false;
					self.isFilter = false;
					self.isLoadingPage = true;
					self.getPagination(res.data.datas);
					self.$Progress.finish();
					self.checkedAll = false;
					self.orderbyLoading = false;
					self.Permission = res.data.Permission;
					self.DataInfoPermission = res.data.DataInfoPermission;
					if (self.Permission.indexOf(1) ==-1) {self.$router.push({name: 'dashboard'});}
				}else if (res.data.Status == 2) {
					window.location.href = 'admincp/login';
				}
			},
			orderByColum(columname){ // orderby colum
				const self = this;
				self.$Progress.start();
				self.orderby = (self.orderby == 'DESC') ? 'ASC' : 'DESC';
				let querys = self.$route.query;
				delete querys.orderby;
				delete querys.filed;
				self.$router.push({path: '?orderby='+self.orderby+'&filed='+columname ,query: querys});
				self.showIndex();
				self.$Progress.finish();
			},
			showIconOrderBy(columname,orderby){
				const self = this;
				if (columname == self.$route.query.filed) {
					if (self.orderbyLoading == false) {
						var iconcheck = (self.orderby == 'DESC') ? 'up' : 'down';
						return '<i class="fa fa-caret-'+iconcheck+'" >';
					}else{
						return '<i class="fa fa-circle-o-notch fa-spin" >';
					}
				}else if (self.filed == columname && self.$route.query.filed ==null) {
					var iconcheck = (orderby == 'DESC') ? 'up' : 'down';
					return '<i class="fa fa-caret-'+iconcheck+'" >';
				}
			},
			filterSearch(){ // tìm kiếm]
				const self = this;
				self.$Progress.start();
				self.isLoadingSearch = true;
				delete self.filedSearch.page;
				var Search = [];
				Object.keys(self.filedSearch).forEach(key => {
					if ( Number.isNaN(self.filedSearch[key]) == true || self.filedSearch[key] == '') {
						delete self.filedSearch[key];
					}else if(isArray(self.filedSearch[key])){
						Search[key] = self.filedSearch[key].join(',');
					}else{
						Search[key] = self.filedSearch[key];
					}
				})
				self.$router.push({query: Search});
				self.showIndex();
			},
			resetSearch(){
				const self = this;
				self.filedSearch = {};
			},
			Refresh(){
				const self = this;
				self.isLoading = true;
				self.$Progress.start();
				self.showIndex();
			},
			getPagination(data){
				const self = this;
				self.pagination = {
					current_page: data.current_page,
					from: data.from,
					last_page: data.last_page,
					per_page: data.per_page,
					to: data.to,
					total: data.total
				};
			},
			clickCallback(pageNum){ // click trang
				const self = this;
				self.$Progress.start();
				pageNum = parseInt(pageNum) > 0 ? pageNum : 1;
				delete self.$route.query.page;
				self.$router.push({path:'?page='+pageNum,query: self.$route.query});
				self.showIndex();
			},
			getLimit(){ // get limit
				const self = this;
				if (self.limit > 0 && self.limit < 100) {}else{self.limit = 25;}
				self.$Progress.start();
				let querylimit = self.$route.query;
				delete querylimit.limit;
				self.$router.push({path: '?page=1&limit='+self.limit,query: querylimit});
				self.showIndex();
			},
			checkedAlls(){ // checked nhiều
				const self = this;
				for (let i = 0; i < self.datas.length; i++) 
				self.datas[i].checked = self.checkedAll;
			},
			activeStatusOne(idUser,Active){
				const self = this;
				self.$dialog.confirm({title: "THÔNG BÁO?",body: "Bạn có muốn xác nhận thực hiện"})
				.then(function(dialog) {
					axios.post("/staff-information/users/activeStatusOne",{idUser: idUser,Active: Active})
					.then(res => {
						if (res.data.Status == true) {
							dialog.close();
							self.showIndex();
							self.$notify({
							group: 'infomation',
							type: 'success',
							title: 'THÔNG BÁO',
							text: 'Cập nhật thành công !'
							});
						}else if (res.data.Status == 2) {
							window.location.href = 'admincp/login';
						}
					}).catch(err => {
						self.isLoading = false;
						self.isDisable = false;
						self.isSave = false;
						self.$notify({
							group: 'infomation',
							type: 'error',
							title: 'LỖI',
							text: 'Có lỗi trong quá trình !'
						});
					})
					//dialog.close();
				})
				.catch(function() {
					console.log('Clicked on cancel');
				});

			},
			activeStatusAll(Active){
				const self = this;
				if (self.datas.length > 0) {
				var data_active = [];	
				for (let i = 0; i < self.datas.length; i++) 
					if (self.datas[i].checked == true) {
						data_active.push(self.datas[i].idUser);
					}
				}
				if (data_active.length > 0) {
					self.$dialog.confirm({title: "THÔNG BÁO?",body: "Bạn có muốn xác nhận thực hiện"})
					.then(function(dialog) {
						axios.post("/staff-information/users/activeStatusAll",{data_active: data_active,Active: Active})
						.then(res => {
							if (res.data.Status == true) {
								dialog.close();
								self.showIndex();
								self.$notify({
								group: 'infomation',
								type: 'success',
								title: 'THÔNG BÁO',
								text: 'Cập nhật thành công !'
								});
							}else if (res.data.Status == 2) {
								window.location.href = 'admincp/login';
							}
						}).catch(err => {
							self.isLoading = false;
							self.isDisable = false;
							self.isSave = false;
							self.$notify({
								group: 'infomation',
								type: 'error',
								title: 'LỖI',
								text: 'Có lỗi trong quá trình !'
							});
						})
						//dialog.close();
					})
					.catch(function() {
						console.log('Clicked on cancel');
					});
					
				}else if (data_active.length == 0) {
					self.$notify({
						group: 'infomation',
						type: 'error',
						title: 'CHÚ Ý',
						text: 'Vui lòng chọn ít nhất 1 dòng !'
					});
				}
			},
			deleteAll(){
				const self = this;
				if (self.datas.length > 0) {
				var data_active = [];	
				for (let i = 0; i < self.datas.length; i++) 
					if (self.datas[i].checked == true) {
						data_active.push(self.datas[i].idUser);
					}
				}
				if (data_active.length > 0) {
					self.$dialog.confirm({title: "THÔNG BÁO?",body: "Bạn có muốn xóa, dữ liệu không thể phục hồi"})
					.then(function(dialog) {
						axios.delete("/staff-information/users/destroy",{params: {'data_active': data_active}})
						.then(res => {
							if (res.data.Status == true) {
								dialog.close();
								self.showIndex();
								self.$notify({
								group: 'infomation',
								type: 'success',
								title: 'THÔNG BÁO',
								text: 'Xóa thành công !'
								});
							}else if (res.data.Status == 2) {
								window.location.href = 'admincp/login';
							}
						}).catch(err => {
							self.isLoading = false;
							self.isDisable = false;
							self.isSave = false;
							self.$notify({
								group: 'infomation',
								type: 'error',
								title: 'LỖI',
								text: 'Có lỗi trong quá trình !'
							});
						})
						//dialog.close();
					})
					.catch(function() {
						console.log('Clicked on cancel');
					});
					
				}else if (data_active.length == 0) {
					self.$notify({
						group: 'infomation',
						type: 'error',
						title: 'CHÚ Ý',
						text: 'Vui lòng chọn ít nhất 1 dòng !'
					});
				}
			},
			activeSisWorkOne(idUser,isWork){
				const self = this;
				self.$dialog.confirm({title: "THÔNG BÁO?",body: "Bạn có muốn xác nhận thực hiện"})
				.then(function(dialog) {
					axios.post("/staff-information/users/activeSisWorkOne",{idUser: idUser,isWork: isWork})
					.then(res => {
						if (res.data.Status == true) {
							dialog.close();
							self.showIndex();
							self.$notify({
							group: 'infomation',
							type: 'success',
							title: 'THÔNG BÁO',
							text: 'Cập nhật thành công !'
							});
						}else if (res.data.Status == 2) {
							window.location.href = 'admincp/login';
						}
					}).catch(err => {
						self.isLoading = false;
						self.isDisable = false;
						self.isSave = false;
						self.$notify({
							group: 'infomation',
							type: 'error',
							title: 'LỖI',
							text: 'Có lỗi trong quá trình !'
						});
					})
					//dialog.close();
				})
				.catch(function() {
					console.log('Clicked on cancel');
				});
				
			},
			loadingDataUser(index,idUser){
				const self = this;
				if (self.datas[index].data_detail == '') {
					axios.post("/staff-information/users/"+idUser+"/loadingDataUser")
					.then(res => {
						if (res.data.Status == true) {
							self.datas[index].data_detail = res.data.data_detail;
						}else if (res.data.Status == 2) {
							window.location.href = 'admincp/login';
						}
					})
				}
			},
			async exportExcel() { // xuất excel
                const self = this;
                self.isLoading = true;
                const res = await axios.get("/staff-information/users/1/exportExcel",{params: self.$route.query});
                let data = res.data.datas;
                let createXLSLFormatObj = [];
                var TitleName = ["DANH SÁCH NHÂN VIÊN"];
                var columcel = ["STT","Mã NV","Tên NV","Email","Ngày sinh","Nơi sinh","Điện thoại","Giới tính","CMND","Ngày cấp CMND","Nơi cấp","Hôn nhân","Thường trú","Tạm trú","Trình độ","Chuyên môn","Phòng ban","Chức vụ","Loại hình công việc","Mã Công ty","Công ty","Thời gian thử việc","Ngày làm việc chính thức","Ngày nghỉ việc","Số bảo hiểm","Tên ngân hàng","Số tài khoản","Ghi chú","Mã người lập","Người lập","Ngày lập","Tình trạng NV","Tình trạng"];

				var dateTime = new Date();
				var filename = "users-"+moment(dateTime).format("DD-MM-YYYY-HH-mm-ss")+".xlsx";
                var sheetname = "users";
				createXLSLFormatObj.push(TitleName);
				createXLSLFormatObj.push([""]);
                createXLSLFormatObj.push(columcel);
                var stt = 0;
                data.forEach(el => {
                    stt++;
                    createXLSLFormatObj.push([
                        stt,
                        el.IDCode,
						el.FullName,
						el.Email,
						isNaN(el.Birthday) ? moment(el.Birthday).format("DD-MM-YYYY") : "",
						el.ProvincesBirth==null ? '' : el.ProvincesBirth,
						el.Phone==null ? '' : el.Phone,
						el.Sex ==0 ? 'Nam' : el.Sex ==1 ? 'Nữ' : 'Khác',
						el.IDcard==null ? '' : el.IDcard,
						isNaN(el.IDcardDate) ? moment(el.IDcardDate).format("DD-MM-YYYY") : "",
						el.ProvincesIDcardAddress==null ? '' : el.ProvincesIDcardAddress,
						el.Married == 0 ? 'Đã kết hôn' : 'Độc thân',
						el.Address==null ? '' : el.Address,
						el.AddressStaying==null ? '' : el.AddressStaying,
						el.LevelName==null ? '' : el.LevelName,
						el.Qualification==null ? '' : el.Qualification,
						el.DepartmentName==null ? '' : el.DepartmentName,
						el.PositionName==null ? '' : el.PositionName,
						el.TypeOfWorkName==null ? '' : el.TypeOfWorkName,
						el.CompanyNameIDCode==null ? '' : el.CompanyNameIDCode,
						el.CompanyName==null ? '' : el.CompanyName,
						isNaN(el.DayProbationaryFrom) ? moment(el.DayProbationaryFrom).format("DD-MM-YYYY")+ ' ~ '+ moment(el.DayProbationaryTo).format("DD-MM-YYYY") : "",
						isNaN(el.DayWork) ? moment(el.DayWork).format("DD-MM-YYYY") : "",
						isNaN(el.DayWorkOff) ? moment(el.DayWorkOff).format("DD-MM-YYYY") : "",
						el.HealthInsuranceNo==null ? '' : el.HealthInsuranceNo,
						el.BankName==null ? '' : el.BankName,
						el.AccountNumber==null ? '' : el.AccountNumber,
						el.Description==null ? '' : el.Description,
						el.FullNameAddIDCode,
						el.FullNameAdd,
						moment(el.created_at).format("DD-MM-YYYY HH:mm:ss"),
						el.isWork == 1 ? "Còn làm" : "Đã nghỉ",
						el.Active == 1 ? "Đang hoạt động" : "Tạm ngưng"
                    ]);
                });

				var ws = XLSX.utils.aoa_to_sheet(createXLSLFormatObj);
                // /* add merges */
                if(!ws['!merges']) ws['!merges'] = [{ s: {r:0, c:0}, e: {r:1, c:columcel.length-1} }];
				
                ws['!cols'] = [ // độ rộng
                    {wpx: 50},
					{wpx: 100},
					{wpx: 200},
					{wpx: 200},
					{wpx: 150},
					{wpx: 200},
					{wpx: 150},
					{wpx: 100},
					{wpx: 150},
					{wpx: 150},
					{wpx: 200},
					{wpx: 150},
					{wpx: 300},
					{wpx: 300},
					{wpx: 200},
					{wpx: 200},
					{wpx: 200},
					{wpx: 200},
					{wpx: 200},
					{wpx: 100},
					{wpx: 300},
					{wpx: 300},
					{wpx: 150},
					{wpx: 150},
					{wpx: 200},
					{wpx: 200},
					{wpx: 200},
					{wpx: 300},
					{wpx: 100},
					{wpx: 200},
					{wpx: 150},
					{wpx: 150},
					{wpx: 150},
					
				];
				let keyCheck = 0;
				var ArrTypeBonus = [];
				let rows_data = 3;
                for (let key in ws) {
					keyCheck++;
                    if (key[0] === '!') continue;
                    ws[key].s = {
						border: {top: { style: "thin",color: {rgb: "2A3F54" } },right: { style: "thin",color: {rgb: "2A3F54" } },let: { style: "thin",color: {rgb: "2A3F54" } },bottom: { style: "thin",color: {rgb: "2A3F54" } }},
						font: {name: "Arial",sz: 9,color: {rgb: "2A3F54" }}	
					};
					if (keyCheck >= rows_data && keyCheck < columcel.length+rows_data) {
						ws[key].s = {
							border: {top: { style: "thin",color: {rgb: "2A3F54" } },right: { style: "thin",color: {rgb: "2A3F54" } },let: { style: "thin",color: {rgb: "2A3F54" } },bottom: { style: "thin",color: {rgb: "2A3F54" } }},
							font: {name: "Arial",sz: 10,bold: true,color: {rgb: "2A3F54" }},
							fill:{fgColor:{ rgb: "C6D2E3" }}
						};
					}
					
				}

				ws.A1.s = { alignment: { wrapText: true, vertical: 'center', horizontal: 'center' },font: {bold: true,name:"Arial",sz: 13,color: {rgb: "2A3F54" },italic: true} };
				var wb = XLSX.utils.book_new();
				XLSX.utils.book_append_sheet(wb, ws, sheetname);
				const wbout = XLSXStyle.write(wb, { type: "buffer", bookType: "xlsx"});
				saveAs(new Blob([wbout], { type: "application/octet-stream" }), filename);
				self.isLoading = false;
			}
		},
	}
</script>

<style scoped>
	
</style>