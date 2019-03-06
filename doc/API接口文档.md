#  Lemon_Tree 服务接口

## 数据字典

在完成数据交互时采用以下字段表示数据内容

| 名称   | 含义                 | 类型 |
| ------ | -------------------- | ------ |
| mid   | 任务编号             | string |
| cid    | cookie编号           | string |
|rid|回执编号| string |
| method | HTTP请求方法         | string |
| host   | HTTP请求主机地址       | string |
| path   | HTTP请求路径       | string |
| header | HTTP请求头 | map |
| param  | HTTP请求参数（URL参数） | map |
| data   | HTTP请求/响应正文 | string |
| cookie | HTTP请求cookie信息（可能会产生cookie重复问题） | string |
| status | 命令执行结果(success\|error)   | string |
| code   | HTTP请求状态码       | int |
| time   | HTTP请求响应时间（Unix时间戳） | int |
| user   | 用户平台与编号（形如“Go client(0123456789)”） | string |

## 任务数据

* 路径前缀：`/mission`
* 验证方式：参数加密
* 特别提醒：任务上传时不需要携带cookie信息，cookie信息由服务器自动分配。

### 上传单个任务

* URL路径：`/upload`

* 连接方式：POST

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

* 响应数据：新任务的_id

  ```
  {
      "_id": "5c7d03b69eb5ef1cac0052b1"
  }
  ```

* 异常响应：未知

### 更新单个任务

- URL路径：`/update?_id={}`

- 连接方式：PUT

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


- 响应数据：新任务的_id

  ```
  {
      "_id": "5c7d03b69eb5ef1cac0052b1"
  }
  ```

- 异常响应：未知

### 查询单个任务

- URL路径：`/select?_id={}`

- 连接方式：GET

- 响应数据：

  - 任务文档信息

  ```json
    {
      "_id": "5c7d5e749eb5ef1e100011b2",
      "method": "GET",
      "host": "baidux.tinoy.xyz",
      "path": "/index.html",
      "header": {
          "referer": "https://www.cnblogs.com/"
      },
      "param": {
          "q": "wolfbolin"
      },
      "cookie": "",
      "data": "",
      "download": 0,
      "upload": 0,
      "target": 4,
      "success": 2,
      "error": 0
  }
  ```

   

## 回执数据

### 获取单个任务

* URL路径：`/receipt`

* 连接方式：GET

* 响应数据：

  ```json
    {
      "rid": "5c7d5e749eb5ef1e100011b2",
      "cid": "5c7d74159eb5ef1e100011b4",
      "method": "GET",
      "host": "baidux.tinoy.xyz",
      "path": "/index.html",
      "header": {},
      "param": {
          "q": "wolfbolin"
      },
      "data": "",
      "cookie": "stardustvideo=1; CURRENT_FNVAL=16"
  }
  ```

### 获取多个任务

* URL路径：`/receipt?num=5`

* 连接方式：GET

* 相关说明：

  * num限定了获取的方案的数量，最小值为2，最大值为10，超过10的值将按照异常访问处理。
  * 响应数据为json数组形式，数组中每个对象为单个任务

* 响应数据：

  ```json
  [
      {},
      {},
      {}
  ]
  ```

  

### 上传单个回执

- URL路径：`/receipt`

- 连接方式：POST

- 相关说明：

  请逐个上传回执信息，若上传失败请重试



- 发送数据：

  ```json
  {
      "status": "success|error",
      "rid": "5c7d5e749eb5ef1e100011b2",
      "cid": "5c7d74159eb5ef1e100011b4",
      "code": 200,
      "data": "HTML doc",
      "time": 1551840263,
      "user": "Go client(123456789)"
  }
  ```

## 数据统计

未完待续



