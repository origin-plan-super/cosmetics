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

      <!-- <el-button type="primary" size="mini" :disabled="selectItem.length<=0">批量设置上级</el-button> -->

      <el-button type="primary" size="mini" icon="el-icon-plus" @click="isAdd=!isAdd">
        <span v-if="!isAdd">添加用户</span>
        <span v-if="isAdd">取消</span>
      </el-button>

      <div class="float-right">
        <el-input @keyup.enter.native="search()" v-model="queryKey" placeholder="请输入关键词，如用户id、用户名" size="mini" style="width:300px">
          <el-button slot="append" @click="search()">搜索</el-button>
        </el-input>
      </div>

    </div>

    <div class="frame" v-if="isAdd">
      <el-card>
        <span slot="header">添加用户</span>
        <add-user @on-success="addUserSuccess"></add-user>
      </el-card>
    </div>

    <div class="frame">

      <el-table stripe header-cell-class-name="table-head-call" header-row-class-name="table-head" :default-expand-all="false" ref="table" @selection-change="selectionChange" v-loading="tableLoading" :data="tableData" :row-key="rowKey" style="width: 100%" border height="70vh" max-height="70vh" size="mini">
        <el-table-column type="selection" align="center"></el-table-column>

        <el-table-column prop="user_id" label="用户账户" resizable show-overflow-tooltip width="100"></el-table-column>

        <el-table-column label="基本信息" resizable show-overflow-tooltip width="200">

          <template slot-scope="scope">
            <div class="user-info">

              <img :src="$getUrl(scope.row.user_head)" class="user-head" alt="">
              <span class="text-info">{{scope.row.user_name}}</span>

            </div>

          </template>

        </el-table-column>

        <el-table-column prop="user_type" label="用户类型" align="left" :filters="vips" :filter-method="filterMethod" width="100">
          <template slot-scope="scope">

            <template v-if="scope.row.vip">
              {{scope.row.vip.vip_name}}
            </template>
            <template v-else>
              普通用户
            </template>

          </template>
        </el-table-column>

        <el-table-column label="上级" resizable show-overflow-tooltip width="200">

          <template slot-scope="scope">

            <template v-if="scope.row.super">
              {{scope.row.super.user_id}} - {{scope.row.super.user_name}}
              <template v-if="scope.row.super.vip">
                - {{scope.row.super.vip.vip_name}}
              </template>
            </template>

          </template>

        </el-table-column>

        <el-table-column></el-table-column>

        <el-table-column prop="add_time" label="创建时间" resizable show-overflow-tooltip width="155"></el-table-column>

        <el-table-column fixed="right" label="操作" width="200" align="center">
          <template slot-scope="scope">
            <!-- <el-button type="text" size="mini" icon="el-icon-search"></el-button> -->
            <el-button type="text" size="mini" @click="showSetUserType(scope.row)">指定身份</el-button>
            <el-button type="text" size="mini" @click="showSetUserSuper(scope.row)">设置上级</el-button>
          </template>
        </el-table-column>

      </el-table>

    </div>

    <div class="frame">
      <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-size.sync="pageSize" layout="total, sizes, prev, pager, next, jumper" :total="total">
      </el-pagination>
    </div>

    <set-user-type @on-success="setUserTypeSuccess" ref="set-user-type"></set-user-type>
    <set-user-super @on-success="setUserSuperSuccess" ref="set-user-super"></set-user-super>

  </div>
</template>


<script>
import addUser from "../../../component/addUser/addUser.vue";
import setUserType from "../../../component/setUserType/setUserType.vue";
import setUserSuper from "../../../component/setUserSuper/setUserSuper.vue";

export default {
  name: "orderList",
  data() {
    return {
      // 当前页
      currentPage: 1,
      // 总条数
      total: 0,
      // 当前每页显示的数量
      pageSize: 20,
      //表格数据
      tableData: [],
      //表格是否显示加载层
      tableLoading: false,
      //记录用的值
      testValue: "",
      //被选中项
      selectItem: [],
      //用户类型

      vips: [],
      isAdd: false,
      identity: {
        isShow: false,
        activeUser: null,
        data: {
          identity: null,
          star_id: null
        }
      },
      superD: {
        isShow: false,
        activeUser: null
      },
      stars: [],
      queryKey: ""
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
      if (this.queryKey.length > 0) {
        this.search();
        return;
      }
      var setTim = setTimeout(() => {
        this.tableLoading = true;
      }, 500);

      //获得vip信息列表
      this.$get("vip/getList", {}, res => {
        res.msg.forEach(item => {
          item.value = item.vip_name;
          item.text = item.vip_name;
        });
        res.msg.unshift({
          value: "普通用户",
          text: "普通用户"
        });
        this.vips = res.msg;
      });
      this.$get(
        "user/getList",
        { page: this.currentPage, limit: this.pageSize },
        res => {
          console.log(res);

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
    rowKey(item) {
      return item.user_id;
    },
    // 当选择项发生变化时会触发该事件
    selectionChange(items) {
      this.selectItem = items;
    },
    see(item) {
      this.$router.push({
        name: "/order/info",
        params: { order_id: item.order_id }
      });
    },
    filterMethod(value, row, column) {
      if (row.vip == null) {
        if (value == "普通用户") {
          return true;
        }
      }
      if (!this.vips[row.vip.vip_level]) return true;
      return this.vips[row.vip.vip_level].text === value;
    },
    //打开设置用户身份的窗口
    showSetUserType(item) {
      console.log(item);
      this.$refs["set-user-type"].show(item);
    },
    //打开设置用户上级的窗口
    showSetUserSuper(item) {
      this.$refs["set-user-super"].show(item);
    },
    //设置用户类型成功后
    setUserTypeSuccess() {
      this.update();
    },
    //设置用户上级成功后
    setUserSuperSuccess() {
      this.update();
    },
    //用户添加成功
    addUserSuccess() {
      this.isAdd = false;
      this.update();
    },
    //搜索
    search() {
      var key = this.queryKey;
      this.$get(
        "user/getList",
        {
          page: this.currentPage,
          limit: this.pageSize,
          key: key
        },
        res => {
          if (res.res >= 0) {
            var setTim = setTimeout(() => {
              this.tableLoading = true;
            }, 500);

            clearTimeout(setTim);
            this.tableLoading = false;
            this.total = res.count;
            this.tableData = res.msg;

            return;
          }
          this.$error("数据查询出错！");
        }
      );
    }
  },
  mounted: function() {
    this.update();
  },
  destroyed() {},
  watch: {},
  components: { addUser, setUserType, setUserSuper }
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

