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

        <el-table-column label="订单总价￥" width="100" prop="money"></el-table-column>

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
            <div class="godos-img-list">

              <img class="goods-img-mini" :key="item.key" :src="$getUrl(item.img_list[0].src)" alt="图片错误！" v-for="(item,index) in scope.row.order_info.goods" v-if="index<10">

              <span class="godos-img-count">
                等{{scope.row.order_info.goods.length}}件商品
              </span>
            </div>

          </template>

        </el-table-column>

        <el-table-column fixed="right" label="操作" width="100" align="center">
          <template slot-scope="scope">
            <el-button type="text" size="mini" icon="el-icon-search" @click="see(scope.row)">订单详情</el-button>
          </template>
        </el-table-column>

        <el-table-column type="expand" fixed="right">
          <template slot-scope="scope">

            <!-- 显示商品 -->

            <div class="goods-list">
              <div class="goods-item" v-for="(item,index) in scope.row.order_info.goods">
                <img :src="$getUrl(item.img_list[0].src)" alt="图片错误！">

                <div class="goods-info">

                  <span class="goods-title" @click="see(scope.row)">
                    {{item.goods_title}}
                  </span>

                  <br>
                  <span class="goods-money text-muted">
                    <span>
                      [单价：￥{{scope.row.order_info.user_spec[item.goods_id].money}}]
                    </span>
                    -
                    <span>
                      [数量 {{scope.row.order_info.user_spec[item.goods_id].goods_count}}]
                    </span>
                    <!-- 规格 -->
                    -
                    <span class="spec-list">

                      [
                      <template v-for="(spec,j) in scope.row.order_info.user_spec[item.goods_id]">
                        <span v-if="typeof(spec)=='object'" class="spec-item">
                          {{j}} : {{spec.title}}。
                        </span>
                      </template>
                      ]

                    </span>
                  </span>

                </div>

                <span class="goods-count">
                  x{{scope.row.order_info.user_spec[item.goods_id].goods_count}}
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

<script>
export default {
  name: "orderList",
  data() {
    return {
      // 当前页
      currentPage: 1,
      // 总条数
      total: 0,
      // 当前每页显示的数量
      pageSize: 10,
      //表格数据
      tableData: [],
      //表格是否显示加载层
      tableLoading: false,
      //记录用的值
      testValue: "",
      //被选中项
      selectItem: [],
      //状态值
      state: [
        {
          icon: "fa fa-credit-card",
          value: "未支付",
          text: "未支付",
          type: "text-info"
        },
        {
          icon: "fa fa-cube",
          value: "未发货",
          text: "未发货",
          type: "text-warning"
        },
        {
          icon: "fa fa-truck",
          value: "已发货",
          text: "已发货",
          type: "text-primary"
        },
        {
          icon: "el-icon-success",
          value: "已签收",
          text: "已签收",
          type: "text-success"
        },
        {
          icon: "el-icon-service",
          value: "退款/售后",
          text: "退款/售后",
          type: "text-danger"
        }
      ],
      interval: null
    };
  },
  methods: {
    //页面切换事件
    handleCurrentChange: function() {
      this.update();
    },
    //大小改变事件
    handleSizeChange: function() {
      this.update();
    },
    update: function(showInfo, message) {
      var setTim = setTimeout(() => {
        this.tableLoading = true;
      }, 500);

      this.$get(
        "order/getList",
        { page: this.currentPage, limit: this.pageSize },
        res => {
          if (showInfo) {
            this.$message({
              message: message ? message : "更新完成~",
              type: "success"
            });
          }
          clearTimeout(setTim);
          this.tableLoading = false;
          this.total = res.count;
          this.tableData = res.msg;
          // var map = ["order_info"];
          // if (res.count > 0) this.tableData = asdasd stringToArr(res.msg, map);
        }
      );
    },
    // 保存
    save(item, saveName, isInfo, isValidate) {
      if (isValidate && item[saveName] == this.testValue) return;

      var save = {};
      save[saveName] = item[saveName];

      this.$post(
        "order/save",
        { where: { order_id: item.order_id }, save: save },
        res => {
          if (res.res >= 1 && isInfo) {
            this.$message({ message: "保存成功！", type: "success" });
          }
          if (res.res < 0) {
            this.$message({ message: "保存失败！请重试！", type: "error" });
          }
        }
      );
    },
    editorder(item) {
      this.$router.push({ name: "/order/edit", params: { order: item } });
    },
    rowKey(item) {
      return item.order_id;
    },
    //记录值
    recordValue(value) {
      this.testValue = value;
    },
    setUpAll(is_up) {
      // this.$refs["].
      var list = this.selectItem;
      for (let i = 0; i < list.length; i++) {
        list[i].is_up = is_up + "";
        this.save(list[i], "is_up", false);
      }
    },
    // 当选择项发生变化时会触发该事件
    selectionChange(items) {
      console.log(items);
      this.selectItem = items;
    },
    see(item) {
      this.$router.push({
        name: "/order/info",
        params: { order_id: item.order_id }
      });
    },

    filterMethod(value, row, column) {
      if (!this.state[row.state]) return true;
      return this.state[row.state].text === value;

      // const property = column["property"];
      // return row[property] ===asdasdasdas12312 value;
    }
  },
  mounted: function() {
    this.update();

    clearInterval(this.interval);

    this.interval = setInterval(() => {
      this.$get("order/getCount", {}, res => {
        if (res.res > this.total) {
          this.total = res.res;
          var $notify = this.$notify.info({
            title: "消息",
            message: "有新订单了！",
            duration: 0,
            onClick: () => {
              this.update(true, "已更新新订单~");
              $notify.close();
            },
            onClose: () => {
              this.update(true, "已更新新订单~");
            }
          });
        }
      });
    }, 2000);
  },
  destroyed() {
    clearInterval(this.interval);
  },
  watch: {}
};

function stringToArr(arr, map) {
  for (var i = 0; i < arr.length; i++) {
    for (var j = 0; j < map.length; j++) {
      arr[i][map[j]] = JSON.parse(arr[i][map[j]]);
    }
  }
  return arr;
}
</script>


<style lang="scss" >
@import "list.scss";
</style>

