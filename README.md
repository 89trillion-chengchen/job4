# 1.整体思路
### 1.登陆注册
![Image text](https://raw.githubusercontent.com/89trillion-chengchen/project4/master/images/%E6%B5%81%E7%A8%8B%E5%9B%BE1.jpg)
### 2.使用礼品码
![Image text](https://raw.githubusercontent.com/89trillion-chengchen/job3/master/images/%E6%B5%81%E7%A8%8B%E5%9B%BE3.jpg)

# 2.接口设计

### （1）登陆注册 
####请求方法  
```php 
HTTP POST
```
#### 接口地址   
```php 
http://89tr.chengchen.com/Login/login
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

# 3.目录结构

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
## 3.1 逻辑分层
  ```php

    classes/ctrl是请求控制层

    classes/service是业务控制层

    classes/unitTest是测试层

    webroot/index.php是入口
  ```
## 4.运行和测试
### 4.1 如何部署运行服务
  ```php
使用docker运行容器，容器包含 nginx、php、php-fpm

配置文件根目录为项目根目录webroot，运行端口为8000
  ```
### 4.2 如何测试接口
  ```php
  终端进入 report 目录
  运行 locust 
  ```