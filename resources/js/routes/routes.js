import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)


import dashboard from '../components/admincp/pages/dashboard/index.vue';
import IndexUsers from '../components/admincp/pages/infomationuser/users/index.vue';
import CreateUsers from '../components/admincp/pages/infomationuser/users/create.vue';
import UpdateUsers from '../components/admincp/pages/infomationuser/users/edit.vue';

import IndexAchievements from '../components/admincp/pages/infomationuser/achievements/index.vue';
import CreateAchievements from '../components/admincp/pages/infomationuser/achievements/create.vue';
import UpdateAchievements from '../components/admincp/pages/infomationuser/achievements/edit.vue';

import IndexPromote from '../components/admincp/pages/infomationuser/promote/index.vue';
import CreatePromote from '../components/admincp/pages/infomationuser/promote/create.vue';
import UpdatePromote from '../components/admincp/pages/infomationuser/promote/edit.vue';


import IndextypeMenusystem from '../components/admincp/pages/bgdata/typemenusystem/index.vue';
import ListmenuypeMenusystem from '../components/admincp/pages/bgdata/typemenusystem/listmenu.vue';

import IndexListpermission from '../components/admincp/pages/bgdata/listpermission/index.vue';



import IndexGrouppermission from '../components/admincp/pages/bgdata/grouppermission/index.vue';
import PermissionGrouppermission from '../components/admincp/pages/bgdata/grouppermission/permission.vue';

import IndexProvinces from '../components/admincp/pages/bgdata/provinces/index.vue'
import IndexDistrict from '../components/admincp/pages/bgdata/district/index.vue'
import IndexWard from '../components/admincp/pages/bgdata/ward/index.vue'

import IndexDepartments from '../components/admincp/pages/infomationuser/departments/index.vue';

import IndexPositions from '../components/admincp/pages/infomationuser/positions/index.vue';

import IndexLevels from '../components/admincp/pages/infomationuser/levels/index.vue';

import IndexCompanys from '../components/admincp/pages/infomationuser/companys/index.vue';
import IndexTypeofwork from '../components/admincp/pages/infomationuser/typeofwork/index.vue';


import IndexUserscontacts from '../components/admincp/pages/laborcontract/userscontacts/index.vue';

import IndexTypecontacts from '../components/admincp/pages/laborcontract/typecontacts/index.vue';

import IndexLogin from '../components/login/login.vue';




import IndexCalculated from '../components/admincp/pages/salarys/calculated/index.vue';
import CreateCalculated from '../components/admincp/pages/salarys/calculated/create.vue';
import UpdateCalculated from '../components/admincp/pages/salarys/calculated/edit.vue';



export const routes = [
    { path: '/', name: 'dashboard', component: dashboard },
    { path: '/login', name: 'login', component: IndexLogin },
    { path: '/home', name: 'dashboard1', component: dashboard },
    { path: '/staff-information/users/', name: 'users.index', component: IndexUsers },
    { path: '/staff-information/users/create', name: 'users.create', component: CreateUsers },
    { path: '/staff-information/users/:idUser/edit', name: 'users.edit', component: UpdateUsers },

    { path: '/staff-information/achievements/', name: 'achievements.index', component: IndexAchievements },
    { path: '/staff-information/achievements/create', name: 'achievements.create', component: CreateAchievements },
    { path: '/staff-information/achievements/:idAchievements/edit', name: 'achievements.edit', component: UpdateAchievements },

    { path: '/staff-information/promote/', name: 'promote.index', component: IndexPromote },
    { path: '/staff-information/promote/create', name: 'promote.create', component: CreatePromote },
    { path: '/staff-information/promote/:idPromote/edit', name: 'promote.edit', component: UpdatePromote },

    { path: '/bgdata/typemenusystem/', name: 'typemenusystem.index', component: IndextypeMenusystem },
    { path: '/bgdata/typemenusystem/:id/listmenu', name: 'typemenusystem.listmenu', component: ListmenuypeMenusystem },
    { path: '/bgdata/listpermission/', name: 'listpermission.index', component: IndexListpermission },

    { path: '/bgdata/grouppermission/', name: 'grouppermission.index', component: IndexGrouppermission },
    { path: '/bgdata/grouppermission/:id/permission', name: 'grouppermission.permission', component: PermissionGrouppermission },
    { path: '/bgdata/provinces', name: 'provinces.index', component: IndexProvinces },
    { path: '/bgdata/district', name: 'district.index', component: IndexDistrict },
    { path: '/bgdata/ward', name: 'ward.index', component: IndexWard },

    { path: '/staff-information/departments/', name: 'departments.index', component: IndexDepartments },
    { path: '/staff-information/positions/', name: 'positions.index', component: IndexPositions },
    { path: '/staff-information/levels/', name: 'levels.index', component: IndexLevels },
    { path: '/staff-information/companys/', name: 'companys.index', component: IndexCompanys },
    { path: '/staff-information/typeofwork/', name: 'typeofwork.index', component: IndexTypeofwork },
    { path: '/laborcontract/userscontacts/', name: 'userscontacts.index', component: IndexUserscontacts },
    { path: '/laborcontract/typecontacts/', name: 'typecontacts.index', component: IndexTypecontacts },

    { path: '/salarys/calculated/', name: 'calculated.index', component: IndexCalculated },
    { path: '/salarys/calculated/create', name: 'calculated.create', component: CreateCalculated },
    { path: '/salarys/calculated/:idCalculated/edit', name: 'calculated.edit', component: UpdateCalculated },

];
const router = new VueRouter({
    base: 'hrm/public/admincp',
    mode: 'history',
    routes,
    scrollBehavior(to, from, savedPosition) {
        return { x: 0, y: 0 }
    },
    meta: { transition: 'fade' }
})

export default router