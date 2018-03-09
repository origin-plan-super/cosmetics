<template>
    <transition name="el-fade-in">
        <div class="load" v-if="loading">
            <div class="load-box">
                <i class="icon el-icon-loading"></i>
                <div class="load-title">{{title}}{{text}}</div>
            </div>
        </div>
    </transition>

</template>
<script>
export default {
  name: "load",
  props: {
    loading: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: ""
    }
  },
  data() {
    return {
      interval: null,
      text: "."
    };
  },
  methods: {},
  computed: {},
  mounted() {},
  destroyed() {
    clearInterval(this.interval);
  },
  components: {},
  watch: {
    loading(val) {
      clearInterval(this.interval);
      if (val) {
        this.text = ".";
        var count = 2;
        this.interval = setInterval(() => {
          this.text = "...".substring(0, count);
          count++;
          if (count > 3) {
            count = 0;
          }
        }, 500);
      }
    }
  }
};
</script>
<style lang="scss" scoped>
@import "load.scss";
</style>
<style>

</style>