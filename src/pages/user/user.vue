<template>
    <div id="user">
        <template>
            <router-view></router-view>
        </template>
    </div>

</template>

<script>
export default {
  name: "user",
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
@import "user.scss";
</style>