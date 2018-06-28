<template>
  <div class="extend">
    <p class="title box-size border-common">
      <img class="ver-middle" :src="imgs.defaultImg" alt="">
      <span class="ver-middle">{{$store.state.addData.extendTitle}}</span>

      <span class="fr box-size right-btn">
        <span class="color-blue cursor" @click="$store.state.allDialog.extendShow=!$store.state.allDialog.extendShow">收起</span>
        <span class="my-btn box-size" v-if="$store.state.allDialog.extendShow" @click="saveThis">确定</span>
      </span>
    </p>
    <!-- 展开的内容 -->
    <div class="extend-content border-common box-size" v-if="extendType==1">
      <div class="head">
        <el-row>
          <el-col :span="2">
            <label for="">
              <p>参保地区：</p>
            </label>
          </el-col>
          <el-col :span="5">
            <!-- <el-select v-model="securityData.area" filterable placeholder="请选择">
              <el-option v-for="item in cityArray" :key="item" :label="item" :value="item">
              </el-option>
            </el-select> -->
            <el-input v-model="securityData.area" placeholder="请输入新的地名"></el-input>
          </el-col>
          <el-col :span="2">
            <label for="">
              <p>时间范围：</p>
            </label>
          </el-col>
          <el-col :span="6" class="special-time">
            <el-date-picker v-model="securityData.starttime" type="month" @change="changeTime(securityData,'starttime')" placeholder="开始月份">
            </el-date-picker>
            <span>--</span>
            <el-date-picker v-model="securityData.endtime" type="month" @change="changeTime(securityData,'endtime')" placeholder="结束月份">
            </el-date-picker>
          </el-col>
          <el-col :span="2">
            <label for="">
              <p>计算规则：</p>
            </label>
          </el-col>
          <el-col :span="6">
            <el-radio v-model="securityData.formula_mode" :label="1" @change="changeMoney">四舍五入</el-radio>
            <el-radio v-model="securityData.formula_mode" :label="2" @change="changeMoney">见分进角</el-radio>
          </el-col>
        </el-row>
      </div>
      <div class="body">
        <el-row>
          <el-col :span="20">
            <table>
              <tr v-for="(item,index) in tableData" class="box-size" v-if="index==0">
                <td v-for="(val,key) in item">{{val}}</td>
              </tr>
              <tr class="box-size" v-else-if="item.is_five==1">
                <td>{{item.title}}</td>
                <td><el-input-number :controls="false" v-model="item.base_company" @change="getNewData('edit',item,'base_company')" :min="0.00" :step="0.01"></el-input-number></td>
                <td><el-input-number :controls="false" v-model="item.rate_company" @change="getNewData('rate',item,'rate_company')" :min="0.00" :step="0.01"></el-input-number></td>
                <td><el-input-number :controls="false" v-model="item.amount_company" @change="getNewData('edit',item,'amount_company')" :min="0.00" :step="0.01"></el-input-number></td>
                <td><el-input-number :controls="false" v-model="item.base_person" @change="getNewData('edit',item,'base_person')" :min="0.00" :step="0.01"></el-input-number></td>
                <td><el-input-number :controls="false" v-model="item.rate_person" @change="getNewData('rate',item,'rate_person')" :min="0.00" :step="0.01"></el-input-number></td>
                <td><el-input-number :controls="false" v-model="item.amount_person" @change="getNewData('edit',item,'amount_person')" :min="0.00" :step="0.01"></el-input-number></td>

                <td>{{item.total_person||0}}</td>
                <td>{{item.total_company||0}}</td>
              </tr>
              <tr class="box-size" v-else-if="item.risksAccount">
                <td class="no-border"></td>
                <td class="no-border"></td>
                <td class="no-border"></td>
                <td class="no-border"></td>
                <td class="no-border"></td>
                <td class="no-border"></td>
                <td class="no-border">五险合计：</td>
                <td class="no-border">{{total.risk_person||0}}</td>
                <td class="no-border">{{total.risk_company||0}}</td>
              </tr>

              <tr class="box-size" v-else-if="item.doSomething">
                <td>
                  <span>{{item.title}}</span>
                  <span class="delete">删除</span>
                </td>
                <td><el-input-number :controls="false" v-model="item.base_company"  :min="0.00" :step="0.01"></el-input-number></td>
                <td><el-input-number :controls="false" v-model="item.rate_company"  :min="0.00" :step="0.01"></el-input-number></td>
                <td><el-input-number :controls="false" v-model="item.amount_company"  :min="0.00" :step="0.01"></el-input-number></td>
                <td><el-input-number :controls="false" v-model="item.base_person"   :min="0.00" :step="0.01"></el-input-number></td>
                <td><el-input-number :controls="false" v-model="item.rate_person"  :min="0.00" :step="0.01"></el-input-number></td>
                <td><el-input-number :controls="false" v-model="item.amount_person"   :min="0.00" :step="0.01"></el-input-number></td>
                <td></td>
                <td></td>
              </tr>

              <tr class="box-size" v-else-if="item.add">
                <td>
                  <span><input class="add-item" type="text" v-model="item.title"></span>
                  <span class="delete" @click="deleteThis(index)" title="删除">删除</span>
                </td>
                <td><el-input-number :controls="false" v-model="item.base_company" @change="getNewData('edit',item,'base_company')"  :min="0.00" :step="0.01"></el-input-number></td>
                <td><el-input-number :controls="false" v-model="item.rate_company" @change="getNewData('rate',item,'rate_company')"  :min="0.00" :step="0.01"></el-input-number></td>
                <td><el-input-number :controls="false" v-model="item.amount_company"  @change="getNewData('edit',item,'amount_company')"  :min="0.00" :step="0.01"></el-input-number></td>
                <td><el-input-number :controls="false" v-model="item.base_person" @change="getNewData('edit',item,'base_person')"   :min="0.00" :step="0.01"></el-input-number></td>
                <td><el-input-number :controls="false" v-model="item.rate_person" @change="getNewData('rate',item,'rate_person')"  :min="0.00" :step="0.01"></el-input-number></td>
                <td><el-input-number :controls="false" v-model="item.amount_person" @change="getNewData('edit',item,'amount_person')"    :min="0.00" :step="0.01"></el-input-number></td>
                
                <td>{{item.total_person||0}}</td>
                <td>{{item.total_company||0}}</td>
              </tr>

              <tr class="box-size" v-else-if="item.addsAccount">
                <td class="no-border">
                  <span class="add-btn my-btn" @click="addThis(index)" title="添加">+ 添加</span>
                </td>
                <td class="no-border"></td>
                <td class="no-border"></td>
                <td class="no-border"></td>
                <td class="no-border"></td>
                <td class="no-border"></td>
                <td class="no-border">添加项合计：</td>
                <td class="no-border">{{total.add_person}}</td>
                <td class="no-border">{{total.add_company}}</td>
              </tr>

              <tr>
                <td class="no-border"></td>
                <td class="no-border"></td>
                <td class="no-border"></td>
                <td class="no-border"></td>
                <td class="no-border"></td>
                <td class="no-border"></td>
                <td class="no-border">合计：</td>
                <td class="no-border">{{(total.add_person-0)+(total.risk_person-0)}}</td>
                <td class="no-border">{{(total.add_company-0)+(total.risk_company-0)}}</td>
              </tr>
            </table>
          </el-col>
          <el-col :span="4">
            <div class="remark">
              <p>备注：</p>
              <textarea rows="10" cols="30" style="width:90%" v-model="securityData.remark"></textarea>
            </div>
          </el-col>
        </el-row>
      </div>
    </div>

    <div class="extend-content border-common box-size" v-else-if="extendType==2">
      <el-row>
        <el-col :span="4">
          <label for="">
            <p>身份证/护照：</p>
          </label>
        </el-col>
        <el-col :span="4">
          <el-input v-model="insuranceData.idNumber" @blur="checkThis('id')" :autofocus="true"></el-input>
        </el-col>
        <el-col :span="3">
          <label for="">
            <p>姓名：</p>
          </label>
        </el-col>
        <el-col :span="4">
          <el-input v-model="insuranceData.name"></el-input>
        </el-col>
        <el-col :span="3">
          <label for="">
            <p>参保地区：</p>
          </label>
        </el-col>
        <el-col :span="4">
          <!-- <el-input v-model="insuranceData.area"></el-input> -->
          <el-select class="select" v-model="insuranceData.area" placeholder="请选择"  @change="changeName(item.value)">
              <el-option v-for="(item,index) in allareaArray" :key="index" :label="item" :value="item">
              </el-option>
          </el-select>
        </el-col>
      </el-row>
      <el-row style="margin-top:10px">
        <el-col :span="4">
          <label for="">
            <p>{{$store.state.allDialog.extendAdd?"开始时间":"停缴时间"}}：</p>
          </label>
        </el-col>
        <el-col :span="4">
          <el-date-picker type="month" placeholder="选择月份" v-model="insuranceData.time" @change="changeThisTime"></el-date-picker>
        </el-col>
        <el-col v-if="$store.state.allDialog.extendAdd" :span="3">
          <label for="">
            <p>是否补缴：</p>
          </label>
        </el-col>
        <el-col v-if="$store.state.allDialog.extendAdd" :span="4">
          <el-radio style="line-height:40px;" v-model="insuranceData.payable" label="否">否</el-radio>
          <el-radio style="line-height:40px;" v-model="insuranceData.payable" label="是">是</el-radio>
        </el-col>
      </el-row>
      <el-row style="margin-top:10px">
        <el-col :span="4">
          <span style="display:block;text-align:right;line-height:32px;color:#666;padding-right:5px;font-size:14px;">{{$store.state.allDialog.extendAdd?"批量增加":"批量减少"}}：</span>
        </el-col>
        <el-col :span="4">
          <el-upload class="upload-demo" ref="upload" :before-remove="beforeRemove" :on-exceed="handleExceed" accept=".xls,.xlsx" :auto-upload="false" :action="urls.importUrl"  :on-success="uploadResult" :on-error="uploadError" :limit="1">
            <el-button size="small" type="primary">点击上传</el-button>
            <div slot="tip" class="el-upload__tip">按模板导入</div>
          </el-upload>
        </el-col>
      </el-row>
    </div>

  </div>
</template>

<script>
export default {
  data() {
    return {
      securityData: {
        area: "",
        starttime: "",
        endtime: "",
        formula_mode: 1,
        remark: ""
      },
      cityArray: ["武汉", "北京"], //选择城市的数组
      allareaArray:[],
      tableData: [
        {
          title: "参保项目",
          base_company: "公司基数(元)",
          rate_company: "公司费率(%)",
          amount_company: "公司固定金额",
          base_person: "个人基数(元)",
          rate_person: "个人费率(%)",
          amount_person: "个人固定金额",
          total_person: "个人合计",
          total_company: "公司合计"
        }
      ],

      total: {
        //合计
        risk_person: 0,
        risk_company: 0,
        add_person: 0,
        add_company: 0
      },

      insuranceData: {
        payable: "否", //是否补缴
        idNumber: "",
        name: "",
        area: "",
        time: ""
      },

      urls: {
        importUrl: "",
        editDetail: "Social/detailArea",// 设置详细
        addperson:"Social/addInsured",//添加社保人员

        addSocialSet:"Social/addArea",//添加社保设置
        editSocialSet:"Social/editArea",

        addTogether:"Social/importInsured",

        reducePerson:"Social/reduceInsured",
        reduceTogether:"Social/importReduce",
      },

      imgs: {
        defaultImg: require("../../assets/imgs/boaters/icon-datalist@2x.png")
      },
      // title:"设置比例",
      extendType: 1 //路由
    };
  },
  methods: {
    // 修改数据后需要整体刷新edit页面
    getNewData(str,data,key) {
      setTimeout(()=>{
        this.$set(data,key,data[key]||0)
        this.changeMoney()
      },100)
      
    },

    changeName(){

    },

    getRightNum(item,key){
      
    },
    changeThisTime(){
      this.insuranceData.time=_g.formatTime(this.insuranceData.time,"month")
    },

    changeTime(item,str){
      item[str]=item[str]?_g.formatTime(item[str],"month"):""
    },

    changeMoney(){
        this.total.risk_person=0
        this.total.risk_company=0
        this.total.add_person=0
        this.total.add_company=0
        this.tableData.forEach((ele,index)=>{
          if((index>0&&index<6)||ele.add){
            if(this.securityData.formula_mode==1){
              ele.total_company=((ele.base_company-0)*(ele.rate_company/100)+(ele.amount_company-0)).toFixed(2)
              ele.total_person=((ele.base_person-0)*(ele.rate_person/100)+(ele.amount_person-0)).toFixed(2)
            }else{
              // 分进角
              ele.total_company=((ele.base_company-0)*(ele.rate_company/100)+(ele.amount_company-0))
              ele.total_person=((ele.base_person-0)*(ele.rate_person/100)+(ele.amount_person-0))
              ele.total_company=ele.total_company.toString()
              ele.total_person=ele.total_person.toString()
              if(ele.total_company&&ele.total_company.indexOf(".")>-1&&ele.total_company.slice(ele.total_company.indexOf(".")+2)>0){
                ele.total_company=((ele.total_company).slice(0,(ele.total_company.indexOf(".")+2))-0+0.1).toFixed(2)
                
              }else{
                ele.total_company=(ele.total_company-0).toFixed(2)
              }
              if(ele.total_person.indexOf(".")>-1&&ele.total_person.slice(ele.total_person.indexOf(".")+2)>0){
                ele.total_person=((ele.total_person).slice(0,(ele.total_person.indexOf(".")+2))-0+0.1).toFixed(2)
              }else{
                ele.total_person=(ele.total_person-0).toFixed(2)
              }
            }

            if(index>0&&index<6){
              this.total.risk_person+=Number(ele.total_person)
              this.total.risk_company+=Number(ele.total_company)
            }
            if(ele.add){
              this.total.add_person+=Number(ele.total_person)
              this.total.add_company+=Number(ele.total_company)
            }
          }
        })
        // 
        this.total.risk_person=Number(this.total.risk_person).toFixed(2)
        this.total.risk_company=Number(this.total.risk_company).toFixed(2)
        this.total.add_person=Number(this.total.add_person).toFixed(2)
        this.total.add_company=Number(this.total.add_company).toFixed(2)

        console.log(this.total)
    },

    // 点击添加按钮
    addThis(index) {
      var obj = {
        add: true,
        title:"",
        base_company: 0,
        rate_company: 0,
        amount_company:0,
        base_person: 0,
        rate_person: 0,
        amount_person:0,
        total_person:0,
        total_company: 0
      };
      this.tableData.splice(index, 0, obj);
    },

    // 确定保存按钮
    saveThis(){
      var url=this.urls.addSocialSet
      var params={}
      if(this.extendType==1){
        url=this.$store.state.editData.isCopy?"Social/addArea":this.$store.state.editData.editId==-1?this.urls.addSocialSet:this.urls.editSocialSet
        if(this.$store.state.editData.editId>-1&&!this.$store.state.editData.isCopy)params.id=this.$store.state.editData.editId
        params.area=this.securityData.area
        // params.id=this.securityData.id
        params.starttime=this.securityData.starttime
        params.endtime=this.securityData.endtime
        params.mode=this.securityData.formula_mode
        params.remark=this.securityData.remark
        params.project=[]
        this.tableData.forEach((ele,index)=>{
          if((index<6&&index>0)||ele.add)params.project.push({
            title:ele.title,
            base_company:ele.base_company,
            base_person:ele.base_person,
            rate_company:ele.rate_company,
            rate_person:ele.rate_person,
            amount_company:ele.amount_company,
            amount_person:ele.amount_person,
          })
        })
        if(!params.area)return(_g.toMessage(this,"warning","参保地区不能为空"))
        if(!params.starttime||!params.endtime)return(_g.toMessage(this,"warning","参保时间不能为空或者格式有问题"))
      }else if(this.extendType==2){
        url=this.$store.state.allDialog.extendAdd?this.urls.addperson:this.urls.reducePerson
        params=this.insuranceData
        
        if(!params.idNumber&&this.$refs.upload.uploadFiles.length==0){
          _g.toMessage(this,"warning","身份证号码为空")
          return
        }else if(!params.name&&this.$refs.upload.uploadFiles.length==0){
           _g.toMessage(this,"warning","姓名为空")
           return
        }else if(!params.area&&this.$refs.upload.uploadFiles.length==0){
           _g.toMessage(this,"warning","参保地区为空")
           return
        }else if(!params.time&&this.$refs.upload.uploadFiles.length==0){
           _g.toMessage(this,"warning","停保时间为空")
           return
        }
      }
      
      if(this.extendType==1||(this.extendType==2&&this.$refs.upload.uploadFiles.length==0))this.apiPost(url,params).then(res=>{
        _g.toMessage(this,res.error?"error":"success",res.msg)
        if(!res.error){
          bus.$emit("search_new_result")
          bus.$emit("reflash_area")
          this.$store.state.allDialog.extendShow=false
        }
      })
      if(!this.$store.state.editData.isCopy&&this.extendType==2&&this.$refs.upload.uploadFiles.length>0){
        this.$refs.upload.submit()
      }
    },

    // 点击删除按钮
    deleteThis(index) {
      this.tableData.splice(index, 1);
      this.changeMoney()
    },

    // 获取传入id的信息
    getDetail() {
      if (this.$store.state.editData.editId > -1) {//编辑
        this.apiPost(this.urls.editDetail, {
          id: this.$store.state.editData.editId
        }).then(res => {
          this.securityData = res.area;
          if(this.$store.state.editData.isCopy){
            this.securityData.id=""
            // this.securityData.area="请输入新的地名"
          }
          this.tableData=this.tableData.concat(res.data.slice(0,5))
          this.tableData.push({risksAccount:true})
          
          res.data.slice(5).forEach(ele=>{
            ele.add=true
          })
          this.tableData=this.tableData.concat(res.data.slice(5))
          this.tableData.push({addsAccount:true})

          // this.getNewData();
          this.changeMoney()
        });
      }else if(this.$store.state.editData.editId==-1){
        var array=["养老保险","生育保险","失业保险","工伤保险","医疗保险"]
        for(var i=0;i<5;i++){
          this.tableData.push({
              is_five:1,
              title: array[i],
              base_company: "",
              base_person: "",
              rate_company: "",
              amount_company:"",
              rate_person:"",
              amount_person: "",
              total_person:"",
              total_company:"",
          })
        }
        this.tableData.push({risksAccount:true})
        this.tableData.push({addsAccount:true})
      }
    },

    checkThis(str){
      if(str=="id"){
        if(this.insuranceData.idNumber&&!/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/.test(this.insuranceData.idNumber)){
          _g.toMessage(this,"warning","身份证号格式不正确")
          this.insuranceData.idNumber=""
        }
      }
    },

    uploadResult(response) {
      _g.toMessage(this,response.error?"error":"success",response.msg)
      // this.$store.state.allDialog.add =response.error!=0
      this.$refs.upload.uploadFiles=[]
      if(!response.error){bus.$emit("search_new_result");this.$store.state.allDialog.extendShow=false}
    },
    uploadError(){
      _g.toMessage(this,"error","请检查网络")
    },
    // 选多了提示
    handleExceed(files, fileList) {
      this.$message.warning(
        `当前限制选择 1 个文件，本次选择了 ${
          files.length
        } 个文件，共选择了 ${files.length + fileList.length} 个文件`
      );
    },
    beforeRemove(file, fileList) {
      return this.$confirm(`确定移除 ${file.name}?`);
    }
  },
  components: {},
  created() {
    if(this.$store.state.tableListData.allareaArray.length==0){
        this.apiPost("Social/searchArea").then(res=>{
          // console.log(res,"area")
          if(!res.error){
            res.data.forEach(ele=>{
              this.allareaArray.push(ele)
            })
            this.$store.state.tableListData.allareaArray=[...this.allareaArray]
          }
        })
      }else{
        this.allareaArray=[].concat(this.$store.state.tableListData.allareaArray)
      }
    var routerpath = _g.getRouterPath(this);
    if (routerpath == "/securityset") {
      // this.title = "设置比例";
      this.extendType = 1;
      this.getDetail();
    } else if (routerpath == "/securityinsurance") {
      this.title = this.$store.state.allDialog.extendAdd
        ? "增加人员"
        : "减少人员";
      this.urls.importUrl = this.$store.state.allDialog.extendAdd
        ? PATH+this.urls.addTogether
        : PATH+this.urls.reduceTogether
      this.extendType = 2;

    }
  },
  mounted(){
    
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

p {
  padding: 0;
  height: 45px;
  line-height: 45px;
  text-align: left;
  color: #666;
  background-color: #f3f3f3;
  margin-top: 20px;
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

.el-input {
  width: 90px;
}
.extend-btn,
.take-up-btn {
  margin-right: 20px;
  color: #169bd5;
}
.extend-btn:hover,
.take-up-btn:hover {
  text-decoration: underline;
}

.extend-content,
.remark {
  padding: 10px;
  border-top: none;
}
.extend-content p {
  margin: 0;
  height: 40px;
  line-height: 40px;
  text-align: right;
  padding-right: 5px;
  font-size: 14px;
  background-color: transparent;
}
.head .el-col {
  line-height: 40px;
}
.body table {
  width: 100%;
  margin-top: 10px;
  border-top: 1px solid #e4e4e4;
  border-left: 1px solid #e4e4e4;
}
.body table tr {
  display: flex;
}
.body table tr td {
  width: 0;
  flex-grow: 1;
  text-align: center;
  height: 40px;
  line-height: 40px;
  border-right: 1px solid #e4e4e4;
  border-bottom: 1px solid #e4e4e4;
  background-color: #fff;
  color: #666;
}
.body table tr:nth-child(1) td {
  font-weight: 800;
  color: #666;
}
.body table tr:nth-last-child(1){
  border-top: 1px solid #e4e4e4;
}
.body table tr td input {
  width: 90%;
  height: 30px;
  border: none;
  border: 1px solid #e4e4e4;
  margin-top: -3px;
  text-indent: 10px;
}
.body table tr td.no-border {
  border: none;
}
.body table tr:nth-child(8) {
  border-top: 1px solid #e4e4e4;
}
.delete {
  color: #0099ff;
  font-size: 12px;
  margin-left: 5px;
  cursor: pointer;
}
.delete:hover {
  text-decoration: underline;
}
.add-btn {
  display: inline-block;
  height: 25px;
  line-height: 25px;
  margin: 0;
}
.body .add-item {
  width: 50px;
}
.remark p {
  text-align: left;
  color: #666;
}
.remark textarea {
  text-indent: 10px;
}

/*  */
.el-upload__tip::before {
  content: "*";
  color: #f56c6c;
  margin-right: 4px;
}
.color-blue:hover{
  text-decoration: underline;
}
</style>
