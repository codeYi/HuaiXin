<template>
  <div class="forget-password pr">
    <headP></headP>
    <div class="content-box">
      <div class="title-box pr">
        <p class="title pa">
          <span class="password fl">找回密码</span>
          <span class="my-btn box-size fr" @click="$router.go(-1)">返回</span>
        </p>
      </div>
      <div class="content">
        <el-steps :active="list.active" align-center finish-status="success">
          <el-step title="账户信息"></el-step>
          <el-step title="验证账户"></el-step>
          <el-step title="重设密码"></el-step>
        </el-steps>

        <div class="write-box">
          <!-- 账户信息 -->
          <div v-show="list.active==0">
            <el-form :model="ruleForm1" status-icon :rules="rules1" ref="ruleForm1" label-width="100px" class="demo-ruleForm">
              <el-form-item label="部门" prop="dep">
                <el-select v-model="ruleForm1.dep" :filterable="true" placeholder="选择部门" :clearable="true" class="color" @change="changeThis('dep',ruleForm1.dep)">
                  <el-option v-for="(item,index) in tableData.departments" :key="index" :label="item" :value="item">
                  </el-option>
                </el-select>
              </el-form-item>
              <el-form-item label="姓名" prop="name" v-if="ruleForm1.dep!='crew'" >
                <el-select v-model="ruleForm1.name" placeholder="选择姓名" :filterable="true" :clearable="true"  class="color">
                  <el-option v-for="(item,index) in tableData.username" :key="index" :label="item.username" :value="item.id">
                  </el-option>
                </el-select>
              </el-form-item>

              <!-- <el-form-item label="身份证号" prop="idNumber" v-else>
                <el-input v-model="ruleForm1.idNumber" placeholder="请输入身份证号码" class="color"></el-input>
              </el-form-item> -->

              
              <el-form-item>
                <el-button type="primary" @click="submitForm('ruleForm1')">确定</el-button>
              </el-form-item>
            </el-form>
          </div>
          <!-- 验证账户 -->
          <div v-show="list.active==1">
            <el-form :model="ruleForm2" :rules="rules2" ref="ruleForm2" label-width="100px" class="demo-ruleForm">
              <!-- <el-form-item label="邮箱" prop="email" title="使用@进行匹配常用的邮箱">
                <el-autocomplete type="text" v-model="ruleForm2.email" auto-complete="off" class="color" :clearable="true" :fetch-suggestions="emailSugest"></el-autocomplete>
              </el-form-item> -->
              <el-form-item label="验证码" prop="checkNumber" title="验证码会发送绑定的邮箱中,请注意查收">
                <el-input type="text" v-model="ruleForm2.checkNumber" auto-complete="off" class="check-number" :clearable="true"></el-input>
                <span v-if="!ruleForm2.sended" class="check-number-btn color-blue cursor" @click="getCheckNumber">获取验证码</span>
                <span v-else class="check-number-btn get-again">({{ruleForm2.time}}s)再次获取</span>
              </el-form-item>
              <el-form-item>
                <el-button @click="list.active--">上一步</el-button>
                <el-button type="primary" @click="submitForm('ruleForm2')">确定</el-button>
              </el-form-item>
            </el-form>
          </div>

          <!-- 重设密码 -->
          <div v-show="list.active==2">
            <el-form :model="ruleForm3" status-icon :rules="rules3" ref="ruleForm3" label-width="100px" class="demo-ruleForm">
              <el-form-item label="新密码" prop="newPassword">
                <el-input type="password" v-model="ruleForm3.newPassword"></el-input>
              </el-form-item>
              <el-form-item label="确认密码" prop="surePassword">
                <el-input type="password" v-model="ruleForm3.surePassword"></el-input>
              </el-form-item>

              <el-form-item>
                <el-button @click="list.active--">上一步</el-button>
                <el-button type="primary" @click="submitForm('ruleForm3')">确定</el-button>
              </el-form-item>
            </el-form>
          </div>
        </div>
      </div>
    </div>
    <div class="foot-box pa">
      <CopyRight></CopyRight>
    </div>
  </div>
</template>

<script>
import headP from "../common/head.vue";
import CopyRight from "../common/footer.vue";
export default {
  data() {
    return {
      list: {
        active: 0
      },
      ruleForm1: {
        dep: "",
        name: ""
      },
      rules1: {
        dep: [{ required: true, trigger: "change", message: "部门不能为空" }],
        name: [{ required: true, trigger: "change", message: "姓名不能为空" }]
      },

      ruleForm2:{
        checkNumber:"",
        email:"",
        getEmailTime:60,
        time:60,//还剩余多少时间
        sended:false,
        timeSet:null,
      },
       rules2: {
        checkNumber: [{ required: true, trigger: "change", message: "验证码不能为空" }],
      },

      ruleForm3:{
        newPassword:"",
        surePassword:"",
      },
       rules3: {
        newPassword: [{ required: true, trigger: "change", message: "新密码不能为空" }],
        surePassword: [{ required: true, trigger: "change", message: "确认密码不能为空" }],
      },
      tableData:{
        departments:[],
        username:[],
      },
      urls:{
        department:"login/department",
        staff:"login/staff",
        checkEmail:"login/sendMessage",//邮箱验证码
        check:"login/checkVerity",
        resetP:"login/forgetPassword",
      }
    };
  },
  methods: {
    submitForm(formName) {
      var array=["ruleForm1","ruleForm2","ruleForm3"]
        this.$refs[formName].validate(valid => {
          if (valid) {
            if(formName=="ruleForm1"){
              this.list.active++
              setTimeout(()=>{
                this.$refs[array[this.list.active]].resetFields();
              },10)
            }else if(formName=="ruleForm2"){
              this.apiPost(this.urls.check,{id:this.ruleForm1.name,verity:this.ruleForm2.checkNumber}).then(res=>{
                _g.toMessage(this,res.error?"error":"success",res.msg)
                if(!res.error){
                  this.list.active++
                  setTimeout(()=>{
                    this.$refs[array[this.list.active]].resetFields();
                  },10)
                }
              })
            }else if(formName=="ruleForm3"){
              var params={
                id:this.ruleForm1.name,
                verity:this.ruleForm2.checkNumber,
                password:this.ruleForm3.newPassword,
                qrpassword:this.ruleForm3.surePassword,
              }
              this.apiPost(this.urls.resetP,params).then(res=>{
                _g.toMessage(this,res.error?"error":"success",res.msg)
                if(!res.error){
                  this.$router.push("/login")
                }
              })
            }
            
            
            
          } else {
            return false;
          }
        });
    },

    changeThis(str,val){
      if(str=="dep"&&val!="crew"){
        this.apiPost(this.urls.staff,{department:val}).then(res=>{
          this.tableData.username=res
          if(res.length==0){
            _g.toMessage(this,"warning","没有数据")
          }
        })
      }
    },
    emailSugest(queryString, cb){
      var results=[]
      var email=["@qq.com","@163.com"]
      if(/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/.test(queryString)){
        cb(results);
      }else if(queryString.indexOf("@")>-1){
        email.forEach(ele=>{
          results.push({
            value:queryString.slice(0,queryString.indexOf("@"))+ele
          })
        })
        cb(results);
      }else{
        cb(results);
      }
    },

    getCheckNumber(){
      this.apiPost(this.urls.checkEmail,{id:this.ruleForm1.name}).then(res=>{
        _g.toMessage(this,res.error?"error":"success",res.msg)
        if(!res.error){
          this.ruleForm2.sended=true
          this.ruleForm2.time=this.ruleForm2.getEmailTime
          this.ruleForm2.timeSet=setInterval(()=>{
            if(this.ruleForm2.time==0){
              this.ruleForm2.sended=false
              clearInterval(this.ruleForm2.timeSet)
            }else{
              this.ruleForm2.time--
            }
          },1000)
        }
      })
    }
  },
  components: {
    headP,
    CopyRight
  },
  created() {
    if(false){
      
    }else{
      this.apiPost(this.urls.department).then(res=>{
        res.data.forEach(ele => {
          if(ele!="crew")this.tableData.departments.push(ele)
        });
      })
    }
  },
  beforeDestroy(){
    if(this.ruleForm2.timeSet)clearInterval(this.ruleForm2.timeSet)
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.forget-password {
  height: 100%;
}
.foot-box {
  bottom: 0px;
  height: 100px;
  width: 100%;
}
.title {
  height: 50px;
  color: #999;
  line-height: 40px;
  width: 1100px;
  margin: 0 auto;
  left: 50%;
  text-align: center;
  transform: translateX(-50%);
}
.title span.password {
  display: inline-block;
  padding-left: 10px;
  border-left: 4px solid rgba(0, 153, 255, 1);
  line-height: 17px;
  margin: 16px 0 0 20px;
}
.my-btn{
  margin-right: 20px;
  margin-top: 9px;
  line-height: 20px;
  text-align: center;
}
.title-box {
  background-color: rgba(243, 243, 243, 1);
  height: 50px;
}
.content {
  width: 1100px;
  margin: 40px auto;

}
.write-box {
  width: 350px;
  margin: 40px auto;
}
input {
  height: 50px;
}
.color{
  width: 100%;
}
.check-number{
  width: 60%;
}
.check-number-btn{
  display: inline-block;
  height: 30px;
  position: relative;
  top: 2px;
  border-radius: 3px;
  padding: 0 3px;
}
.check-number-btn:hover{
  text-decoration: underline;
}
.get-again{
  background-color: #e4e4e4;
  color: #fff;
}
</style>
