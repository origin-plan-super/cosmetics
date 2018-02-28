<template>
  <div id="order">

    <template>
      <router-view></router-view>
    </template>
  </div>

</template>

<script>
export default {
  name: "order",
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
@import "order.scss";
</style>