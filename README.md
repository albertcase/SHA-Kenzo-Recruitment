# SHA-Kenzo-Recruitment`s API

### 1. 提交信息API

Method: POST

##### API URL:

```html
domian/api/submit
```
##### Get Parameter

name: 张三, moblile: 13112345678, province:上海, city:上海, area:黄浦区, address:湖滨路

```javascript
{
name: '张三',
tel: '13112345678',
province: '上海',
city: '上海',
area: '黄浦区',
address: '湖滨路'
}
```

##### Response

##### status 1

```javascript
{
status: '1',
msg: '信息提交成功',
userStatus: {
    "isold": 0,
    "isgift": 1,
    "issubmit": 1,
    "isluckydraw": 0
  }
}
```

#####  status 0

```javascript
{
status: '0',
msg: '信息提交失败',
userStatus: {
    "isold": 0,
    "isgift": 1,
    "issubmit": 0,
    "isluckydraw": 0
  }
}
```

---

### 2. 领取小样API

Method: POST

##### API URL:

```html
domian/api/gift
```
##### Get Parameter
null

```javascript
{

}
```

##### Response

##### status 1

```javascript
{
status: '1',
msg: '小样领取成功',
userStatus: {
    "isold": 0,
    "isgift": 1,
    "issubmit": 1,
    "isluckydraw": 0
  }
}
```

#####  status 0

```javascript
{
status: '0',
msg: '非新关注用户没有领取资格',
userStatus: {
    "isold": 0,
    "isgift": 0,
    "issubmit": 0,
    "isluckydraw": 0
  }
}
```