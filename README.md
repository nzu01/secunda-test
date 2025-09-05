# README

Проект: **Organizations API**  
Бэкенд на Laravel + Docker.  
Документация API — **ReDoc**.

---

## Быстрый старт

### 1) Клонирование репозитория
```bash
git clone https://github.com/nzu01/secunda-test.git organizations-api
cd organizations-api
```

### 2) Настройка окружения
Скопируйте пример переменных окружения и при необходимости поправьте значения:
```bash
cp .env.example .env
```

> Обратите внимание на параметры подключения к БД в `.env` (порт по умолчанию в docker — **5458**).

### 3) Запуск Docker
- Для локальной разработки используйте **docker-compose-dev**:
  ```bash
  docker compose -f docker-compose-dev.yml up -d
  ```
- Для прода (или максимально приближённого окружения) используйте **docker-compose**:
  ```bash
  docker compose -f docker-compose.yml up -d
  ```

> Если у вас один файл и он называется `docker-compose.yml`, можно запускать короче:  
> `docker compose up -d`

### 4) Установка зависимостей и подготовка базы
Зайдите в контейнер рабочего окружения:
```bash
docker compose exec workspace bash
```

Внутри контейнера:
```bash
composer install
php artisan migrate
php artisan db:seed
```

Готово ✅

---

## Доступы по портам

- **API**: http://localhost:**8280**
- **Документация (ReDoc)**: http://localhost:**8080**
- **Postgres**: localhost:**5458**

---

## Кэширование

Почти все методы API завернуты в кэш.  
Чтобы сбросить кэш:
```bash
php artisan cache:clear
```

> Команда выполняется внутри контейнера `workspace`. Если вы вне контейнера, сначала войдите:
> ```bash
> docker compose exec workspace bash
> ```

---

## Полезные команды

- Пересобрать контейнеры после изменений:
  ```bash
  docker compose build --no-cache
  docker compose up -d
  ```
- Просмотреть логи приложения:
  ```bash
  docker compose logs -f workspace
  ```
- Выполнить artisan/команды без входа в bash:
  ```bash
  docker compose exec workspace php artisan <команда>
  ```

---

## Типичные проблемы и решения

- **Порт занят**  
  Если 8280/8080/5458 заняты — измените порты в `docker-compose(-dev).yml` и/или в `.env`.

- **Нет доступа к БД / миграции падают**  
  Проверьте переменные в `.env`:
  ```
  DB_CONNECTION=pgsql
  DB_HOST=db
  DB_PORT=5432
  DB_DATABASE=app
  DB_USERNAME=app
  DB_PASSWORD=app
  ```
  Внешний порт 5458 проброшен на контейнерный 5432 — это нормально.

- **Composer вылетает по памяти**  
  Запускайте `composer install` внутри контейнера `workspace`. Если всё равно не хватает — в крайнем случае:
  ```bash
  COMPOSER_ALLOW_SUPERUSER=1 composer install
  ```

---

## Структура Docker

- `docker-compose-dev.yml` — конфигурация для локалки (hot-reload, удобные volume’ы и т.п.).
- `docker-compose.yml` — конфигурация для прод-сборки/деплоя.
- Сервис `workspace` — PHP/Laravel окружение для разработки и выполнения команд.
- Сервис `redoc` — статическая выдача документации OpenAPI (порт 8080).
- Сервис `db` — PostgreSQL (в контейнере 5432, наружу проброшен на 5458).

---

## Документация API

Файл OpenAPI (`openapi.yaml`) монтируется в контейнер ReDoc и доступен по:  
**http://localhost:8080**

> Если вы обновляете `openapi.yaml`, ReDoc обновится при перезапуске контейнера `redoc`:
> ```bash
> docker compose restart redoc
> ```

## Авторизация API
Используйте header 
`Authorization: 41c86ea3-ea36-4855-af72-47e4225df069`