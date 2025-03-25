# 🌟 YUI INSTALLER 🌟

## 🚀 Introduction

Welcome to my personal laravel installer **YUI**! This setup is designed for developers who want a **lightweight, modern, and efficient** foundation for building Laravel applications with a React frontend powered by **[Inertia.js](https://inertiajs.com)**.

This kit is **JavaScript-first**, using **JSX instead of TSX**, making it accessible to developers who prefer plain JavaScript over TypeScript. It includes **React 19, TailwindCSS 4**, and Breeze for simple authentication and scaffolding.

---

## 🎯 Features

✔️ **Laravel** – Laravel provides a complete ecosystem for web artisans.  
✔️ **Laravel Breeze** – Lightweight authentication with Inertia.js.  
✔️ **Laravel Pulse** – delivers at-a-glance insights into your application's performance and usage.  
✔️ **Orion** – The simplest way to create REST API with Laravel.  
✔️ **Inertia.js** – Create modern single-page React, Vue, and Svelte apps using classic server-side routing.  
✔️ **React 19 + JSX** – Simple, clean, and TypeScript-free.  
✔️ **React Icons** – Include popular icons in your React projects easily with react-icons.  
✔️ **TanStack Query** – Powerful asynchronous state management for TS/JS.  
✔️ **Zustand** – A small, fast, and scalable bearbones state management solution.  
✔️ **Laravel-Spatie-permission** – Associate users with roles and permissions.  
✔️ **TailwindCSS 4** – Modern styling with utility-first CSS.  
✔️ **Vite-Powered** – Lightning-fast HMR for smooth development.  
✔️ **Pre-configured Testing** – Includes PHPUnit & Pest.  
✔️ **Hero UI** – Beautiful, fast and modern React UI library for building accessible and customizable web applications.  
✔️ **Quick Setup** – Get started in minutes!

---

## 🛠 Getting Started

### 1️⃣ Install the Yui Installer

Install the Yui installer globally using Composer: **[luis-developer-08/yui-installer](https://packagist.org/packages/luis-developer-08/yui-installer)**

```bash
composer global require luis-developer-08/yui-installer
```

### 2️⃣ Create a New Laravel Project

Use the Yui installer to generate a new Laravel project:

```bash
yui new my-laravel-app
```

### 3️⃣ Select Your Database

During installation, you will be prompted to choose a database. Select the one you want by entering the corresponding number:

```bash
[0] sqlite
[1] mysql
```

### 4️⃣ Choose a UI Provider

```bash
[0] None
[1] Hero UI
```

### 5️⃣ Navigate to Your Project Directory

After the project is created, navigate to the project folder:

```bash
cd my-laravel-app
```

### 6️⃣ Start the Development Server

Run the following command to start the development server:

```bash
composer run dev
```

### 🎉 Your application is now configured and running!

---

## ⚡ Create Inertia Components Easily

This starter kit includes a custom Artisan command to quickly generate Inertia.js React components with flexible directory options.

### 🏗️ Generate a New Component

```bash
php artisan make:inertia Components/MyComponent
```

This will create:

```
resources/js/Components/MyComponent.jsx
```

### 📂 Flexible Folder Placement

You can specify different folders using flags:

- `-c` or `--components`: Create the component in `resources/js/Components/`
- `-l` or `--layouts`: Create the component in `resources/js/Layouts/`
- `-s` or `--sections`: Create the component in `resources/js/Sections/`
- `-p` or `--pages`: Create the component in `resources/js/Pages/`

✅ **Example usage with flags:**

```bash
php artisan make:inertia Dashboard -p
```

Creates:

```
resources/js/Pages/Dashboard.jsx
```

You can also create nested folders like:

```bash
php artisan make:inertia Components/Auth/Login
```

Creates:

```
resources/js/Components/Auth/Login.jsx
```

### ✨ Example Generated Component

```jsx
import React from "react";

const MyComponent = () => {
  return <div>{/* MyComponent component */}</div>;
};

export default MyComponent;
```

### 🚀 Smart Behavior

- Automatically prevents duplicate folder nesting.
- Opens the newly created file in your default editor.
- Ensures the correct folder placement, whether using flags or specifying the path.

---

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

### 🔗 Auto-Registered Route in `routes/api.php`

```php
Orion::resource('posts', \App\Http\Controllers\Orion\PostController::class)->middleware(['auth', 'web']);
```

This command ensures that controllers are correctly placed, models are created if missing, and routes are automatically registered.

---

## 📖 Documentation

For more details on YUI, visit the official [YUI Website](https://yui-app.balbuena.io/).

### 🔗 **Feature Documentation Links**

- **[Laravel](https://laravel.com/docs)**
- **[Laravel Breeze](https://laravel.com/docs/11.x/starter-kits#laravel-breeze)**
- **[Laravel Pulse](https://laravel.com/docs/12.x/pulse)**
- **[Orion](https://orion.tailflow.org/guide)**
- **[Inertia.js](https://inertiajs.com)**
- **[React](https://react.dev)**
- **[React Icons](https://react-icons.github.io/react-icons)**
- **[TanStack Query](https://tanstack.com/query/latest/docs)**
- **[Zustand](https://zustand.docs.pmnd.rs/getting-started/introduction)**
- **[Spatie Permission](https://spatie.be/docs/laravel-permission)**
- **[TailwindCSS](https://tailwindcss.com)**
- **[Vite](https://vite.dev)**
- **[Pest](https://pestphp.com)**
- **[Hero UI](https://www.heroui.com/)**

## 🤝 Contributing

We welcome contributions! Check out the [Yui Installer contribution guide](https://github.com/luis-developer-08/yui-installer/blob/main/CONTRIBUTING.md) to get involved.

## 📜 Code of Conduct

Be kind and respectful. Please follow Yui's [Code of Conduct](https://github.com/luis-developer-08/yui-installer/blob/main/CODE_OF_CONDUCT.md).

## ⚖️ License

This installer is **open-source** under the **MIT license**.

---
