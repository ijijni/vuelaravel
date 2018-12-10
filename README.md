# vuelaravel
vue+laravel rbac
使用方式，首先配置好，nodjs运行环境和PHP运行环境
进入frontEnd
运行npm install 安装前端依赖包 运行npm run dev，开启前端测试环境

进入PHP，运行composer install 安装laravel第三方依赖包

把php/database 目录下的vuelaravel.sql 导入数据库

把php目录下的.env.example文件复制为.env,然后配置好.env里面的数据库连接配置

后台登陆默认账号和密码为，admin vuelaravel

前端frontEnd/build 目录下的webpack.base.conf.js文件，需要修改后端访问地址，如下
var DEV_HOST = JSON.stringify('http://localhost:38083/')
var PUB_HOST = JSON.stringify('')

程序默认开发后端地址为，http://localhost:38083/，生产环境则为后端的地址


demo演示地址为：http://demo.vuelaravel.net 账号：admin 密码：vuelaravel

官网：http://www.vuelaravel.net

更详细的开发文档：https://www.kancloud.cn/ijijni/vuelaravel



