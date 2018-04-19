<template>
    <div class="select-supplier">

        <el-select :loading="isLoading" v-model="select" placeholder="请选择">
            <el-option v-for="item in list" :key="item.supplier_id" :label="item.supplier_name" :value="item.supplier_id">
            </el-option>
        </el-select>

    </div>
</template>
<script src="./select-supplier.js"></script>
<style lang="scss" scoped>
@import "select-supplier.scss";
</style>