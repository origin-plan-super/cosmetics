<template>
  <div id="goodsAdd">
    <div class="frame">

      <el-form :model="goods" :rules="rules" ref="goodsForm" label-width="150px" size="mini" @submit.native.prevent>

        <el-card>

          <div slot="header" class="text-size-default">
            <el-button type="text" icon="el-icon-back" @click="$router.go(-1)" size="mini"></el-button>
            基本信息
          </div>

          <el-form-item prop="goods_title" label="商品名称">
            <el-input v-model="goods.goods_title" :maxlength="255"></el-input>
          </el-form-item>

          <el-form-item label="商品分类" prop="goods_class">
            <class-select v-model="goods.goods_class"></class-select>
          </el-form-item>

          <el-form-item label='商品规格'>
            <el-button @click='show.spec=!show.spec'>展开/收起选择规格</el-button>
            <el-collapse-transition>
              <spec v-show='show.spec' :sku.sync="goods.sku" :tree.sync="goods.tree"></spec>
            </el-collapse-transition>
          </el-form-item>

          <el-form-item label='选择供货商'>
            <select-supplier v-model="goods.supplier_id"></select-supplier>
          </el-form-item>

          <el-form-item label="商品图片">
            <select-img v-model="goods.img_list"></select-img>
          </el-form-item>

          <el-form-item label="商品详情">
            <editor v-model="goods.goods_content" ref="editor" style="width:100%"></editor>
          </el-form-item>

        </el-card>

        <el-card>
          <div slot="header" class="text-size-default">物流信息</div>
          <div class="cube-half">
            <el-form-item prop="logistics" label="物流模板">
              <el-select v-model="goods.logistics" filterable placeholder="请选择">
                <el-option v-for="item in logistics.template" :key="item.title" :label="item.title" :value="item.title">
                </el-option>
              </el-select>
            </el-form-item>
          </div>
        </el-card>

        <el-card>
          <div slot="header" class="text-size-default">其他信息</div>

          <el-form-item label='购买后是否成为会员' prop='is_unique'>
            <div>
              <el-radio v-model="goods.is_unique" label="1">是</el-radio>
            </div>
            <div>
              <el-radio v-model="goods.is_unique" label="0">否</el-radio>
            </div>
          </el-form-item>

          <el-form-item label='是否立刻上架' prop='is_up'>
            <div>
              <el-radio v-model="goods.is_up" label="1">立刻上架</el-radio>
            </div>
            <div>
              <el-radio v-model="goods.is_up" label="0">暂不上架</el-radio>
            </div>
          </el-form-item>

        </el-card>

        <el-card>
          <el-form-item>
            <el-button type="primary" @click='submitForm()'>保存</el-button>
            <el-button @click="cancelar()">取消</el-button>
          </el-form-item>
        </el-card>

      </el-form>

    </div>

  </div>
</template>


<script src="./edit.js"></script>
<style lang="scss" scoped>
@import "edit.scss";
</style>
<style>
.el-card__header {
  /* padding: 10px 20px; */
}
</style>
