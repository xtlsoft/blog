```yaml
route: /read/${category}/${id}.html
title: 阅读文章
template: read.html.php
id: ${id}
category: ${category}
offset: ${offset}
```

```php
global $articles;
$rslt = [];
foreach ($articles as $cid=>$category) {
    foreach ($category as $offset=>$article) {
        $rslt[] = [
            "id" => $article['id'],
            "category" => $cid,
            "offset" => $offset
        ];
    }
}
return $rslt;
```
