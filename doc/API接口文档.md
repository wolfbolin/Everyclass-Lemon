#  Lemon_Tree 服务接口



## 任务上传

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

   

## 方案获取

### 获取单个方案

* URL路径：`/solution`

* 连接方式：GET

* 响应数据：

  ```json
    {
      "mid": "5c7d5e749eb5ef1e100011b2",
      "method": "GET",
      "host": "baidux.tinoy.xyz",
      "path": "/index.html",
      "header": "",
      "param": {
          "q": "wolfbolin"
      },
      "data": "",
      "cookie": "stardustvideo=1; CURRENT_FNVAL=16"
  }
  ```

### 上传单个方案

- URL路径：`/solution`

- 连接方式：POST

- 发送数据：

  ```json
  {
      "status": "success|error",
      "cid": "5c7d74159eb5ef1e100011b4",
      "mid": "5c7d5e749eb5ef1e100011b2",
      "code": "200",
      "data": "HTML doc",
      "time": "2019-3-5 12:00",
      "user_ip": "114.114.114.114",
      "user_ua": "Go client(123456789)"
  }
  ```

## 数据统计

未完待续



