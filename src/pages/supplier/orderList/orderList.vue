<template>
    <div id="orderList">
        <div class="frame">
            <el-button-group>

                <el-tooltip class="item" effect="dark" content="刷新列表" placement="top-start">
                    <el-button type="primary" size="mini" icon="el-icon-refresh" @click="update()"></el-button>
                </el-tooltip>
                <el-tooltip class="item" effect="dark" content="删除选中" placement="top-start">
                    <el-button type="primary" size="mini" icon="el-icon-delete" :disabled="selectItem.length<=0"></el-button>
                </el-tooltip>

            </el-button-group>

            <!-- <el-button type="primary" size="mini" :icon="isOpen?'el-icon-remove-outline':'el-icon-circle-plus-outline'" @click="isOpen=!isOpen">{{isOpen?"全部收起":"全部展开"}}</el-button> -->
        </div>

        <div class="frame">

            <el-table header-cell-class-name="table-head-call" header-row-class-name="table-head" :default-expand-all="false" ref="table" @selection-change="selectionChange" v-loading="tableLoading" :data="tableData" :row-key="rowKey" style="width: 100%" border height="70vh" max-height="70vh" size="mini">
                <el-table-column type="selection" align="center"></el-table-column>

                <el-table-column prop="order_id" label="订单号" resizable show-overflow-tooltip width="155"></el-table-column>
                <el-table-column prop="user_id" label="用户ID" resizable show-overflow-tooltip width="100"></el-table-column>
                <el-table-column prop="user_name" label="用户名" resizable show-overflow-tooltip width="100"></el-table-column>

                <el-table-column label="订单总价￥" width="100" prop="price"></el-table-column>

                <el-table-column prop="add_time" label="创建时间" resizable show-overflow-tooltip width="155"></el-table-column>

                <el-table-column prop="state" label="状态" align="left" :filters="state" :filter-method="filterMethod" resizable show-overflow-tooltip width="140">
                    <template slot-scope="scope">

                        <template v-if="state[scope.row.state]">

                            <span :class="state[scope.row.state].type">
                                <i style="width:20px;display: inline-block;" :class="state[scope.row.state].icon" v-if="state[scope.row.state].icon"></i>
                                {{state[scope.row.state].text}}
                            </span>

                        </template>

                        <template v-else>
                            <span class="text-error">
                                <i style="width:20px;display: inline-block;" class="el-icon-error"></i>
                                未知状态：{{scope.row.state}}
                            </span>
                        </template>

                    </template>
                </el-table-column>

                <el-table-column label="商品信息">
                    <template slot-scope="scope">

                        <div class="goods-info-img">
                            <img :src="$getUrl(scope.row.snapshot.img)" alt="图片错误！">
                        </div>
                        {{scope.row.snapshot.goods_title}}

                    </template>

                </el-table-column>

                <el-table-column fixed="right" label="操作" width="80" align="center">
                    <template slot-scope="scope">
                        <el-button type="text" size="mini" icon="el-icon-search" @click="see(scope.row)"></el-button>
                    </template>
                </el-table-column>

                <el-table-column type="expand" fixed="right">
                    <template slot-scope="scope">

                        <!-- 显示商品 -->

                        <div class="goods-list">
                            <div class="goods-item">
                                <img :src="$getUrl(scope.row.snapshot.img)" alt="图片错误！">

                                <div class="goods-info">

                                    <span class="goods-title" @click="see(scope.row)">
                                        {{scope.row.snapshot.goods_title}}
                                    </span>

                                    <br>
                                    <span class="goods-money text-muted">
                                        <span>
                                            [ 单价：￥{{scope.row.snapshot.price}} ]
                                        </span>
                                        -
                                        <span>
                                            [ 数量 {{scope.row.snapshot.count}} ]
                                        </span>
                                        -
                                        <span>
                                            [ 总价：￥{{scope.row.price}} ]
                                        </span>
                                        <!-- 规格 -->
                                        <div class="spec-list">

                                            <template v-for="n in 3">
                                                <span v-if="scope.row.snapshot['s'+n]" class="order-label" :key="n">{{scope.row.snapshot['s'+n]}}</span>
                                            </template>

                                        </div>
                                    </span>

                                </div>

                                <span class="goods-count">
                                    x{{scope.row.snapshot.count}}
                                </span>
                            </div>
                        </div>

                    </template>

                </el-table-column>

            </el-table>

        </div>

        <div class="frame">
            <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-size.sync="pageSize" layout="total, sizes, prev, pager, next, jumper" :total="total">
            </el-pagination>
        </div>

    </div>
</template>
<script src="./orderList.js"></script>
<style lang="scss" scoped>
@import "orderList.scss";
</style>