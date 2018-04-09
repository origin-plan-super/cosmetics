<template>
  <div class="add-user">
    <el-form ref="form" :rules="rules" size="mini" :model="add" label-width="150px">

      <el-form-item label="用户手机号" prop="user_id">
        <el-input v-model="add.user_id" placeholder="用户手机号，用户登录。"></el-input>
      </el-form-item>

      <el-form-item label="用户名" prop="user_name">
        <el-input v-model="add.user_name"></el-input>
      </el-form-item>

      <el-form-item label="用户类型" prop="user_type">

        <el-radio v-model="add.user_type" :label="0">普通用户</el-radio>
        <el-radio v-model="add.user_type" :label="1">分销商</el-radio>

      </el-form-item>

      <el-form-item>
        <el-button type="primary" @click="submitForm()">添加</el-button>
      </el-form-item>

    </el-form>

  </div>
</template>
<script>
export default {
  props: {},
  data() {
    return {
      add: {
        user_name: "",
        user_id: "",
        user_type: ""
      },
      rules: {
        user_id: [
          { required: true, message: "请输入用户手机号", trigger: "blur" },
          { max: 32, message: "长度不能超过 32 个字符", trigger: "blur" }
        ],
        user_name: [
          { required: true, message: "请输入用户名", trigger: "blur" },
          { max: 32, message: "长度不能超过 32 个字符", trigger: "blur" }
        ],
        user_type: [
          { required: true, message: "请选择用户类型", trigger: "blur" }
        ]
      }
    };
  },
  methods: {
    submitForm() {
      this.$refs["form"].validate(valid => {
        if (valid) {
          var add = this.add;

          this.$post("user/add", { add: add }, res => {
            if (res.res >= 1) {
              //添加成功
              this.$success("添加成功！");
              this.$emit("on-success");
              return;
            }

            if (res.res == -3) {
              //用户的id冲突
              this.$warn("用户id已存在！请修改后重试！");
              return;
            }

            this.$error("添加失败！请重试");
          });
        } else {
          return false;
        }
      });
    },
    resetForm() {
      this.$refs["form"].resetFields();
    }
  },
  computed: {},
  mounted() {
    this.add.user_type = 0;
  },
  destroyed() {},
  components: {},
  watch: {}
};
</script>
<style lang="scss" scoped>
@import "addUser.scss";
</style>
<style>

</style>