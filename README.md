# 说明文档

需要进去容器里面先执行

## 安装

1. 安装依赖

    ```shell
    composer install
    ```

2. 创建数据库

    默认是 `13306`端口  
    账号: root  
    密码: 123456  

    ```shell
    创建数据库名字：`yexk`  
    字符集是： `utf8mb4`、`utf8mb4_general_ci`
    ```

3. 创建数据表

    ```shell
    php bin/swoft migrate:up
    ```
