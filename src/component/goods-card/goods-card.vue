<template>
    <div class="goods-card">
        <div class="goods-img">
            <img :src="$getUrl(img)">
        </div>

        <div class="goods-title">
            {{title}}
        </div>
        <div class="goods-info">
            {{info}}
        </div>

        <div class="footer">
            <slot></slot>
        </div>

    </div>
</template>
<script>
export default {
  name: "goods-card",
  props: {
    title: {
      type: String,
      default: ""
    },
    img: {
      type: String,
      default: ""
    },
    info: {
      type: String,
      default: ""
    }
  },
  data() {
    return {};
  },
  methods: {},
  computed: {},
  //过滤器
  filters: {},
  mounted() {
    this.$nextTick(() => {});
  },
  //Vue 实例销毁后调用
  destroyed() {},
  watch: {},
  components: {}
};
</script>
<style lang="scss" scoped>
@import "goods-card.scss";
</style>