<template>
  <div class="switch-panel">
    <div class="switch-head">
      <slot name="head"></slot>
      <el-button class="switch-show-btn" @click="isShow=!isShow" :size="btnSize">{{isShow?showTitle:hideTitle}}</el-button>
      <slot name="head-append"></slot>
    </div>

    <div class="switch-panel-body" v-if="isShow">
      <slot></slot>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    show: {
      type: Boolean,
      default: false
    },
    hideTitle: {
      type: String,
      default: "展开"
    },
    showTitle: {
      type: String,
      default: "关闭"
    },
    btnSize: {
      type: String,
      default: "mini"
    }
  },
  data() {
    return {
      isShow: this.show
    };
  },
  methods: {},
  computed: {},
  mounted() {},
  components: {},
  watch: {
    show() {
      this.isShow = this.show;
    },
    isShow(val) {
      this.$emit("update:show", val);
    }
  }
};
</script>
<style lang="scss">
@import "switchPanel.scss";
</style>