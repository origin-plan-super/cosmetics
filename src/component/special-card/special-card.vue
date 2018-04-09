<template>
  <div :class="['special-card',size]" :style="{backgroundImage:`url(${$getUrl(bg)})`,color:bg?'#fff':'#343434'}" @click="openInfo()">
    <div class="slot">
      <slot></slot>
    </div>
    <div class="special-card-body">
      <div class="special-title">{{title}}</div>
      <div class="special-info">{{info}}</div>
    </div>
  </div>
</template>
<script>
export default {
  name: "special-card",
  props: {
    specialId: {
      type: String,
      default: ""
    },
    bg: {
      type: String,
      default: ""
    },
    title: {
      type: String,
      default: ""
    },
    info: {
      type: String,
      default: ""
    },
    size: {
      type: String,
      default: ""
    },
    open: {
      type: Boolean,
      default: true
    }
  },
  data() {
    return {};
  },
  methods: {
    openInfo() {
      if (this.open) {
        this.$router.push({
          path: "/renovation/specialEdit",
          query: {
            special_id: this.specialId
          }
        });
      } else {
        this.$emit("click");
      }
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
  components: {}
};
</script>
<style lang="scss" scoped>
@import "special-card.scss";
</style>