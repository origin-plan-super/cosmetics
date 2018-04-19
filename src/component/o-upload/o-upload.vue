<template>
  <div class="o-upload">
    <el-upload :action="$serverUpFile" ref="upload" :show-file-list="false" :data="parame" multiple :on-error="error" :on-success="success">
      <slot></slot>
    </el-upload>
  </div>
</template>
<script>
export default {
  name: "o-upload",
  props: {
    parame: {
      type: Object,
      default() {
        return {
          token: localStorage.token,
          admin_id: localStorage.admin_id,
          src: "",
          del_src: ""
        };
      }
    },
    src: {
      type: String,
      default: ""
    },
    delSrc: {
      type: String,
      default: ""
    },
    value: {
      type: String,
      default: ""
    }
  },
  data() {
    return {};
  },
  methods: {
    success(res) {
      this.$emit("input", res.msg.src);
    },
    error() {
      this.$error("文件上传失败！");
    }
  },
  computed: {},
  //过滤器
  filters: {},
  mounted() {
    this.$nextTick(() => {
      this.parame.src = this.src;
      this.parame.del_src = this.delSrc;
    });
  },
  //Vue 实例销毁后调用
  destroyed() {},
  watch: {},
  components: {}
};
</script>
<style lang="scss" scoped>
@import "o-upload.scss";
</style>