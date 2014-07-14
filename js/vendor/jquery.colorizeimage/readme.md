Небольшой плагин "окрашивающий" картинку

Как использовать

* Задать любой класс черно-белой картинке, например .uncolored
* Добавить к тегу img (к черной белой картнке) атрибут data-colored в котором сожержится путь к цветой картинке
* Инициализировать плагин для картинок с данным классом (.uncolored)


```html
 <img alt="" src="images/image.jpg" data-colored="img/google-color.png" class="uncolored" />
```


```javascript
$(document).ready(function(){
  $('.uncolored-images').ColorizeImage();
});
```