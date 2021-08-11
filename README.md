# 1.整体思路
### 1.登陆注册
![Image text](https://raw.githubusercontent.com/89trillion-chengchen/project4/master/images/%E6%B5%81%E7%A8%8B%E5%9B%BE1.jpg)
### 2.使用礼品码
![Image text](https://raw.githubusercontent.com/89trillion-chengchen/project4/master/images/%E6%B5%81%E7%A8%8B%E5%9B%BE2.jpg)
# 2.存储设计

（1）用户表

| 内容     | 数据库 | key        | 类型     |
| -------- | ------ | ---------- | -------- |
| 主键     | mysql  | id         | int      |
| 用户昵称 | mysql  | name       | varchar  |
| 金币数   | mysql  | coin       | int      |
| 钻石数   | mysql  | diamond    | int      |
| 创建时间 | mysql  | creatTime  | datetime |
| 更新时间 | mysql  | updateTime | datetime |

（2）用户奖励表

| 内容       | 数据库     | key          | 类型     |
| ---------- | ---------- | ------------ | -------- |
| 主键       | mysql      | id           | int      |
| 用户id | mysql      | uid  | int      |
| 英雄   | mysql      | hero      | varchar |
| 道具     | mysql      | props   | varchar |
| 士兵   | mysql     | soldier | varchar |

# 3.接口设计

### （1）登陆注册 
####请求方法  
```php 
HTTP POST
```
#### 接口地址   
```php 
http://89tr.chengchen.com/Login/login
```
#### 请求参数
```php 
{
    "uid":"1"
}
```
#### 响应
```php 
{
    "status": 200,
    "msg": "ok",
    "data": {
        "id": "1",
        "name": "吴一凡",
        "coin": "1201",
        "diamond": "2135",
        "creatTime": "2021-08-04 20:14:42",
        "updateTime": "2021-08-05 11:29:39"
    }
}
```
### （2）客户端调用 - 验证礼品码
####请求方法
```php 
HTTP POST
```
#### 请求参数
```php 
{
    "uid":"1",
    "code":"code_O0afeWfR",
    "role":"1"
}
```
#### 接口地址
```php 
http://89tr.chengchen.com/Login/useCode
```
#### 响应
```php 
{
    "status": 200,
    "msg": "ok",
    "data": {
        "id": "1",
        "name": "吴一凡",
        "coin": "1335",
        "diamond": "3425",
        "creatTime": "2021-08-04 20:14:42",
        "updateTime": "2021-08-05 12:26:02",
        "hero": [
            "预言家",
            "法师"
        ],
        "soldier": [
            "弓箭手",
            "士兵"
        ],
        "props": [
            "十连抽",
            "奖券"
        ]
    }
}
```

# 4.目录结构

```php 
.
├── README.md
├── classes
│   ├── ctrl
│   │   ├── GiftCodeCtrl.php
│   │   ├── LoginCtrl.php
│   ├── entity
│   │   ├── user.php
│   ├── service
│   │   ├── AnswerService.php
│   │   ├── BaseService.php
│   │   ├── CacheService.php
│   │   ├── GiftCodeService.php
│   │   ├── LoginService.php
│   │   └── SampleService.php
│   ├── unitTest
│   │   ├── GiftCodeServiceTest.php
│   │   └── LoginServiceTest.php
├── report
│   ├── locustfile.py
│   └── report_1628134286.6186502.html
└── webroot
    └── index.php

```
## 4.1 逻辑分层
  ```php

    classes/ctrl是请求控制层

    classes/service是业务控制层

    classes/unitTest是测试层

    webroot/index.php是入口
  ```
## 5.运行和测试
### 5.1 如何部署运行服务
  ```php
使用docker运行容器，容器包含 nginx、php、php-fpm

配置文件根目录为项目根目录webroot，运行端口为8000
  ```
### 5.2 如何测试接口
  ```php
  终端进入 report 目录
  运行 locust 
  ```