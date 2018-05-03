<template>
  <div>
    <script id="editor" type="text/plain"></script>
  </div>
</template>
<script>
import '../../../static/ueditor/ueditor.config.js'
import '../../../static/ueditor/ueditor.all.min.js'
import '../../../static/ueditor/lang/zh-cn/zh-cn.js'
import '../../../static/ueditor/ueditor.parse.min.js'
export default {
  name: 'UE',
  data () {
    return {
      editor: null
    }
  },
  props: {
    defaultMsg: {
      type: String
    },
    config: {
      type: Object
    }
  },
  mounted() {
    const _this = this;
    this.editor = UE.getEditor('editor', this.config); // 初始化UE
    this.editor.addListener('ready', function () {
      _this.editor.execCommand('serverparam', function(editor) {
        return {
          'authkey': Lockr.get('authKey'),
          'sessionid': Lockr.get('sessionId')
        }
      });
      _this.editor.setContent(_this.defaultMsg); // 确保UE加载完成后，放入内容。
    });
  },
  methods: {
    getUEContent() { // 获取内容方法
      return this.editor.getContent()
    },
    setUEContent(value) { // 设置内容方法
      return this.editor.setContent(value)
    }
  },
  destroyed() {
    this.editor.destroy();
  }
}
</script>