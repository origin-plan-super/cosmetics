<template>
  <div id="feedbackInfo">
    <div class="frame">
      <template v-if="info.isShow">
        <el-alert :title="info.text" :type="info.type" :closable="info.close" :show-icon="info.icon">
        </el-alert>
      </template>
    </div>

    <div class="frame">

      <el-card>

        <load :loading="refreshBtnLoad" title="加载中"></load>

        <el-button type="text" icon="el-icon-back" @click="$router.go(-1)" size="mini"></el-button>
        <el-button type="text" icon="el-icon-refresh" :loading="refreshBtnLoad" @click="update()" size="mini"></el-button>

        <div class="frame" style="width:50%">

          <el-form ref="form" v-if="feedback!=null" label-width="100px">

            <el-form-item label="反馈号">
              <span>{{feedback.feedback_id}}</span>
            </el-form-item>

            <el-form-item label="状态">
              <span v-if="feedback.is_ok == 0" class="text-warning">
                <i class="el-icon-warning"></i>
              </span>
              <span v-if="feedback.is_ok == 1" class="text-success">
                <i class="el-icon-success"></i>
              </span>

              <el-button type="text" size="mini" @click="setOk(feedback)">处理</el-button>

            </el-form-item>

            <el-form-item label="类型">

              <template v-if="type[feedback.feedback_type]">

                <span :class="type[feedback.feedback_type].type">
                  <i style="width:20px;display: inline-block;" :class="type[feedback.feedback_type].icon" v-if="type[feedback.feedback_type].icon"></i>
                  {{type[feedback.feedback_type].text}}
                </span>

              </template>
              <template v-else>
                <span class="text-error">
                  <i style="width:20px;display: inline-block;" class="el-icon-error"></i>
                  未知类型：{{feedback.feedback_type}}
                </span>
              </template>

            </el-form-item>

            <el-form-item label="反馈详情">
              <div class="feedback-info">
                {{feedback.feedback_info}}
              </div>
            </el-form-item>

          </el-form>

        </div>
      </el-card>

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
      testValue: "",
      //反馈类型
      // 0 : bug 反馈
      // 1 : 意见反馈
      // 2 : UI问题
      // 3 ：其他
      type: [
        {
          icon: "fa fa-bug",
          value: "bug 反馈",
          text: "bug 反馈",
          type: "text-danger"
        },
        {
          icon: "el-icon-service",
          value: "意见反馈",
          text: "意见反馈",
          type: "text-warning"
        },
        {
          icon: "fa fa-window-maximize",
          value: "UI问题",
          text: "UI问题",
          type: "text-primary"
        },
        {
          icon: "",
          value: "其他",
          text: "其他",
          type: "text-info"
        }
      ]
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
    },
    setOk(item) {
      var is_ok = item.is_ok == 1 ? 0 : 1;
      this.$post(
        "feedback/save",
        {
          where: { feedback_id: item.feedback_id },
          save: { is_ok: is_ok }
        },
        res => {
          if (res.res >= 1) {
            this.$success("操作成功！");
            item.is_ok = is_ok;
            return;
          }
          this.$error("操作失败！请重试");
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