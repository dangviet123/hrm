<template>
    <div class="row" style="display: block;">
		<div class="col-md-12 col-sm-12" v-if="isLoadingPage">
			<nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li> 
                    <li class="breadcrumb-item"><a href="#">Dữ liệu nền</a></li> 
                    <li class="breadcrumb-item"><a href="#">Nhóm quyền</a></li>
                    <li aria-current="page" class="breadcrumb-item active">Phân quyền</li>
                </ol>
			</nav>
            <div class="x_content" style="background: white;border: 1px solid #e6e9ed;padding-top: 13px;padding-left: 13px;padding-right: 13px;" >
				<a @click="Refresh" class="btn btn-defaults"> <i :class="{'fa fa-refresh': !isLoading,'fa fa-refresh fa-spin': isLoading}"></i> Làm Mới</a>
			</div>
                <div class="row" style="display: block;">
                    <div class="col-md-12" >
                        <div class="x_panel">
                            <div class="x_title">
                                <h2> <i class="fa fa-navicon" ></i> PHÂN QUYỀN</h2>
                                <div class="clearfix"></div>
                                
                            </div>
                            <div class="x_content">
                                <p style="text-transform: uppercase;">NHÓM QUYỀN: {{ GroupName }}</p>
                                <vue-nestable v-model="nestableItems"  >
                                    <span slot-scope="{item}"
                                        :item="item"
                                        :max-depth="2">
                                        
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
                                        <div class="pull-right group-but">
                                            <p-check class="p-switch" color="info" :title="item.Active==1 ? 'Hoạt động': 'Tạm ngưng'" :checked="item.Active" @change="activePermission(item.idMenu,item.idGroup,item.Active =! item.Active)">&nbsp </p-check>
                                            <button type="button" @click="loadPermission(item.idMenu,$route.params.id)" class="btn text-secondary btn-sm btn-cusize group-permision notmaging" ><i class="fa fa-archive" ></i> Quyền</button>
                                        </div>
                                    </span>
                                </vue-nestable>
                            </div>
                        </div>
                    </div>
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
export default {
    data() {
        return {
            idGroup: this.$route.params.id,
            idMenu: 0,
            nestableItems: [],
            dataPermission: [],
            isDisable: false,
            isSave: false,
            GroupName: '',
            isChecked: false,
            isLoading: false,
            isLoadingPage: false,
            Permission: []
        }
    },
    beforeRouteEnter(to, from, next) {
        try {
            next(self => {
                self.showIndex();	
            })
        } catch (err) {
            next(false)
        }
    },
    components: {VueNestable,VueNestableHandle},
    methods: {
        showIndex(){
            const self = this;
            self.$Progress.start();
            axios.get("/bgdata/grouppermission/"+self.idGroup+"/permission")
            .then(res => {
                if (res.data.Status == true) {
                    self.nestableItems = res.data.datas;
                    self.GroupName = res.data.GroupName;
                    self.isLoading = false;
                    self.isLoadingPage = true;
                    self.$Progress.finish();
                    self.Permission = res.data.Permission;
					if (self.Permission.indexOf(10) ==-1) {self.$router.push({name: 'dashboard'});}
                }
            })
            .catch(err => {
                console.error(err); 
            })
        },
        loadPermission(idMenu,idGroup){
            const self = this;
            self.isChecked = false;
            self.idMenu = idMenu;
            axios.get("/bgdata/grouppermission/"+idMenu+"/"+idGroup+"/loadPermission")
            .then(res => {
                self.dataPermission = res.data.dataPermission;
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
            axios.put("/bgdata/grouppermission/"+self.idMenu+"/"+self.idGroup+"/savePermission",{Permission: Permission})
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
                }
                
            }).catch(err => {
                self.$Progress.finish();
                console.log(err);
            })
        },
        activePermission(idMenu,idGroup,Active){
            const self = this;
            self.$Progress.start();
            axios.put("/bgdata/grouppermission/"+idMenu+"/"+idGroup+"/activePermission",{Active: Active})
            .then(res => {
                if (res.data.Status == true) {
                    self.showIndex();
                    self.$Progress.finish();
                }
                
            }).catch(err => {
                self.$Progress.finish();
                console.log(err);
            })
        },
        Refresh(){
            const self = this;
            self.isLoading = true;
            self.showIndex();
        }
    }
}
</script>