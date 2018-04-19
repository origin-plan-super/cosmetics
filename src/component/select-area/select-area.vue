<template>
    <div class="select-area">
        <div class="text-left">
            <el-button icon="el-icon-question" type="text" class="text-info" @click="isShopGHelp = true"></el-button>
        </div>
        <area-panel :title="`省`" :addressData="addressData" v-model="selectListProvinces" @change="(item)=>{provincesSelect=item}" :list="provinces"></area-panel>
        <area-panel :title="`${provincesSelect?provincesSelect.label+' -':'<- 请选择省 |'} 市`" :addressData="addressData" v-model="selectListCitys" @change="(item)=>{citysSelect=item}" :list="citys"></area-panel>
        <area-panel style="margin-right:20px" :title="`${citysSelect?citysSelect.label+' -':'<- 请选择市 |'} 区`" :addressData="addressData" v-model="selectListCountys" @change="(item)=>{countysSelect=item}" :list="countys"></area-panel>
        <!-- 帮助 -->

        <div class="tree-panel">
            <div class="tree-head">已选择</div>
            <div class="tree-body">
                <el-tree :data="tree" default-expand-all @node-click="handleNodeClick">
                    <span class="custom-tree-node" slot-scope="{ node, data }">
                        <span>{{ node.label }}</span>
                        <span>
                            <el-button type="text" size="mini" @click="() => remove(data)" icon="el-icon-delete"></el-button>
                        </span>
                    </span>
                </el-tree>
            </div>
            <div class="tree-footer"></div>
        </div>

        <el-dialog title="帮助" :visible.sync="isShopGHelp" :append-to-body="true">

            <p>不选择表示全部允许。</p>
            <p>例：</p>
            <p>如果选择的地址为：上海市。则表示上海全市都可以配送。</p>
            <p>如果选择的地址为：上海市-市辖区-黄浦区。则表示只有黄浦区可以配送。</p>
            <p>可多选。</p>
            <span slot="footer" class="dialog-footer">
                <el-button size="mini" @click="isShopGHelp = false">关闭</el-button>
            </span>

        </el-dialog>

    </div>

</template>
<script src="./select-area.js"></script>

<style lang="scss" scoped>
@import "select-area.scss";
</style>