
<template>
	<div class="row" style="display: block;">
		<loading :active.sync="isLoading" :can-cancel="false" color="#2196F3" :width= "50" :height= "50"></loading>
		<div class="col-md-12 col-sm-12" v-if="isLoadingPage">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="#">Home</a></li>
			    <li class="breadcrumb-item"><a href="#">Lương</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Bảng lương</li>
			  </ol>
			</nav>
			<div class="x_content_button">
				<a v-tooltip.top-center="DataInfoPermission[2].ListName" v-if="Permission.indexOf(2) !=-1" class="btn btn-defaults" @click="Refresh"> 
					<i :class="DataInfoPermission[2].Icon"></i>
				</a>
				<a v-tooltip.top-center="DataInfoPermission[3].ListName" v-if="Permission.indexOf(3) !=-1" class="btn btn-defaults" @click="createdNew" >
					<i :class="DataInfoPermission[3].Icon"></i>
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
				<a v-tooltip.top-center="DataInfoPermission[12].ListName" v-if="Permission.indexOf(12) !=-1" class="btn btn-defaults" @click="ManageCheckAll(1)">
					<i :class="DataInfoPermission[12].Icon"></i>
				</a>
				<a v-tooltip.top-center="DataInfoPermission[13].ListName" v-if="Permission.indexOf(13) !=-1" class="btn btn-defaults" @click="LockedAll(1)">
					<i :class="DataInfoPermission[13].Icon"></i>
				</a>
				<a v-tooltip.top-center="DataInfoPermission[14].ListName" v-if="Permission.indexOf(14) !=-1" class="btn btn-defaults" @click="LockedAll(0)">
					<i :class="DataInfoPermission[14].Icon"></i>
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
							<label>Số phiếu</label>
							<input type="text"  v-model="filedSearch.IDCode" class="form-control" >
						</div>
						<div class="col-md-4">
							<label>Tiêu đề</label>
							<input type="text"  v-model="filedSearch.Calculated" class="form-control" >
						</div>

						<div class="col-md-4">
							<label>Nhân viên</label>
							<v-select :options="DataUsers" 
								label="IDCodeFullName" 
								:reduce="tag => tag.idUser" 
								v-model="filedSearch.idUserSalary"
								taggable multiple>
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
							<label>Người lập</label>
							<v-select :options="DataUsers" 
								label="IDCodeFullName" 
								:reduce="tag => tag.idUser" 
								v-model="filedSearch.idUserAdd"
								taggable multiple>
							</v-select>
						</div>

						<div class="col-md-4">
							<label>Người duyệt</label>
							<v-select :options="DataUsers" 
								label="IDCodeFullName" 
								:reduce="tag => tag.idUser" 
								v-model="filedSearch.idManageCheck"
								taggable multiple>
							</v-select>
						</div>

						<div class="col-md-4">
							<label >Ghi chú</label>
							<input type="text"  v-model="filedSearch.Notes" class="form-control" >
						</div>
						
						<div class="col-md-4">
							<label>Tình trạng duyệt</label>
							<v-select  :options="ListManageCheck" label="Name" :reduce="tag => tag.ManageCheck" v-model="filedSearch.ManageCheck" ></v-select>
						</div>
						<div class="col-md-4">
							<label>Tình trạng</label>
							<v-select  :options="ListActive" label="Name" :reduce="tag => tag.Active" v-model="filedSearch.Active" ></v-select>
						</div>
						<div class="col-md-4">
							<label>Khóa</label>
							<v-select  :options="ListLocked" label="Name" :reduce="tag => tag.Locked" v-model="filedSearch.Locked" ></v-select>
						</div>
						<div class="col-md-4">
							<label >Ngày lập từ</label>
							<date-picker v-model="filedSearch.date_from"  value-type="format" format="DD-MM-YYYY"></date-picker>
						</div>
						<div class="col-md-4">
							<label >Ngày lập đến</label>
							<date-picker v-model="filedSearch.date_to"  value-type="format" format="DD-MM-YYYY"></date-picker>
						</div>
						<div class="col-md-4">
							<label >Tháng lương</label>
							<date-picker v-model="filedSearch.MonthlySalary" value-type="format"  type="month" format="MM-YYYY"></date-picker>
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
                <h2>BẢNG LƯƠNG</h2>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
				  <views-teamplate-salary :idCalculated="idCalculated" ></views-teamplate-salary>
				<div class="table-responsive">
					<table class="table table-hover table-bordered">
						<thead>
							<tr class="headings">
								<th class="text-center" style="width:50px">
									<p-check class="p-icon p-smooth" v-model="checkedAll" @change="checkedAlls">
										<i class="icon fa fa-check-square" slot="extra"></i>
									</p-check>
								</th>

								<th @click="orderByColum('IDCode')" style="width:130px" class="text-center">Số phiếu
									<span class="icon_sort" v-html="showIconOrderBy('IDCode',this.orderby)"></span>
								</th>
								<th @click="orderByColum('IDCodeUser')" style="width:100px" class="text-center">Mã NV
									<span class="icon_sort" v-html="showIconOrderBy('IDCodeUser',this.orderby)"></span>
								</th>
								<th @click="orderByColum('idUserSalary')" style="width:200px"> Tên NV
									<span class="icon_sort" v-html="showIconOrderBy('idUserSalary',this.orderby)"></span>
								</th>
								<th @click="orderByColum('Email')" > Email
									<span class="icon_sort" v-html="showIconOrderBy('Email',this.orderby)"></span>
								</th>
								<th @click="orderByColum('MonthlySalary')" style="width:110px" class="text-center">Tháng lương
									<span class="icon_sort" v-html="showIconOrderBy('MonthlySalary',this.orderby)"></span>
								</th>
								<th @click="orderByColum('TotalSalaryDataAVG')" style="width:110px" class="text-center">Thành tiền
									<span class="icon_sort" v-html="showIconOrderBy('TotalSalaryDataAVG',this.orderby)"></span>
								</th>
								<th style="width:50px" class="text-center"><i class="fa fa-eye" ></i></th>
								<th @click="orderByColum('Active')" class="text-center" style="width:100px">Tình trạng
									<span class="icon_sort" v-html="showIconOrderBy('Active',this.orderby)"></span>
								</th>
								<th @click="orderByColum('ManageCheck')" class="text-center" style="width:50px">Duyệt
									<span class="icon_sort" v-html="showIconOrderBy('ManageCheck',this.orderby)"></span>
								</th>
								<th @click="orderByColum('Locked')" class="text-center" style="width:50px">Khóa
									<span class="icon_sort" v-html="showIconOrderBy('Locked',this.orderby)"></span>
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
								<td class="text-center" @click="datas[index].checked=!datas[index].checked">
									<v-popover  placement="right">
										<span class="tooltip-target b3" @click="loadingDataDetail(index,data.idCalculated)">{{ data.IDCode }}</span>
										<template slot="popover">
											<div class="popover-body">
												<div v-if="datas[index].data_detail == ''" style="line-height: 295px;text-align: center;width: 200px;">
													<i class="fa fa-spinner fa-spin" style="font-size: 20px;"></i>
												</div>	
												<div v-if="datas[index].data_detail != ''" >
													<div><strong class="title-infobb">Công ty: </strong> {{ datas[index].data_detail.CompanyName }}</div>
													<div><strong class="title-infobb">Tiêu đề: </strong> {{ datas[index].data_detail.Calculated }}</div>
													<div><strong class="title-infobb">Tên nhân viên: </strong> {{ data.FullNameStaff }}</div>
													<div><strong class="title-infobb">Phòng: </strong> {{ datas[index].data_detail.DepartmentName }}</div>
													<div><strong class="title-infobb">Cấp bậc: </strong> {{ datas[index].data_detail.PositionName }}</div>
													<div><strong class="title-infobb">Ngày lập: </strong> {{ datas[index].data_detail.created_at | fomatDate }}</div>
													<div><strong class="title-infobb">Người lập: </strong> {{ datas[index].data_detail.FullNameAdd }}</div>
													<div><strong class="title-infobb">Ngày công: </strong> {{ datas[index].data_detail.NumberWorkdays }}</div>
													<div><strong class="title-infobb">Tổng cộng: </strong>
														<vue-numeric read-only v-bind:precision="0" currency="" separator="," v-model="datas[index].data_detail.TotalSalaryDataA"></vue-numeric>
													 </div>
													<div><strong class="title-infobb">Tổng trừ: </strong>
														<vue-numeric read-only v-bind:precision="0" currency="" separator="," v-model="datas[index].data_detail.TotalSalaryDataB"></vue-numeric>
													</div>
													<div><strong class="title-infobb">Tiền nhận: </strong>
														<vue-numeric read-only v-bind:precision="0" currency="" separator="," v-model="datas[index].data_detail.TotalSalaryDataAVG"></vue-numeric>
													</div>
													<div><strong class="title-infobb">Tiền bằng chữ: </strong> {{ datas[index].data_detail.TotalSalaryWords }}</div>
													<div><strong class="title-infobb">Ghi chú: </strong> {{ datas[index].data_detail.Notes }}</div>
													<div v-if="datas[index].data_detail.ManageCheck"><strong class="title-infobb">Người duyệt: </strong> {{ datas[index].data_detail.FullNameManageCheck }}</div>
													<div v-if="datas[index].data_detail.ManageCheck"><strong class="title-infobb">Ngày duyệt: </strong> {{ datas[index].data_detail.DateManageCheck | fomatDateTime }}</div>
												</div>
											</div>
										</template>
									</v-popover>
								</td>
								<td class="text-center">{{ data.IDCodeUser }}</td>
								<td>{{ data.FullNameStaff }}</td>
								<td>{{ data.Email }}</td>
								<td class="text-center">{{ data.MonthlySalary | fomatDateMonthYear }}</td>
								<td class="text-center">
									<vue-numeric read-only v-bind:precision="0" currency="" separator="," v-model="data.TotalSalaryDataAVG"></vue-numeric>
								</td>
								<td class="text-center" @click="datas[index].checked=!datas[index].checked">
									<a class="badge" @click="viewsTeamplateSalary(data.idCalculated)" v-tooltip.top-center="'In'">
										<i class="fa fa-eye" ></i>
									</a>
								</td>
								
								<td class="text-center" @click="datas[index].checked=!datas[index].checked">
									<a class="badge" v-if="data.Active==1 && Permission.indexOf(6) !=-1 && data.Locked==0">
										<i @click="activeStatusOne(data.idCalculated,1,data.Locked)" v-tooltip.top-center="'Hoạt động'" class="fa fa-check text text-success" ></i>
									</a>
									<a class="badge" v-if="data.Active==0 && Permission.indexOf(5) !=-1 && data.Locked==0">
										<i @click="activeStatusOne(data.idCalculated,0,data.Locked)" v-tooltip.top-center="'Tạm ngưng'" class="fa fa-close text text-danger" ></i>
									</a>
									<a class="badge" v-if="data.Active==0 && Permission.indexOf(5) == -1 && data.Locked==0">
										<i v-tooltip.top-center="data.Active==1 ? 'Hoạt động' : 'Tạm ngưng'" :class="data.Active==1 ? 'fa fa-check text-unper' : 'fa fa-close text-unper'" ></i>
									</a>
									<a class="badge" v-if="data.Active==1 && Permission.indexOf(6) == -1 && data.Locked==0">
										<i v-tooltip.top-center="data.Active==1 ? 'Hoạt động' : 'Tạm ngưng'" :class="data.Active==1 ? 'fa fa-check text-unper' : 'fa fa-close text-unper'" ></i>
									</a>
									<a class="badge" v-if="data.Locked==1">
										<i v-tooltip.top-center="data.Active==1 ? 'Hoạt động' : 'Tạm ngưng'" :class="data.Active==1 ? 'fa fa-check text-unper' : 'fa fa-close text-unper'" ></i>
									</a>
								</td>
								<td class="text-center" @click="datas[index].checked=!datas[index].checked">
									<a class="badge" v-if="data.ManageCheck==0 && Permission.indexOf(12) !=-1 && data.Locked==0">
										<i @click="ManageCheckOne(data.idCalculated,1,data.Locked)" class="fa fa-close text text-danger" v-tooltip.top-center="'Chưa duyệt'"></i>
									</a>
									<a class="badge" v-if="data.ManageCheck==1 && Permission.indexOf(12) !=-1 && data.Locked==0">
										<i class="fa fa-check text text-success" v-tooltip.top-center="'Đã duyệt'"></i>
									</a>
									<a class="badge" v-if="Permission.indexOf(12) ==-1 || data.Locked==1">
										<i v-tooltip.top-center="data.ManageCheck==1 ? 'Đã duyệt': 'Chưa duyệt'" :class="data.ManageCheck==0 ? 'fa fa-close text-unper' : 'fa fa-check text-unper'"></i>
									</a>
								</td>
								<td class="text-center" @click="datas[index].checked=!datas[index].checked">
									<a class="badge" href="javascript:" v-if="data.Locked==1 && Permission.indexOf(14) !=-1">
										<i @click="LockedOne(data.idCalculated,0)" v-tooltip.top-center="'Khóa'" class="fa fa-lock" ></i>
									</a>
									<a class="badge" href="javascript:" v-if="data.Locked==0 && Permission.indexOf(13) !=-1">
										<i @click="LockedOne(data.idCalculated,1)" v-tooltip.top-center="'Mở'" class="fa fa-unlock" ></i>
									</a>
									<a class="badge" v-if="data.Locked==0 && Permission.indexOf(13) == -1">
										<i v-tooltip.top-center="data.Locked==1 ? 'Khóa' : 'Mở'" :class="data.Locked==1 ? 'fa fa-lock text-unper' : 'fa fa-unlock text-unper'" ></i>
									</a>
									<a class="badge" v-if="data.Locked==1 && Permission.indexOf(14) == -1">
										<i v-tooltip.top-center="data.Locked==1 ? 'Khóa' : 'Mở'" :class="data.Locked==1 ? 'fa fa-lock text-unper' : 'fa fa-unlock text-unper'" ></i>
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
	import VueNumeric from 'vue-numeric'
	import viewsTeamplateSalary from './view.vue'
	
	import { saveAs } from 'file-saver';
	import * as XLSXStyle from 'xlsx-style';
	import XLSX from 'xlsx'
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
				MonthlySalary: this.$route.query.MonthlySalary,
				Bacsic: this.$route.query.Bacsic,
				FullNameAdd: this.$route.query.FullNameAdd,
				IDCode: this.$route.query.IDCode,
				Notes: this.$route.query.Notes,
				Active: this.$route.query.Active,
				idCompany: (this.$route.query.idCompany) ? this.$route.query.idCompany.split(',') : [],
				Locked: this.$route.query.Locked,
				ManageCheck: this.$route.query.ManageCheck,
				idDepartment: (this.$route.query.idDepartment) ? this.$route.query.idDepartment.split(',') : [],
				idPosition: (this.$route.query.idPosition) ? this.$route.query.idPosition.split(',') : [],
				idUserSalary: (this.$route.query.idUserSalary) ? this.$route.query.idUserSalary.split(',') : [],
				idUserAdd: (this.$route.query.idUserAdd) ? this.$route.query.idUserAdd.split(',') : [],
				idManageCheck: (this.$route.query.idManageCheck) ? this.$route.query.idManageCheck.split(',') : []
			},
			pagination: {},
			limit: 0,
			page: this.$route.query.page,
			ListActive: [{Active: '1',Name: 'Hoạt động'},{Active: '0',Name: 'Ngưng hoạt động'}],
			ListManageCheck: [{ManageCheck: '1',Name: 'Đã duyệt'},{ManageCheck: '0',Name: 'Chưa duyệt'}],
			ListLocked: [{Locked: '1',Name: 'Khóa'},{Locked: '0',Name: 'Mở'}],
			checkedAll: false,
			title_cp: '',
			idCalculated: 0,
			isLoading: false,
			isLoadingPage: false,
			Permission: [],
			DataInfoPermission: [],
			DataCompany: [],
			DataDepartment: [],
			DataPosition: [],
			DataUsers: []
	      }
	    },
		components: {VueNumeric,viewsTeamplateSalary},
		beforeRouteEnter(to, from, next) {
			try {
				next(self => {
					if (self.filedSearch.idDepartment.length > 0) {
						for (let i = 0; i < self.filedSearch.idDepartment.length; i++) 
						self.filedSearch.idDepartment[i] = parseInt(self.filedSearch.idDepartment[i]);
					}
					if (self.filedSearch.idPosition.length > 0) {
						for (let i = 0; i < self.filedSearch.idPosition.length; i++) 
						self.filedSearch.idPosition[i] = parseInt(self.filedSearch.idPosition[i]);
					}
					if (self.filedSearch.idCompany.length > 0) {
						for (let i = 0; i < self.filedSearch.idCompany.length; i++) 
						self.filedSearch.idCompany[i] = parseInt(self.filedSearch.idCompany[i]);
					}
					if (self.filedSearch.idUserSalary.length > 0) {
						for (let i = 0; i < self.filedSearch.idUserSalary.length; i++) 
						self.filedSearch.idUserSalary[i] = parseInt(self.filedSearch.idUserSalary[i]);
					}
					if (self.filedSearch.idManageCheck.length > 0) {
						for (let i = 0; i < self.filedSearch.idManageCheck.length; i++) 
						self.filedSearch.idManageCheck[i] = parseInt(self.filedSearch.idManageCheck[i]);
					}
					if (self.filedSearch.idUserAdd.length > 0) {
						for (let i = 0; i < self.filedSearch.idUserAdd.length; i++) 
						self.filedSearch.idUserAdd[i] = parseInt(self.filedSearch.idUserAdd[i]);
					}
					self.showIndex();
				})
			} catch (err) {
				next(false)
			}
		},

	    methods: {
	    	createdNew(){
				const self = this;
				self.$router.push({name: 'calculated.create'});
			},
			update(){
				const self = this;
				if (self.datas.length > 0) {
				var data_active = [];	
				for (let i = 0; i < self.datas.length; i++) 
					if (self.datas[i].checked == true) {
						data_active.push(self.datas[i].idCalculated);
					}
				}

				if (data_active.length == 1) {
					let idCalculated = data_active[0];
					self.idCalculated = idCalculated;
					self.$router.push({name: 'calculated.edit',params: {idCalculated: idCalculated}});
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
			showIndex(){
				const self = this;
				self.$Progress.start();
				self.orderbyLoading = true;
				self.isLoading = true;
				axios.get("/salarys/calculated",{params: self.$route.query})
				.then(res => {
					if (res.data.Status == true) {
						self.datas = res.data.datas.data;
						self.filed = res.data.filed;
						self.orderby = res.data.orderby;
						self.limit = res.data.limit;
						self.isLoadingSearch = false;
						self.isFilter = false;
						self.isLoading = false;
						self.isLoadingPage = true;
						self.orderbyLoading = false;
						self.getPagination(res.data.datas);
						self.$Progress.finish();
						self.checkedAll = false;
						self.Permission = res.data.Permission;
						self.DataCompany = res.data.DataCompany;
						self.DataInfoPermission = res.data.DataInfoPermission;
						self.DataDepartment = res.data.DataDepartment;
						self.DataPosition = res.data.DataPosition;
						self.DataUsers = res.data.DataUsers;
						if (self.Permission.indexOf(1) ==-1) {self.$router.push({name: 'dashboard'});}
					}else if (res.data.Status == 2) {
						window.location.href = 'admincp/login';
					}
				})
				.catch(err => {
					console.error(err); 
				})
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
			activeStatusOne(idCalculated,Active,Locked){
				if (Locked == 1) {return false;}
				const self = this;
				self.$dialog.confirm({title: "THÔNG BÁO?",body: "Bạn có muốn xác nhận thực hiện"})
				.then(function(dialog) {
					axios.post("/salarys/calculated/activeStatusOne",{idCalculated: idCalculated,Active: Active})
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
			ManageCheckOne(idCalculated,ManageCheck,Locked){
				if (Locked == 1) {return false;}
				const self = this;
				self.$dialog.confirm({title: "THÔNG BÁO?",body: "Bạn có muốn xác nhận thực hiện duyệt phiếu"})
				.then(function(dialog) {
					axios.post("/salarys/calculated/ManageCheckOne",{idCalculated: idCalculated,ManageCheck: ManageCheck})
					.then(res => {
						if (res.data.Status == true) {
							dialog.close();
							self.showIndex();
							self.$notify({
							group: 'infomation',
							type: 'success',
							title: 'THÔNG BÁO',
							text: 'Duyệt thành công !'
							});
						}else if (res.data.Status == 2) {
							window.location.href = 'admincp/login';
						}
					}).catch(err => {
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
					//dialog.close();
				})
				.catch(function() {
					console.log('Clicked on cancel');
				});
				
			},
			LockedOne(idCalculated,Locked){
				const self = this;
				self.$dialog.confirm({title: "THÔNG BÁO?",body: "Bạn có muốn xác nhận thực hiện mở khóa"})
				.then(function(dialog) {
					axios.post("/salarys/calculated/LockedOne",{idCalculated: idCalculated,Locked: Locked})
					.then(res => {
						if (res.data.Status == true) {
							dialog.close();
							self.showIndex();
							self.$notify({
							group: 'infomation',
							type: 'success',
							title: 'THÔNG BÁO',
							text: 'Mở khóa thành công !'
							});
						}else if (res.data.Status == 2) {
							window.location.href = 'admincp/login';
						}
					}).catch(err => {
						this.$Progress.finish();
						vm.isDisable = false;
						vm.isSave = false;
						this.$notify({
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
						if (self.datas[i].Locked == 1) {
							self.$notify({
								group: 'infomation',
								type: 'error',
								title: 'LỖI',
								text: 'Phiếu bị khóa không thể thao tác !'
							});
							return false;
						}
						data_active.push(self.datas[i].idCalculated);
					}
				}
				if (data_active.length > 0) {
					self.$dialog.confirm({title: "THÔNG BÁO?",body: "Bạn có muốn xác nhận thực hiện"})
					.then(function(dialog) {
						axios.post("/salarys/calculated/activeStatusAll",{data_active: data_active,Active: Active})
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
						if (self.datas[i].Locked == 1) {
							self.$notify({
								group: 'infomation',
								type: 'error',
								title: 'LỖI',
								text: 'Phiếu bị khóa không thể thao tác !'
							});
							return false;
						}
						data_active.push(self.datas[i].idCalculated);
					}
				}
				if (data_active.length > 0) {
					self.$dialog.confirm({title: "THÔNG BÁO?",body: "Bạn có muốn xóa, dữ liệu không thể phục hồi"})
					.then(function(dialog) {
						axios.delete("/salarys/calculated/destroy",{params: {'data_active': data_active}})
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
							this.$Progress.finish();
							vm.isDisable = false;
							vm.isSave = false;
							this.$notify({
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
			LockedAll(Locked){
				const self = this;
				if (self.datas.length > 0) {
				var data_active = [];	
				for (let i = 0; i < self.datas.length; i++) 
					if (self.datas[i].checked == true) {
						data_active.push(self.datas[i].idCalculated);
					}
				}
				if (data_active.length > 0) {
					self.$dialog.confirm({title: "THÔNG BÁO?",body: "Bạn có muốn xác nhận thực hiện"})
					.then(function(dialog) {
						axios.post("/salarys/calculated/LockedAll",{data_active: data_active,Locked: Locked})
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
			ManageCheckAll(ManageCheck){
				const self = this;
				if (self.datas.length > 0) {
				var data_active = [];	
				for (let i = 0; i < self.datas.length; i++) 
					if (self.datas[i].checked == true) {
						if (self.datas[i].Locked == 1) {
							self.$notify({
								group: 'infomation',
								type: 'error',
								title: 'LỖI',
								text: 'Phiếu bị khóa không thể thao tác !'
							});
							return false;
						}
						data_active.push(self.datas[i].idCalculated);
					}
				}
				if (data_active.length > 0) {
					self.$dialog.confirm({title: "THÔNG BÁO?",body: "Bạn có muốn xác nhận thực hiện duyệt phiếu"})
					.then(function(dialog) {
						axios.post("/salarys/calculated/ManageCheckAll",{data_active: data_active,ManageCheck: ManageCheck})
						.then(res => {
							if (res.data.Status == true) {
								dialog.close();
								self.showIndex();
								self.$notify({
									group: 'infomation',
									type: 'success',
									title: 'THÔNG BÁO',
									text: 'Duyệt thành công !'
								});
							}else if (res.data.Status == 2) {
								window.location.href = 'admincp/login';
							}
						}).catch(err => {
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
			loadingDataDetail(index,idCalculated){
				const self = this;
				if (self.datas[index].data_detail == '') {
					axios.post("/salarys/calculated/"+idCalculated+"/loadingDataDetail")
					.then(res => {
						if (res.data.Status == true) {
							self.datas[index].data_detail = res.data.data_detail;
						}else if (res.data.Status == 2) {
							window.location.href = 'admincp/login';
						}
					})
				}
			},
			viewsTeamplateSalary(idCalculated){
				$('#myLargeModal').modal('show');
				const self = this;
				self.idCalculated = idCalculated;
				
			},
			async exportExcel() { // xuất excel
                const self = this;
                self.isLoading = true;
                const res = await axios.get("/salarys/calculated/1/exportExcel",{params: self.$route.query});
                let data = res.data.datas;
                let createXLSLFormatObj = [];
                var TitleName = ["BẢNG LƯƠNG NHÂN VIÊN"];
                var columcel = ["STT","Số phiếu","Mã NV","Nhân viên","Email","Bộ phận","Vị trí","Mã công ty","Công ty","Tiêu đề","Ghi chú","Số ngày công","Tháng lương","Tổng cộng","Tổng trừ","Tiền nhận","Tiền nhận bằng chữ","Mã người lập","Người lập","Ngày lập","Mã người duyệt","Người duyệt","Ngày duyệt","Tình trạng","Duyệt"];

				var dateTime = new Date();
				var filename = "salarys-calculated-"+moment(dateTime).format("DD-MM-YYYY-HH-mm-ss")+".xlsx";
                var sheetname = "salarys-calculated";
				createXLSLFormatObj.push(TitleName);
				createXLSLFormatObj.push([""]);
                createXLSLFormatObj.push(columcel);
                var stt = 0;
                data.forEach(el => {
                    stt++;
                    createXLSLFormatObj.push([
                        stt,
                        el.IDCode,
						el.FullNameSffIDCode,
						el.FullNameSff,
						el.Email==null ? '' : el.Email,
						el.DepartmentName==null ? '' : el.DepartmentName,
						el.PositionName==null ? '' : el.PositionName,
						el.CompanyNameIDCode,
						el.CompanyName,
						el.Calculated==null ? '' : el.Calculated,
						el.Notes==null ? '' : el.Notes,
						el.NumberWorkdays,
						isNaN(el.MonthlySalary) ? moment(el.MonthlySalary).format("MM-YYYY") : "",
						el.TotalSalaryDataA,
						el.TotalSalaryDataB,
						el.TotalSalaryDataAVG,
						el.TotalSalaryWords,
						el.FullNameAddIDCode,
						el.FullNameAdd,
						moment(el.created_at).format("DD-MM-YYYY HH:mm:ss"),
						el.FullNameCheckIDCode==null ? '' : el.FullNameCheckIDCode,
						el.FullNameCheck==null ? '' : el.FullNameCheck,
						isNaN(el.DateManageCheck) ? moment(el.DateManageCheck).format("DD-MM-YYYY HH:mm:ss") : "",
						el.Active == 1 ? "Đang hoạt động" : "Tạm ngưng",
						el.ManageCheck == 1 ? "Đã duyệt" : "Chưa duyệt"
                    ]);
                });

				var ws = XLSX.utils.aoa_to_sheet(createXLSLFormatObj);
				//ws['!autofilter']={ref:"A1:E1"};
                // /* add merges */
                if(!ws['!merges']) ws['!merges'] = [{ s: {r:0, c:0}, e: {r:1, c:columcel.length-1} }];
				
                ws['!cols'] = [ // độ rộng
                    {wpx: 50},
					{wpx: 100},
					{wpx: 100},
					{wpx: 200},
					{wpx: 200},
					{wpx: 200},
					{wpx: 200},
					{wpx: 100},
					{wpx: 300},
					{wpx: 250},
					{wpx: 300},
					{wpx: 150},
					{wpx: 150},
					{wpx: 150},
					{wpx: 150},
					{wpx: 150},
					{wpx: 350},
					{wpx: 100},
					{wpx: 200},
					{wpx: 150},
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

				for (let i = rows_data+1;  i <= rows_data + data.length; i++) {
					ws["N"+i].s = {
						border: {top: { style: "thin",color: {rgb: "2A3F54" } },right: { style: "thin",color: {rgb: "2A3F54" } },let: { style: "thin",color: {rgb: "2A3F54" } },bottom: { style: "thin",color: {rgb: "2A3F54" } }},
						font: {name: "Arial",sz: 9,color: {rgb: "2A3F54" }},
						numFmt: "#,##0"
					};
					ws["O"+i].s = {
						border: {top: { style: "thin",color: {rgb: "2A3F54" } },right: { style: "thin",color: {rgb: "2A3F54" } },let: { style: "thin",color: {rgb: "2A3F54" } },bottom: { style: "thin",color: {rgb: "2A3F54" } }},
						font: {name: "Arial",sz: 9,color: {rgb: "2A3F54" }},
						numFmt: "#,##0"
					};
					ws["P"+i].s = {
						border: {top: { style: "thin",color: {rgb: "2A3F54" } },right: { style: "thin",color: {rgb: "2A3F54" } },let: { style: "thin",color: {rgb: "2A3F54" } },bottom: { style: "thin",color: {rgb: "2A3F54" } }},
						font: {name: "Arial",sz: 9,color: {rgb: "2A3F54" }},
						numFmt: "#,##0"
					};
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