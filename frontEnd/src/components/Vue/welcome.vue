<template>
	<div>
<span :center="true">欢迎页面</span>
  <h4>欢迎 <span v-text="username"></span></h4>
  </div>
</template>

<script>
  import btnGroup from '../Common/btn-group.vue'
  import http from '../../assets/js/http'

  export default {
    data() {
      return {
        username: ''
      }
    },
    methods: {
      search() {
        router.push({ path: this.$route.path, query: { keywords: this.keywords, page: 1 }})
      },
      selectItem(val) {
        this.multipleSelection = val
      },
      getOption(props) {
        this.options1 = props
        // console.log(props)
      },
      getUsername() {
        this.username = Lockr.get('userInfo').username
      },
      init() {
        this.getUsername()
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
    mixins: [http]
  }
</script>