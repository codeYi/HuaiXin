<template>
  <el-dialog :title="title" :visible.sync="$store.state.allDialog.repay" width="600px" class="border-common">
    <div class="overflow-y">
      <div class="head">
          <ul v-if="!showSupplier">
            <li class="grow_1 bac_grey">借款金额</li>
            <li class="grow_3">{{formData.doPayData.amount}}</li>
            <li class="grow_1 bac_grey">已还金额</li>
            <li class="grow_3">{{formData.doPayData.receipt}}</li>
          </ul>
           <ul v-if="showSupplier">
            <li class="grow_1 bac_grey">供应商名称</li>
            <li class="grow_3">{{formData.supplier.title}}</li>
            <li class="grow_1 bac_grey">属性</li>
            <li class="grow_3">{{formData.supplier.attribute}}</li>
            <li class="grow_1 bac_grey">应付金额</li>
            <li class="grow_3">{{formData.doPayData.amount}}</li>
            <li class="grow_1 bac_grey">实付合计</li>
            <li class="grow_3">{{formData.doPayData.receipt}}</li>
          </ul>
      </div>
      <div class="body">
          <div v-if="formData.doPayData.record&&formData.doPayData.record.length>0">
            <p>{{secondTitle}}  <span v-if="showSupplier" class="my-btn fr" @click="$store.state.allDialog.download=true;$store.state.downExcell.isSpecial=true">导出{{title}}</span></p>
            <table>
              <tr v-if="!showSupplier" >
                <th class="grow_1 bac_grey">还款时间</th>
                <th class="grow_1 bac_grey">还款金额</th>
                <th class="grow_1 bac_grey">变更人</th>
                <th class="grow_1 bac_grey">变更时间</th>
              </tr>
              <tr v-if="!showSupplier" v-for="item in formData.doPayData.record">
                <td class="grow_1">{{item.time||item.date}}</td>
                <td class="grow_1">{{item.money}}</td>
                <td class="grow_1">{{item.changer}}</td>
                <td class="grow_1">{{item.change_date}}</td>
              </tr>

              <tr v-if="showSupplier">
                <th class="grow_1 bac_grey">费用发生时间</th>
                <th class="grow_1 bac_grey">应付金额</th>
                <th class="grow_1 bac_grey">实付金额</th>
                <th class="grow_1 bac_grey">操作</th>
              </tr>
              <tr v-if="showSupplier" v-for="(item,index) in formData.doPayData.record">
                <td class="grow_1">{{item.time||item.date}}</td>
                <td class="grow_1">{{item.pay_before}}</td>
                <td class="grow_1"><input class="edit-money" type="number" v-model="item.pay_after" ref="edit" :readonly="!item.editThis"></td>
                <td class="grow_1">
                  <span class="color-blue cursor" v-show="!item.editThis" @click="edit(item,index)">编辑</span>
                  <span class="color-blue cursor" v-show="item.editThis" @click="doneThis(item,index)">完成</span>
                </td>
              </tr>

            </table>
            <div class="page-box border-common">
              <el-pagination layout="prev, pager, next" :current-page="page" @current-change="changeCurrent"  :page-size="pageSize" :total="recordAll.length"></el-pagination>
            </div>
          </div>
          <div v-else>
            <p>暂无{{secondTitle}}</p>
          </div>
      </div>

      <div class="foot" v-if="!showSupplier">
        <el-form label-width="80px" :model="payNow" class="my-form" ref="my-form">
          <el-form-item label="还款时间" class="allwidth">
            <el-date-picker v-model="payNow.date" type="date" placeholder="选择日期" >
            </el-date-picker>
          </el-form-item>
          <el-form-item label="还款金额" >
            <el-input v-model="payNow.money" :autofocus="true" @blur="testMoney(payNow.money)" title="两位小数的数字格式">
              <el-select v-if="routerpath=='/repayMoney'" v-model="payNow.currency" slot="prepend" placeholder="请选择" style="width:83px">
                <el-option label="人民币" value="人民币"></el-option>
                <el-option label="美元" value="美元"></el-option>
              </el-select>
            </el-input>
          </el-form-item>
          <el-form-item label="变更人">
            <el-input v-model="payNow.changer" :readonly="true"></el-input>
          </el-form-item>
          <el-form-item label="变更时间">
            <el-input v-model="change_date" :readonly="true"></el-input>
          </el-form-item>
        </el-form>
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
      formData:{
        doPayData:{
          amount:"",
          receipt:"",
          record:[]
        },
        supplier:{
          title:"",
          attribute:"",
        }
      },
      payNow:{
        date:"",
        currency:"人民币",
        money:"",
        changer:"",
      },
      title:"还款",
      secondTitle:"还款记录",

      recordAll:[],//所有的记录
      pageSize:5,
      page:1,

      change_date:"",//变更时间
      urls:{
        url:"",
        dopayDetail:"borrow/receiptInfo",
        pay:"borrow/receiptBorrow",//还款

        chargeInfoDetail:"charge/repaymentInfo",//还款记录
        changePay:"charge/repayment",

        supplierDetail:"supplier/detailInfo"

      },

      showSupplier:false,
      routerpath:"",
    };
  },
  methods: {
    closeThis(str) {
      var routerpath = _g.getRouterPath(this);
      if (str == "save"&&routerpath!="/supplierInfo") {
        if(!this.payNow.date){
          _g.toMessage(this,"warning","还款时间为空")
          return
        }else if(!this.payNow.money){
          _g.toMessage(this,"warning","还款金额为空")
          return
        }
        this.apiPost(this.urls.url,this.payNow).then(res=>{
        _g.toMessage(this,(res&&!res.error)?"success":"error",res.msg)
          if(!res.error){
            this.$store.state.allDialog.repay = false;
            bus.$emit("search_new_result")
          }
        })
      }else{
        this.$store.state.allDialog.repay = false;
        // if(routerpath=="/supplierInfo"){
        //   bus.$emit("search_new_result")
        // }
      }
    },
    edit(item,index){
      item.editThis=true
      this.$set(this.formData.doPayData.record,index,item)
      this.$refs.edit[index].focus()
    },
    doneThis(item,index){
      var params={
        id:item.id,
        money:item.pay_after
      }
      this.apiPost("supplier/editInfo",params).then(res=>{
        _g.toMessage(this,res.error?"error":"success",res.msg)
        if(!res.error){
          bus.$emit("search_new_result")
          item.editThis=false
          this.$set(this.formData.doPayData.record,index,item)
          this.formData.doPayData.receipt=0
          this.formData.doPayData.record.forEach(ele=>{
            this.formData.doPayData.receipt+=Number(ele.pay_after)
          })
        }
      })
    },
    changeCurrent(val){
      this.formData.doPayData.record=this.recordAll.slice(this.pageSize*(val-1),this.pageSize*(val))
    },
    testMoney(value){
      if(value&&!/^([1-9][0-9]*)+(.[0-9]{1,2})?$/.test(value)){
        this.payNow.money=""
        _g.toMessage(this,"warning","还款金额格式不正确")
      }
    },
    getInfo(){
      var routerpath = _g.getRouterPath(this);
      this.routerpath=routerpath
      if(routerpath=="/repayMoney"){
        this.urls.url=this.urls.pay
        this.apiPost(this.urls.dopayDetail,{id:this.$store.state.editData.editId}).then(res=>{
          this.recordAll=[...res.record]
          this.formData.doPayData=res
          this.change_date=res.time
          this.payNow.changer=res.username
          this.formData.doPayData.record=res.record.slice(0,this.pageSize)
          // this.$set(this.payNow,"changer",res.username)
          
        })
        this.title="还款"
        this.secondTitle="还款记录"
        this.showSupplier=false
      }else if(routerpath=="/chargeInfo"){
        this.urls.url=this.urls.changePay
        this.apiPost(this.urls.chargeInfoDetail,{id:this.$store.state.editData.editId}).then(res=>{
          this.recordAll=[...res.record]
          this.formData.doPayData.amount=res.info.amount
          this.formData.doPayData.receipt=res.info.receipt
          this.formData.doPayData.record=res.record.slice(0,this.pageSize)
          this.change_date=res.time
          // this.formData.doPayData.changer=res.username
          this.payNow.changer=res.username
        })
        this.title="还款"
        this.secondTitle="还款记录"
        this.showSupplier=false
      }else if(routerpath=="/supplierInfo"){
        this.apiPost(this.urls.supplierDetail,{id:this.$store.state.editData.editId}).then(res=>{
          this.recordAll=[...res.detail]
          this.formData.supplier.title=res.info.title
          this.formData.supplier.attribute=res.info.attribute
          this.formData.doPayData.amount=res.info.pay_before
          this.formData.doPayData.receipt=res.info.pay_after

          res.detail.forEach(ele=>{
            ele.editThis=false
          })

          this.formData.doPayData.record=res.detail.slice(0,this.pageSize)
          
        })
        this.title="费用明细"
        this.secondTitle="费用明细信息"
        this.showSupplier=true
      }
      this.payNow.id=this.$store.state.editData.editId
      this.payNow.date=_g.getToday()

    }
  },
  components: {},
  created() {
    this.getInfo()
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style scoped src="../../../assets/css/removeInputSungle.css">

</style>




<style scoped>
.my-form {
  display: flex;
  flex-wrap: wrap
}
.my-form > div {
  width:48%;
}
.my-form > div .el-input {
  width: 100%;
}
.double-blank > label {
  line-height: 15px;
  height: 15px;
}
.add-set {
  border-bottom: 1px solid #e4e4e4;
}
.head{
  margin-bottom: 10px;
}
.head>ul{
  display: flex;
  flex-wrap: wrap;
  border-top: 1px solid #ebeef5;
  border-left: 1px solid #ebeef5;
}
.head li{
  width: 20%;
  height: 40px;
  line-height: 40px;
  border-right: 1px solid #ebeef5;
  border-bottom: 1px solid #ebeef5;
  font-size: 12px;
}
.grow_1{
  flex-grow: 1
}
.grow_3{
  flex-grow: 2;
  text-indent: 20px;
}

.bac_grey{
  background-color: #F3F3F3;
  text-align: center;
}
.body p{
  text-align: left;
  text-indent: 20px;
  /* padding-right: 10px; */
}
.body table{
  width: 100%;
}
.body table tr{
  display: flex;
  border-top: 1px solid #ebeef5;
  border-bottom: none;
  border-left: 1px solid #ebeef5;
}
.body table tr th,.body table tr td{
  width: 0;
  font-size: 12px;
  height: 35px;
  line-height: 35px;
  text-align: center;
  border-right: 1px solid #ebeef5;
  /* border-bottom: 1px solid #ebeef5; */
}
.foot{
  border-top: 1px solid #ebeef5;
  padding-top: 20px;
}
.page-box{
  border-bottom: none;
  display: flex;
  justify-content: flex-end;
}
.page-box>div{
  
}
.color-blue:hover{
  text-decoration: underline;
}
.edit-money{
  border: 0;
  width: 100%;
  height: 90%;
  position: relative;
  top: -2px;
  text-align: center;
}
.my-btn{
  padding: 0 2px;
  text-indent: 0;
  height: 25px;
  line-height: 25px;
  position: relative;
  font-size:12px;
}

</style>
