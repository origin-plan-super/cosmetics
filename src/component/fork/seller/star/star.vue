<template>
  <div class="seller-star">
    <div class="frame">

      <el-button-group>
        <el-button type="primary" size="mini" icon="el-icon-refresh" @click="update()" :loading="tableLoading"></el-button>
        <el-button type="primary" size="mini" icon="el-icon-delete" @click="dels()" :disabled="selectItem.length<=0"></el-button>
      </el-button-group>
      <el-button type="primary" size="mini" icon="el-icon-plus" @click="addStar()">{{isAdd?'取消':'添加等级'}}</el-button>
    </div>

    <div class="frame">
      <!-- 添加等级面板 -->
      <star-add v-if="isAdd" @on-success="addSuccess()"></star-add>

    </div>

    <div class="frame" v-if="!isAdd">

      <el-table ref='table' @selection-change="selectionChange" v-loading="tableLoading" :data="tableData" :row-key="rowKey" style="width: 100%" border max-height='70vh' stripe size='mini'>

        <el-table-column type='selection' align="center"></el-table-column>

        <el-table-column prop="star_name" width="300" label="等级名称" resizable show-overflow-tooltip>

          <template slot-scope="scope">
            <span v-if="!scope.row.isEdit">{{scope.row.star_name}}</span>
            <span v-else>
              <el-input v-focus :maxlength="20" @focus="testValue=scope.row.star_name" @keyup.enter.native="save(scope.row,'star_name',true,true);scope.row.isEdit=false" size="mini" v-model="scope.row.star_name">
                <i slot="suffix">
                  {{scope.row.star_name.length}} /20
                </i>
                <el-button slot="append" @click="save(scope.row,'star_name',true,true);scope.row.isEdit=false">保存</el-button>
              </el-input>
            </span>
          </template>

        </el-table-column>

        <el-table-column prop="gain" label="所得利润%" width="300" resizable show-overflow-tooltip>
          <template slot-scope="scope">

            <span v-if="!scope.row.isEdit">{{scope.row.gain}}%</span>
            <span v-else>
              <el-input v-focus :maxlength="11" @focus="testValue=scope.row.gain" @keyup.enter.native="save(scope.row,'gain',true,true);scope.row.isEdit=false" size="mini" v-model="scope.row.gain">
                <el-button slot="append" @click="save(scope.row,'gain',true,true);scope.row.isEdit=false">保存</el-button>
              </el-input>
            </span>

          </template>

        </el-table-column>

        <el-table-column prop="star_type" label="经营范围" width="150" resizable show-overflow-tooltip>

          <template slot-scope="scope">

            <!-- //type 0 全部商品 -->
            <!-- //type 1 选择商品 -->
            <span v-if="scope.row.star_type==0">全部商品</span>
            <span v-if="scope.row.star_type==1">部分商品</span>

          </template>

        </el-table-column>

        <el-table-column></el-table-column>

        <el-table-column fixed="right" label="操作" width="100" align="center">
          <template slot-scope="scope">
            <el-button type="text" size="mini" @click="edit(scope.row)">
              <i v-if="scope.row.isEdit" class="el-icon-close"></i>
              <i v-if="!scope.row.isEdit" class="el-icon-edit"></i>
            </el-button>
            <el-button icon="el-icon-delete" type="text" size="mini" @click="delDate.item=scope.row;delDate.index=scope.$index;dialogVisible=true;"></el-button>
            <el-button icon="el-icon-search" type="text" size="mini" @click="show(scope.row)"></el-button>
          </template>
        </el-table-column>

      </el-table>

    </div>

    <el-dialog title="确定删除？" :visible.sync="dialogVisible" width="30%">
      <span>
        <i class="el-icon-warning text-danger" style="font-size:30px"></i>
        <span class="text-danger">重要提示：</span>
        <span>删除后相关分销功能将受到影响！如果此时用户下单，分销功能将不能正常工作！确定删除吗？</span>
      </span>
      <span slot="footer" class="dialog-footer">
        <el-button size="mini" @click="dialogVisible = false;delDate.item=null">取 消</el-button>
        <el-button size="mini" type="danger" @click="del(delDate.item,delDate.index,tableData)">确定删除</el-button>
        <!-- del(scope.row,scope.$index,tableData) -->
      </span>
    </el-dialog>

  </div>

</template>
<script>
import starAdd from "./add/add.vue";

export default {
  name: "seller-star",
  props: {},
  data() {
    return {
      dialogVisible: false,
      delDate: {
        index: 0,
        item: null
      },
      //表格数据
      tableData: [],
      //表格是否显示加载层
      tableLoading: false,
      //记录用的值
      testValue: "",
      //被选中项
      selectItem: [],
      //是否是保存数据状态
      isPreservation: false,
      //是否显示添加面板
      isAdd: false
    };
  },
  methods: {
    update: function() {
      var setTim = setTimeout(() => {
        this.tableLoading = true;
      }, 500);

      this.$get("star/getList", {}, res => {
        clearTimeout(setTim);
        this.tableLoading = false;
        if (res.count > 0) {
          for (let i = 0; i < res.count; i++) {
            res.msg[i].isEdit = false;
          }
          this.tableData = res.msg;
        }
      });
    },
    // 当选择项发生变化时会触发该事件
    selectionChange(items) {
      this.selectItem = items;
    },
    rowKey(item) {
      return item.feeback_id;
    },
    //添加等级
    addStar() {
      this.isAdd = !this.isAdd;
    },
    //添加成功后
    addSuccess() {
      this.isAdd = false;
      this.update();
    },
    edit(item) {
      item.isEdit = !item.isEdit;
    },
    save(item, saveName, isInfo, isValidate) {
      if (isValidate && item[saveName] == this.testValue) return;
      this.testValue = item[saveName];
      var tim = setTimeout(() => {
        this.isPreservation = true;
      }, 100);
      var msg;
      msg = this.$message({
        message: "正在保存",
        duration: 0,
        iconClass: "el-icon-loading"
      });
      var save = {};
      save[saveName] = item[saveName];

      this.$post(
        "star/save",
        { where: { star_id: item.star_id }, save: save },
        res => {
          clearTimeout(tim);
          msg.close();

          this.isPreservation = false;

          if (res.res >= 1 && isInfo) {
            this.$message({ message: "保存成功！", type: "success" });
          }
          if (res.res < 0) {
            this.$message({ message: "保存失败！请重试！", type: "error" });
          }
        }
      );
    },
    //
    show(item) {
      this.$router.push({
        name: "/fork/starGoodsList",
        params: { star_id: item.star_id }
      });
    },
    del(item, i, list) {
      this.dialogVisible = false;
      const loading = this.$loading({
        lock: true,
        text: "正在删除……",
        spinner: "el-icon-loading",
        background: "rgba(0, 0, 0, 0.7)"
      });

      this.$post("star/del", { where: { star_id: item.star_id } }, res => {
        if (res.res >= 1) {
          this.$message({ type: "success", message: "删除成功！" });
          list.splice(i, 1);
          loading.close();
          return;
        }
        this.$message({ type: "error", message: "删除失败请重试！" });
      });
    },
    dels() {
      // loading
      var arr = [];
      for (let i = 0; i < this.selectItem.length; i++) {
        var item = this.selectItem[i];
        arr.push(item.star_id);
      }

      this.$post("star/dels", { ids: arr }, res => {
        if (res.res >= 1) {
          this.$message({ type: "success", message: "删除成功！" });
          this.update();
          return;
        }
        this.$message({ type: "error", message: "删除失败请重试！" });
      });
    }
  },
  computed: {},
  mounted() {
    this.update();
  },
  components: { starAdd },
  watch: {}
};
</script>
<style lang="scss" >
@import "star.scss";
</style>