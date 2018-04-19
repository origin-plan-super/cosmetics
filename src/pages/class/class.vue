<template>
  <div id="class">

    <el-card>
      <el-button-group>

        <el-tooltip class="item" content="刷新" placement="top">
          <el-button type="primary" size="mini" icon="el-icon-refresh" @click="update()" :loading="updateLoading"></el-button>
        </el-tooltip>

        <el-tooltip class="item" content="整理排序" placement="top">
          <el-button type="primary" size="mini" icon="el-icon-sort" @click="initSort()"></el-button>
        </el-tooltip>

        <el-tooltip class="item" content="添加分组" placement="top">
          <el-button type="primary" size="mini" icon="el-icon-plus" @click="add()"></el-button>
        </el-tooltip>

        <el-tooltip class="item" :content="isOpenAll?'收起全部':'展开全部'" placement="top">

          <el-button type="primary" size="mini" @click="isOpenAll=!isOpenAll">
            <span v-if="isOpenAll">
              <i class="el-icon-remove-outline"></i>
            </span>
            <span v-else>
              <i class="el-icon-circle-plus-outline"></i>
            </span>
          </el-button>

        </el-tooltip>

      </el-button-group>

      <ul class="tree-list">
        <li v-for="(group,i) in tree" class="tree-item" :key="group.item.class_id">

          <div class="tree-body" @click.self="group.isOpen=!group.isOpen">
            <span @click="group.isOpen=!group.isOpen" class="tree-open">
                 <i v-if="group.isOpen" class="el-icon-remove-outline"></i>
              <i v-if="!group.isOpen" class="el-icon-circle-plus-outline"></i>
            </span>

            <!-- 上传 -->

            <el-upload :show-file-list="false" class="upload" @click.native="setItem(group.item)" :action="uploadAction" :data="uploadData(group.item)" :on-success="uploadSuccess">
              <img class="tree-img" v-if="group.item.img" v-img="group.item.img">
            </el-upload>

            <span class="tree-label" v-if="!group.isEdit" @click="group.isEdit=!group.isEdit">{{group.item.class_title}}</span>
            <el-input v-focus="group.isEdit" @focus="testValue=group.item.class_title" @blur="save(group.item,'class_title',true,true); group.isEdit=false" @keyup.enter.native="save(group.item,'class_title',true,true) ;group.isEdit=false" style="width:200px" size="mini" v-model="group.item.class_title" v-if="group.isEdit"></el-input>
            <div class="tree-tool">
              <el-button type="text" icon="el-icon-edit" @click="group.isEdit=!group.isEdit" v-if="!group.item.class_title"></el-button>
              <!-- 上传 -->
              <el-upload :show-file-list="false" class="upload" @click.native="setItem(group.item)" :action="uploadAction" :data="uploadData(group.item)" :on-success="uploadSuccess">
                <el-button type="text" icon="el-icon-picture-outline" v-if="!group.item.img"></el-button>
              </el-upload>

              <el-button type="text" icon="el-icon-plus" @click="append(group);group.isOpen=true"></el-button>
              <el-button type="text" icon="el-icon-delete" @click="del(group.item,tree,i)"></el-button>
              <el-button type="text" icon="el-icon-sort-up" @click="sort(group,'up',tree,i)"></el-button>
              <el-button type="text" icon="el-icon-sort-down" @click="sort(group,'down',tree,i)"></el-button>

              <!--  -->
              <span class='hide'>{{group.item.sort}}</span>

            </div>
          </div>

          <ul v-if="group.isOpen && group.children.length>0">
            <li v-for="(item,j) in group.children" class="tree-item" :key="item.item.class_id">
              <div class="tree-body">

                <el-upload :show-file-list="false" class="upload" @click.native="setItem(item.item)" :action="uploadAction" :data="uploadData(item.item)" :on-success="uploadSuccess">
                  <img class="tree-img" v-if="item.item.img" v-img="item.item.img">
                </el-upload>

                <span class="tree-label" v-if="!item.isEdit" @click="item.isEdit=!item.isEdit">{{item.item.class_title}}</span>
                <el-input v-focus="item.isEdit" @focus="testValue=item.item.class_title" @blur="save(item.item,'class_title',true,true);item.isEdit=false" @keyup.enter.native="save(item.item,'class_title',true,true);item.isEdit=false" style="width:200px" size="mini" v-model="item.item.class_title" v-if="item.isEdit"></el-input>

                <div class="tree-tool">
                  <el-button type="text" icon="el-icon-edit" @click="item.isEdit=!item.isEdit" v-if="!item.item.class_title"></el-button>

                  <el-upload :show-file-list="false" class="upload" @click.native="setItem(item.item)" :action="uploadAction" :data="uploadData(item.item)" :on-success="uploadSuccess">
                    <el-button type="text" icon="el-icon-picture-outline" v-if="!item.item.img"></el-button>
                  </el-upload>

                  <el-button type="text" icon="el-icon-delete" @click="del(item.item,group.children,j)"></el-button>
                  <!--  -->
                  <el-button type="text" icon="el-icon-sort-up" @click="sort(item,'up',group.children,j)"></el-button>
                  <el-button type="text" icon="el-icon-sort-down" @click="sort(item,'down',group.children,j)"></el-button>
                  <span class='hide'>{{item.item.sort}}</span>

                </div>
              </div>
            </li>
          </ul>

        </li>
      </ul>

    </el-card>

  </div>
</template>

<script>
export default {
  name: "class",
  data() {
    return {
      list: [],
      tree: [],
      defaultProps: {
        children: "children",
        label: "label"
      },
      testValue: "",
      uploadAction: this.$serverUpFile,
      uploadItem: null,
      isOpenAll: true,
      updateLoading: false
    };
  },
  methods: {
    //获得最新数据
    update() {
      this.updateLoading = true;
      this.tree = [];
      this.list = [];
      this.$get("Class/getList", {}, res => {
        if (res.count > 0) {
          this.list = res.msg;
          this.builderList(res.msg);
        } else {
          this.updateLoading = false;
        }
      });
    },
    //构建列表，设置层级关系，组成树
    builderList() {
      var tree = [];
      var list = this.list;
      for (let i = 0; i < list.length; i++) {
        const item = list[i];
        //如果没有上级id，代表是顶级
        item.sort = parseInt(item.sort);
        if (!item.super_id) {
          tree.push({
            isRoot: true,
            isOpen: true,
            isEdit: false,
            item: item,
            children: []
          });
        }
      }

      for (let i = 0; i < list.length; i++) {
        const item = list[i];
        //如果上级id，就寻找上级id，并且插入
        item.sort = parseInt(item.sort);

        if (item.super_id) {
          for (let j = 0; j < tree.length; j++) {
            const superItem = tree[j];

            if (item.super_id == superItem.item.class_id) {
              superItem.children.push({
                item: item,
                isEdit: false
              });
            }
          }
        }
      }
      this.tree = tree;
      this.initSort();
    },
    //整理排序，删除、新增之后需要执行一次
    initSort() {
      var tree = this.tree;

      for (let i = 0; i < tree.length; i++) {
        const group = tree[i];
        if (group.item.sort != i) {
          group.item.sort = i;
          this.save(group.item, "sort", true);
        }

        for (let j = 0; j < group.children.length; j++) {
          const item = group.children[j];
          if (item.item.sort != j) {
            item.item.sort = j;
            this.save(item.item, "sort", true);
          }
        }
      }
      this.updateLoading = false;
    },
    //单个排序
    sort(item, type, list, index) {
      // this.$message({ type: "info", message: "已经置顶了!" });
      // this.save(item, "sort", false);
      var x;
      if (type == "up") {
        if (index <= 0) {
          this.$message({ type: "info", message: "已经置顶了~" });
          return;
        }

        x = list[index];
        list[index] = list[index - 1];
        list[index - 1] = x;
      }
      if (type == "down") {
        if (index >= list.length - 1) {
          this.$message({ type: "info", message: "已经最后了~" });
          return;
        }
        x = list[index];
        list[index] = list[index + 1];
        list[index + 1] = x;
      }

      this.initSort();
    },
    //在分组后新增节点
    append(group) {
      var add = {
        super_id: group.item.class_id,
        class_title: "",
        sort: group.children.length
      };

      this.$post("class/add", { add: add }, res => {
        if (res.res == 1) {
          group.children.push({
            isEdit: true,
            item: res.msg
          });
          this.initSort();
        }
      });
    },
    //新增分组
    add() {
      var add = { class_title: "", sort: this.tree.length };
      this.$post("class/add", { add: add }, res => {
        if (res.res == 1) {
          this.tree.push({
            isEdit: true,
            isRoot: true,
            isOpen: true,
            item: res.msg,
            children: []
          });
          this.initSort();
        }
      });
    },
    //删除分组或分组下的节点
    del(item, list, index) {
      var delClass = data => {
        this.$post("class/del", data, res => {

          if (res.res >= 1) {
            this.$message({
              type: "success",
              message: "删除成功!"
            });

            list.splice(index, 1);
            this.initSort();
          }

          if (res.res < 0) {
            this.$message({
              type: "error",
              message: "删除失败!请重试"
            });
          }
        });
      };

      if (true) {
        delClass({ class: item });
        return;
      }

      var title = `确定删除 [ ${item.class_title} ] 分类吗？`;
      this.$confirm(title, "提示", {
        type: "warning",
        confirmButtonText: "确定",
        cancelButtonText: "取消"
      })
        .then(() => {
          delClass({ class: item });
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消删除"
          });
        });
    },
    //保存字段
    save(_item, saveName, isInfo, isValidate) {
      var item = _item;
      if (isValidate && item[saveName] == this.testValue) return;

      var save = {};
      save[saveName] = item[saveName];

      this.$post(
        "class/save",
        { where: { class_id: item.class_id }, save: save },
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
    //上传文件时候需要的data
    uploadData(item) {
      var data = {
        src: "classImg/",
        token: localStorage.token,
        admin_id: localStorage.admin_id
      };

      if (item.img) {
        //有要删除的文件
        data.del_src = item.img;
      }

      return data;
    },
    //设置当前选中的item
    setItem(item) {
      this.uploadItem = item;
      this.testValue = this.uploadItem.img;
    },
    //上传成功后的回调函数
    uploadSuccess(response, file, fileList) {
      this.uploadItem.img = response.msg.src;
      this.save(this.uploadItem, "img", true, true);
      this.initSort();
    }
  },
  mounted: function() {
    this.update();
  },
  components: {},
  watch: {
    isOpenAll(val) {
      for (let i = 0; i < this.tree.length; i++) {
        this.tree[i].isOpen = val;
      }
    }
  }
};
</script>

<style lang="scss" scoped>
@import "class.scss";
</style>