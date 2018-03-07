<template>
  <div id="info" class="info">

    <template v-if="info.isShow">
      <el-alert :title="info.text" :type="info.type" :closable="info.close" :show-icon="info.icon">
      </el-alert>
    </template>

    <el-card style="margin:20px 0">
      <el-button @click="update()" size="small" icon="el-icon-refresh" :loading="refreshBtnLoad">刷新</el-button>
      <el-form v-if="saveAdmin" label-width="80px" ref="saveForm" size="small" style="width:300px">
        <el-form-item label="登录账户">
          <span class="text-info">{{saveAdmin.admin_id}}</span>
        </el-form-item>
        <el-form-item label="修改密码" prop="admin_pwd">
          <el-input v-model="saveAdmin.admin_pwd"></el-input>
        </el-form-item>
        <el-form-item label="确定密码" prop="admin_pwd2">
          <el-input v-model="saveAdmin.admin_pwd2"></el-input>
        </el-form-item>
        <el-form-item label="管理名" prop="admin_name">
          <el-input v-model="saveAdmin.admin_name"></el-input>
        </el-form-item>
        <el-form-item>

          <el-button type="primary" @click="save()">保存</el-button>
          <el-button @click="resetForm()">重置</el-button>

        </el-form-item>
      </el-form>

    </el-card>

  </div>
</template>
<script>
export default {
  name: "info",
  data() {
    return {
      admin_id: "",
      refreshBtnLoad: false,
      info: {
        type: "",
        text: "",
        isShow: false,
        close: false,
        icon: false
      },
      saveAdmin: {}
    };
  },
  methods: {
    update() {
      this.refreshBtnLoad = true;
      this.$get("admin/get", { where: { admin_id: this.admin_id } }, res => {
        this.refreshBtnLoad = false;
        console.log(res);
        if (res.res >= 1) {
          // this.admin_id;
          this.saveAdmin = res.msg;
        }
        if (res.res < 0) {
          //管理不存在

          this.info.text = `管理信息获取失败，请重试！ 管理号：[ ${
            this.admin_id
          } ]`;
          this.info.isShow = true;
          this.info.type = "error";

          this.$message({
            showClose: true,
            type: "error",
            message: `管理信息获取失败，请重试！`
          });
        }
      });
    },
    save() {
      var save = {};
      if (this.saveAdmin.admin_pwd != "") {
        if (this.saveAdmin.admin_pwd !== this.saveAdmin.admin_pwd2) {
          this.$message({ type: "error", message: "两次输入的密码不一致！" });
          return false;
        } else {
          save = this.saveAdmin;
        }
      } else {
        save = {
          admin_name: this.saveAdmin.admin_name
        };
      }
      this.$post(
        this.$serverAdmin + "Admin/save",
        { where: { admin_id: this.saveAdmin.admin_id }, save: save },
        res => {
          if (res.res == 1) {
            this.$message({
              message: "保存成功",
              type: "success"
            });
            return;
          }
          this.$message({
            message: "保存失败！请重试",
            type: "success"
          });
        }
      );
    },
    resetForm() {
      this.$refs["saveForm"].resetFields();
    }
  },
  mounted() {
    if (!this.$route.params["admin_id"]) {
      if (!localStorage.edit_admin_id) {
        this.$router.go(-1);
        return;
      } else {
        this.admin_id = localStorage.edit_admin_id;
      }
    } else {
      this.admin_id = this.$route.params["admin_id"];
    }
    localStorage.edit_admin_id = this.admin_id;
    this.update();
  },
  components: {},
  watch: {}
};
</script>
<style lang="scss" scoped>
@import "info.scss";
</style>
<style lang="scss">
.table-expand {
  font-size: 0;
}

.table-expand label {
  width: 90px;
  color: #99a9bf;
}

.table-expand .el-form-item {
  margin-right: 0;
  margin-bottom: 0;
  width: 50%;
}
</style>
