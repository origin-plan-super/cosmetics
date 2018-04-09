<template>
  <div id="couponAdd">
    <div style="width:500px">

      <el-form :model="add" :rules="rules" size="small" ref="ruleForm" label-width="150px" class="demo-ruleForm">

        <el-form-item label="优惠券名称" prop="name">
          <el-input v-model="add.name"></el-input>
        </el-form-item>

        <el-form-item label="面值" prop="denominations">
          <el-input v-model.number="add.denominations"></el-input>
        </el-form-item>

        <el-form-item label="有效时间" prop="start_at">
          <el-date-picker v-model="time" value-format="timestamp" type="daterange" range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期">
          </el-date-picker>
        </el-form-item>

        <el-form-item>
          <el-button type="primary" @click="submitForm()">立即创建</el-button>
          <el-button @click="resetForm()">重置</el-button>
        </el-form-item>
      </el-form>

    </div>

  </div>
</template>
<script>
export default {
  name: "couponAdd",
  props: {},
  data() {
    return {
      time: "",
      add: {
        name: "",
        denominations: "",
        origin_condition: "",
        start_at: "",
        end_at: "",
        value: ""
      },
      rules: {
        name: [
          { required: true, message: "请输入优惠券名称", trigger: "blur" },
          { max: 255, message: "长度在 0 到 255 个字符", trigger: "blur" }
        ],
        denominations: [
          { required: true, message: "请输入面值", trigger: "blur" },
          { type: "number", message: "面值必须为数字值", trigger: "blur" }
        ],
        start_at: [
          { required: true, message: "请选择使用期限", trigger: "blur" }
        ]
      }
    };
  },
  methods: {
    submitForm() {
      this.$refs["ruleForm"].validate(valid => {
        if (valid) {
          this.$post(
            "coupon/add",
            {
              add: this.add
            },
            res => {
              if (res.res >= 1) {
                this.$success("添加成功！");
                return;
              }
              this.$error("添加失败！");
            }
          );
        } else {
          console.log("error submit!!");
          return false;
        }
      });
    },
    resetForm() {
      this.$refs["ruleForm"].resetFields();
    }
  },
  computed: {},
  //过滤器
  filters: {},
  mounted() {
    this.$nextTick(() => {});
  },
  //Vue 实例销毁后调用
  destroyed() {},
  watch: {
    time(val) {
      this.add.start_at = val[0] / 1000;
      this.add.end_at = val[1] / 1000;
    },
    "add.denominations"(val) {
      this.add.value = val;
    }
  },
  components: {}
};
</script>
<style lang="scss" scoped>
@import "add.scss";
</style>