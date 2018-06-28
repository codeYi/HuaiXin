<template>
  <div class="settings pr">
    <p class="title box-size">{{$store.state.theader.label}}</p>
    <div class="edit-box box-size pa">
      <div class="edit">
        <el-form label-position="right" ref="edit" :data="userInfo" :rules="rules" label-width="80px" :model="userInfo">
          <!-- <el-form-item label="邮箱" prop="email">
            <el-select v-model="userInfo.email" placeholder="请选择">
              <el-option v-for="item in emialData" :key="item" :label="item" :value="item">
              </el-option>
            </el-select>
          </el-form-item> -->
          <el-form-item label="旧密码" prop="password">
            <el-input type="password" v-model="userInfo.password"></el-input>
          </el-form-item>
          <el-form-item label="新密码" prop="newPassword">
            <el-input type="password" v-model="userInfo.newPassword"></el-input>
          </el-form-item>
          <el-form-item label="确认密码" prop="qrPassword">
            <el-input type="password" v-model="userInfo.qrPassword" ref="surepassword"></el-input>
          </el-form-item>
        </el-form>
        <el-button type="primary" class="setting-sub" @click="submitForm('edit')">提交</el-button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      userInfo: {
        // email: "",
        password: "", //旧
        newPassword: "", //新
        qrPassword: "" //确认
      },
      rules: {
        email: [{ required: true, message: "请选择邮箱", trigger: "change" }],
        password: [
          { required: true, message: "请输入旧密码", trigger: "blur" }
        ],
        newPassword: [
          { required: true, message: "请输入新密码", trigger: "blur" }
        ],
        qrPassword: [
          { required: true, message: "请再次输入新密码", trigger: "blur" }
        ]
      },
      emialData: [] //所有的邮箱
    };
  },
  methods: {
    // 提交设置
    submitForm(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          if(this.userInfo.newPassword===this.userInfo.qrPassword&&(this.userInfo.newPassword.length>5&&this.userInfo.qrPassword.length>5)){
            this.apiPost("login/changePassword",this.userInfo).then(res=>{
              _g.toMessage(this,res.error?"error":"success",res.msg)
              if(!res.error){
                localStorage.setItem("my_loginTime","");
                localStorage.setItem("my_userInfo","");
                setInterval(()=>{this.$router.push("/login")},300)
              }
            })
          }else{
            this.userInfo.qrPassword=""
            _g.toMessage(this,"warning","两次密码不一致或密码长度短于6位")
            this.$refs.surepassword.focus();
          }
        } else {
          return false;
        }
      });
    }
  },
  components: {},
  created() {}
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.settings {
  height: 100%;
}
.title {
  padding: 0;
  text-align: left;
  height: 45px;
  line-height: 45px;
  text-indent: 20px;
  border: 1px solid #e4e4e4;
}
.edit-box {
  border: 1px solid #e4e4e4;
  border-top: none;
  text-align: center;
  background-color: #fff;
  top: 45px;
  bottom: 0;
  width: 100%;
}
.edit {
  display: inline-block;
  width: 300px;
  margin: 60px auto;
}
.setting-sub {
  margin-top: 70px;
  font-size: 13px;
  width: 80px;
  height: 35px;
}
</style>
