<template>
	
	<div>
		<div class="col-md-12 col-sm-12" >
				<div class="x_panel">
				
	              <div class="x_content">
	                <div class="col-md-12">
						<div class="formform-group" :class="{ 'form-group--error':$v.TypeMenu.TypeMenuName.$error }">
							<label for="TypeMenuName">Tên loại menu <span class="text-danger" >*</span></label> 
							<input 
								type="text" 
								id="TypeMenuName" 
								v-model.trim="$v.TypeMenu.TypeMenuName.$model" 
								class="form-control" 
								:class="{'border border-danger': $v.TypeMenu.TypeMenuName.$error}"
								:disabled="isDisable"
								>
							<span v-if="!$v.TypeMenu.TypeMenuName.required" class="text-danger error">Trường không được để trống</span>
							<span v-if="!$v.TypeMenu.TypeMenuName.minLength" class="text-danger error">Vui lòng nhập tối thiểu {{$v.TypeMenu.TypeMenuName.$params.minLength.min}} kí tự</span>
							<span v-if="!$v.TypeMenu.TypeMenuName.maxLength" class="text-danger error">Vui lòng nhập tối da {{$v.TypeMenu.TypeMenuName.$params.maxLength.max}} kí tự</span>
						</div>
	                	
	                </div>
	                <div class="col-md-12">
						<div class="formform-group" >
							<label for="Description">Mô tả</label> 
							<textarea :disabled="isDisable"  cols="30" rows="4" id="Description" v-model="TypeMenu.Description" class="form-control"></textarea>
						</div>
	                </div>
					<div class="col-md-12">
						<div class="formform-group" >
							<label for="OrderBy">Thứ tự</label> 
							<input :disabled="isDisable" type="text" id="OrderBy" v-model="TypeMenu.OrderBy" class="form-control">
						</div>
	                </div>
					<div class="col-md-12">
						<div class="formform-group" >
							<p-check :disabled="isDisable" class="p-switch p-fill" color="success_gren" :checked="TypeMenu.Active" v-model="TypeMenu.Active" >
								{{ TypeMenu.Active==1 ? 'Hoạt động': 'Tạm ngưng' }}
							</p-check>
						</div>
					</div>
					
	              <div class="clearfix"></div>
				  <hr>
	                <div class="form-group row">
		              	<div class="col-md-12 col-sm-12">
		              		<button @click="ResetForm" :disabled="isDisable" type="reset" class="btn btn-primary"><i class="fa fa-rotate-left"></i> Làm lại</button> 
		              		<button @click="UpdateForm" :disabled="isDisable" type="submit" class="btn btn-success">
								<i :class="{'fa fa-plus': !isSave,'fa fa-spinner fa-spin': isSave}"></i>
								{{isSave==true ? 'Đang xử lý' : 'Lưu lại'}}</button>
		              	</div>
	              	</div>
	            </div>
			</div>
		</div>
	</div>
</template>
<script>
import { required, minLength, between,maxLength } from 'vuelidate/lib/validators'
export default {
	props: ['idTypeMenu'],
	watch: { 
		idTypeMenu(newVal, oldVal) { // watch it
			this.getInfo(newVal);
		}
	},
	data() {
		return {
			isDisable: false,
			isSave: false,
			TypeMenu: {
				TypeMenuName: '',
				Description: '',
				OrderBy: 0,
				Active: false
			}
		}
	},
	validations: {
		TypeMenu: {
			TypeMenuName: {
				required,
				minLength: minLength(4),
				maxLength: maxLength(225)
			}
		}
	},
	created(){
		const self =this;
		self.getInfo(self.idTypeMenu);
	},
	methods: {
		ResetForm(){
			this.TypeMenu = {
				TypeMenuName: '',
				Description: '',
				OrderBy: 0,
				Active: false
			};
		},
		getInfo(idTypeMenu){ // lấy thông tin
			const self =this;
			self.$Progress.start();
			axios.get("/bgdata/typemenusystem/"+idTypeMenu+"/edit")
			.then(res => {

				if (res.data.Status == true) {
					self.TypeMenu =res.data.datas;
					self.$Progress.finish();
				}else if (res.data.Status == 2) {
					window.location.href = 'admincp/login';
				}
			})
			.catch(err => {
				console.error(err); 
			})
		},
		UpdateForm(){
			const self =this;
			this.$v.$touch();
			if (!this.$v.$invalid) {
				self.isDisable = true;
				self.isSave = true;
				this.$Progress.start();
				axios.put("/bgdata/typemenusystem/update",{TypeMenu: self.TypeMenu})
				.then(res => {
					if (res.data.Status == true) {
						this.$Progress.finish();
						self.isDisable = false;
						self.isSave = false;
						self.listenisSaveCheck();
						this.$notify({
						  group: 'infomation',
						  type: 'success',
						  title: 'THÔNG BÁO',
						  text: 'Chỉnh sửa thành công !'
						});
						$('#exampleModal').modal('hide');
					}else if (res.data.Status == 2) {
						window.location.href = 'admincp/login';
					}
					else{
						this.$Progress.fail();
						self.isDisable = false;
						self.isSave = false;
						this.$notify({
							group: 'infomation',
							type: 'error',
							title: 'LỖI',
							text: 'Có lỗi trong quá trình !'
						});
					}
				})
				.catch(err => {
					this.$Progress.fail();
					self.isDisable = false;
					self.isSave = false;
					this.$notify({
						group: 'infomation',
						type: 'error',
						title: 'LỖI',
						text: 'Có lỗi trong quá trình !'
					});
					
				})
			} else {
				this.$notify({
				  group: 'infomation',
				  type: 'error',
				  title: 'LỖI',
				  text: 'Vui Lòng Nhập Đầy Đủ Thông Tin !'
				});
			}
		},
		listenisSaveCheck(){
			this.$emit('listenisSaveCheck',true);	
		}
    }
}
</script>
