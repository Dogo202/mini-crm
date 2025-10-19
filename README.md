# Mini CRM (Laravel 12)

Мини-CRM для сбора и обработки заявок через встраиваемый виджет.  
Стек: Laravel 12, PHP 8.3+, MySQL, spatie/laravel-permission, spatie/laravel-medialibrary, Breeze (Blade).

## Содержание
- [Быстрый старт (Docker)](#быстрый-старт-docker)
- [Альтернатива: локальный запуск без Docker](#альтернатива-локальный-запуск-без-docker)
- [Тестовые данные и доступы](#тестовые-данные-и-доступы)
- [API](#api)
- [Виджет (встраивание)](#виджет-встраивание)
- [Админ-панель](#админ-панель)
- [Структура проекта](#структура-проекта)
- [Тесты](#тесты)
- [Документация API (Swagger)](#документация-api-swagger)
- [Ограничение 1 заявка/24ч](#ограничение-1-заявка24ч)
- [Траблшутинг](#траблшутинг)

---

## Быстрый старт (Docker)

### Требования
- Docker Desktop или Docker Engine + docker compose plugin

### 1) Клонируем и готовим `.env`
```bash
git clone <repo>
cd mini-crm
cp .env.example .env
make up
make init

```
Открыть:

Виджет: http://localhost:8080/widget

Админка: http://localhost:8080/admin/tickets

Логин: test@test.com, Пароль: password

###Встраивание виджета

```html
<iframe src="http://localhost:8080/widget" width="100%" height="560" style="border:0;"></iframe>
```

###Тесты
```nashorn js
make test
```
####Ожидаем
can create ticket → 201
daily limit → 429

###Документация API (Swagger)
Откройте docs/openapi.yaml тут: https://editor.swagger.io


