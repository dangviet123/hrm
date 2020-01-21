<template>
	<div class="col-md-3 left_col">
      <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
          <!-- <a href="./admincp" class="site_title"><i class="fa fa-users"></i> <span>AX</span></a> -->
        </div>

        <div class="clearfix"></div>

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
          <div class="menu_section">
            <ul class="nav side-menu">
              <li v-for="(dataMenu, index) in dataMenus" :key="index" :class="((routePath.length > 0 && routePath[1] == dataMenu.Slug) || dataMenu.Display==1) ? 'active' : ''" >
                <router-link   v-if="dataMenu.children.length==0"  @click="showMenuChild(index)" :to="'/'+dataMenu.Slug"><i :class="dataMenu.Icon"></i>{{ dataMenu.MenuName }}</router-link>
                <a v-if="dataMenu.children.length>0" @click="showMenuChild(index)" ><i :class="dataMenu.Icon"></i>{{ dataMenu.MenuName }}
                  <span :class="((routePath.length > 0 && routePath[1] == dataMenu.Slug) || dataMenu.Display==1) ? 'fa fa-angle-down' : 'fa fa-angle-right'"></span>
                </a>
                <ul class="nav child_menu" v-if="dataMenu.children.length>0" :style="(dataMenu.Display==1 || (routePath.length > 0 && routePath[1] == dataMenu.Slug)) ? 'display:block': ''">
                  <li  v-for="(children, index) in dataMenu.children" :key="index" :class="routePath.length > 0 && routePath[2] == children.Slug ? 'active': ''">
                    <router-link :to="'/'+dataMenu.Slug+'/'+children.Slug" > <i :class="children.Icon"></i> {{ children.MenuName }}</router-link>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
</template>
<script>
export default {
  data() {
    return {
      dataMenus: [],
      routePath: (this.$route.path) ? this.$route.path.split('/') : [],
      SSFullName: '',
      SSImage: ''
    }
  },
  watch: {
    '$route' (to, from) {
      this.routePath = (this.$route.path) ? this.$route.path.split('/') : [];
    }
  },
  created(){
    const self = this;
    self.showIndex();
  },
  methods: {
    showIndex(){
      const self = this;
      axios.get("/home/getMenuLeft")
      .then(res => {
        if (res.data.Status == true) {
          self.dataMenus = res.data.datas;
          self.SSFullName = res.data.SSFullName;
          self.SSImage = res.data.SSImage;
        }
      })
      .catch(err => {
        console.error(err); 
      })
    },
    showMenuChild(index){
      const self = this;
      self.routePath = [];
      for (let i = 0; i < self.dataMenus.length; i++) {
        if (self.dataMenus[i] != self.dataMenus[index]) {
          self.dataMenus[i].Display = 0;
        }
      }
      self.dataMenus[index].Display =  self.dataMenus[index].Display == 0 ? 1:0;
    }
  }
}
</script>