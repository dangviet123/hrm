
<template>
	<div class="row" style="display: block;">
		<loading :active.sync="isLoading" :can-cancel="false" color="#2196F3" :width= "50" :height= "50"></loading>
		<div class="col-md-12 col-sm-12" v-if="isLoadingPage">

			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="#">Home</a></li>
			    <li class="breadcrumb-item"><a href="#">Thông tin nhân viên</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Thưởng thành tích</li>
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
				<a v-tooltip.top-center="DataInfoPermission[12].ListName" v-if="Permission.indexOf(12) !=-1" class="btn btn-defaults" @click="ManageCheckAll(1)">
					<i :class="DataInfoPermission[12].Icon"></i>
				</a>
				<a v-tooltip.top-center="DataInfoPermission[7].ListName" v-if="Permission.indexOf(7) !=-1" class="btn btn-defaults" @click="deleteAll">
					<i :class="DataInfoPermission[7].Icon"></i>
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
							<label>Mã KT</label>
							<input type="text"  v-model="filedSearch.IDCode" class="form-control" >
						</div>
						<div class="col-md-4">
							<label>Tên giải thưởng/Thành tích</label>
							<input type="text"  v-model="filedSearch.Achievements" class="form-control" >
						</div>

						<div class="col-md-4">
							<label>Nhân viên</label>
							<v-select :options="DataUsers" 
								label="IDCodeFullName" 
								:reduce="tag => tag.idUser" 
								v-model="filedSearch.idStaff"
								taggable multiple>
							</v-select>
						</div>

						<div class="col-md-4">
							<label>Loại thưởng</label>
							<v-select  :options="DataAchievementsType" label="AchievementsType" :reduce="tag => tag.idAchievementsType" v-model="filedSearch.idAchievementsType" ></v-select>
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


			<!-- start modal per  -->
                <div class="modal fade bd-example-modal-lg" id="myLargeModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">{{ title_cp }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body content-fix">
                                <component v-bind:is="isComponent" @listenisSaveCheck="listenisSave" :idAchievements="idAchievements" ></component>
                            </div>
                        </div>
                    </div>
                </div>
                    <!-- end modal per  -->

			<div class="x_panel">
              <div class="x_title">
                <h2>THƯỞNG THÀNH TÍCH</h2>
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
								<th @click="orderByColum('IDCode')" style="width:100px" class="text-center">Mã KT
									<span class="icon_sort" v-html="showIconOrderBy('IDCode',this.orderby)"></span>
								</th>
								<th @click="orderByColum('IDCodeStaff')" style="width:100px" class="text-center">Mã NV
									<span class="icon_sort" v-html="showIconOrderBy('IDCodeStaff',this.orderby)"></span>
								</th>
								<th @click="orderByColum('idStaff')" style="width:150px" class="text-center">Nhân viên
									<span class="icon_sort" v-html="showIconOrderBy('idStaff',this.orderby)"></span>
								</th>
				
								<th @click="orderByColum('idAchievementsType')" style="width:120px" class="text-center">
									Loại thưởng
									<span class="icon_sort" v-html="showIconOrderBy('idAchievementsType',this.orderby)"></span>
								</th>
								<th @click="orderByColum('Bonus')" style="width:150px" class="text-center">
									Tiền thưởng
									<span class="icon_sort" v-html="showIconOrderBy('Bonus',this.orderby)"></span>
								</th>
								<th @click="orderByColum('BonusDay')" style="width:150px" class="text-center">
									Ngày thưởng
									<span class="icon_sort" v-html="showIconOrderBy('BonusDay',this.orderby)"></span>
								</th>
								
								<th @click="orderByColum('Active')" class="text-center" style="width:100px">Tình trạng
									<span class="icon_sort" v-html="showIconOrderBy('Active',this.orderby)"></span>
								</th>
								<th @click="orderByColum('ManageCheck')" class="text-center" style="width:70px">Duyệt
									<span class="icon_sort" v-html="showIconOrderBy('ManageCheck',this.orderby)"></span>
								</th>
								<th @click="orderByColum('Locked')" class="text-center" style="width:70px">Khóa
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
								
								<td class="text-center" @click="datas[index].checked=!datas[index].checked" >
									<v-popover  placement="right">
										<span class="tooltip-target b3" @click="getInfoAchievements(index,data.idAchievements)">{{ data.IDCode }}</span>
										<template slot="popover">
											<div class="popover-body">
												<div v-if="datas[index].data_detail == ''" style="line-height: 250px;text-align: center;width: 200px;">
													<i class="fa fa-spinner fa-spin" style="font-size: 20px;"></i>
												</div>	
												<div v-if="datas[index].data_detail != ''" >
													<div><strong class="title-infobb">Tên giải thưởng/Thành tích: </strong> {{  data.Achievements }}</div>
													<div><strong class="title-infobb">Công ty/CN: </strong> {{ datas[index].data_detail.CompanyName }}</div>
													<div><strong class="title-infobb">Nhân viên: </strong> {{ data.FullNameStaff }} ({{data.IDCodeStaff}})</div>
													<div><strong class="title-infobb">Phòng: </strong> {{ datas[index].data_detail.DepartmentName }}</div>
													<div><strong class="title-infobb">Vị trí: </strong> {{ datas[index].data_detail.PositionName }}</div>
													<div><strong class="title-infobb">Quà thưởng: </strong> {{ datas[index].data_detail.Artifacts }}</div>
													<div><strong class="title-infobb">Người lập: </strong> {{ datas[index].data_detail.FullNameAdd }}</div>
													<div><strong class="title-infobb">Ngày lập: </strong> {{ datas[index].data_detail.created_at | fomatDateTime }}</div>
													<div v-if="datas[index].data_detail.ManageCheck"><strong class="title-infobb">Người duyệt: </strong> {{ datas[index].data_detail.FullNameCheck }}</div>
													<div v-if="datas[index].data_detail.ManageCheck"><strong class="title-infobb">Ngày duyệt: </strong> {{ datas[index].data_detail.DateManageCheck | fomatDateTime }}</div>
												</div>
											</div>
										</template>
									</v-popover>
								</td>
								<td class="text-center">{{ data.IDCodeStaff }}</td>
								<td class="text-center">{{ data.FullNameStaff }}</td>
								<td class="text-center">{{ data.AchievementsType }}</td>
								
								<td class="text-center"> 
									<vue-numeric read-only v-bind:precision="0" currency="" separator="," v-model="data.Bonus"></vue-numeric>
								</td>
								<td class="text-center">{{data.BonusDay | fomatDate}}</td>
								
								<td class="text-center" @click="datas[index].checked=!datas[index].checked">
									<a class="badge" v-if="data.Active==1 && Permission.indexOf(6) !=-1 && data.Locked==0">
										<i @click="activeStatusOne(data.idAchievements,1,data.Locked)" v-tooltip.top-center="'Hoạt động'" class="fa fa-check text text-success" ></i>
									</a>
									<a class="badge" v-if="data.Active==0 && Permission.indexOf(5) !=-1 && data.Locked==0">
										<i @click="activeStatusOne(data.idAchievements,0,data.Locked)" v-tooltip.top-center="'Tạm ngưng'" class="fa fa-close text text-danger" ></i>
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
										<i @click="ManageCheckOne(data.idAchievements,1,data.Locked)" class="fa fa-close text text-danger" v-tooltip.top-center="'Chưa duyệt'"></i>
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
										<i @click="LockedOne(data.idAchievements,0)" v-tooltip.top-center="'Khóa'" class="fa fa-lock" ></i>
									</a>
									<a class="badge" href="javascript:" v-if="data.Locked==0 && Permission.indexOf(13) !=-1">
										<i @click="LockedOne(data.idAchievements,1)" v-tooltip.top-center="'Mở'" class="fa fa-unlock" ></i>
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
	import { isNullOrUndefined, isArray, isUndefined } from 'util';
	import draggable from "vuedraggable";
	import { required, minLength, between,maxLength } from 'vuelidate/lib/validators'
	import VueNumeric from 'vue-numeric'
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
				CompanyName: this.$route.query.CompanyName,
				FullName: this.$route.query.FullName,
				Description: this.$route.query.Description,
				IDCode: this.$route.query.IDCode,
				Phone: this.$route.query.Phone,
				Notes: this.$route.query.Notes,
				Active: this.$route.query.Active,
				Locked: this.$route.query.Locked,
				ManageCheck: this.$route.query.ManageCheck,
				idAchievementsType: parseInt(this.$route.query.idAchievementsType),
				idDepartment: (this.$route.query.idDepartment) ? this.$route.query.idDepartment.split(',') : [],
				idPosition: (this.$route.query.idPosition) ? this.$route.query.idPosition.split(',') : [],
				idCompany: (this.$route.query.idCompany) ? this.$route.query.idCompany.split(',') : [],
				idStaff: (this.$route.query.idStaff) ? this.$route.query.idStaff.split(',') : [],
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
			idAchievements: 0,
			isLoading: false,
			isLoadingPage: false,
			Permission: [],
			DataInfoPermission: [],
			DataAchievementsType: [],
			DataCompany: [],
			DataDepartment: [],
			DataPosition: [],
			DataUsers: []
	      }
		},
		components: {VueNumeric},

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
					if (self.filedSearch.idStaff.length > 0) {
						for (let i = 0; i < self.filedSearch.idStaff.length; i++) 
						self.filedSearch.idStaff[i] = parseInt(self.filedSearch.idStaff[i]);
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
				self.$router.push({name: 'achievements.create'});
			},
			update(){
				const self = this;
				if (self.datas.length > 0) {
				var data_active = [];	
				for (let i = 0; i < self.datas.length; i++) 
					if (self.datas[i].checked == true) {
						data_active.push(self.datas[i].idAchievements);
					}
				}

				if (data_active.length == 1) {
					let idAchievements = data_active[0];
					self.idAchievements = idAchievements;
					self.$router.push({name: 'achievements.edit',params: {idAchievements: idAchievements}});
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
					self.showIndex();
				}
			},
			showIndex(){
				const self = this;
				self.$Progress.start();
				self.orderbyLoading = true;
				self.isLoading = true;
				axios.get("/staff-information/achievements",{params: self.$route.query})
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
						self.getPagination(res.data.datas);
						self.$Progress.finish();
						self.checkedAll = false;
						self.orderbyLoading = false;
						self.Permission = res.data.Permission;
						self.DataInfoPermission = res.data.DataInfoPermission;
						self.DataAchievementsType = res.data.DataAchievementsType;
						self.DataCompany = res.data.DataCompany;
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
			activeStatusOne(idAchievements,Active,Locked){
				if (Locked == 1) {return false;}
				const self = this;
				self.$dialog.confirm({title: "THÔNG BÁO?",body: "Bạn có muốn xác nhận thực hiện"})
				.then(function(dialog) {
					axios.post("/staff-information/achievements/activeStatusOne",{idAchievements: idAchievements,Active: Active})
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
			ManageCheckOne(idAchievements,ManageCheck,Locked){
				if (Locked == 1) {return false;}
				const self = this;
				self.$dialog.confirm({title: "THÔNG BÁO?",body: "Bạn có muốn xác nhận thực hiện duyệt phiếu"})
				.then(function(dialog) {
					axios.post("/staff-information/achievements/ManageCheckOne",{idAchievements: idAchievements,ManageCheck: ManageCheck})
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
						self.isLoading = false;
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
			LockedOne(idAchievements,Locked){
				const self = this;
				self.$dialog.confirm({title: "THÔNG BÁO?",body: "Bạn có muốn xác nhận thực hiện mở khóa"})
				.then(function(dialog) {
					axios.post("/staff-information/achievements/LockedOne",{idAchievements: idAchievements,Locked: Locked})
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
						if (self.datas[i].Locked == 1) {
							self.$notify({
								group: 'infomation',
								type: 'error',
								title: 'LỖI',
								text: 'Phiếu bị khóa không thể thao tác !'
							});
							return false;
						}
						data_active.push(self.datas[i].idAchievements);
					}
				}
				if (data_active.length > 0) {
					self.$dialog.confirm({title: "THÔNG BÁO?",body: "Bạn có muốn xác nhận thực hiện"})
					.then(function(dialog) {
						axios.post("/staff-information/achievements/activeStatusAll",{data_active: data_active,Active: Active})
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
						if (self.datas[i].Locked == 1) {
							self.$notify({
								group: 'infomation',
								type: 'error',
								title: 'LỖI',
								text: 'Phiếu bị khóa không thể thao tác !'
							});
							return false;
						}
						data_active.push(self.datas[i].idAchievements);
					}
				}
				if (data_active.length > 0) {
					self.$dialog.confirm({title: "THÔNG BÁO?",body: "Bạn có muốn xóa, dữ liệu không thể phục hồi"})
					.then(function(dialog) {
						axios.delete("/staff-information/achievements/destroy",{params: {'data_active': data_active}})
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
						data_active.push(self.datas[i].idAchievements);
					}
				}
				if (data_active.length > 0) {
					self.$dialog.confirm({title: "THÔNG BÁO?",body: "Bạn có muốn xác nhận thực hiện"})
					.then(function(dialog) {
						axios.post("/staff-information/achievements/LockedAll",{data_active: data_active,Locked: Locked})
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
						data_active.push(self.datas[i].idAchievements);
					}
				}
				if (data_active.length > 0) {
					self.$dialog.confirm({title: "THÔNG BÁO?",body: "Bạn có muốn xác nhận thực hiện duyệt phiếu"})
					.then(function(dialog) {
						axios.post("/staff-information/achievements/ManageCheckAll",{data_active: data_active,ManageCheck: ManageCheck})
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
			getInfoAchievements(index,idAchievements){
				const self = this;
				if (self.datas[index].data_detail == '') {
					axios.post("/staff-information/achievements/"+idAchievements+"/getInfoAchievements")
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
                const res = await axios.get("/staff-information/achievements/1/exportExcel",{params: self.$route.query});
                let data = res.data.datas;
                let createXLSLFormatObj = [];
                var TitleName = ["THƯỞNG THÀNH TÍCH"];
                var columcel = ["STT","Mã KT","Mã NV","Nhân viên","Bộ phận","Vị trí","Mã công ty","Công ty","Tên giải thưởng","Ghi chú","Loại thưởng","Tiền thưởng","Quà thưởng","Ngày thưởng","Mã người lập","Người lập","Ngày lập","Mã người duyệt","Người duyệt","Ngày duyệt","Tình trạng","Duyệt"];
				
				var dateTime = new Date();
				var filename = "achievements-"+moment(dateTime).format("DD-MM-YYYY-HH-mm-ss")+".xlsx";
                var sheetname = "achievements";
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
						el.DepartmentName==null ? '' : el.DepartmentName,
						el.PositionName==null ? '' : el.PositionName,
						el.CompanyNameIDCode,
						el.CompanyName,
						el.Achievements==null ? '' : el.Achievements,
						el.Notes==null ? '' : el.Notes,
						el.AchievementsType,
						el.Bonus,
						el.Artifacts==null ? '' : el.Artifacts,
						isNaN(el.BonusDay) ? moment(el.BonusDay).format("DD-MM-YYYY") : "",
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
                // /* add merges */
                if(!ws['!merges']) ws['!merges'] = [{ s: {r:0, c:0}, e: {r:1, c:columcel.length-1} }];
				
                ws['!cols'] = [ // độ rộng
                    {wpx: 50},
					{wpx: 100},
					{wpx: 100},
					{wpx: 200},
					{wpx: 150},
					{wpx: 150},
					{wpx: 100},
					{wpx: 300},
					{wpx: 250},
					{wpx: 250},
					{wpx: 200},
					{wpx: 200},
					{wpx: 200},
					{wpx: 150},
					{wpx: 100},
					{wpx: 200},
					{wpx: 150},
					{wpx: 100},
					{wpx: 200},
					{wpx: 150},
					{wpx: 150},
					{wpx: 150}
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
				// mảng format định dạng các trường
				for (let i = rows_data+1;  i <= rows_data + data.length; i++) {
					ws["L"+i].s = {
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