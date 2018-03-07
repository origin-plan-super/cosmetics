<template>
  <div id="feedbackInfo">
    <div class="frame" style="width:50%">

      <el-form ref="form" v-if="feedback!=null" label-width="80px">

        <el-form-item label="反馈id">
          <span>{{feedback.feedback_id}}</span>
        </el-form-item>

        <el-form-item label="反馈详情">
          <div class="feedback-info">
            {{feedback.feedback_info}}
          </div>
        </el-form-item>

      </el-form>

    </div>

  </div>
</template>
<script>
export default {
  name: "feedbackInfo",
  props: {},
  data() {
    return {
      feedback_id: "",
      feedback: null,
      refreshBtnLoad: false,
      info: {
        type: "",
        text: "",
        isShow: false,
        close: false,
        icon: false
      },
      testValue: ""
    };
  },
  methods: {
    update() {
      this.refreshBtnLoad = true;
      this.$get(
        "feedback/get",
        { where: { feedback_id: this.feedback_id } },
        res => {
          this.refreshBtnLoad = false;
          if (res.res >= 1) {
            this.feedback = res.msg;
          }
          if (res.res < 0) {
            this.info.text = `反馈信息获取失败，请重试！ 反馈ID：[ ${
              this.feedback_id
            } ]`;
            this.info.isShow = true;
            this.info.type = "error";

            this.$message({
              showClose: true,
              type: "error",
              message: `反馈信息获取失败，请重试！`
            });
          }
        }
      );
    }
  },
  computed: {},
  mounted() {
    if (!this.$route.params["feedback_id"]) {
      if (!localStorage.feedback_id) {
        this.$router.go(-1);
        return;
      } else {
        this.feedback_id = localStorage.feedback_id;
      }
    } else {
      this.feedback_id = this.$route.params["feedback_id"];
    }
    localStorage.feedback_id = this.feedback_id;
    this.update();
  },
  components: {},
  watch: {}
};
</script>
<style lang="scss" scoped>
@import "info.scss";
</style>
<style>

</style>