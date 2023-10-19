# RPT

## 1. Описание задачи
Создать скрипт, который в папке `/datafiles` найдет все файлы, имена которых состоят из цифр и букв латинского алфавита, имеют расширение ixt и выведет на экран имена этих файлов, упорядоченных по имени.

Задание выполнено в файле `FileSearch.php`.  
Дополнительно реализована возможность рекурсивного поиска файлов в указанной директории.
### Примеры использования
```
php FileSearch.php
```
```
php FileSearch.php --path ./datafiles
```
```
php FileSearch.php --path ./datafiles --recursive
```
```
php FileSearch.php --recursive
```
```
php FileSearch.php --recursive --path ./datafiles
```

## 2. Описание задачи
Написать класс Init на php, от которого нельзя сделать наследника, состоящий из 3 методов:
- `create()`. Доступен только для методов класса, создает таблицу `test`, содержащую 5 полей;
- `fill()`. Доступен только для методов класса, заполняет таблицу случайными данными;
- `get()`. Доступен извне класса, выбирает из таблицы `test` данные по критерию: `result` среди значений `'normal'` и `'success'`.

В конструкторе выполняются методы `create()` и `fill()`.

Задание реализовано в файле `init.php`.  
БД использована для простоты SQLite.
### Примеры использования
```
php init.php
```
Также можно при вызове передать путь до директории, где хранится файл БД.
```
php init.php ./db
```
## 3. Описание задачи
Cоздать 3 кнопки с названиями 1, 2, 3, расположенные друг над другом, используя jQuery.

Начальный вид:  
1  
2  
3

Нажали на любую кнопку, меняется порядок на:  
2  
3  
1

Нажали на любую кнопку, меняется порядок на:  
3  
1  
2

Нажали на любую кнопку, меняется порядок на:  
1  
2  
3

Реализация в файле `index.html`
## 4. Описание задачи
Оптимизировать запрос БД.

Полное описание и реализация в файле `sql_optimization.md`
