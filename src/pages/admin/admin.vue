<template>
  <div id="admin">

    <template>
      <router-view></router-view>
    </template>
  </div>

</template>

<script>
export default {
  name: "admin",
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
@import "admin.scss";
</style>