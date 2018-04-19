<template>
  <div class="class-select">

    <el-select v-loading="isLoad" v-model="select" filterable default-first-option placeholder="请选择商品分类">
      <el-option-group v-for="group in list" :key="group.class_title" :label="group.class_title">
        <el-option v-for="item in group.children" :key="item.class_title" :label="item.class_title" :value="item.class_id">
        </el-option>
      </el-option-group>
    </el-select>
  </div>
</template>
<script src="./class-select.js"></script>
<style lang="scss" scoped>
@import "class-select.scss";
</style>