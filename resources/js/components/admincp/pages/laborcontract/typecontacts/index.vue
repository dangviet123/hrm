
<template>
	<div class="row" >
		<div class="col-md-12 col-sm-12" >

			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="#">Home</a></li>
			    <li class="breadcrumb-item"><a href="#">Hợp đồng lao động</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Loại hợp đồng</li>
			  </ol>
			</nav>
			<div class="x_content" style="background: white;border: 1px solid #e6e9ed;padding-top: 15px;" >
				<a class="btn btn-app"><i class="fa fa-refresh"></i> Làm Mới</a>
				<a class="btn btn-app" @click="createdNew" ><i class="fa fa-plus-square-o"></i>Thêm mới</a>
				<a class="btn btn-app"><i class="fa fa-edit"></i>Chỉnh sửa</a>
				<a class="btn btn-app"><i class="fa fa-check"></i> Hoạt động</a>
				<a class="btn btn-app"><i class="fa fa-close"></i> Tạm ngưng</a>
				<a class="btn btn-app"><i class="fa fa-trash"></i> Xóa</a>
				<a class="btn btn-app collapse-link" @click="isFilter =! isFilter"><i class="fa fa-filter" ></i>Bộ lọc</a>
			</div>
			<transition name="slide-fade">
				<div class="x_panel" v-show="isFilter" >
					<div class="x_title"><h2>BỘ LỌC KẾT HỢP</h2> <div class="clearfix"></div></div>
					<ul class="nav navbar-right panel_toolbox" style="min-width:0px">
	                  	<li><a @click="isFilter =! isFilter"><i class="fa fa-close"></i></a>	
	                  </li>
	                </ul>
					<div class="x_content" >
						<div class="col-md-4">
							<label for="fullname">Mã nhân viên</label>
							<input type="text" id="fullname" class="form-control" >
						</div>
						<div class="col-md-4">
							<label for="fullname">Mã nhân viên</label>
							<input type="text" id="fullname" class="form-control" >
						</div>
						<div class="col-md-4">
							<label for="fullname">Mã nhân viên</label>
							<input type="text" id="fullname" class="form-control" >
						</div>
						<div class="col-md-4">
							<label for="fullname">Mã nhân viên</label>
							<input type="text" id="fullname" class="form-control" >
						</div>
						<div class="col-md-4">
							<label for="fullname">Mã nhân viên</label>
							<input type="text" id="fullname" class="form-control" >
						</div>
						<div class="col-md-4">
							<label for="fullname">Mã nhân viên</label>
							<input type="text" id="fullname" class="form-control" >
						</div>
						<div class="col-md-4">
							<label for="fullname">Mã nhân viên</label>
							<input type="text" id="fullname" class="form-control" >
						</div>
						<div class="col-md-4">
							<label for="fullname">Mã nhân viên</label>
							<input type="text" id="fullname" class="form-control" >
						</div>
						<div class="col-md-4">
							<label for="fullname">Mã nhân viên</label>
							<input type="text" id="fullname" class="form-control" >
						</div>
						
					</div>
						<div class="clearfix"></div>
					<hr>
					<div class="form-group row">
		                <div class="col-md-6 col-sm-6  offset-md-5">
						  <button class="btn btn-primary" type="reset"><i class="fa fa-rotate-left" ></i> Làm lại</button>
		                  <button type="submit" class="btn btn-success"><i class="fa fa-filter" ></i> Tìm kiếm</button>
		                </div>
	               </div>

				</div>
			</transition>
			<div class="x_panel">
              <div class="x_title">
                <h2>LOẠI HỢP ĐỒNG</h2>

                <div class="clearfix"></div>
              </div>
              <div class="x_content">

                <table class="table-bordered table table-striped bulk_action">
                  <thead>
                    <tr>
                      <th style="width: 50px" class="text-center">
						<div class="el-checkbox el-checkbox-blue">
							<input type="checkbox" name="check" id="2_1">
							<label class="el-checkbox-style" for="2_1"></label>
						</div>
                      </th>
                      <th style="width:100px" class="text-center">Mã NV 
                      	<span class="icon_sort"><i class="fa fa-sort-amount-desc" ></i></span>
                      </th>
                      <th>Tên NV</th>
                      <th>Email</th>
                      <th>Điện thoại</th>
                      <th class="text-center" style="width: 100px">Tình trạng</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <th scope="row" class="text-center">
						<div class="el-checkbox el-checkbox-blue">
							<input type="checkbox" name="check" id="2_2">
							<label class="el-checkbox-style" for="2_2"></label>
						</div>
                      </th>
                      <td class="text-center">00001</td>
                      <td>Mark</td>
                      <td>Otto</td>
                      <td>@mdo</td>
                      <td class="text-center">
                      	<button class="btn btn-sm btn-default" type="button" data-placement="top" data-toggle="tooltip" data-original-title="Đang hoạt động">
                      		<a class="text-success"><i class="fa fa-check"></i></a>
                      	</button>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row" class="text-center">
						<div class="el-checkbox el-checkbox-blue">
							<input type="checkbox" name="check" id="2_3">
							<label class="el-checkbox-style" for="2_3"></label>
						</div>
                      </th>
                      <td class="text-center">00002</td>
                      <td>Jacob</td>
                      <td>Thornton</td>
                      <td>@fat</td>
                      <td class="text-center">
                      	<button class="btn btn-sm btn-default" type="button" data-placement="top" data-toggle="tooltip" data-original-title="Đang hoạt động">
                      		<a class="text-success"><i class="fa fa-check"></i></a>
                      	</button>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row" class="text-center">
						<div class="el-checkbox el-checkbox-blue">
							<input type="checkbox" name="check" id="2_4">
							<label class="el-checkbox-style" for="2_4"></label>
						</div>
                      </th>
                      <td class="text-center">00003</td>
                      <td>Larry</td>
                      <td>the Bird</td>
                      <td>@twitter</td>
                      <td class="text-center">
                      	<button class="btn btn-sm btn-default" type="button" data-placement="top" data-toggle="tooltip" data-original-title="Tạm ngưng">
                      		<a class="text-danger"><i class="fa fa-close"></i></a>
                      	</button>
                      </td>
                    </tr>
                  </tbody>
                </table>

              </div>
              <div class="row">

              		<div class="col-sm-7">
              			<ul class="pagination">
						    <li class="page-item">
						      <a class="page-link" href="#" aria-label="Previous">
						        <span aria-hidden="true">&laquo;</span>
						        <span class="sr-only">Previous</span>
						      </a>
						    </li>
						    <li class="page-item"><a class="page-link" href="#">1</a></li>
						    <li class="page-item"><a class="page-link" href="#">2</a></li>
						    <li class="page-item"><a class="page-link" href="#">3</a></li>
						    <li class="page-item">
						      <a class="page-link" href="#" aria-label="Next">
						        <span aria-hidden="true">&raquo;</span>
						        <span class="sr-only">Next</span>
						      </a>
						    </li>
						</ul>
              		</div>
              		<div class="col-sm-5 text-right">
              			<div id="datatable_info" role="status" aria-live="polite">Xem 1 đến 10 trong 57 Loại hợp đồng 
							<i class="fa fa-ellipsis-v" ></i>
              				Xem <input class="input-showrows" type="text" name="" value="25"> dòng
              			</div>
              		</div>
                </div>
            </div>
		</div>
	</div>
	
</template>
<style>

</style>
<script>
	export default {
		data () {
	      return {
	      	isFilter: false
	      }
	    },
	    methods: {
	    	createdNew(){
	    		//this.$router.push({name: 'users.create'});
	    		//this.$Progress.start();
	    	}
	    }
	}
</script>

