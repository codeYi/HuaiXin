<template>
  <div class="table-head">
    <p class="title box-size border-common">
      <img class="ver-middle" :src="url" alt="">
      <span class="ver-middle">{{type=="search"?'搜索查询':'数据列表'}}</span>
      <span class="fr box-size right-btn">
        <span :class="item.class?item.class:'my-btn box-size'" v-for="item in btnData" v-if="item.show&&!item.isSelect" @click="item.click">{{item.label}}</span>
        <!-- <el-select  v-for="(item,index) in btnData" :key="index" v-if="item.show&&item.isSelect" v-model="item.value" placeholder="请选择"><el-option v-for="one in 15" :key="one" :value="one">{{one}}</el-option></el-select> -->
      </span>
    </p>
  </div>
</template>

<script>

export default {
  props:["url","type","btns"],
  data() {
    return {
      btnData:[
        {
          label:"查询设置",
          show:true,
          showStr:"1,8-1-1",
          click:this.query
        },
        {
          label:"展开筛选",
          show:true,
          class:"extend-class",
          showStr:"8-1-1",
          click:this.showSearch
        },
        {
          label:"查询结果",
          show:true,
          showStr:"1,1-1,8-1-1",
          click:this.getSearchResult,
        },{
          label:"添加船员",
          show:true,
          showStr:"2",
          click:this.add,
        },{
          label:"字段设置",
          show:true,
          showStr:"2,2-2,2-3,3-1,3-2,3-3,8-1-2",
          click:this.SetWords
        }
        ,{
          label:"导入家汇信息",
          show:true,
          showStr:"2",
          click:this.importData
        },{
          label:"导出船员数据",
          show:true,
          showStr:"2",
          click:this.exportInfo,
        },{
          label:"导出人民币汇款单",
          show:true,
          showStr:"2",
          click:this.exportRmb,
        },{
          label:"导出外币汇款单",
          show:true,
          showStr:"2",
          click:this.exportWb,
        },{
          label:"显示条数",
          show:true,
          showStr:"2",
          isSelect:true,
          value:"",
        },{
          label:"编辑",
          show:true,
          showStr:"3",
          click:this.edit,
        },{
          label:"添加",
          show:true,
          showStr:"2-2,2-3,5-2,4-5-1,7-1,8-1-2,9-1,9-2",
          click:this.add,
        },
        {
          label:"添加借款",
          show:true,
          showStr:"5-1",
          click:this.add
        },
        {
          label:"添加收款",
          show:true,
          showStr:"6-1",
          click:this.add
        },
        {
          label:"导出数据",
          show:true,
          showStr:"2-1,3-1,3-2,3-3,3-4,5-1,5-2,5-3,6-1,6-2,7-1,8-1-2,",
          click:this.exportInfo,
        },
        {
          label:"导出报销数据",
          show:true,
          showStr:"4-4-1",
          click:function(){}
         
        },
        {
          label:"导出报销记录",
          show:true,
          showStr:"4-2-1,4-2-2",
          click:this.exportInfo,
        },{
          label:"导出报销记录",
          show:true,
          showStr:"4-2-3",
          click:this.exportOfficeInfo,
        },
        {
          label:"导出报销单",
          show:true,
          showStr:"4-3-1,4-3-2,4-3-3",
          click:function(){}
        },
        {
          label:"添加社保设置",
          show:true,
          showStr:"3-1",
          click:this.extend,
        },
        {
          label:"增加人员",
          show:true,
          showStr:"3-2",
          click:this.insuranceAdd
        },
        {
          label:"减少人员",
          show:true,
          showStr:"3-2",
          click:this.insuranceReduce
        },
        {
          label:"批量设置",
          show:true,
          showStr:"3-1,3-3",
          click:this.batchThis,
        }
      ],

      urls:{
        router:null,//当前路由
      }
    };
  },
  methods:{
    // 判断显示的按钮
    btnsShow(){ 
      this.btnData.forEach(ele=>{
        if(ele.showStr.split(",").includes(this.btns)){
          ele.show=true
        }else{
          ele.show=false
        }
      })
    },

    // 查询结果
    getSearchResult(){
      var routerpath = _g.getRouterPath(this);
      this.$store.state.searchData[routerpath.slice(1)].page=1
      this.$store.state.reflash.tableReflash=false
      setTimeout(()=>{
        this.$store.state.reflash.tableReflash=true
      },100)
    },
    
    SetWords(){
      this.$store.state.allDialog.wordsSet=true
    },

    exportWb(){
      var url="Mariner/exportRemittanceEn"
      var str=_g.objToStr(this.$store.state.searchData.boaters)
      this.apiPost("mariner/checkRule").then(res=>{
        if(!res.error){
          console.log(PATH+url+"?"+str+"&id="+this.$store.state.selectIdArray.join(","))
          location.href=PATH+url+"?"+str+"&id="+this.$store.state.selectIdArray.join(",")
        }else{
          _g.toMessage(this,res.error?"error":"success",res.msg)
        }
      })
    },
    exportRmb(){
      var url="Mariner/exportRemittanceCn"
      var str=_g.objToStr(this.$store.state.searchData.boaters)
      
      this.apiPost("mariner/checkRule").then(res=>{
        if(!res.error){
          console.log(PATH+url+"?"+str+"&id="+this.$store.state.selectIdArray.join(","))
          // return
          location.href=PATH+url+"?"+str+"&id="+this.$store.state.selectIdArray.join(",")
        }else{
          _g.toMessage(this,res.error?"error":"success",res.msg)
        }
      })
    },


    exportOfficeInfo(){
      var url="expenses/exportOffice"
      var str=_g.objToStr(this.$store.state.searchData.refundRecordOffice)
      // console.log(":Test")
      // return
      location.href=PATH+url+"?"+str
    },
    showSearch(){
      this.$store.state.allDialog.searchExtend=!this.$store.state.allDialog.searchExtend
      this.btnData[1].label=this.$store.state.allDialog.searchExtend?"展开筛选":"收起筛选"
    },
    // 添加
    add(){
      var path=this.urls.router
      this.$store.state.allDialog.add=true
    },

    importData(){
      this.$store.state.allDialog.batch=true
    },

    // 导出
    exportInfo(){
      // console.log(this.$store.state.tableListData.listData)
      this.$store.state.allDialog.download=true
    },

    // 编辑  需要在$store.state中保存船东信息
    edit(){
      var path=this.urls.router
      if(path==="/shipowners"||path==="/shipnames"){
        this.$store.state.allDialog.shipownerEditShow=true
      }
    },


    extend(){
      this.$store.state.allDialog.extendShow=false
      this.$store.state.editData.editId=-1
      this.$store.state.editData.isCopy=false
      this.$store.state.addData.extendTitle="新增设置"
      setTimeout(()=>{this.$store.state.allDialog.extendShow=true},0)
    },

    insuranceAdd(){
      this.$store.state.allDialog.extendShow=false
      setTimeout(()=>{
        this.$store.state.allDialog.extendShow=true
      },10)
      this.$store.state.allDialog.extendAdd=true
    },

    insuranceReduce(){
      this.$store.state.allDialog.extendShow=false
      setTimeout(()=>{
        this.$store.state.allDialog.extendShow=true
      },10)
      this.$store.state.allDialog.extendAdd=false
    },

    // 批量设置
    batchThis(){
      this.$store.state.allDialog.batch=true
    },

    query(){
      var path=this.urls.router
      this.$store.state.allDialog.query=true
    }
  },
  components: {},
  created() {

    this.urls.router=_g.getRouterPath(this)
    this.btnData[1].label=this.$store.state.allDialog.searchExtend?"展开筛选":"收起筛选"
    this.btnsShow()
  },
  mounted(){
    if(!bus._events.search_new_result){
        bus.$on("search_new_result",(value)=>{
          this.getSearchResult()
        })
      }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
p {
  padding: 0;
  height: 46px;
  line-height: 45px;
  text-align: left;
  color: #333;
  background-color: #f3f3f3;
  /* border-bottom: none; */
}

.title > img {
  display: inline-block;
  width: 20px;
  margin: 0px 10px;
}
.right-btn {
  height: 45px;
}
.my-btn {
  display: inline-block;
  text-align: center;
  margin-right: 10px;
  line-height: 20px;
  margin-top: 6px;
}
.title .my-btn:last-child {
  margin-right: 20px;
}

.el-input{
  width: 90px;
}
.extend-class{
  color: #0099FF;
  margin-right: 10px;
  cursor: pointer;
}
.extend-class:hover{
  text-decoration: underline;
}
</style>
