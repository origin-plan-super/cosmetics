<template>
    <div class="freight-area">

        <el-table :data="areas" border size="mini">

            <el-table-column prop="area" label="可配送区域">
                <template slot-scope="scope">
                    <div v-if="type=='edit'">
                        <span>选择可配送区域</span>
                        <el-button type="text" icon="el-icon-edit-outline" @click="selectArea(scope.row,scope.$index)"></el-button>
                        <el-button type="text" icon="el-icon-delete" @click="del(scope.row,scope.$index,areas)"></el-button>
                    </div>
                    <span class="text-info">
                        <span v-if="scope.row.areasInfo.length<=0">请选择可配送区域</span>
                        <span v-else>
                            <span v-for="item in scope.row.areasInfo" :key="item.value">{{item.label}},</span>
                        </span>
                    </span>
                </template>
            </el-table-column>

            <el-table-column prop="first" :label="`首件(${isType})`">
                <template slot-scope="scope">
                    <el-input v-if="type=='edit'" size="mini" v-model.number="scope.row.first"></el-input>
                    <span v-if="type=='look'">{{scope.row.first}}</span>
                </template>
            </el-table-column>

            <el-table-column prop="first_price" label="运费(元)">
                <template slot-scope="scope">
                    <el-input v-if="type=='edit'" size="mini" v-model.number="scope.row.first_price"></el-input>
                    <span v-if="type=='look'">{{scope.row.first_price}}</span>
                </template>
            </el-table-column>

            <el-table-column prop="continued" :label="`续件(${isType})`">
                <template slot-scope="scope">
                    <el-input v-if="type=='edit'" size="mini" v-model.number="scope.row.continued"></el-input>
                    <span v-if="type=='look'">{{scope.row.continued}}</span>
                </template>
            </el-table-column>

            <el-table-column prop="continued_price" label="运费(元)">
                <template slot-scope="scope">
                    <el-input v-if="type=='edit'" size="mini" v-model.number="scope.row.continued_price"></el-input>
                    <span v-if="type=='look'">{{scope.row.continued_price}}</span>
                </template>
            </el-table-column>

        </el-table>

        <el-button type="text" @click="add" icon="el-icon-plus" v-if="type=='edit'">新增</el-button>

        <el-dialog :close-on-click-modal="false" title="选择可配送区域" width="80%" :visible.sync="isShowSelectArea">

            <template>
                <select-area v-model="manyAreaValue" ref="select-area"></select-area>
            </template>

            <span slot="footer" class="dialog-footer">
                <el-button size="mini" @click="isShowSelectArea = false">取 消</el-button>
                <el-button size="mini" type="primary" @click="setSelectArea()">确 定</el-button>
            </span>
        </el-dialog>

    </div>
</template>
<script src="./freight-area.js"></script>
<style lang="scss" scoped>
@import "freight-area.scss";
</style>