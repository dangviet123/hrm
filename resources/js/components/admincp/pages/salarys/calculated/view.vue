<template>
    <div class="modal fade bd-example-modal-lg" id="myLargeModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <br>
                <div class="modal-body content-fix">
                    <div id="printObj">

                        <div class="col-md-12 col-xs-12">
                            <table class="table table-bordered tablethpading">
                                <!-- <tr>
                                    <th colspan="3" style="text-align: center;">{{ dataGroup.CompanyName }}</th>
                                    
                                </tr> -->
                                <tr>
                                    <th style="width:150px">Mã Nhân Viên</th>
                                    <td>{{ dataGroup.IDCodeUser }}</td>
                                    <td rowspan="4" style="width:250px;vertical-align: top !important;" class="text-center">
                                        <h5 class="modal-title" id="exampleModalLongTitle">PHIẾU LƯƠNG</h5>
                                        <strong>Số phiếu:</strong> {{ dataGroup.IDCode }}<br>
                                        Tháng {{ dataGroup.Month }} năm {{ dataGroup.Year }}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width:150px">Tên Nhân Viên</th>
                                    <td>{{ dataGroup.FullName }}</td>
                                </tr>
                                <tr>
                                    <th style="width:150px">Email</th>
                                    <td>{{ dataGroup.Email }}</td>
                                </tr>
                                <tr>
                                    <th style="width:150px">Ngày Công</th>
                                    <td>{{ dataGroup.NumberWorkdays }}</td>
                                    
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6 col-xs-6">
                            <table class="table table-bordered tablethpading">
                                <tr>
                                    <th colspan="2">CÁC KHOẢN THU NHẬP (A)</th>
                                </tr>
                                <tr v-for="(SalaryDataA, index) in dataGroup.SalaryDataA" :key="index">
                                    <th>{{ SalaryDataA.RowName }}</th>
                                    <td>
                                        <vue-numeric read-only style="border: initial;" v-model="SalaryDataA.RowValue" v-bind:precision="0" currency="" separator=","  class="form-control"></vue-numeric>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tổng Cộng</th>
                                    <th>
                                        <vue-numeric read-only style="border: initial;" v-model="dataGroup.TotalSalaryDataA" v-bind:precision="0" currency="" separator=","  class="form-control"></vue-numeric>
                                    </th>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6 col-xs-6">
                            <table class="table table-bordered tablethpading">
                                <tr>
                                    <th colspan="2">CÁC KHOẢN PHẢI NỘP (B)</th>
                                </tr>
                                <tr v-for="(SalaryDataB, index) in dataGroup.SalaryDataB" :key="index">
                                    <th>{{ SalaryDataB.RowName }}</th>
                                    <td>
                                        <vue-numeric read-only style="border: initial;" v-model="SalaryDataB.RowValue" v-bind:precision="0" currency="" separator=","  class="form-control"></vue-numeric>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tổng Trừ</th>
                                    <th>
                                        <vue-numeric read-only style="border: initial;" v-model="dataGroup.TotalSalaryDataB" v-bind:precision="0" currency="" separator=","  class="form-control"></vue-numeric>
                                    </th>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <table class="table table-bordered tablethpading">
                                <tr>
                                    <th>Thành Tiền (A-B)</th>
                                    <th>
                                        <vue-numeric read-only style="border: initial;" v-model="dataGroup.TotalSalaryDataAVG" v-bind:precision="0" currency="" separator=","  class="form-control"></vue-numeric>
                                    </th>
                                </tr>
                                <tr>
                                    <th>Bằng Chữ</th>
                                    <th style="text-transform: capitalize;">
                                        {{dataGroup.TotalSalaryWords}}
                                    </th>
                                </tr>
                                
                            </table>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-secondary" @click="print"  title="In"><i class="fa fa-print" ></i></button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fa fa-close" ></i>
                            Đóng</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VueNumeric from 'vue-numeric';
export default {
    props: ['idCalculated'],
	watch: { 
		idCalculated(newVal, oldVal) { // watch it
			this.getInfo(newVal);
		}
    },
    data() {
        return {
            dataGroup: {
                TotalSalaryDataA: 0,
                TotalSalaryDataB: 0,
                TotalSalaryDataAVG: 0,
                NumberWorkdays: 0,
                SalaryDataA: [],
                SalaryDataB: [],
            },
            
        }
    },
    components: {VueNumeric},
    created(){
        if (this.idCalculated > 0) {
            this.getInfo(this.idCalculated);
        }
    },
    methods: {
        print() {
        // Pass the element id here
        this.$htmlToPaper('printObj');
        },
        getInfo(idCalculated){
            const self =this;
            axios.post("/salarys/calculated/"+idCalculated+"/viewPrint")
            .then(res => {
                if (res.data.Status == true) {
                    self.dataGroup = res.data.DataDetail;

                }else if (res.data.Status == 2) {
                    window.location.href = 'admincp/login';
                }
            })
            .catch(err => {
                console.error(err); 
            })
        }
        
    }
}
</script>