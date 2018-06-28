<template>
  <el-dialog title="查询字段设置" :visible.sync="$store.state.allDialog.query" width="600px" class="border-common">
    <div class="query-box">
      <div>
        <p class="title">字段的显示隐藏</p>
        <div class="overflow-y">
          <el-checkbox-group v-model="wordsShow">
            <el-checkbox class="option-one" v-for="item in words" :key="item" :label="item"></el-checkbox>
          </el-checkbox-group>
        </div>
      </div>
      <div>
        <p class="title">字段排序</p>
        <ul class="overflow-y">
          <li v-for="(one,index) in wordsShow" class="order-box">
            <span>{{one}}</span>
            <div class="btn-box">
              <span><img class="cursor" src="../../../assets/imgs/common/next.png" alt="" @click="changePlace('next',one,index)"></span>
              <span><img class="cursor" src="../../../assets/imgs/common/pre.png" alt=""  @click="changePlace('pre',one,index)"></span>
              <span><img class="cursor" src="../../../assets/imgs/common/first.png" alt=""  @click="changePlace('first',one,index)"></span>
              <span><img class="cursor" src="../../../assets/imgs/common/last.png" alt=""  @click="changePlace('last',one,index)"></span>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <span slot="footer" class="dialog-footer">
      <el-button @click="closeThis('cancel')">取 消</el-button>
      <el-button type="primary" @click="closeThis('save')">确 定</el-button>
    </span>
  </el-dialog>
</template>

<script>
export default {
  data() {
    return {
      wordsShow: [],
      words:[], //正在便利的

      wordsArrayBoaters: ["简称","姓名","身份证/护照","CID","FLEET","OWNER POOL","VESSEL","职务","MANNING OFFICE"],//基础数组
      wordsArraySecurityinsurance:["简称","姓名","身份证/护照","CID","社保状态","参保地区","缴费时间","是否停过保"],//社保人员
      wordsArraySecurityinfo:["简称","CID","姓名","身份证/护照","是否补缴","船队","船东","参保地区","缴费时间","缴费类型"],
      wordsArrayStaffInfo:["简称","姓名","身份证/护照","性别","部门","职务","船东","任命职务","年龄","任命时间","到司时间","离职时间","司龄","婚否","出生时间","学历","学位","专业","毕业日期","毕业院校","专业技术职称","政治面貌","户口地","劳动合同起始日","劳动合同终止日","参加工作日期","船上任职资格","出生地","转正日期","电话","手机","邮箱","住址","备注"],

      wordsArrayRefundRecordBoaters:["简称","姓名","船队","客户","船名","状态","报销总计","报销时间","报销地点"],
      wordsArrayRefundSignBoater:["姓名","简称","部门","船名","CID","状态","报销总计","报销时间","报销地点"],
      wordsArrayRefundSignOffice:["报销人","简称","部门","职务","状态","报销总计","报销时间","报销地点","报销项目"],
      wordsArrayRefundSignStaff:["报销人","简称","部门","船名","CID","状态","报销总计","报销时间","报销地点","差旅天数","返程日期"],
      wordsArrayRefundStastics:["简称","姓名","部门","职务","客户","船队","船名","报销总计","报销时间","报销地点","费用发生日期","报销项目",],
      canSetArray:["boaters","securityinsurance","securityinfo","staffInfo","refundRecordBoaters","refundSignBoater","refundSignOffice","refundSignStaff","refundStastics"]//可以设置搜索的路由
    };
  },
  methods: {
    closeThis(str) {
      var goal = [];
      var setStr=""
      var routerpath = _g.getRouterPath(this);
      if (str == "save") {
        for(var i=0;i<this.canSetArray.length;i++){
          if(routerpath.slice(1)==this.canSetArray[i]){
            setStr=this.canSetArray[i]
            break;
          }
        }
        localStorage.setItem(setStr,JSON.stringify(this.wordsShow))
        // localStorage.setItem(setStr,"[]")  //重置本地存储
        // bus.$emit("search_set")

        _g.showloading("修改设置中")
        this.$store.state.reflash.searchReflash=false
        setTimeout(()=>{
           this.$store.state.reflash.searchReflash=true
           _g.closeloading(this)
        },10)
      }
      this.$store.state.allDialog.query = false;

      
      // 是否需要提示
      
    },

    changePlace(str,one,index){
      if(str=="pre"&&index!=0){
        var a=this.wordsShow[index-1]
        this.wordsShow.splice(index,1,a)
        this.wordsShow.splice(index-1,1,one)
      }else if(str=="next"&&index!=this.wordsShow.length-1){
        var a = this.wordsShow[index+1]
        this.wordsShow.splice(index+1,1,one)
        this.wordsShow.splice(index,1,a)
      }else if(str=="first"){
        this.wordsShow.splice(index,1)
        this.wordsShow.unshift(one)
      }else if(str=="last"){
        this.wordsShow.splice(index,1)
        this.wordsShow.push(one)
      }
    },
    getSearchSet(){
      var routerpath = _g.getRouterPath(this);
      for(var i=0;i<this.canSetArray.length;i++){
          if(routerpath.slice(1)==this.canSetArray[i]){
            var str='wordsArray'+this.canSetArray[i].slice(0,1).toUpperCase()+this.canSetArray[i].slice(1)
            this.words=this[str]
            var list = localStorage.getItem(this.canSetArray[i])?JSON.parse(localStorage.getItem(this.canSetArray[i])):[]
            this.wordsShow=list.length>0?list:this.words
            break;
          }
      }
    }
  },
  components: {},
  created() {
    this.getSearchSet()
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.query-box {
  display: flex;
  height: 300px;
}
.query-box > div {
  width: 0;
  flex-grow: 1;
  /* background: red; */

}
.query-box > div:nth-child(1) {
  border-right: 1px solid #e4e4e4;
}
.query-box > div>div,.query-box > div>ul{
  overflow-y: auto;
  height: 260px;
}
P {
  text-align: left;
  text-indent: 20px;
  height: 30px;
  line-height: 30px;
  box-sizing: border-box;
  border-bottom: 1px solid #e4e4e4;
  margin-bottom: 10px;
}
.option-one {
  display: block;
  margin-left: 30px;
}
.order-box{
  padding: 5px 10px;
}
.btn-box{
  display: inline-block;
  float:right;
}
.btn-box img{
  display: inline-block;
  margin-left: 10px;
}
</style>
