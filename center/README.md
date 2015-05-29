目录说明
====

* server: 所有Server程序
* web: 网页和web程序
* extension: 相关PHP扩展

php扩展
----
基于[PHP-CPP](https://github.com/CopernicaMarketingSoftware/PHP-CPP)构建的，请务必安装PHP-CPP工具  

server
----
基于swoole扩展，需要安装[swoole扩展](https://github.com/matyhtf/swoole)
```shell
pecl install swoole
```

web框架
----
已使用composer做依赖管理，在项目根目录执行下面的指令
```shell
composer install
```
