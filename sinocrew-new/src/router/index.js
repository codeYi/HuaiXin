import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      component: resolve => require(['../components/login/login.vue'], resolve)
    },
    {
      path:"/login",
      component: resolve => require(['../components/login/login.vue'], resolve)
    },
    {
      path:"/forgetPassword",
      component:resolve => require(['../components/login/forgetPassword.vue'], resolve)
    },
    {
      path:"/common",
      component:resolve => require(['../components/common/common.vue'], resolve),
      children:[
        // 首页
        {
          path:"/homepage",
          component:resolve=> require(['../components/homepage/homepage.vue'], resolve),
        },

        // 船员
        {
          path:"/boaters",
          component:resolve=> require(['../components/boaters/boatersList.vue'], resolve),
        },{
          path:"/boaters/info/:id",
          component:resolve=> require(['../components/boaters/boaterDetails.vue'], resolve),
        },
        {
          path:"/settings",
          component:resolve=> require(['../components/mysettings/settings.vue'], resolve),
        },
        {
          path:"/shipowners",
          component:resolve=> require(['../components/boaters/shipownersList.vue'], resolve),
        },
        {
          path:"/shipnames",
          component:resolve=> require(['../components/boaters/shipNameList.vue'], resolve),
        },

        // 社保
        {
          path:"/securityset",
          component:resolve=> require(['../components/socialsecurity/set.vue'], resolve),
        },{
          path:"/securityinsurance",
          component:resolve=> require(['../components/socialsecurity/insurance.vue'], resolve),
        },{
          path:"/securityinfo",
          component:resolve=> require(['../components/socialsecurity/info.vue'], resolve),
        },{
          path:"/securitycompare",
          component:resolve=> require(['../components/socialsecurity/compare.vue'], resolve),
        },

        // 报销
        {
          path:"/refundAddBoaters",
          component:resolve=> require(['../components/refund/add/addboatersRefund.vue'], resolve),
        },{
          path:"/refundAddStaffs",
          component:resolve=> require(['../components/refund/add/addstaffsRefund.vue'], resolve),
        },{
          path:"/refundAddOffice",
          component:resolve=> require(['../components/refund/add/addofficeRefund.vue'], resolve),
        },

        // 报销记录
        {
          path:"/refundRecordBoaters",
          component:resolve=> require(['../components/refund/record/boatersrecord.vue'], resolve),
        },{
          path:"/refundRecordBoaters/info/:id",
          component:resolve=> require(['../components/refund/add/addboatersRefund.vue'], resolve),
        },

        {
          path:"/refundRecordStaff",
          component:resolve=> require(['../components/refund/record/staffrecord.vue'], resolve),
        },{
          path:"/refundRecordStaff/info/:id",
          component:resolve=> require(['../components/refund/add/addstaffsRefund.vue'], resolve),
        },
        {
          path:"/refundRecordOffice",
          component:resolve=> require(['../components/refund/record/officerecord.vue'], resolve),
        },{
          path:"/refundRecordOffice/info/:id",
          component:resolve=> require(['../components/refund/add/addofficeRefund.vue'], resolve),
        },

        // 报销签批
        {
          path:"/refundSignOffice",
          component:resolve=> require(['../components/refund/sign/office.vue'], resolve),
        },{
          path:"/refundSignOffice/info/:id",
          component:resolve=> require(['../components/refund/add/addofficeRefund.vue'], resolve),
        },
        
        {
          path:"/refundSignBoater",
          component:resolve=> require(['../components/refund/sign/boaters.vue'], resolve),
        },{
          path:"/refundSignBoater/info/:id",
          component:resolve=> require(['../components/refund/add/addboatersRefund.vue'], resolve),
        },
        
        {
          path:"/refundSignStaff",
          component:resolve=> require(['../components/refund/sign/staff.vue'], resolve),
        },{
          path:"/refundSignStaff/info/:id",
          component:resolve=> require(['../components/refund/add/addstaffsRefund.vue'], resolve),
        },

        // 报销统计
        {
          path:"/refundStastics",
          component:resolve=> require(['../components/refund/statistics/statistics.vue'], resolve),
        },
        
        // 报销模板
        {
          path:"/refundSet",
          component:resolve=> require(['../components/refund/set/set.vue'], resolve),
        },

        // 借还款
        {
          path:"/repayMoney",
          component:resolve=> require(['../components/repayment/repay.vue'], resolve),
        },{
          path:"/accident",
          component:resolve=> require(['../components/repayment/accident.vue'], resolve),
        },{
          path:"/repaycompare",
          component:resolve=> require(['../components/repayment/repaycompare.vue'], resolve),
        },

        // 收费
        {
          path:"/chargeInfo",
          component:resolve=> require(['../components/charge/chargeinfo.vue'], resolve),
        },{
          path:"/chargeCompare",
          component:resolve=> require(['../components/charge/chargecompare.vue'], resolve),
        },


        // 供应商
        {
          path:"/supplierInfo",
          component:resolve=> require(['../components/suppliers/supplierInfo.vue'], resolve),
        },
        

        // 员工
        {
          path:"/staffInfo",
          component:resolve=> require(['../components/staff/staffInfo.vue'], resolve),
        },



        // 权限
        {
          path:"/permissionsAgent",
          component:resolve=> require(['../components/Jurisdiction/agent.vue'], resolve),
        },{
          path:"/roleManager",
          component:resolve=> require(['../components/Jurisdiction/roleManager.vue'], resolve),
        },{
          path:"/permisionSettings/:id",
          component:resolve=> require(['../components/Jurisdiction/permisionSettings.vue'], resolve),
        },{
          path:"/accountManager",
          component:resolve=> require(['../components/Jurisdiction/accountManager.vue'], resolve),
        },


        
        
      ]
    }
  ],
  
})
