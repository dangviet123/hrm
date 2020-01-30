<template>
	<div class="top_nav">
      <div class="nav_menu">
          <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-reorder" style="font-size: 20px;"></i></a>
          </div>
          <nav class="nav navbar-nav">
          <ul class=" navbar-right">
            <li class="nav-item dropdown open" style="padding-left: 15px;">
              <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                <img :src="'images/users/'+session_user.Image" alt=""> {{ session_user.FullName }}
              </a>
              <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item"  href="javascript:;"> Thông tin</a>
                <a class="dropdown-item" @click="checkLogout"><i class="fa fa-sign-out pull-right"></i> Đăng xuất</a>
              </div>
            </li>

            <!-- <li role="presentation" class="nav-item dropdown open">
              <a href="javascript:;" class="dropdown-toggle info-number" id="navbarDropdown1" data-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-envelope-o"></i>
                <span class="badge bg-green">6</span>
              </a>
              <ul class="dropdown-menu list-unstyled msg_list" role="menu" aria-labelledby="navbarDropdown1">
                <li class="nav-item">
                  <a class="dropdown-item">
                    <span class="image"><img src="public/images/img.jpg" alt="Profile Image" /></span>
                    <span>
                      <span>John Smith</span>
                      <span class="time">3 mins ago</span>
                    </span>
                    <span class="message">
                      Film festivals used to be do-or-die moments for movie makers. They were where...
                    </span>
                  </a>
                </li>
                
                <li class="nav-item">
                  <a class="dropdown-item">
                    <span class="image"><img src="public/images/img.jpg" alt="Profile Image" /></span>
                    <span>
                      <span>John Smith</span>
                      <span class="time">3 mins ago</span>
                    </span>
                    <span class="message">
                      Film festivals used to be do-or-die moments for movie makers. They were where...
                    </span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="dropdown-item">
                    <span class="image"><img src="public/images/img.jpg" alt="Profile Image" /></span>
                    <span>
                      <span>John Smith</span>
                      <span class="time">3 mins ago</span>
                    </span>
                    <span class="message">
                      Film festivals used to be do-or-die moments for movie makers. They were where...
                    </span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="dropdown-item">
                    <span class="image"><img src="public/images/img.jpg" alt="Profile Image" /></span>
                    <span>
                      <span>John Smith</span>
                      <span class="time">3 mins ago</span>
                    </span>
                    <span class="message">
                      Film festivals used to be do-or-die moments for movie makers. They were where...
                    </span>
                  </a>
                </li>
                <li class="nav-item">
                  <div class="text-center">
                    <a class="dropdown-item">
                      <strong>See All Alerts</strong>
                      <i class="fa fa-angle-right"></i>
                    </a>
                  </div>
                </li>
              </ul>
            </li> -->

          </ul>
        </nav>
      </div>
    </div>
</template>

<script>
export default {
  data() {
    return {
      session_user: {
        Image: 'no-ava.png'
      }
    }
  },
  created(){
    this.showIndex();
  },
  methods: {
    checkLogout(){
      axios.post("/home/checkLogout")
      .then(res => {
        window.location.href='admincp/login';
      })
      .catch(err => {
        console.error(err); 
      })
    },
    showIndex(){
      const self = this;
      axios.get("/home/getHeader")
      .then(res => {
        if (res.data.Status == true) {
          self.session_user = res.data.session_user;
        }
      })
      .catch(err => {
        console.error(err); 
      })
    }
  }
}
</script>
