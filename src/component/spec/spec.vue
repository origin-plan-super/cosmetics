<template>
  <div id="spec" v-loading="isLoading">

    <div class="text-size-default">
      <p>选择规格</p>
    </div>
    <div class="spec-list">

      <spce-group :tree="tree" @on-edit="onEditGroup" :disabled="isEditModel" @change="changeSkuGroupProp" v-for="(group,i) in skuGroup" :list="skuGroup" :index="i" :title="group.sku_group_name" :node="group.node" :group="group" :key="group.sku_group_id"></spce-group>
      <el-button type="text" @click="addSkuGroup()">添加规格</el-button>
      <div class="text-size-default text-info">规格添加后请勿随意删除，删除后正在使用相应规格的商品在编辑时规格部分将受到影响</div>
    </div>

    <div class="text-size-default">
      <p>价格&库存</p>
    </div>

    <div class="param-list">

      <p>
        <span class="text-info">批量填充：</span>
        <el-input v-model="filling.money" placeholder="销售价￥" style="width:100px"></el-input>
        <el-input v-model="filling.purchase_price" placeholder="采购价￥" style="width:100px"></el-input>
        <el-input v-model="filling.earn_price" placeholder="佣金￥" style="width:100px"></el-input>
        <el-input v-model="filling.stock" placeholder="库存" style="width:100px"></el-input>
        <el-button type="primary" @click="batchFilling()">确认</el-button>
      </p>

      <el-table :data="sku" style="width: 100%" size='small' border>

        <el-table-column :label="item.k" :prop="'s'+(index+1)" v-for="(item,index) in tree" :key="item.k" />
        <el-table-column></el-table-column>

        <el-table-column label="销售价￥" width="100">
          <template slot-scope="scope">
            <el-input v-model="scope.row.price"></el-input>
          </template>
        </el-table-column>

        <el-table-column label="采购价￥" width="100">
          <template slot-scope="scope">
            <el-input v-model="scope.row.purchase_price"></el-input>
          </template>
        </el-table-column>

        <el-table-column label="佣金￥" width="100">
          <template slot-scope="scope">
            <el-input v-model="scope.row.earn_price"></el-input>
          </template>
        </el-table-column>

        <el-table-column label="库存" width="100">
          <template slot-scope="scope">
            <el-input v-model="scope.row.stock_num"></el-input>
          </template>
        </el-table-column>

      </el-table>
    </div>

  </div>
</template>

<script>
import SpceGroup from "./spce-group/spce-group.vue";
export default {
  name: "spec",
  props: {
    sku: {
      type: Array,
      default: []
    },
    tree: {
      type: Array,
      default: []
    }
  },
  data() {
    return {
      //是否是编辑模式
      isEditModel: false,
      //用于添加规格下的属性
      addProp: {
        title: ""
      },
      //规格组
      skuGroup: [],
      //价格&库存的列表
      paramList: [],
      //批量填充的变量记录
      filling: {
        money: "", //金额
        stock: "", //库存
        earn_price: "", //佣金
        purchase_price: "" //采购价
      },
      isLoading: false
    };
  },
  methods: {
    update() {
      this.isLoading = true;
      //异步加载数据
      this.$get(
        "skuGroup/getList",
        {},
        res => {
          this.isLoading = false;
          if (res.res >= 1) {
            this.skuGroup = this.initList(res.msg);
            return;
          }
          if (res.res < 0) {
            this.$error("加载规格数据失败！");
          }
        },
        error => {
          this.isLoading = false;
        }
      );
    },
    //初始化规格列表
    initList(list) {
      list.forEach(item => {
        item.node.forEach(node => {
          node.check = false;
        });
      });
      return list;
    },
    //新增规格组
    addSkuGroup() {
      this.$prompt("请输入新的规格组名", "添加规格组", {
        confirmButtonText: "确定",
        cancelButtonText: "取消"
      })
        .then(({ value }) => {
          if (value.length > 6) {
            this.$warn("规格组名长度不能超过6位数~");
          } else {
            this.addAjax(value);
          }
        })
        .catch(() => {});
    },
    //新增规格组提交
    addAjax(name) {
      this.isLoading = true;
      this.$post(
        "skuGroup/add",
        {
          add: { sku_group_name: name }
        },
        res => {
          console.log(res);

          this.isLoading = false;
          if (res.res >= 1) {
            this.skuGroup.push(res.msg);
            this.onEditGroup(false);
            return;
          }
          this.$error("添加失败！请重试~");
        },
        error => {
          this.isLoading = false;
        }
      );
    },

    onEditGroup(isEdit) {
      this.isEditModel = isEdit;
    },
    //批量填充
    batchFilling() {
      this.sku.forEach(item => {
        item.price = this.filling.money;
        item.stock_num = this.filling.stock;
        item.purchase_price = this.filling.purchase_price;
        item.earn_price = this.filling.earn_price;
      });
      this.filling.money = "";
      this.filling.stock = "";
      this.filling.purchase_price = "";
      this.filling.earn_price = "";
    },
    //规格组发生变化
    changeSkuGroupProp() {
      var skuGroup = this.skuGroup;
      var tree = [];
      skuGroup.forEach((group, i) => {
        let isAdd = false;
        let treeItem = {
          k: group.sku_group_name,
          v: [],
          k_s: "s" + (i + 1)
        };
        group.node.forEach(node => {
          if (node.check) {
            isAdd = true;
            treeItem.v.push({
              id: node.sku_group_prop_name,
              name: node.sku_group_prop_name
            });
          }
        });
        //判断是否超出限制，没超出限制就添加，否则提示
        if (isAdd) {
          if (tree.length + 1 <= 3) {
            tree.push(treeItem);
          } else {
            this.$info("最多添加三组~");
          }
        }
      });

      if (tree.length <= 0) {
        this.$emit("update:tree", []);
        this.$emit("update:sku", []);
        return;
      }

      //将组转换为sku
      var test = generateGroup(tree);

      var skus = [];
      test.forEach((item, i) => {
        var sku = {
          id: i,
          price: 0,
          stock_num: 0,
          purchase_price: 0,
          earn_price: 0
        };
        item.forEach((s, j) => {
          sku["s" + (j + 1)] = s;
        });
        skus.push(sku);
      });

      this.$emit("update:tree", tree);
      //保持原有数据

      //如果是之前有过的，就保留原有数据
      skus.forEach(sku => {
        this.sku.forEach(sku2 => {
          var is = true;
          for (let i = 1; i < 3; i++) {
            let key = "s" + i;
            if (sku[key] && sku2[key]) {
              //如果存在
              if (sku[key] != sku2[key]) {
                //如果不等于就不做操作，全等于才做操作
                is = false;
              }
            }
          }
          if (is) {
            sku.stock_num = sku2.stock_num;
            sku.price = sku2.price;
            sku.purchase_price = sku2.purchase_price;
            sku.earn_price = sku2.earn_price;
          }
        });
      });
      this.$emit("update:sku", skus);
    }
  },
  mounted() {
    this.update();
    this.$nextTick(() => {});
  },
  watch: {
    tree(tree) {}
  },
  components: {
    SpceGroup
  }
};

function generateGroup(arr) {
  //初始化结果为第一个数组
  var result = new Array();
  //字符串形式填充数组
  for (var i = 0; i < arr[0].v.length; i++) {
    result.push(JSON.stringify(arr[0].v[i].id));
  }
  //从下标1开始遍历二维数组
  for (var i = 1; i < arr.length; i++) {
    //使用临时遍历替代结果数组长度(这样做是为了避免下面的循环陷入死循环)
    var size = result.length;
    //根据结果数组的长度进行循环次数,这个数组有多少个成员就要和下一个数组进行组合多少次
    for (var j = 0; j < size; j++) {
      //遍历要进行组合的数组
      for (var k = 0; k < arr[i].v.length; k++) {
        //把组合的字符串放入到结果数组最后一个成员中
        //这里使用下标0是因为当这个下标0组合完毕之后就没有用了，在下面我们要移除掉这个成员
        //组合下一个json字符串
        var temp = result[0] + "," + JSON.stringify(arr[i].v[k].id);
        result.push(temp);
      }
      //当第一个成员组合完毕，删除这第一个成员
      result.shift();
    }
  }

  //转换字符串为json对象
  for (var i = 0; i < result.length; i++) {
    result[i] = JSON.parse("[" + result[i] + "]");
  }
  //打印结果
  return result;
}
</script>

<style lang="scss" scoped>
@import "spec.scss";
</style>