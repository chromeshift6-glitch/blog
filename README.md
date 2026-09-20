# PHP Blog

Небольшой полностью рабочий блог на чистом PHP без фреймворков. Категории и статьи хранятся в MySQL, представления формируются через Smarty. Для локального запуска подготовлено Docker-окружение с Nginx, PHP-FPM и MySQL.

## Возможности

- главная страница с категориями и тремя последними статьями в каждой;
- страница категории с сортировкой по дате или просмотрам;
- пагинация статей;
- страница статьи с автоматическим увеличением счётчика просмотров;
- три похожие статьи на основе общих категорий;
- связь статьи с одной или несколькими категориями;
- миграции и повторно запускаемый сидинг;
- страницы ошибок 404 и 500;
- адаптивная вёрстка и исходники SCSS.

## Технологии

- PHP 8.1 и PDO;
- MySQL 8.4;
- Smarty 5;
- Nginx и PHP-FPM;
- SCSS (Dart Sass);
- Docker Compose.

## Быстрый запуск через Docker

Потребуются Docker и Docker Compose.

1. Создайте локальные файлы окружения:

   ```bash
   cp env/app.env.example env/app.env
   cp env/mysql.env.example env/mysql.env
   ```

2. При необходимости замените логины и пароли. Значения `DB_DATABASE`, `DB_USERNAME` и `DB_PASSWORD` в `env/app.env` должны совпадать с `MYSQL_DATABASE`, `MYSQL_USER` и `MYSQL_PASSWORD` в `env/mysql.env`.

3. Соберите и запустите контейнеры:

   ```bash
   docker compose up -d --build
   ```

   При первом запуске PHP-контейнер автоматически установит зависимости из `composer.lock`. MySQL и PHP имеют healthcheck, поэтому Nginx запустится только после их готовности.

4. Создайте таблицы и тестовые данные:

   ```bash
   docker compose exec php php bin/migrate.php up
   docker compose exec php php bin/seed.php
   ```

5. Откройте [http://localhost:8080](http://localhost:8080).

Проверить соединение с базой можно командой:

```bash
docker compose exec php php bin/check-db.php
```

Остановить окружение без удаления данных:

```bash
docker compose stop
```

> Команда `docker compose down -v` удаляет контейнеры вместе с MySQL volume и всеми локальными данными проекта.

## Миграции и сидинг

Применить ещё не выполненные миграции:

```bash
docker compose exec php php bin/migrate.php up
```

Откатить последний пакет миграций:

```bash
docker compose exec php php bin/migrate.php down
```

Заполнить базу категориями и статьями:

```bash
docker compose exec php php bin/seed.php
```

Сидинг можно запускать повторно: записи обновляются по уникальным названиям, связи статей с категориями пересоздаются без дубликатов.

## Сборка стилей

Готовый CSS уже находится в `public/css/app.css`. Для изменения SCSS потребуется Node.js 20.19 или новее.

```bash
npm install
npm run build:css
```

Режим автоматической пересборки при разработке:

```bash
npm run watch:css
```

Исходный файл стилей расположен в `assets/scss/app.scss`.

## Маршруты

| Метод | URL | Назначение |
|---|---|---|
| GET | `/` | Главная страница |
| GET | `/category/{id}` | Статьи категории |
| GET | `/category/{id}?sort=date&page=1` | Сортировка и пагинация |
| GET | `/article/{id}` | Страница статьи |

Для параметра `sort` поддерживаются значения `date` и `views`. Неизвестное значение заменяется сортировкой по дате.

## Структура проекта

```text
assets/scss/             исходники стилей
bin/                     консольные команды
database/migrations/     миграции MySQL
docker/                  конфигурация PHP и Nginx
env/                     примеры переменных окружения
public/                  точка входа и публичные файлы
src/Controller/          HTTP-контроллеры
src/Database/            подключение, мигратор и сидеры
src/Http/                запрос, ответ и HTTP-ошибки
src/Repository/          SQL-запросы к категориям и статьям
src/Routing/             маршрутизация
src/View/                настройка Smarty и форматирование вывода
templates/               Smarty-шаблоны
```

Запрос проходит через `public/index.php`. Маршрутизатор выбирает контроллер, контроллер получает данные через репозиторий и передаёт их Smarty-шаблону. SQL выполняется только через PDO и подготовленные запросы.

## Запуск без Docker

Потребуются PHP 8.1 с расширением `pdo_mysql`, Composer, MySQL и веб-сервер с корнем в каталоге `public`.

```bash
composer install
php bin/migrate.php up
php bin/seed.php
php -S 127.0.0.1:8080 -t public
```

Перед выполнением команд экспортируйте переменные `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` и `DB_PASSWORD`.
