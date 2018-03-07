<template>
  <div class="add-user">
    <el-form ref="form" :rules="rules" size="mini" :model="add" label-width="150px">

      <el-form-item label="用户登录账号" prop="user_id">
        <el-input v-model="add.user_id"></el-input>
      </el-form-item>

      <el-form-item label="用户登录密码" prop="user_pwd">
        <el-input v-model="add.user_pwd"></el-input>
      </el-form-item>

      <el-form-item label="用户名" prop="user_name">
        <el-input v-model="add.user_name"></el-input>
      </el-form-item>

      <el-form-item label="用户类型" prop="user_type">
        <el-radio v-model="add.user_type" :label="0">普通用户</el-radio>
        <el-radio v-model="add.user_type" :label="1">代理商</el-radio>
      </el-form-item>

      <el-form-item>
        <el-button type="primary" @click="submitForm()">立即创建</el-button>
        <el-button @click="resetForm()">重置</el-button>
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
        user_pwd: "",
        user_type: ""
      },
      rules: {
        user_id: [
          { required: true, message: "请输入用户账户", trigger: "blur" },
          { max: 32, message: "长度不能超过 32 个字符", trigger: "blur" }
        ],
        user_pwd: [
          { required: true, message: "请输入用户登录密码", trigger: "blur" },
          { max: 16, message: "长度不能超过 16 个字符", trigger: "blur" }
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
          console.log(add);
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