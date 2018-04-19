<template>
    <div id="list">
        <div style="margin-bottom:20px">
            <el-button size="mini" icon="el-icon-refresh" @click="update" :loading="tableLoading"></el-button>
            <el-button size='mini' icon="el-icon-delete" :disabled="selectItem.length<=0" @click="dels"></el-button>
            <el-button size="mini" @click="$router.push('/supplier/add')" icon="el-icon-plus">添加分销商</el-button>

        </div>

        <el-table ref='table' @selection-change="selectionChange" v-loading="tableLoading" :data="tableData" :row-key="rowKey" style="margin-bottom:20px" border max-height='70vh' stripe size='mini'>

            <el-table-column type='selection' align="center"></el-table-column>
            <el-table-column prop="supplier_name" label="分销商名称" width="200"></el-table-column>
            <el-table-column prop="supplier_login_id" label="分销商登录名" width="200"></el-table-column>
            <el-table-column prop="add_time" label="创建日期" width="150"></el-table-column>
            <el-table-column></el-table-column>

            <el-table-column fixed="right" label="操作" width="150" align="center">
                <template slot-scope="scope">
                    <el-button type="text" icon="el-icon-edit" size="mini" @click="edit(scope.row,scope.$index,tableData)"></el-button>
                    <el-button type="text" icon="el-icon-delete" size="mini" @click="del(scope.row,scope.$index,tableData)"></el-button>
                    <el-button type="text" size="mini" @click="showOrder(scope.row)">查看订单</el-button>
                </template>
            </el-table-column>

        </el-table>

        <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-size.sync="pageSize" layout="total, sizes, prev, pager, next, jumper" :total="total">
        </el-pagination>
    </div>
</template>
<script src="./list.js"></script>
<style lang="scss" scoped>
@import "list.scss";
</style>