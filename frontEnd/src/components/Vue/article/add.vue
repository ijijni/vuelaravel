<template>
  <div>
  <h1>编辑邮件模板</h1>
  标题：<el-input v-model="title" placeholder="请输入模板标题" style="width:40%"></el-input>
  <div style="margin: 20px 0;"></div>
  备注：<el-input
  type="textarea"
  :rows="2"
  placeholder="请输入模板备注"
  v-model="note" style="width:40%">
</el-input>
  <div style="margin-top:40px">
    <div class="editor-container">
      <UE :defaultMsg=defaultMsg :config=config ref="ue"></UE>
    </div>
  </div>
  <div style="margin: 20px 0;"></div>
  <el-button type="primary" @click="getAlldata">保存</el-button>
  <el-button type="primary" @click="goback">返回</el-button>
  </div>
</template>
<style>
</style>
<script>
  import btnGroup from '../../Common/btn-group.vue'
  import http from '../../../assets/js/http'
  import UE from '../../Common/ue.vue';
  export default {
    data() {
      return {
        loading: 'false',
        title: '',
        note: '',
        defaultMsg: '这里填写邮件内容',
        config: {
          initialFrameWidth: null,
          initialFrameHeight: 350
        }
      }
    },
    methods: {
      getUEContent() {
        let content = this.$refs.ue.getUEContent();
        this.$notify({
          title: '获取成功，可在控制台查看！',
          message: content,
          type: 'success'
        });
        console.log(content)
      },
      getAlldata() {
        this.loading = true
        let content = this.$refs.ue.getUEContent()
        let data = {
          title: this.title,
          note: this.note,
          content: content
        }
        this.apiPost('vue/article/savetemplate', data).then((res) => {
          // console.log(res)
          this.handelResponse(res, (data) => {
            _g.toastMsg('success', '添加成功')
            setTimeout(() => {
              this.goback()
            }, 1500)
          })
        })
      },
      goback() {
        this.$router.push({ path: '/vue/article/list' });
      },
      getKeywords() {
        this.keywords = ''
        this.currentPage = 1
      },
      init() {
        _g.closeGlobalLoading()
        // this.loading = true
        this.getKeywords()
        // this.getAlldata()
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
      btnGroup,
      UE
    },
    mixins: [http],
    filters: {
      countchannel: function (value) {
        return value ? JSON.parse(value).length : 0
      }
    }
  }
</script>