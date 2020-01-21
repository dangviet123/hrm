
<template>
	<div class="row" style="display: block;">
		<loading :active.sync="isLoading" :can-cancel="false" color="#2196F3" :width= "50" :height= "50"></loading>
		<div class="col-md-12 col-sm-12" v-if="isLoadingPage">

			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="#">Home</a></li>
			    <li class="breadcrumb-item"><a href="#">Dữ liệu nền</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Tỉnh thành phố</li>
			  </ol>
			</nav>
			<div class="x_content_button" >
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
						<label>Mã</label>
						<input type="text"  v-model="filedSearch.IDCode" class="form-control" >
					</div>
					<div class="col-md-4">
						<label>Tỉnh thành phố</label>
						<input type="text"  v-model="filedSearch.Provinces" class="form-control" >
					</div>
					<div class="col-md-4">
						<label for="Notes">Ghi chú</label>
						<input type="text" id="Notes" v-model="filedSearch.Notes" class="form-control" >
					</div>
					<div class="col-md-4">
						<label>Người lập</label>
						<input type="text"  v-model="filedSearch.FullName" class="form-control" >
					</div>
					<div class="col-md-4">
						<label>Tình trạng</label>
						<v-select  :options="ListActive" label="Name" :reduce="tag => tag.Active" v-model="filedSearch.Active" ></v-select>
					</div>
					<div class="col-md-4">
						<label for="created_at">Ngày lập từ</label>
						<date-picker v-model="filedSearch.date_from"  value-type="format" format="DD-MM-YYYY"></date-picker>
					</div>
					<div class="col-md-4">
						<label for="created_at">Ngày lập đến</label>
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
			
			<!-- modal -->
			<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="exampleModalLabel">{{ title_cp }}</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
			        <component v-bind:is="isComponent" @listenisSaveCheck="listenisSave" :idProvinces="idProvinces" ></component>
			      </div>
			    </div>
			  </div>
			</div>
			<!-- end modal  -->
			<div class="x_panel">
              <div class="x_title">
                <h2>TỈNH/TP</h2>
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

							<th @click="orderByColum('IDCode')" style="width:100px" class="text-center">Mã
								<span class="icon_sort" v-html="showIconOrderBy('IDCode',this.orderby)"></span>
							</th>
							<th @click="orderByColum('Provinces')">Tỉnh/TP
								<span class="icon_sort" v-html="showIconOrderBy('Provinces',this.orderby)"></span>
							</th>

							<th @click="orderByColum('idUserAdd')" style="width:150px" class="text-center">
								Người lập
								<span class="icon_sort" v-html="showIconOrderBy('idUserAdd',this.orderby)"></span>
							</th>
							<th @click="orderByColum('created_at')" style="width:100px" class="text-center">Ngày lập
								<span class="icon_sort" v-html="showIconOrderBy('created_at',this.orderby)"></span>
							</th>
							<th @click="orderByColum('OrderBy')" class="text-center" style="width:100px">Thứ tự
								<span class="icon_sort" v-html="showIconOrderBy('OrderBy',this.orderby)"></span>
							</th>
							<th @click="orderByColum('Active')" class="text-center" style="width:100px">Tình trạng
								<span class="icon_sort" v-html="showIconOrderBy('Active',this.orderby)"></span>
							</th>
							<th  class="text-center" style="width:50px"><i class="fa fa-arrows" ></i></th>
							
						</tr>
					</thead>
					<draggable v-model="datas" tag="tbody" handle=".handle"  @change="sortBy" >
						<tr v-for="(data, index) in datas" :key="index" :class="{'tr-active': data.checked}" @click="datas[index].checked=!datas[index].checked">
							<th scope="row" class="text-center">
								<p-check class="p-icon p-smooth" color="primary-o" :checked="datas[index].checked" v-bind:id="index" @click="datas[index].checked=!datas[index].checked">
									<i class="icon fa fa-check" slot="extra"></i>
								</p-check>
							</th>
							<td class="text-center">{{ data.IDCode }}</td>
							<td>{{ data.Provinces }}</td>
							<td class="text-center">{{ data.FullName }}</td>
							<td class="text-center">{{data.created_at | fomatDate}}</td>
							<td class="text-center">{{ data.OrderBy }}</td>
							<td class="text-center" @click="datas[index].checked=!datas[index].checked">
								<a class="badge" v-if="data.Active==1 && Permission.indexOf(6) !=-1">
									<i @click="activeStatusOne(data.idProvinces,1)" v-tooltip.top-center="'Hoạt động'" class="fa fa-check text text-success" ></i>
								</a>
								<a class="badge" v-if="data.Active==0 && Permission.indexOf(5) !=-1">
									<i @click="activeStatusOne(data.idProvinces,0)" v-tooltip.top-center="'Tạm ngưng'" class="fa fa-close text text-danger" ></i>
								</a>
								<a class="badge" v-if="data.Active==0 && Permission.indexOf(5) == -1">
									<i v-tooltip.top-center="data.Active==1 ? 'Hoạt động' : 'Tạm ngưng'" :class="data.Active==1 ? 'fa fa-check' : 'fa fa-close'" ></i>
								</a>
								<a class="badge" v-if="data.Active==1 && Permission.indexOf(6) == -1">
									<i v-tooltip.top-center="data.Active==1 ? 'Hoạt động' : 'Tạm ngưng'" :class="data.Active==1 ? 'fa fa-check' : 'fa fa-close'" ></i>
								</a>
							</td>
							<td class="text-center" @click="datas[index].checked=!datas[index].checked">
								<a href="javascript:" class="badge"><i class="fa fa-arrows handle" v-tooltip.top-center="'Sắp xếp'"></i></a>
							</td>
							
						</tr>
					</draggable>
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
	import appCreate from './create';
	import appUpdate from './edit';
	import { isNullOrUndefined, isArray } from 'util';
	import draggable from "vuedraggable";
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
				Provinces: this.$route.query.Provinces,
				IDCode: this.$route.query.IDCode,
				FullName: this.$route.query.FullName,
				Notes: this.$route.query.Notes,
				Active: this.$route.query.Active
			},
			pagination: {},
			limit: 0,
			page: this.$route.query.page,
			ListActive: [{Active: '1',Name: 'Hoạt động'},{Active: '0',Name: 'Ngưng hoạt động'}],
			checkedAll: false,
			title_cp: '',
			idProvinces: 0,
			isLoading: false,
			isLoadingPage: false,
			Permission: [],
			DataInfoPermission: []
	      }
	    },
	    components: {appCreate,appUpdate,draggable},
		beforeRouteEnter(to, from, next) {
			try {
				next(self => {
					self.showIndex();
				})
			} catch (err) {
				next(false)
			}
		},

	    methods: {
	    	createdNew(){
				const self = this;
				$('#exampleModal').modal('show');
				self.isComponent = appCreate;
				self.title_cp = 'THÊM MỚI';
			},
			update(){
				const self = this;
				if (self.datas.length > 0) {
				var data_active = [];	
				for (let i = 0; i < self.datas.length; i++) 
					if (self.datas[i].checked == true) {
						data_active.push(self.datas[i].idProvinces);
					}
				}
				if (data_active.length == 1) {
					let idProvinces = data_active[0];
					$('#exampleModal').modal('show');
					self.title_cp = 'CHỈNH SỬA';
					self.isComponent = '';
					self.isComponent = appUpdate;
					self.idProvinces = idProvinces;
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
				axios.get("/bgdata/provinces",{params: self.$route.query})
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
			activeStatusOne(idProvinces,Active){
				const self = this;
				self.$dialog.confirm({title: "THÔNG BÁO?",body: "Bạn có muốn xác nhận thực hiện"})
				.then(function(dialog) {
					axios.post("/bgdata/provinces/activeStatusOne",{idProvinces: idProvinces,Active: Active})
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
						data_active.push(self.datas[i].idProvinces);
					}
				}
				if (data_active.length > 0) {
					self.$dialog.confirm({title: "THÔNG BÁO?",body: "Bạn có muốn xác nhận thực hiện"})
					.then(function(dialog) {
						axios.post("/bgdata/provinces/activeStatusAll",{data_active: data_active,Active: Active})
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
						data_active.push(self.datas[i].idProvinces);
					}
				}
				if (data_active.length > 0) {
					self.$dialog.confirm({title: "THÔNG BÁO?",body: "Bạn có muốn xóa, dữ liệu không thể phục hồi"})
					.then(function(dialog) {
						axios.delete("/bgdata/provinces/destroy",{params: {'data_active': data_active}})
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
			sortBy(){
				const self = this;
				var idProvinces = [];
				var orderby = [];
				for (let i = 0; i < self.datas.length; i++) {
					idProvinces.push(self.datas[i].idProvinces);
					orderby.push(self.datas[i].OrderBy);
				}
				orderby.sort(function(a,b) {return (self.orderby == 'ASC') ? a - b : b - a;});
				self.isLoading = true;
				axios.post("/bgdata/provinces/sortBy",{idProvinces: idProvinces,orderby: orderby})
				.then(res => {
					if (res.data.Status == true) {
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
			},
			async exportExcel() { // xuất excel
                const self = this;
                self.isLoading = true;
                const res = await axios.get("/bgdata/provinces/1/exportExcel",{params: self.$route.query});
                let data = res.data.datas;
                let createXLSLFormatObj = [];
                var TitleName = ["QUẬN HUYỆN"];
                var columcel = ["STT","Mã Tỉnh/TP","Tỉnh/TP","Ghi chú","Mã người lập","Người lập","Ngày lập","Tình trạng"];
				
				var dateTime = new Date();
				var filename = "provinces-"+moment(dateTime).format("DD-MM-YYYY-HH-mm-ss")+".xlsx";
                var sheetname = "provinces";
                
				createXLSLFormatObj.push(TitleName);
				createXLSLFormatObj.push([""]);
                createXLSLFormatObj.push(columcel);
                var stt = 0;
                data.forEach(el => {
                    stt++;
                    createXLSLFormatObj.push([
                        stt,
                        el.IDCode,
						el.Provinces,
						el.Notes==null ? '' : el.Notes,
                        el.FullNameIDCode,
                        el.FullName,
                        moment(el.created_at).format("DD-MM-YYYY HH:mm:ss"),
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
                    {wpx: 100},
                    {wpx: 200},
                    {wpx: 150},
                    {wpx: 150}
				];
				let keyCheck = 0;
                for (let key in ws) {
					keyCheck++;
                    if (key[0] === '!') continue;
                    ws[key].s = {
						border: {top: { style: "thin",color: {rgb: "2A3F54" } },right: { style: "thin",color: {rgb: "2A3F54" } },let: { style: "thin",color: {rgb: "2A3F54" } },bottom: { style: "thin",color: {rgb: "2A3F54" } }},
						font: {name: "Arial",sz: 9,color: {rgb: "2A3F54" }}	
					};
					if (keyCheck >= 3 && keyCheck < columcel.length+3) {
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
		}
	}
</script>