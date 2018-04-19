<template>
    <div id="list">

        <div class="tool-panel">
            <el-button @click="$router.push('add')" size="mini" icon="el-icon-plus">新增运费模板</el-button>
            <el-button @click="update()" size="mini" icon="el-icon-refresh"></el-button>

        </div>

        <el-card v-for="(item,i) in list" :key="item.freight_id" shadow="hover">
            <div slot="header">
                <span class="text-info">
                    {{item.freight_name}}
                </span>
                <div class="float-right text-info">
                    <span>最后编辑时间：{{item.edit_time}}</span>
                    <div class="tool">
                        <el-button class="tool-item" type="text" size="mini" icon="el-icon-edit" @click="edit(item,i,list)"></el-button>
                        <el-button class="tool-item" type="text" size="mini" icon="el-icon-delete" @click="del(item,i,list)"></el-button>
                    </div>
                </div>

            </div>
            <freight-area v-model="item.areas" :value-type="item.value_type" type="look"></freight-area>
            <!-- <pre v-html="item"></pre> -->
        </el-card>

    </div>
</template>
<script src="./list.js"></script>
<style lang="scss" scoped>
@import "list.scss";
</style>