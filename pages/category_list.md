```yaml
route: /category/${category}/${page}.html
title: ${category_name}
template: list.html.php
category: ${category}
limit:
  page: ${page}
  number: 10
```

```php
global $categories, $articles;
$rslt = [];
foreach ($categories as $id=>$n) {
  if (!isset($articles[$id]) || $articles[$id] == false) continue;
  $number = count($articles[$id]);
  $rslt = array_merge($rslt, array_map(
    function ($item) use ($id, $n, $number) {
      return ['page'=>$item, 'category'=>$id, 'category_name'=>$n];
    },
    range(1, ceil($number / 10))
  ));
}
return $rslt;
```
