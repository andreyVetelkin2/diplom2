# 🎓 Дипломный проект: Система управления достижениями сотрудников

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com)

![Система управления достижениями](screenshot.jpg)

## 📌 О проекте

Профессиональная веб-система для учета и анализа профессиональных достижений сотрудников организации, разработанная как дипломный проект. 

**Ключевые возможности:**
- 🧑💼 Управление профилями сотрудников и структурой организации
- 📊 Визуализация статистики в виде интерактивных графиков
- 🏆 Учет профессиональных достижений и сертификатов
- 📑 Генерация автоматических отчетов
- 🔐 Ролевая система доступа (Администратор/Руководитель/Сотрудник)


## 🚀 Технологический стек
- **Backend**: Laravel 9, PHP 8
- **Frontend**: Bootstrap 5, Chart.js, ApexCharts
- **База данных**: MySQL
- **Дополнительно**: REST API, JWT Auth, Excel Export

## ⚙️ Установка

1. Клонировать репозиторий:
```bash
git clone https://github.com/andreyVetelkin2/diplom2.git
```
Установить зависимости:
```bash
composer install
npm install
```
Настроить окружение:
```bash
cp .env.example .env
```
Запустить миграции:

```bash
php artisan migrate --seed
```
Запустить сервер:

```bash
php artisan serve
```
