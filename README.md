# 🌟 YUI INSTALLER 🌟

## 🚀 Introduction

Welcome to my personal laravel installer **YUI**! This setup is designed for developers who want a **lightweight, modern, and efficient** foundation for building Laravel applications with a React frontend powered by **[Inertia.js](https://inertiajs.com)**.

This kit is **JavaScript-first**, using **JSX instead of TSX**, making it accessible to developers who prefer plain JavaScript over TypeScript. It includes **React 19, TailwindCSS 4**, and Breeze for simple authentication and scaffolding.

---

## 🎯 Why Choose This Kit?

✔️ **React 19 + JSX** – Simple, clean, and TypeScript-free  
✔️ **Laravel 12 + Breeze** – Lightweight authentication with Inertia.js  
✔️ **Inertia.js** – Create modern single-page React, Vue, and Svelte apps using classic server-side routing.  
✔️ **Orion** – The simplest way to create REST API with Laravel  
✔️ **TanStack Query** – Powerful asynchronous state management for TS/JS  
✔️ **Laravel-permission** – Associate users with roles and permissions  
✔️ **TailwindCSS 4** – Modern styling with utility-first CSS  
✔️ **Vite-Powered** – Lightning-fast HMR for smooth development  
✔️ **Pre-configured Testing** – Includes PHPUnit & Pest  
✔️ **Quick Setup** – Get started in minutes!

---

## 🛠 Getting Started

### 1️⃣ Install

```bash
composer global require luis-developer-08/yui-installer
```

### 2️⃣ Create a New Laravel Project

```bash
yui new my-laravel-app
```

🎉 Your application is now up and running!

---

## ⚡ Create Inertia Components Easily

This starter kit includes a custom Artisan command to quickly generate Inertia.js React components:

### 🏗️ Generate a New Component

```bash
php artisan make:inertia Components/MyComponent
```

This will create a new file at `resources/js/Components/MyComponent.jsx` with a basic component template.

### 📂 File Structure

```
resources/js/Components/MyComponent.jsx
```

### ✨ Example Generated Component

```jsx
import React from "react";

const MyComponent = () => {
  return <div>{/* MyComponent component */}</div>;
};

export default MyComponent;
```

This command ensures that components are placed in the correct directory and prevents overwriting existing files. It also automatically opens the newly created file for editing.

---

## ⚡ Create Orion Controllers Easily

This starter kit also includes a command to quickly generate Orion controllers along with their associated models:

### 🏗️ Generate a New Orion Controller

```bash
php artisan make:orion PostController
```

This will create:

- `app/Http/Controllers/Orion/PostController.php`
- `app/Models/Post.php` (if it doesn’t exist)
- Adds a route in `routes/api.php`

### 📂 File Structure

```
app/Http/Controllers/Orion/PostController.php
app/Models/Post.php
```

### ✨ Example Generated Controller

```php
<?php

namespace App\Http\Controllers\Orion;

use Orion\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
    protected $model = Post::class;
}
```

### ✨ Example Generated Model (if not existing)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];
}
```

### 🔗 Auto-Registered Route in `routes/api.php`

```php
Orion::resource('posts', \App\Http\Controllers\Orion\PostController::class);
```

This command ensures that controllers are correctly placed, models are created if missing, and routes are automatically registered.

---

## 📖 Documentation

For more details on Laravel Breeze, visit the official [Laravel Starter Kit docs](https://laravel.com/docs/master/starter-kits#laravel-breeze).

## 🤝 Contributing

We welcome contributions! Check out the [Laravel contribution guide](https://laravel.com/docs/contributions) to get involved.

## 📜 Code of Conduct

Be kind and respectful. Please follow Laravel's [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## ⚖️ License

This starter kit is **open-source** under the **MIT license**.

---
