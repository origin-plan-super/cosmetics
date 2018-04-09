<template>
  <div id="coupon">
    <template>
      <router-view></router-view>
    </template>
  </div>

</template>

<script>
export default {
  name: "coupon",
  data() {
    return {};
  },
  methods: {
    update() {}
  },
  mounted: function() {
    this.update();
  },
  components: {},
  watch: {}
};
</script>

<style lang="scss" scoped>
@import "coupon.scss";
</style>