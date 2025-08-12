# Тестовый проект Laravel todo list api.


## Docker

Для старта и тестирования выполните следующие команды.

```
docker compose up -d
```

```
docker compose exec php /bin/bash
```

Первичная подготовка Laravel. Скопируйте и вставьте внутри контейнера при первом запуске
```
cp .env.example .env.local && php artisan key:generate && touch database/database.sqlite && php artisan migrate
```

```
php artisan test
```


## Тестирования через curl.

Создание задачи
```
curl -X POST -H "Content-Type: application/json" -d '{ "title": "Задача №1", "description": "Купить молоко", "status": false}' http://localhost/api/tasks
```

Получение спика
```
curl http://localhost/api/tasks
```

Получение задачи по id
```
curl http://localhost/api/tasks/1
```

Редактирование задачи
```
curl -X PUT -H "Content-Type: application/json" -d '{ "title": "Задача №1", "description": "Купить молоко", "status": true}' http://localhost/api/tasks/1
```

Удаление задачи
```
curl -X DELETE http://localhost/api/tasks/1
```


## Тестирования через powershell Windows.

Создание задачи
```
Invoke-WebRequest -Method "POST" -ContentType "application/json" -Body '{ "title": "Задача №1", "description": "Купить молоко", "status": false}' -Uri "http://localhost/api/tasks"
```

Редактирование задачи
```
Invoke-WebRequest -Method "PUT" -ContentType "application/json" -Body '{ "title": "Задача №1", "description": "Купить молоко", "status": true}' -Uri "http://localhost/api/tasks/6"
```