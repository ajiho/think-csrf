# think-csrf

是基于thinkphp6.x封装的一个防止csrf攻击的composer包

## 为什么还要封装think-csrf

thinkphp官方的表单令牌其实从实际开发角度来说，它只适合用来防止表单重复提交(虽然官方文档上说可以防止csrf攻击)。
因为框架在验证表单令牌通过后会立马删除session中的token,这样对于ajax提交的方式是非常不友好的，
因为页面没有刷新而session中的表单令牌已经更新导致再次提交表单会失败,
该依赖包就是用来解决这个问题,且验证csrf的token值是长时间保存在cookie中的,
相对于官方表单令牌保存在session中有不会过期、和减轻服务端压力的特点。

防止表单重复提交和防止csrf攻击应该分开来做，你在使用`think-csrf`的同时也不影响你使用tp官方的表单令牌,它
们不会产生冲突

# 安装

```
composer require ajiho/think-csrf
```

# 配置

/config/csrf.php

```php
<?php
return [
    //cookie中的key(可自行更换)
    'cookie_key' => '0cc175b9c0f1b6a831c399e269772661',
    //传递的csrf参数名称
    'param_key' => '_token',
];
```

# 使用

```php
\ajiho\middleware\VerifyCsrfToken::class
```


## 表单提交

下面是示例,是基于[think-smarty](https://gitee.com/ajiho/think-smarty)模板引擎来定义的，
如果你是tp框架自带的模板引擎，那么调用函数的语法应该是`{:csrf_field()}`

```html
<form action="" method="post">
    <{ csrf_field() }>
  <div class="form-group">
  <label for="email">Email address:</label>
  <input type="email" class="form-control" placeholder="Enter email" id="email">
  </div>
  <div class="form-group">
  <label for="pwd">Password:</label>
  <input type="password" class="form-control" placeholder="Enter password" id="pwd">
  </div>
  <div class="form-group form-check">
  <label class="form-check-label">
    <input class="form-check-input" type="checkbox"> Remember me
  </label>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
```

## AJAX提交
如果是AJAX提交的表单，可以将`token`设置在`meta`中
~~~
<meta name="csrf-token" content="<{ csrf_token() }>">
~~~

或直接在视图文件中调用`csrf_meta()`函数也能生成上面的meta标签


然后在全局Ajax中使用这种方式设置X-CSRF-Token请求头并提交：

```javascript
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
```


## 助手函数

### csrf_field()
返回一个携带token的隐藏域

### csrf_meta()
返回一个携带token的meta标签

### csrf_token()
只返回token值

