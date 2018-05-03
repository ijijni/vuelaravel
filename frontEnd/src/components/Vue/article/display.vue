<template>
  <div>
  <h1>查看邮件模板</h1>
  标题：<el-input v-model="formInline.title" placeholder="请输入模板标题" style="width:40%"></el-input>
  <div style="margin: 20px 0;"></div>
  备注：<el-input
  type="textarea"
  :rows="2"
  placeholder="请输入模板备注"
  v-model="formInline.note" style="width:40%">
</el-input>
  <div style="margin-top:40px">
    <div >
    <span v-html="formInline.content"></span>
    </div>
  </div>
  <div style="margin: 20px 0;"></div>
  <el-button type="primary" @click="goback">返回</el-button>
  </div>
</template>

<script>
  import btnGroup from '../../Common/btn-group.vue'
  import http from '../../../assets/js/http'

  export default {
    data() {
      return {
        tableData1: []
      }
    },
    props: ['formInline'],
    methods: {
      getAlldata() {
        this.loading = true
      },
      goback() {
        this.$parent.firstShow = true
        this.$parent.secondShow = false
        this.$parent.fouthShow = true
      },
      init() {
        this.getAlldata()
      }
    },
    created() {
      this.init()
    },
    computed: {
      addShow() {
        return _g.getHasRule('users-save')
      },
      editShow() {
        return _g.getHasRule('users-update')
      },
      deleteShow() {
        return _g.getHasRule('users-delete')
      }
    },
    watch: {
      '$route' (to, from) {
        this.init()
      }
    },
    components: {
      btnGroup
    },
    mixins: [http],
    filters: {
    }
  }
</script>