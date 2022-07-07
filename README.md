# 文件备份共享平台

## 接口文档

### 授权

> GET /oauth/authorize

- 入参

| 参数           | 类型     | 备注             |
|--------------|--------|----------------|
| redirect_uri | String | 重定向的地址         |
| state        | String | 自定义数据，回调时会携带回来 |

### 登录

> POST /oauth/login

- 入参

| 参数 | 类型   | 备注                    |
| ---- | ------ | ----------------------- |
| code | String | 重定向后自动携带的 Code |

- 出参

| 参数  | 类型   | 备注               |
| ----- | ------ | ------------------ |
| token | String | 登录后返回的 Token |

### 新增/修改文件

> POST /file/{id:\d+}

- 入参

| 参数      | 类型      | 示例                            | 备注         |
|---------|---------|-------------------------------|------------|
| id      | Integer | 1                             | 0为新增，>0为修改 |
| path    | String  | /markdown/fs-server/README.md | 文件路径       |
| summary | String  | 这是一份 README 文件                | 描述         |
| tags    | Array   | ["Markdown", "文档"]            | 0为新增，>0为修改 |
| file    | File    | 无                             | 需要备份的文件    |

### 文件列表

> GET /file

- 入参

| 参数      | 类型     | 示例  | 备注   |
|---------|--------|-----|------|
| dirname | String | /   | 文件目录 |

- 出参

| 参数                | 类型       | 备注         |
|-------------------|----------|------------|
| count             | Integer  | 当前目录下的文件总数 |
| list              | Array    | 文件列表       |
| list.*.id         | Integer  | 文件ID       |
| list.*.path       | String   | 文件路径       |
| list.*.title      | String   | 文件名        |
| list.*.summary    | String   | 文件描述       |
| list.*.tags       | Array    | 文件标签       |
| list.*.version    | Integer  | 文件版本       |
| list.*.is_dir     | Boolean  | 是否是文件夹     |
| list.*.created_at | Datetime | 创建时间       |
| list.*.updated_at | Datetime | 更新时间       |

### 文件详情

> GET /file/{id:\d+}

- 出参

| 参数         | 类型       | 备注     |
|------------|----------|--------|
| id         | Integer  | 文件ID   |
| path       | String   | 文件路径   |
| title      | String   | 文件名    |
| summary    | String   | 文件描述   |
| tags       | Array    | 文件标签   |
| version    | Integer  | 文件版本   |
| is_dir     | Boolean  | 是否是文件夹 |
| created_at | Datetime | 创建时间   |
| updated_at | Datetime | 更新时间   |

### 批量读取下载地址

> POST /file/download-url

- 入参

| 参数  | 类型    | 示例      | 备注      |
|-----|-------|---------|---------|
| ids | Array | [1,2,3] | 文件ID 列表 |

- 出参

| 参数         | 类型      | 备注     |
|------------|---------|--------|
| list       | Array   |        |
| list.*.id  | Integer | 文件ID   |
| list.*.url | String  | 文件下载地址 |
