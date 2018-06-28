// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
// import Vue from 'vue'
// window.vue = Vue

import App from './App'
import router from './router'
import store from './vuex/store.js'
window.store = store
var bus = new Vue()
window.bus = bus

import axios from 'axios'
Vue.prototype.$axios = axios
window.$axios = axios

import './assets/css/resetCss.css'
import './assets/css/commonCss.css'
import './assets/css/scroller.css'

import './assets/css/animate.css'

// 引入字体图标
import '../static/iconfont/demo.css'
import '../static/iconfont/iconfont.css'

// 引入全局方法
import _g from './assets/js/globalFunctions'
window._g = _g
window.PATH = "/api/"
// window.PATH="/"
Vue.prototype.apiPost = _g.apiPost
Vue.prototype.apiGet = _g.apiGet


// 引入窗口尺寸监听方法
// import './assets/js/windowSize.js'

// 引入excel下载组件并重命名为downloadExcel
import JsonExcel from "vue-json-excel";
Vue.component("downloadExcel", JsonExcel)

// 引入ElementUI

import 'element-ui/lib/theme-chalk/index.css';
// import {Select,Input,Checkbox,Button,Option,Message} from "element-ui"
// Vue.use(Select);Vue.use(Input);Vue.use(Checkbox);Vue.use(Button);Vue.use(Option);
// Vue.use(Message)
// import ElementUI from 'element-ui'
// Vue.use(ElementUI, { size:'small'})




import "./assets/js/jquery.jqprint-0.3"


import './assets/css/elementUi.css'

Vue.config.productionTip = false
/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  components: { App },
  template: '<App/>'
})

router.beforeEach((to, from, next) => {
  
  store.state.allDialog.extendShow = false
  store.state.allDialog.searchExtend = true

  if (store.state.searchData[to.path.slice(1)]) store.state.searchData[to.path.slice(1)] = { page: 1, listRows: 10, time: [] }
  if (store.state.searchSetData[to.path.slice(1)]) store.state.searchSetData[to.path.slice(1)].forEach(ele => {
    ele.value = ""
    ele.start = ""
    ele.end = ""
  });

  if (to.fullPath == "/forgetPassword" || to.path == "/login" || to.path == "/") {
    next()
  }
  else if (to.path == "/refundAddOffice" || to.path == "/refundAddStaffs" || to.path == "/refundAddBoaters") {
    store.state.reflash.contentReflash = false
    setTimeout(() => {
      store.state.reflash.contentReflash = true
    }, 10)
    next()
  }
  else {
    if (!localStorage.getItem("my_loginTime")) {
      router.replace("/login")
      next()
    } else {
      next()
    }
  }
})

router.afterEach((to, from) => {
  // if (!sessionStorage.getItem("logined")) {
  //   router.replace("/login")
  // }
  if (to.path !== "/login") {
    axios.post(PATH + "index/warn").then(res => {
      var array1 = ["待签批员工差旅报销申请", "待签批船员差旅报销申请", "待签批办公费用报销申请", "员工差旅报销通过提醒", "船员差旅报销通过提醒", "办公费用报销通过提醒"]
      var array2 = ["user_sign", "mariner_sign", "office_sign", "user_warn", "mariner_warn", "office_warn"]
      var array3 = ["/refundSignStaff", "/refundSignBoater", "/refundSignOffice", "/refundRecordStaff", "/refundRecordBoaters", "/refundRecordOffice"]
      var array4=["员工差旅报销签批","船员差旅报销签批","办公费用报销签批","员工差旅报销记录","船员差旅报销记录","办公费用报销记录"]
      var array5=["3-2-1","3-2-0","3-2-2","3-1-1","3-1-0","3-1-2"]
      var goal = []
      array1.forEach((ele, index) => {
        goal.push({ label: ele, count: res.data[array2[index]], url: array3[index],title:array4[index],menu:array5[index]})
      })
      store.state.infoNotice.warningData = goal
      store.state.infoNotice.infoNum = Object.values(res.data).reduce((a, b) => a + b) - res.data.isMariner

      // 船员
      if (res.data.isMariner) {
        store.state.reflash.searchReflash=false
        store.state.leftmenuData.forEach((ele1, index1) => {
          ele1.show = ele1.isactive = false
          ele1.children.forEach((ele2, index2) => {
            ele2.show = false
            ele2.data.forEach((ele3, index3) => {
              ele3.show = ele3.isactive = false
            })
          })
        })
        
        store.state.leftmenuData[3].show = store.state.leftmenuData[3].isactive = true
        store.state.leftmenuData[3].children[0].show = true
        store.state.leftmenuData[3].children[0].data[0].show = true
        store.state.leftmenuData[3].children[0].data[0].isactive =(to.path=="/refundAddBoaters")?true:false

        store.state.leftmenuData[3].children[1].show = true
        store.state.leftmenuData[3].children[1].data[0].show=true
        store.state.leftmenuData[3].children[1].data[0].isactive = (to.path=="/refundRecordBoaters")?true:false



      } else if (to.path == "/homepage" && from.path == "/login") {
        store.state.reflash.searchReflash=true //
        store.state.leftmenuData.forEach((ele1, index1) => {
          ele1.show = true
          ele1.isactive = false
          ele1.children.forEach((ele2, index2) => {
            ele2.show = true
            ele2.data.forEach((ele3, index3) => {
              ele3.show = true
              ele3.isactive = false
            })
          })
        })
        store.state.leftmenuData[0].isactive = true
        store.state.leftmenuData[0].children[0].data[0].isactive = true
      }
    })
    
    // 从首页便捷入口进入 点击返回的
    if(to.path=="/homepage"){
      store.state.leftmenuData.forEach((ele1,index1)=>{
        ele1.children.forEach((ele2,index2)=>{
          ele2.data.forEach((ele3,index3)=>{
            if(index1==0&&index2==0&&index3==0){
              ele3.isactive=true
              ele1.isactive=true
            }else{
              ele3.isactive=false
              ele1.isactive=false
            }
          })
        })
      })
    }
  }
})