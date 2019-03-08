#  Lemon_Tree 服务接口

[TOC]

## 任务数据

* 验证方式：自定义`X-Auth-Token`请求头
* 特别提醒：任务上传时不需要携带cookie信息，cookie信息由服务器自动分配。

### 任务初始化

* URL路径：`/mission/init`

* 请求方式：Any

* 响应数据：

  ```json
  {
      "status": "success",
      "mission_deleted_count": 0,
      "cookie_deleted_count": 0,
      "receipt_deleted_count": 0,
      "statistic_update_count": 0,
      "user_deleted_count": 0
  }
  ```

* 异常响应：
  - HTTP401：需要身份认证

### 上传单个任务

* URL路径：`/mission`

* 请求方式：POST

* 上传数据：

  * 数据格式：json
  * 约束条件：数据形式按照以下表格形式构成

  ```json
  {
      "method": "GET",
      "host": "baidux.tinoy.xyz",
      "path": "/index.html",
      "header": {
          "referer": "https://www.cnblogs.com/"
      },
      "param": {
          "q": "wolfbolin"
      },
      "data": "a=123",
      "target": 1
  }
  ```

* 响应数据：

  ```json
  {
      "mid": "5c7d03b69eb5ef1cac0052b1"
  }
  ```

* 异常响应：

  * HTTP401：需要身份认证
  * HTTP403：访问参数异常

### 查询单个任务

- URL路径：`/mission?mid={}`

- 请求方式：GET

- 响应数据：

  ```json
  {
      "method": "GET",
      "host": "baidux.tinoy.xyz",
      "path": "/index.html",
      "header": {},
      "param": {
          "q": "wolfbolin"
      },
      "data": "",
      "target": 1,
      "download": 0,
      "upload": 0,
      "success": 0,
      "error": 0,
      "mid": "5c816aa7e43c5d31f40003f2"
  }
  ```

* 异常响应：
  * HTTP401：需要身份认证
  * HTTP403：访问参数异常
  * HTTP406：请求参数无法被响应

### 更新单个任务

- URL路径：`/mission?mid={}`

- 请求方式：PUT

- 上传数据：

  - 数据格式：json
  - 约束条件：数据形式按照以下表格形式构成

  ```json
  {
      "method": "GET",
      "host": "baidux.tinoy.xyz",
      "path": "/index.html",
      "header": {
          "referer": "https://www.cnblogs.com/"
      },
      "param": {
          "q": "wolfbolin"
      },
      "data": "",
      "target": 1,
      "download": 0,
      "upload": 0,
      "success": 0,
      "error": 0
  }
  ```


- 响应数据：

  ```json
  {
      "mid": "5c816aa7e43c5d31f40003f2",
      "mission_modified_count": 1
  }
  ```

- 异常响应：

    - HTTP401：需要身份认证
  - HTTP403：访问参数异常
  - HTTP304：没有数据被修改

### 删除单个任务

- URL路径：`/mission?mid={}`

- 请求方式：DELETE


- 响应数据：新任务的_id

  ```json
  {
      "mid": "5c816aa7e43c5d31f40003f2",
      "mission_modified_count": 1
  }
  ```

- 异常响应：

    - HTTP401：需要身份认证
    - HTTP403：访问参数异常
    - HTTP304：没有数据被修改

## Cookie数据

* 验证方式：自定义`X-Auth-Token`请求头

### 上传单个Cookie

* URL路径：`/cookie`

* 请求方式：POST

* 上传数据：

  * 数据格式：json
  * 约束条件：数据形式按照以下表格形式构成

  ```json
  {
  	"cookie": "my cookie 1",
  	"time": 1551890542
  }
  ```

* 响应数据：

  ```json
  {
      "cid": "5c7d03b69eb5ef1cac0052b1"
  }
  ```

* 异常响应：

  * HTTP401：需要身份认证
  * HTTP403：访问参数异常

### 查询单个Cookie

- URL路径：`/cookie?cid={}`

- 请求方式：GET

- 响应数据：

  ```json
  {
      "cookie": "my cookie 1",
      "time": 1551890542,
      "download": 0,
      "upload": 0,
      "success": 0,
      "error": 0,
      "cid": "5c8175cfe43c5d31f40003f6"
  }
  ```

* 异常响应：
  * HTTP401：需要身份认证
  * HTTP403：访问参数异常
  * HTTP406：请求参数无法被响应

### 更新单个Cookie

- URL路径：`/cookie?cid={}`

- 请求方式：PUT

- 上传数据：

  - 数据格式：json
  - 约束条件：数据形式按照以下表格形式构成

  ```json
  {
      "cookie": "my cookie 1",
      "time": 1551890542,
      "download": 0,
      "upload": 0,
      "success": 0,
      "error": 0
  }
  ```


- 响应数据：

  ```json
  {
      "cid": "5c8175cfe43c5d31f40003f6",
      "cookie_modified_count": 1
  }
  ```

- 异常响应：

    - HTTP401：需要身份认证
  - HTTP403：访问参数异常
  - HTTP304：没有数据被修改

### 删除单个Cookie

- URL路径：`/cookie?cid={}`

- 请求方式：DELETE


- 响应数据：新任务的_id

  ```json
  {
      "cid": "5c8175cfe43c5d31f40003f6",
      "cookie_deleted_count": 1
  }
  ```

- 异常响应：

    - HTTP401：需要身份认证
  - HTTP403：访问参数异常
  - HTTP304：没有数据被修改

## 回执数据

* 特别提醒：

### 获取单个任务

* URL路径：`/receipt`

* 请求方式：GET

* 响应数据：

  ```json
  [
      {
          "method": "GET",
          "host": "baidux.tinoy.xyz",
          "path": "/index.html",
          "header": {},
          "param": {
              "q": "wolfbolin"
          },
          "data": "",
          "target": 1,
          "download": 0,
          "upload": 0,
          "success": 0,
          "error": 0,
          "mid": "5c816b2ae43c5d31f40003f3",
          "cid": "5c8175c4e43c5d31f40003f5",
          "cookie": "my cookie 1"
      }
  ]
  ```

*  异常响应：
  * HTTP403：访问参数异常

### 获取多个任务

* URL路径：`/receipt?num=5`

* 请求方式：GET

* 相关说明：

  * num限定了获取的方案的数量，最小值为2，最大值为10，超过10的值将按照异常访问处理。
  * 响应数据为json数组形式，数组中每个对象为单个任务
  * 可能没有足够多的任务需要完成（例如获取五个但是只有两个任务）

* 响应数据：

  ```json
  [
      {},
      {},
      {}
  ]
  ```

* 异常响应：

  - HTTP403：访问参数异常

### 上传单个回执

- URL路径：`/receipt`

- 请求方式：POST

- 相关说明：

  请逐个上传回执信息，若上传失败请重试

- 发送数据：

  ```json
  {
      "status": "success|error",
      "mid": "5c7d5e749eb5ef1e100011b2",
      "cid": "5c7d74159eb5ef1e100011b4",
      "code": 200,
      "data": "HTML doc",
      "time": 1551840263,
      "user": "Go client(123456789)"
  }
  ```

* 响应数据：

  ```json
  {
      "rid": "5c817ea3e43c5d31f40003f9",
      "mid": "5c7d5e749eb5ef1e100011b2",
      "cid": "5c7d74159eb5ef1e100011b4"
  }
  ```

* 异常响应：

  * HTTP403：访问参数异常

## 数据统计

### 服务状态

- URL路径：`/statistic/healthy`

- 请求方式：GET

- 验证方式：自定义`X-Auth-Token`请求头

- 响应数据：

  ```json
  {
      "healthy": true,
      "mongodb": true
  }
  ```

- 异常响应：

  - HTTP401：需要身份认证

### 数据统计

- URL路径：`/statistic/status`

- 请求方式：GET

- 响应数据：

  ```
  {
      "total_download": 7,
      "stage_download": 7,
      "total_upload": 10,
      "stage_upload": 10,
      "total_success": 10,
      "stage_success": 10,
      "total_error": 0,
      "stage_error": 0,
      "total_user": 8,
      "stage_user": 8
  }
  ```

  

  

## 请求异常

已知的所有异常将以json数据形式反馈信息，格式如下样例所示：

```
{
    "status": "error",
    "info": "错误原因简报"
}
```

### 403 Forbidden

当服务器检测到您请求不符合要求时，将反馈403状态。

### 404 Not found

当您传入了错误的路径或参数指定的数据不存在时，将反馈404状态。

## 数据字典

在完成数据交互时采用以下字段表示数据内容

| 名称               | 含义                                           | 类型   |
| ------------------ | ---------------------------------------------- | ------ |
| rid                | 回执编号                                       | string |
| mid                | 任务编号                                       | string |
| cid                | cookie编号                                     | string |
| rid                | 回执编号                                       | string |
| method             | HTTP请求方法                                   | string |
| host               | HTTP请求主机地址                               | string |
| path               | HTTP请求路径                                   | string |
| header             | HTTP请求头                                     | map    |
| param              | HTTP请求参数（URL参数）                        | map    |
| data               | HTTP请求/响应正文                              | string |
| cookie             | HTTP请求cookie信息（可能会产生cookie重复问题） | string |
| status             | 命令执行结果(success\|error)                   | string |
| code               | HTTP请求状态码                                 | int    |
| time               | HTTP请求响应时间/Cookie更新时间（Unix时间戳）  | int    |
| user               | 用户平台与编号（形如“Go client(0123456789)”）  | string |
| download           | 数据下发次数                                   | int    |
| upload             | 数据回传次数                                   | int    |
| success            | 任务完成次数                                   | int    |
| error              | 任务失败次数                                   | int    |
| xxx_modified_count | xxx项目文档更新数量                            | int    |
| xxx_deleted_count  | xxx项目文档删除数量                            | int    |
| total_download     | 下发总次数                                     | int    |
| stage_download     | 本次下发数                                     | int    |
| total_upload       | 回传总次数                                     | int    |
| stage_upload       | 本次回传数                                     | int    |
| total_success      | 成功总次数                                     | int    |
| stage_success      | 本次成功数                                     | int    |
| total_error        | 失败总次数                                     | int    |
| stage_error        | 本任务失败                                     | int    |
| total_user         | 总参与用户                                     | int    |
| stage_user         | 本次用户数                                     | int    |
|                    |                                                |        |
|                    |                                                |        |
|                    |                                                |        |
|                    |                                                |        |
|                    |                                                |        |
|                    |                                                |        |



