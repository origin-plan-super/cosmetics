<template>
    <div id="feedback">

        <template>
            <router-view></router-view>
        </template>
    </div>

</template>

<script>
export default {
  name: "feedback",
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
@import "feedback.scss";
</style>