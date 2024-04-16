#!/bin/bash

# Шаги сборки проекта
echo "Шаг 1: Установка зависимостей (Composer)"
composer install

# Шаги сборки проекта
echo "Шаг 2: Установка зависимостей (NPM)"
npm install

echo "Шаг 3: Выполнение миграций"
php artisan migrate

echo "Шаг 4: Сборка проекта"
npm run dev

# Запуск проекта
echo "Шаг 5: Запуск проекта"
php artisan serve
