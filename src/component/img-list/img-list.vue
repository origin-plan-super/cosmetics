<template>
  <div class="img-list">
    <draggable v-model="list" :options="{group:'img_list'}" @end="dragEnd(list)">
      <div class="img-item" v-for="(item,i) in value" :key="i">
        <div class="del" @click="remove(item,i,value)" title="删除">
          <i class="el-icon-close"></i>
        </div>
        <o-img :src="item.src"></o-img>
      </div>
    </draggable>
  </div>
</template>
<script>
import draggable from "vuedraggable";

export default {
  name: "img-list",
  props: {
    value: {
      type: Array,
      default: []
    }
  },
  data() {
    return {
      list: []
    };
  },
  methods: {
    //拖拽完成的时候
    dragEnd(list) {
      this.$emit("input", list);
      this.$emit("drag-end", list);
    },
    remove(item, i, list) {
      this.$emit("on-remove", item, i, list, () => {});
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
  watch: {
    value(val) {
      this.list = val.slice();
    }
  },
  components: { draggable }
};
</script>
<style lang="scss" scoped>
@import "img-list.scss";
</style>