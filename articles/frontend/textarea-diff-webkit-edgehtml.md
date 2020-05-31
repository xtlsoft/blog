# Edge 和 Chrome 在 textarea 的 DOM API 上的一个小区别

% xtlsoft, 2019-07-31 14:16:03

~~标题真长~~

## 组件的样子

```html
<textarea id="test_textarea"></textarea>
```

然后向内输入内容：`Hello, World`

## 在 Edge 上

```javascript
let elem = document.findElementById("test_textarea");
console.log(elem.value);
// Reply: Hello, World
console.log(elem.innerHTML);
// Reply: Hello, World
```

## 在 Chrome 上

```javascript
let elem = document.findElementById("test_textarea");
console.log(elem.value);
// Reply: Hello, World
console.log(elem.innerHTML);
// Reply: null
```

## 后记

~~文章真好水~~

就酱
