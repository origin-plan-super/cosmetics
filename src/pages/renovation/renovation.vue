<template>
    <div id="renovation">
        <template>
            <router-view></router-view>
        </template>
    </div>

</template>

<script>
export default {
  name: "renovation",
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
@import "renovation.scss";
</style>