# Laravel Task Manager

A mini task management system built with Laravel.

## Purpose

This project helps users organize projects and tasks.

## Main Features

- Login and logout
- Dashboard
- Project CRUD
- Task CRUD
- Task status management
- Search
- Filter
- Sort
- Pagination
- AJAX status update
- Validation

## Technologies

- Laravel
- PHP
- MySQL
- Blade
- HTML
- CSS
- JavaScript
- AJAX
- Git/GitHub

## Database

┌──────────────┐
│     USER     │
├──────────────┤
│ id           │
│ name         │
│ email        │
│ password     │
└──────┬───────┘
       │ 1
       │
       │ N
┌──────▼───────┐
│   PROJECT    │
├──────────────┤
│ id           │
│ user_id      │
│ name         │
│ description  │
└──────┬───────┘
       │ 1
       │
       │ N
┌──────▼───────┐
│     TASK     │
├──────────────┤
│ id           │
│ project_id   │
│ user_id      │
│ title        │
│ description  │
│ status       │
│ deadline     │
└──────────────┘