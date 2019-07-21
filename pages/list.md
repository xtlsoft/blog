```yaml
route: /list/${page}.html
title: 文章列表
template: list.html.php
category: all
limit:
  page: ${page}
  number: 10
```

```php
return array_map(
  function ($item) {
    return ['page'=>$item];
  },
  range(1, ceil(getArticleNumber() / 10))
);
```
