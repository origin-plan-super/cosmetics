<template>

  <div id="login">
    <div class="mask"></div>
    <div class="form">
      <div class="form-list">
        <div class="login-head">
          <div class="login-title">
            后台登录
            <p class="text-info">
              <span class="app-name"> {{app.name}}</span>
            </p>
          </div>
        </div>
        <el-form :model="login"
                 :rules="rules"
                 ref="ruleForm"
                 class="demo-ruleForm">
          <el-form-item prop="admin_id">
            <el-input v-model="login.admin_id"
                      placeholder='请输入账户'></el-input>
          </el-form-item>
          <el-form-item prop="admin_pwd">
            <el-input v-model="login.admin_pwd"
                      type="password"
                      placeholder='请输入密码'></el-input>
          </el-form-item>

          <el-row :gutter='20'>
            <el-col :span='14'>
              <el-form-item prop="admin_code">
                <el-input v-model="login.admin_code"
                          placeholder='验证码'></el-input>
              </el-form-item>
            </el-col>
            <el-col :span='10'>
              <img :src="code"
                   @click='getCode()'
                   class="code"
                   alt="">
            </el-col>
          </el-row>

          <el-form-item>
            <el-button :type="isLoginSuccess?'success':'primary'"
                       :loading="isLoginLoad"
                       @click='submitForm()'>{{btnTitle}}</el-button>
          </el-form-item>

        </el-form>
        <el-alert :title="alertTitle"
                  :closable='false'
                  :type="alertType">
        </el-alert>
      </div>
    </div>
  </div>

</template>

<script >
export default {
  name: "login",
  data() {
    return {
      app: {
        name: "化妆品商城"
      },
      isLoginLoad: false,
      isLoginSuccess: false,
      btnTitle: "登录",
      alertType: "",
      alertTitle: "",
      code: "",
      login: {
        admin_id: "",
        admin_pwd: "",
        admin_code: ""
      },
      rules: {
        admin_id: [
          { required: true, message: "请输入账户！", trigger: "change" }
        ],
        admin_pwd: [
          { required: true, message: "请输入密码！", trigger: "change" },
          {
            min: 3,
            max: 16,
            message: "长度在 3 到 16 个字符",
            trigger: "change"
          }
        ],
        admin_code: [
          { required: true, message: "请输入验证码！", trigger: "change" },
          {
            min: 4,
            max: 4,
            message: "长度为 4 个字符！",
            trigger: "change"
          }
        ]
      }
    };
  },
  methods: {
    submitForm() {
      this.isLoginLoad = true;
      this.$refs["ruleForm"].validate(valid => {
        if (valid) {
          this.$post("index/login", this.login, res => {
            this.isLoginLoad = false;
            if (res.res == 1) {
              localStorage.token = res.token;
              localStorage.admin_id = this.login.admin_id;
              this.isLoginLoad = true;
              this.$get("admin/getUserInfo", {}, res => {
                console.log(res);
                this.isLoginLoad = false;
                if (res.res >= 1) {
                  //成功
                  this.alertType = "success";
                  this.alertTitle = "登录成功！正在为您跳转~";
                  this.isLoginSuccess = true;
                  this.btnTitle = "登录成功~";

                  localStorage.adminUserInfo = JSON.stringify(res.msg);

                  setTimeout(() => {
                    this.$router.push("/index");
                  }, 500);
                }
              });

              return;
            }

            if (res.res == -1) {
              // 账户和密码不正确
              this.alertType = "error";
              this.alertTitle = "账户或密码不正确";
            }

            if (res.res == -2) {
              //验证码不正确
              this.alertType = "error";
              this.alertTitle = "验证码不正确";
            }
            this.getCode();
          });
        } else {
          this.isLoginLoad = false;
          return false;
        }
      });
    },
    getCode: function() {
      this.code = this.$serverAdmin + "index/getCode/" + Math.random();
    }
  },
  mounted: function() {
    this.$nextTick(() => {
      this.getCode();
    });
  }
};
</script>


<style lang='scss' scoped>
@import "login.scss";
</style>

