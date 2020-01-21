<template>
    <div>
        <div class="col-md-12">
            <div class="formform-group" :class="{ 'form-group--error':$v.listMenu.MenuName.$error }">
                <label>Tên danh mục<span class="text-danger" >*</span></label> 
                <input type="text" 
                :disabled="isDisable" 
                v-model="$v.listMenu.MenuName.$model"
                :class="{'border border-danger': $v.listMenu.MenuName.$error}" 
                class="form-control" />
                <span v-if="!$v.listMenu.MenuName.required" class="text-danger error">Trường không được để trống</span>
                <span v-if="!$v.listMenu.MenuName.minLength" class="text-danger error">Vui lòng nhập tối thiểu {{$v.listMenu.MenuName.$params.minLength.min}} kí tự</span>
                <span v-if="!$v.listMenu.MenuName.maxLength" class="text-danger error">Vui lòng nhập tối da {{$v.listMenu.MenuName.$params.maxLength.max}} kí tự</span>
            </div>
        </div>
        <div class="col-md-12">
            <div class="formform-group" :class="{ 'form-group--error':$v.listMenu.Slug.$error }">
                <label>Slug<span class="text-danger" >*</span></label> 
                <input type="text" 
                :disabled="isDisable" 
                v-model="$v.listMenu.Slug.$model" 
                @input="uniqueSlug=false"
                :class="[{'border border-danger': $v.listMenu.Slug.$error},{'border border-danger':uniqueSlug}]"  
                class="form-control" />
                <span v-if="!$v.listMenu.Slug.required" class="text-danger error">Trường không được để trống</span>
                <span v-if="!$v.listMenu.Slug.isUnique" class="text-danger error">Trường đã tồn tại trên hệ thống</span>
                <span v-show="uniqueSlug" class="text-danger">Trường đã tồn tại trên hệ thống</span>
                <span v-if="!$v.listMenu.Slug.minLength" class="text-danger error">Vui lòng nhập tối thiểu {{$v.listMenu.Slug.$params.minLength.min}} kí tự</span>
                <span v-if="!$v.listMenu.Slug.maxLength" class="text-danger error">Vui lòng nhập tối da {{$v.listMenu.Slug.$params.maxLength.max}} kí tự</span>
            </div>
        </div>
        

        <div class="col-md-12">
            <div class="formform-group" >
                <label for="Link">Link truy cập</label> 
                <input type="text" :disabled="isDisable" v-model="listMenu.Link"  id="Link" class="form-control" />
            </div>
        </div>
        <div class="col-md-12">
            <div class="formform-group" >
                <label for="idMenu">Danh mục cha</label> 
                <v-select :disabled="isDisable" :options="ListCategory" label="MenuName" :reduce="tag => tag.idMenu" v-model="listMenu.id_parent"  ></v-select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="formform-group" >
                <label for="Icon">Biểu tượng</label> 
                <input type="text" :disabled="isDisable" v-model="listMenu.Icon"  id="Icon"  class="form-control" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="formform-group" >
                <label for="Link">Target</label> 
                <input type="text" :disabled="isDisable" v-model="listMenu.Target" class="form-control" />
            </div>
        </div>
        <div class="col-md-12">
            <div class="formform-group" >
                <label for="Description">Mô tả</label> 
                <textarea :disabled="isDisable"  cols="30" rows="3" id="Description" v-model="listMenu.Description"   class="form-control"></textarea>
            </div>
        </div> 
        <div class="col-md-12">
            <div class="formform-group" >
                <p-check :disabled="isDisable" class="p-switch p-fill" color="success_gren" :checked="listMenu.Active" v-model="listMenu.Active" >
                    {{ listMenu.Active==1 ? 'Hoạt động': 'Tạm ngưng' }}
                </p-check>
            </div>
        </div>
        <div class="clearfix"></div>
        <hr>
        <div class="form-group row">
            <div class="col-md-12 col-sm-12">
                <button  :disabled="isDisable"  type="reset" class="btn btn-primary"><i class="fa fa-rotate-left"></i> Làm lại</button> 
                <button :disabled="isDisable" @click="createdMenu" type="submit" class="btn btn-success">
                    <i :class="{'fa fa-plus': !isSave,'fa fa-spinner fa-spin': isSave}"></i>
                    {{isSave==true ? 'Đang xử lý' : 'Lưu lại'}}</button>
            </div>
        </div>
    </div>
</template>

<script>
import { required, minLength, between,maxLength } from 'vuelidate/lib/validators'
export default {
    props:["ListCategory"],
    data() {
        return {
            idTypeMenu: this.$route.params.id,
            isDisable: false,
            isSave: false,
            uniqueSlug: false,
            listMenu: {
                idTypeMenu: this.$route.params.id,
                id_parent: 0,
                MenuName: '',
                Description: '',
                Link: '',
                Slug: '',
                Icon: 'fa fa-angle-right',
                Target: '_self',
                Active: false
            },
            nestableItems: []
        }
    },
    validations: {
		listMenu: {
			MenuName: {
				required,
				minLength: minLength(4),
				maxLength: maxLength(225)
            },
            Slug: {
                required,
                isUnique(value){
                    if (value == '') return true
                    return new Promise((resolve, reject) => {
                        axios.post("/bgdata/typemenusystem/checkSlug",{Slug: value,idTypeMenu: this.listMenu.idTypeMenu,id_parent: this.listMenu.id_parent})
                        .then(res => {
                            resolve(res.data.Status);
                        })
                    });
                },
				minLength: minLength(3),
				maxLength: maxLength(225)
			}
		}
    },
    methods: {
        createdMenu(){
            const self = this;
            self.$v.$touch();
            self.$v.listMenu.Slug;
            if (!self.$v.$invalid) {
                self.isDisable = true;
				self.isSave = true;
                this.$Progress.start();
                axios.post("/bgdata/typemenusystem/createdMenu",{listMenu: self.listMenu})
				.then(res => {
					if (res.data.Status == true) {
						this.$Progress.finish();
						self.isDisable = false;
                        self.isSave = false;
                        this.$emit('listenisSaveCheck',true);
                        self.listMenu = {idTypeMenu: this.$route.params.id,id_parent: 0, MenuName: '',Description: '',Link: '',Slug: '',Icon: 'fa fa-angle-right',Target: '_self',Active: false};
                        this.$v.$reset();
						this.$notify({
						  group: 'infomation',
						  type: 'success',
						  title: 'THÔNG BÁO',
						  text: 'Thêm Mới Thành Công !'
                        });
                        $('#exampleModal').modal('hide');
                    }else if (res.data.Status == 2) {
                        window.location.href = 'admincp/login';
                    }
                    else if(res.data.Status == false){
                        this.$Progress.finish();
                        self.isDisable = false;
                        self.isSave = false;
                        self.uniqueSlug = true;
                        this.$notify({
                            group: 'infomation',
                            type: 'warn',
                            title: 'CHÚ Ý',
                            text: res.data.Massages
                        });
                    }else{
                        this.$Progress.finish();
                        self.isDisable = false;
                        self.isSave = false;
                    }
				})
				.catch(err => {
					this.$Progress.finish();
					self.isDisable = false;
					self.isSave = false;
					this.$notify({
						group: 'infomation',
						type: 'error',
						title: 'LỖI',
						text: 'Có lỗi trong quá trình !'
					});
					
				})
            }else{
                this.$notify({
				  group: 'infomation',
				  type: 'warn',
				  title: 'CHÚ Ý',
				  text: 'Vui Lòng kiểm tra lại thông tin !'
				});
            }
        }
    } 
}
</script>