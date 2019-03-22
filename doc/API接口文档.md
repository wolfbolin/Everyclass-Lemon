#  Lemon_Tree 服务接口

**协议版本：v0.1.0**

**Protocol Version：v0.1.0**

[TOC]

## 综述

## 更新日志

**特别提醒**：所有线上服务，协议版本均应保持版本号前两位为一致的。

**更新原则**：若只发生文档更新，前两位版本号将不发生变化。若接口内容发生变化，协议版本号前两位将会被更新并写入更新日志。

### v0.1.0

初始版本，未发布前版本



## 任务数据

* 验证方式：自定义`X-Auth-Token`请求头
* 特别提醒：任务上传时不需要携带cookie信息，cookie信息由服务器自动分配。

### 任务初始化

* URL路径：`/mission/init`

* 请求方式：GET

* 相关说明：该操作将清空所有mission和cookie信息，并清空统计信息中包含`stage`的字段。

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
      "scheme": "http",
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
      "mid": "5c83e661e43c5d3a80007ae4",
      "status": "success"
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
      "scheme": "http",
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
      "mid": "5c83e661e43c5d3a80007ae4",
      "status": "success"
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
      "scheme": "http",
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
      "mid": "5c83e661e43c5d3a80007ae4",
      "mission_modified_count": 1,
      "status": "success"
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
      "mid": "5c83e661e43c5d3a80007ae4",
      "mission_deleted_count": 1,
      "status": "success"
  }
  ```

- 异常响应：

    - HTTP401：需要身份认证
    - HTTP403：访问参数异常
    - HTTP304：没有数据被修改

### 上传多个任务

* URL路径：`/mission/bulk`

* 请求方式：POST

* 上传数据：

  * 数据格式：json
  * 约束条件：单个任务对象的内容参考单任务样例

  ```json
  [
      {},
      {},
      {}
  ]
  ```

* 响应数据：

  ```json
  {
      "info": [
          "5c83e659e43c5d3a80007ae2",
          "5c83e659e43c5d3a80007ae3"
      ],
      "status": "success"
  }
  ```

* 异常响应：

  * HTTP401：需要身份认证
  * HTTP403：访问参数异常

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
      "cid": "5c83f148e43c5d3a80007ae8",
      "status": "success"
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
      "cid": "5c83f148e43c5d3a80007ae8",
      "status": "success"
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
      "cid": "5c83f148e43c5d3a80007ae8",
      "cookie_modified_count": 1,
      "status": "success"
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
      "cid": "5c83edace43c5d3a80007ae7",
      "cookie_deleted_count": 1,
      "status": "success"
  }
  ```

- 异常响应：

  - HTTP401：需要身份认证
  - HTTP403：访问参数异常
  - HTTP304：没有数据被修改

### 上传多个Cookie

* URL路径：`/cookie/bulk`

* 请求方式：POST

* 上传数据：

  - 数据格式：json
  - 约束条件：单个任务对象的内容参考单任务样例

  ```json
  [
      {},
      {},
      {}
  ]
  ```

* 响应数据：

  ```json
  {
      "info": [
          "5c83eabde43c5d3a80007ae5",
          "5c83eabde43c5d3a80007ae6"
      ],
      "status": "success"
  }
  ```

* 异常响应：

  * HTTP401：需要身份认证
  * HTTP403：访问参数异常

### 查询多个Cookie

- URL路径：`/cookie/bulk?num=`
- 请求方式：GET
- 相关说明：
  - num限定了获取的方案的数量，最小值为1，最大值为20，超过20的值将按照异常访问处理。
  - 响应数据为json数组形式，数组中每个对象为单个Cookie信息
  - 可能没有足够多的Cookie被返回（例如获取五个但是只有两个Cookie信息）
  - 默认获取20条Cookie数据，优先列出error值较高的。

- 响应数据：

  ```json
  {
      "status": "success",
      "data": [
          {},
          {},
          {},
          {}
      ]
  }
  ```

- 异常响应：

  - HTTP401：需要身份认证
  - HTTP403：访问参数异常
  - HTTP304：没有数据被修改

### 查询Cookie列表

- URL路径：`/cookie/list`

- 请求方式：GET

- 相关说明：

  - 响应中包含了所有Cookie的列表

- 响应数据：

  ```json
  {
      "status": "success",
      "number": 4,
      "cid": [
          "5c82346be43c5d4b6c006383",
          "5c83eabde43c5d3a80007ae5",
          "5c83eabde43c5d3a80007ae6",
          "5c83f148e43c5d3a80007ae8"
      ]
  }
  ```

- 异常响应：

  - HTTP401：需要身份认证
  - HTTP403：访问参数异常
  - HTTP304：没有数据被修改

## 回执数据

* 特别提醒：

### 获取单个任务

* URL路径：`/task`

* 请求方式：GET

* 相关说明：

  * 当Cookie真的不存在时，cid和cookie字段仍然存在，但是为空。回传时仍需保留cid字段。

* 响应数据：

  ```json
  {
      "status": "success",
      "data": [
          {
              "method": "GET",
              "scheme": "http",
              "host": "baidux.tinoy.xyz",
              "path": "/index.html",
              "header": {},
              "param": {
                  "q": "wolfbolin"
              },
              "data": "",
              "mid": "5c83e659e43c5d3a80007ae2",
              "cid": "5c83f148e43c5d3a80007ae8",
              "cookie": "my cookie 1"
          }
      ],
      "info": {
          "num": 1,
          "count": 1
      }
  }
  ```

* 异常响应：

  * HTTP401：需要身份认证
  * HTTP403：访问参数异常

### 获取多个任务

* URL路径：`/task?num=5`

* 请求方式：GET

* 相关说明：

  * num限定了获取的方案的数量，最小值为2，最大值为10，超过10的值将按照异常访问处理。
  * 响应数据为json数组形式，数组中每个对象为单个任务
  * 可能没有足够多的任务需要完成（例如获取五个但是只有两个任务）

* 响应数据：单个任务对象的内容参考单任务样例

  ```json
  {
      "status": "success",
      "data": [
          {
              "method": "GET",
              "scheme": "http",
              "host": "baidux.tinoy.xyz",
              "path": "/index.html",
              "header": {},
              "param": {
                  "q": "wolfbolin"
              },
              "data": "",
              "mid": "5c83e659e43c5d3a80007ae2",
              "cid": "5c83f148e43c5d3a80007ae8",
              "cookie": "my cookie 1"
          },
          {
              "method": "GET",
              "scheme": "http",
              "host": "baidux.tinoy.xyz",
              "path": "/index.html",
              "header": {},
              "param": {
                  "q": "wolfbolin"
              },
              "data": "",
              "mid": "5c83e659e43c5d3a80007ae2",
              "cid": "5c83f148e43c5d3a80007ae8",
              "cookie": "my cookie 1"
          }
      ],
      "info": {
          "num": 3,
          "count": 2
      }
  }
  ```

* 异常响应：

  * HTTP401：需要身份认证
  - HTTP403：访问参数异常

### 回执单个任务

- URL路径：`/task`

- 请求方式：POST

- 相关说明：

  * 请逐个上传回执信息，若上传失败请重试
  * 即使cid为空，依然需要回传该字段，不可省略。

- 发送数据：

  ```json
  {
      "status": "success|error",
      "mid": "5c7d5e749eb5ef1e100011b2",
      "cid": "5c7d74159eb5ef1e100011b4",
      "code": 200,
      "data": "HTML doc",
      "time": 1551840263,
      "user": "Go client(UUID)"
  }
  ```

* 响应数据：

  **该响应格式将在0.2.0版本修改**

  ```json
  {
      "rid": "5c817ea3e43c5d31f40003f9",
      "mid": "5c7d5e749eb5ef1e100011b2",
      "cid": "5c7d74159eb5ef1e100011b4"
  }
  ```

* 异常响应：

  * HTTP401：需要身份认证
  * HTTP403：访问参数异常

## 服务信息

## 心跳包

* URL路径：`/hello_world`

* 请求方式：GET

* 响应数据：

  ```json
  {
      "status": "success",
      "info": "Hello, world!"
  }
  ```

### 协议版本

* URL路径：`/info/version`

* 请求方式：GET

* 响应数据：

  ```json
  {
      "status": "success",
      "version": "0.1.0"
  }
  ```

### 服务状态

- URL路径：`/info/healthy`

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

- URL路径：`/info/status`

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
| scheme             | HTTP/HTTPS                                     | string |
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



