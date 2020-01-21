<template>
	<div class="row" style="display: block;">
		<div class="col-md-12 col-sm-12" v-if="isLoadingPage">
			<nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li> 
                    <li class="breadcrumb-item"><a href="#">Dữ liệu nền</a></li> 
                    <li class="breadcrumb-item"><a href="#">Loại menu</a></li>
                    <li aria-current="page" class="breadcrumb-item active">Thêm danh sách</li>
                </ol>
			</nav>
            <div class="x_content" style="background: white;border: 1px solid #e6e9ed;padding-top: 13px;padding-left: 13px;padding-right: 13px;" >
				<a @click="Refresh" class="btn btn-defaults"> <i :class="{'fa fa-refresh': !isLoading,'fa fa-refresh fa-spin': isLoading}"></i> Làm Mới</a>
				<a class="btn btn-defaults" @click="createdNew" ><i class="fa fa-plus"></i> Thêm mới</a>
			</div>
                <div class="row" style="display: block;">
                    <div class="col-md-12" >
                        <div class="x_panel">
                        <div class="x_title">
                            <h2> <i class="fa fa-navicon" ></i> DANH SÁCH MENU</h2>
                            <div class="clearfix"></div>
                            
                        </div>
                        <div class="x_content">
                            <p style="text-transform: uppercase;">TÊN LOẠI: {{ TypeMenuName }}</p>
                            <vue-nestable v-model="nestableItems" @change="updateChangeMenu">
                                <vue-nestable-handle
                                slot-scope="{ item }"
                                :item="item"
                                >
                                <span style="display: inline-block;width: 180px;" >
                                    <i :class="item.Icon" ></i>
                                    &nbsp
                                    {{ item.MenuName}} 
                                    &nbsp
                                </span>
                                
                                <span v-for="(itemp, indexp) in item.Permission" :key="indexp">
                                    <a class="btn btn-default btn-sm btn-cusize btn-per notmaging" :title="itemp.ListName">
                                        <i :class="itemp.Icon"></i> </a>
                                </span>
                                <div class="pull-right group-but btn-group">
                                    <button type="button" @click="loadPermission(item.idMenu)" class="btn text-secondary btn-sm btn-cusize group-permision" ><i class="fa fa-archive" ></i> Quyền</button>
                                    <button type="button" @click="activeStatusOne(item.idMenu,item.Active)" :class="item.Active==1 ? 'btn text-success btn-sm btn-cusize group-permision': 'btn text-danger btn-sm btn-cusize group-permision'"> <i :class="item.Active==1 ? 'fa fa-check' : 'fa fa-close'" ></i> {{ item.Active==1 ? 'Hoạt động' : 'Tạm ngưng' }} </button>
                                    <button type="button" @click="update(item.idMenu,item.idTypeMenu)" class="btn text-info btn-sm btn-cusize group-permision"><i class="fa fa-pencil-square" ></i> Chỉnh sửa</button>
                                    <button type="button" @click="deleteMenu(item.idMenu)" class="btn text-danger btn-sm btn-cusize group-permision"><i class="fa fa-trash" ></i> Xóa</button>
                                </div>
                                </vue-nestable-handle>
                            </vue-nestable>
                        </div>
                     </div>
                    </div>
                    <!-- modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">{{ titleCom }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <component v-bind:is="isComponent" :ListCategory="ListCategory" @listenisSaveCheck="listenisSaveCheck" :arrMenu="arrMenu" ></component>
                            </div>
                        </div>
                    </div>
                    <!-- end modal  -->
                </div>
                <!-- start modal per  -->
                <div class="modal fade bd-example-modal-lg" id="myLargeModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">PHÂN QUYỀN</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body content-fix">
                                <div class="col-items" v-for="(Permission, index) in dataPermission" :key="index" >

                                    <p-check class="p-icon p-smooth" color="primary-o" v-model="dataPermission[index].checked" :checked="dataPermission[index].checked"  :id="index" @click="dataPermission[index].checked=!dataPermission[index].checked">
                                        <i class="icon fa fa-check" slot="extra"></i>
                                        {{ Permission.ListName }}
                                    </p-check>
                                </div>
                                
                            </div>
                            <div class="modal-footer">
                                
                                <button @click="uncheckPermission(isChecked =! isChecked)"  type="reset" class="btn btn-primary"><i class="fa fa-check"></i> Chọn tất cả</button>
                                <button @click="savePermission" :disabled="isDisable"  type="submit" class="btn btn-success">
                                    <i :class="{'fa fa-plus': !isSave,'fa fa-spinner fa-spin': isSave}"></i>
                                    {{isSave==true ? 'Đang xử lý' : 'Lưu lại'}}</button>
                            </div>
                        </div>
                    </div>
                </div>

                    <!-- end modal per  -->
			</div>
		</div>
	</div>
</template>

<script>
import { VueNestable, VueNestableHandle } from 'vue-nestable'
import appCreatemenu from './createmenu'
import appUpdatemenu from './updatemenu'
export default {
    data() {
        return {
            idTypeMenu: this.$route.params.id,
            nestableItems: [],
            ListCategory: [],
            TypeMenuName: '',
            isComponent: '',
            titleCom: '',
            idMenu: 0,
            arrMenu: {},
            dataPermission: [],
            isDisable: false,
            isSave: false,
            isChecked: false,
            isLoading: false,
            isLoadingPage: false,
            Permission: []
        }
    },
    components: {VueNestable,VueNestableHandle,appCreatemenu,appUpdatemenu},

    beforeRouteEnter(to, from, next) {
        try {
            next(self => {
                self.showIndex();	
            })
        } catch (err) {
            next(false)
        }
    },
    methods:{
        createdNew(){
            const self = this;
            self.isComponent = appCreatemenu;
            self.titleCom = 'THÊM MỚI';
			$('#exampleModal').modal('show');
        },
        listenisSaveCheck(data){
            const self = this;
            if (data == true) {
                self.showIndex();
            }
        },
        Refresh(){
            const self = this;
            self.isLoading = true;
            self.$Progress.start();
            self.showIndex();
        },
        update(idMenu,idTypeMenu){
            const self = this;
            self.isComponent = appUpdatemenu;
            self.titleCom = "CHỈNH SỬA";
            self.arrMenu = {
                'idMenu': idMenu,
                'idTypeMenu': idTypeMenu
            };
            $('#exampleModal').modal('show');
        },
        showIndex(){
            const self = this;
            axios.post("/bgdata/typemenusystem/listmenu",{idTypeMenu: self.idTypeMenu})
            .then(res => {
                if (res.data.Status == true) {
                    self.nestableItems = res.data.datas;
                    self.ListCategory = res.data.menuSelect;
                    self.TypeMenuName = res.data.TypeMenuName;
                    self.isLoading = false;
                    self.isLoadingPage = true;
                    self.$Progress.finish();
                    self.Permission = res.data.Permission;
					if (self.Permission.indexOf(9) ==-1) {self.$router.push({name: 'dashboard'});}
                }else if (res.data.Status == 2) {
                    window.location.href = 'admincp/login';
                }
            })
            .catch(err => {
                console.error(err); 
            })
        },
        deleteMenu(idMenu){
            const self = this;
            self.$swal({
                title: 'THÔNG BÁO?',
                text: "Bạn có muốn xác nhận thực hiện",
                icon: 'warning'
                
                }).then((result) => {
                if (result.value) {
                    self.$Progress.start();
                    axios.delete("/bgdata/typemenusystem/deleteMenu/"+idMenu)
                    .then(res => {
                        if (res.data.Status == true) {
                            self.showIndex();
                            self.$notify({
                                group: 'infomation',
                                type: 'success',
                                title: 'THÔNG BÁO',
                                text: 'Xóa thành công !'
                            });
                            
                        }else if (res.data.Status == false) {
                            self.$Progress.fail();
                            self.$notify({
                                group: 'infomation',type: 'error',title: 'LỖI',text: res.data.Massages
                            });
                        }else if (res.data.Status == 2) {
                            window.location.href = 'admincp/login';
                        }
                    }).catch(err => {
                        self.$Progress.fail();
                        self.$notify({
                            group: 'infomation',type: 'error',title: 'LỖI',text: 'Có lỗi trong quá trình !'
                        });
                    })
                }
            })
        },
        activeStatusOne(idMenu,Active){
            const self = this;
            self.$swal({
                title: 'THÔNG BÁO?',
                text: "Bạn có muốn xác nhận thực hiện",
                icon: 'warning'
                }).then((result) => {
                if (result.value) {
                    self.$Progress.start();
                    axios.post("/bgdata/typemenusystem/activeStatusOneMenu",{idMenu: idMenu,Active: Active})
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
                }
            })
        },
        updateChangeMenu(){
            const self = this;
            self.$Progress.start();
            axios.post("/bgdata/typemenusystem/UpdateOrderByMenu",{nestableItems: self.nestableItems})
            .then(res => {
                if (res.data.Status == true) {
                    self.showIndex();
                    self.$Progress.finish();
                }
                else if (res.data.Status == 2) {
                    window.location.href = 'admincp/login';
                }
            }).catch(err => {
                self.$Progress.finish();
                console.log(err);
            })
        },
        loadPermission(idMenu){
            const self = this;
            self.isChecked = false;
            self.idMenu = idMenu;
            axios.get("/bgdata/typemenusystem/"+idMenu+"/loadPermission")
            .then(res => {
                if (res.data.Status == true) {
                    self.dataPermission = res.data.dataPermission;
                }
                else if (res.data.Status == 2) {
                    window.location.href = 'admincp/login';
                }
            })
            .catch(err => {
                console.error(err); 
            })
            $('#myLargeModal').modal('show');
        },
        uncheckPermission(type){ // chọn quyền
            const self = this;
            if (self.dataPermission.length > 0) {
                for (let i = 0; i < self.dataPermission.length; i++) {
                    self.dataPermission[i].checked = type;
                }
            }
        },
        savePermission(){
            const self = this;
            self.isSave = true;
            self.isDisable = true;
            let Permission = [];
            if (self.dataPermission.length > 0) {
                for (let i = 0; i < self.dataPermission.length; i++) {
                    if (self.dataPermission[i].checked == true) {
                        Permission.push(self.dataPermission[i].idList);
                    }
                }
            }
            self.$Progress.start();
            axios.put("/bgdata/typemenusystem/"+self.idMenu+"/savePermission",{Permission: Permission})
            .then(res => {
                if (res.data.Status == true) {
                    self.isSave = false;
                    self.isDisable = false;
                    self.showIndex();
                    self.$notify({
                        group: 'infomation',
                        type: 'success',
                        title: 'THÔNG BÁO',
                        text: 'Cập nhật thành công !'
                    });
                    self.$Progress.finish();
                    $('#myLargeModal').modal('hide');
                }else if (res.data.Status == 2) {
                    window.location.href = 'admincp/login';
                }
                
            }).catch(err => {
                self.$Progress.finish();
                console.log(err);
            })
        }
    },
    
}
</script>