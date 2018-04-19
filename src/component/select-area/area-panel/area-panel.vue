<template>
    <div class="area-panel">
        <div class="area-head">
            <slot name="title">{{title}}</slot>
        </div>
        <div class="area-body">
            <ul class="area-list">

                <li class="area-list-item">
                    <span class="area-list-item-check">
                        <el-checkbox v-model="selectAll"></el-checkbox>
                    </span>
                    <span class="area-list-item-label">全选</span>
                </li>

                <li class="area-list-item" v-for="(item,i) in addressList" :key="i" @click="select(item,i,list)">
                    <span class="area-list-item-check">
                        <el-checkbox v-model="item.check" @change="change" @click.native.stop></el-checkbox>
                    </span>
                    <span class="area-list-item-label">{{item.label}}</span>
                </li>

            </ul>
        </div>
        <div class="area-footer"></div>
    </div>
</template>
<script src="./area-panel.js"></script>
<style lang="scss" scoped>
@import "area-panel.scss";
</style>