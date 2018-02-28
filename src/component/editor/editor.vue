<template>
  <div class="editor">
    <script id="editor" type="text/plain"></script>
  </div>
</template>


<script>
// const UE = require("UE"); // eslint-disable-line
import "../../../static/UE/ueditor.config.js";
import "../../../static/UE/ueditor.all.min.js";
import "../../../static/UE/lang/zh-cn/zh-cn.js";
import "../../../static/UE/ueditor.parse.min.js";
// import "../../../static/UE/themes/default/css/ueditor.css";

export default {
  name: "editor",
  props: {
    value: String
  },
  data() {
    return {
      editor: null
    };
  },
  methods: {
    getContent() {
      return this.editor.getContent();
    },
    test() {
      console.log(this.getContent());
    }

    // testMsg() {
    //   const _this = this;
    //   this.defaultMsg = this.value;
    //   console.log(this.defaultMsg);
    //   this.editor.setContent(this.defaultMsg); // 确保UE加载完成后，放入内容。
    // }
  },
  computed: {},
  watch: {},
  mounted() {
    this.editor = UE.getEditor("editor", {
      BaseUrl: "",
      //这个是静态资源的路径
      UEDITOR_HOME_URL: "../../../static/UE/",
      initialFrameWidth: "100%",
      initialFrameHeight: 500

      // toolbars:[]  //编辑器里需要用的功能
    }); // 初始化UE
    this.editor.addListener("ready", () => {
      this.editor.setContent(this.value); // 确保UE加载完成后，放入内容。
    });

    this.editor.addListener("contentChange", () => {
      var value = this.editor.getContent();
      console.log(value);
      this.$emit("input", value);
    });
    this.$nextTick(() => {});
  },
  destroyed() {
    this.editor.destroy();
  }
};
</script>


<style lang="scss" scoped>
@import "editor.scss";
</style>