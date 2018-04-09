<template>
  <div id="HelpAdd">
    <el-form :model="add" :rules="rules" ref="form" label-width="100px" style="width:90%" size="small">
      <el-form-item label="帮助标题" prop="help_title">
        <el-input type="text" :maxlength="255" placeholder="请输入帮助标题" v-model="add.help_title"></el-input>
      </el-form-item>

      <el-form-item label="帮助类型" prop="name">

        <el-select v-model="add.help_type" placeholder="请选择类型">

          <el-option label="新手指引" value="0"></el-option>
          <el-option label="活动" value="1"></el-option>
          <el-option label="物流配送" value="2"></el-option>
          <el-option label="售后问题" value="3"></el-option>
          <el-option label="订单问题" value="4"></el-option>

        </el-select>

      </el-form-item>
      <el-form-item label="帮助内容">
        <editor v-model="add.help_content" ref="editor" style="width:100%"></editor>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="submitForm">发布</el-button>
      </el-form-item>

    </el-form>
  </div>
</template>
<script>
import editor from "../../../component/editor/editor.vue";

export default {
  name: "HelpAdd",
  props: {},
  data() {
    return {
      add: {
        help_title: "",
        help_content: "",
        help_type: ""
      },
      rules: {
        help_title: [
          { required: true, message: "请输入帮助标题", trigger: "blur" }
        ],
        help_content: [
          { required: true, message: "请输入帮助信息", trigger: "blur" }
        ]
      }
    };
  },
  methods: {
    submitForm() {
      this.add.help_content = this.$refs["editor"].getContent();

      this.$refs["form"].validate(valid => {
        if (valid) {
          this.$post(
            "help/add",
            {
              add: this.add
            },
            res => {
              if (res.res >= 1) {
                this.$success("发布成功");
                setTimeout(() => {
                  // this.$router.push("/help/list");
                }, 300);
                return;
              }
              this.$error("发布失败！请重试~");
            }
          );
        } else {
          return false;
        }
      });
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
  watch: {},
  components: { editor }
};
</script>
<style lang="scss" scoped>
@import "add.scss";
</style>