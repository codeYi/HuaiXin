<template>
  <div class="download-box">

  </div>
</template>

<script>
import ExportJsonExcel from "js-export-excel"
export default {
  data() {
    return {
      
    };
  },
  components: {},
  mounted() {
    var routerpath=_g.getRouterPath(this);
    setTimeout(()=>{
      this.$store.state.allDialog.download=false
    },3000)
    var option = {};
    var obj={
      sheetData:[],
      sheetName:"",
      sheetFilter:[],
      sheetHeader:[],
    }
    var allData=[
      {
        router:"/boaters",
        fileName:"船员信息",
        url:"mariner/exportMariner",
        add:{},
      },{
        router:"/securityset",
        fileName:"社保设置",
        url:"Social/exportArea",
        add:{//需要增加的项目
          sheetFilter:["index","area","starttime","endtime"],
          sheetHeader:["序号","地区","开始时间","结束时间"],
        },
      },{
        router:"/securityinsurance",
        fileName:"参保人员",
        url:"social/exportList",
        add:{//需要增加的项目
          sheetFilter:["index","cid","name","id_number"],
          sheetHeader:["序号","CID","姓名","身份证号"],
        },
      },{
        router:"/securityinfo",
        fileName:"社保信息",
        url:"Social/exportInfo",
        add:{//需要增加的项目
          sheetFilter:["index","pay_month","cid","name"],
          sheetHeader:["序号","缴费年月","CID","姓名"],
        },
      },{
        router:"/refundRecordBoaters",
        fileName:"船员差旅报销记录",
        url:"expenses/exportMariner",
        add:{}
      },{
        router:"/refundRecordStaff",
        fileName:"员工差旅报销记录",
        url:"expenses/exportUser",
        add:{}
      },{
        router:"/refundRecordOffice",
        fileName:"办公费用报销记录",
        url:"expenses/exportOffice",
        add:{}
      },{
        router:"/refundSignBoater",
        fileName:"船员差旅签批记录",
        url:"Expenses/listMariner",
        add:{}
      },{
        router:"/refundSignStaff",
        fileName:"员工差旅签批记录",
        url:"Expenses/listMariner",
        add:{}
      },{
        router:"/refundSignOffice",
        fileName:"办公费用签批记录",
        url:"Expenses/listMariner",
        add:{}
      },{
        router:"/securitycompare",
        fileName:"社保对账",
        url:"social/exportInsured",
        add:{
          sheetFilter:["index"],
          sheetHeader:["序号"],
        }
      },{
        router:"/repayMoney",
        fileName:"借还款",
        url:"borrow/exportBorrow",
        add:{}
      },{
        router:"/accident",
        fileName:"意外险",
        url:"borrow/exportInsurance",
        add:{
        }
      },{
        router:"/repaycompare",
        fileName:"借还款对账",
        url:"borrow/exportSure",
        add:{}
      },{
        router:"/chargeInfo",
        fileName:"收费信息",
        url:"charge/exportCharge",
        add:{}
      },{
        router:"/chargeCompare",
        fileName:"收费对账",
        url:"charge/exportSure",
        add:{}
      },{
        router:"/supplierInfo",
        fileName:"供应商信息",
        url:"supplier/exportSupplier",
        add:{}
      },{
        router:"/staffInfo",
        fileName:"员工信息",
        url:"staff/exportStaff",
        add:{}
      }
    ]
    var specialArray=[
      {
        router:"/boaters",
        fileName:"费用明细",
        url:"supplier/exportDetail",
        add:{},
      }
    ]

    var exportSet=["/boaters"]
    
    if(!this.$store.state.downExcell.isSpecial){
      allData.forEach(ele=>{
        if(ele.router==routerpath){
          option.fileName = ele.fileName
          obj.sheetName=ele.fileName
          obj.sheetFilter=ele.add.sheetFilter||[]
          obj.sheetHeader=ele.add.sheetHeader||[]

          this.$store.state.downExcell.rule.forEach(ele1=>{
            if(ele1.type!=="selection"){
             obj.sheetFilter.push(ele1.prop||ele1.type)
              obj.sheetHeader.push(ele1.label)
            }
            // obj.sheetFilter.push(ele1.prop||ele1.type)
            // obj.sheetHeader.push(ele1.label)
          })

          var params1=JSON.stringify(this.$store.state.searchData[ele.router.slice(1)])
          var params=JSON.parse(params1)
          if(exportSet.includes(ele.router)){
            params.mariner_id=[...this.$store.state.selectIdArray]
          }

          // return


          this.apiPost(ele.url,params).then(res=>{
            // _g.toMessage(this,res.error?"error":"success",res.msg)
            if(res.error)return
            if(routerpath=="/securityset"){
              res.forEach((ele,index0)=>{
                ele["index"]=index0+1

                ele.project.forEach((ele1,index)=>{
                  ele["base_company"+index]=ele1.base_company||"0.00"
                  ele["rate_company"+index]=ele1.rate_company||"0.00"
                  ele["amount_company"+index]=ele1.amount_company||"0.00"
                  ele["total_company"+index]=ele1.total_company||"0.00"
                  ele["base_person"+index]=ele1.base_person||"0.00"
                  ele["rate_person"+index]=ele1.rate_person||"0.00"
                  ele["amount_person"+index]=ele1.amount_person||"0.00"
                  ele["total_person"+index]=ele1.total_person||"0.00"
                })
              })
              obj.sheetData=[...res]
            }else if(routerpath=="/securityinsurance"||routerpath=="/securityinfo"||routerpath=="/repayMoney"||routerpath=="/accident"||routerpath=="/chargeInfo"||routerpath=="/supplierInfo"||routerpath=="/staffInfo"){
              res.forEach((ele,index0)=>{
                 ele["index"]=index0+1
              })
              obj.sheetData=[...res]
            }else if(routerpath=="/securitycompare"||routerpath=="/repaycompare"||routerpath=="/chargeCompare"){
              res.data.forEach((ele1,index1)=>{
                ele1["index"]=index1+1
                ele1.sure=ele1.sure==1?"是":"否"
              })
              obj.sheetData=[...res.data]
              obj.sheetFilter.push("sure")
              obj.sheetHeader.push("是否确定")
              if(routerpath=="/securitycompare"){
                obj.sheetData.push({
                  area:"总计",
                  receipt:res.receipt,
                  debt:res.debtTotal,
                })
              }else if(routerpath=="/repaycompare"){
                obj.sheetData.push({
                  tally:"总计",
                  rmb_amount:res.rmb_amount,
                  rmb_repayment:res.rmb_repayment,
                  usd_amount:res.usd_amount,
                  usd_repayment:res.usd_repayment,
                })
              }else if(routerpath=="/chargeCompare"){
                obj.sheetData.push({
                  month:"总计",
                  amount:res.amount,
                  repayment:res.repayment,
                })
              }
            }else if(routerpath=="/boaters"){
              res.data.forEach((ele,index0)=>{
                 ele["index"]=index0+1
              })
              obj.sheetData=[...res.data]
            }else if(routerpath=="/refundRecordBoaters"){
              res.forEach((ele,index0)=>{
                 ele["index"]=index0+1
              })
              obj.sheetData=[...res]
            }else if(routerpath=="/refundRecordStaff"){
              res.data.forEach((ele,index0)=>{
                 ele["index"]=index0+1
              })
              obj.sheetData=[...res.data]
            }else if(routerpath=="/refundRecordOffice"){
              res.data.forEach((ele,index0)=>{
                 ele["index"]=index0+1
              })
              obj.sheetData=[...res.data]
            }else if(routerpath=="/refundSignBoater"){
              res.data.forEach((ele,index0)=>{
                 ele["index"]=index0+1
              })
              obj.sheetData=[...res.data]
            }else if(routerpath=="/refundSignStaff"){
              res.data.forEach((ele,index0)=>{
                 ele["index"]=index0+1
              })
              obj.sheetData=[...res.data]
            }else if(routerpath=="/refundSignOffice"){
              res.data.forEach((ele,index0)=>{
                 ele["index"]=index0+1
              })
              obj.sheetData=[...res.data]
            }
            
            option.datas=[obj]
            console.log(option.datas)
            var toExcel = new ExportJsonExcel(option); //new
            toExcel.saveExcel(); //保存
            
            return
          })
        }
      })
    }else{
      this.$store.state.downExcell.isSpecial=false;

      option.fileName = specialArray[0].fileName
      obj.sheetName=specialArray[0].fileName
      obj.sheetFilter=specialArray[0].add.sheetFilter||[]
      obj.sheetHeader=specialArray[0].add.sheetHeader||[]

      this.apiPost(specialArray[0].url,{id:this.$store.state.editData.editId}).then(res=>{
        obj.sheetHeader=["供应商名称","供应商属性","发展日期","备注"]
        obj.sheetFilter=["title","attr","date","remark"]
        obj.sheetData.push({
          title:res.info.title,
          attr:res.info.attribute,
          date:res.info.develop_date,
          remark:res.info.remark
        })
        obj.sheetData.push({
          title:"",
          attr:"",
          date:"",
          remark:"",
        })
        obj.sheetData.push({
          title:"序号",
          attr:"费用发生日期",
          date:"应付金额",
          remark:"实付金额"
        })
        res.detail.forEach((ele,index)=>{
          obj.sheetData.push({
            title:index+1,
            attr:ele.date,
            date:ele.pay_before,
            remark:ele.pay_after
          })
        })

        option.datas=[obj]
        var toExcel = new ExportJsonExcel(option); //new
        toExcel.saveExcel(); //保存
      })
    }
    
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
