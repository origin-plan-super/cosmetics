<template>
  <div id="edit">

    <el-form :model="save" :rules="rules" size="small" ref="form" label-width="100px" style="width:70%">
      <el-form-item label="标题" prop="title">
        <el-input type="text" :maxlength="255" placeholder="请输入标题" v-model="save.title"></el-input>
      </el-form-item>

      <el-form-item label="内容" prop="msg">
        <el-input type="textarea" :maxlength="255" placeholder="请输入内容" :autosize="{ minRows: 5}" v-model="save.msg"></el-input>
      </el-form-item>
      <el-form-item label="类型" prop="name">

        <el-select v-model="save.type" placeholder="请选择类型">

          <el-option label="随享季公告" value="1"></el-option>
          <el-option label="活动消息" value="2"></el-option>
          <el-option label="随享季助手" value="3"></el-option>
          <el-option label="交易物流" value="4"></el-option>

        </el-select>

      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="submitForm">保存</el-button>
      </el-form-item>
    </el-form>

  </div>
</template>
<script>
export default {
  name: "edit",
  props: {},
  data() {
    return {
      msg_id: "",
      save: {
        title: "",
        msg: "",
        type: "1"
      },
      rules: {
        title: [{ required: true, message: "请输入标题", trigger: "blur" }],
        msg: [{ required: true, message: "请输入内容", trigger: "blur" }]
      }
    };
  },
  methods: {
    submitForm() {
      this.$refs["form"].validate(valid => {
        if (valid) {
          this.$post(
            "msg/save",
            {
              where: { msg_id: this.msg_id },
              save: this.save
            },
            res => {
              if (res.res >= 1) {
                this.$success("发布成功");
                setTimeout(() => {
                  this.$router.push("/msg/list");
                }, 100);
                return;
              }
              this.$error("发布失败！请重试~");
            }
          );
        } else {
          return false;
        }
      });
    },
    update() {
      this.$get(
        "msg/get",
        {
          where: { msg_id: this.msg_id }
        },
        res => {
          this.save = res.msg;
        }
      );
    }
  },
  computed: {},
  //过滤器
  filters: {},
  mounted() {
    this.msg_id = this.$route.query.msg_id;
    this.update();
    this.$nextTick(() => {});
  },
  //Vue 实例销毁后调用
  destroyed() {},
  watch: {},
  components: {}
};
</script>
<style lang="scss" scoped>
@import "edit.scss";
</style>