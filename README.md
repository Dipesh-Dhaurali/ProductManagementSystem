# Product Management System (Laravel CRUD)

A **Laravel-based Product Management System** that allows users to **create, read, update, and delete products** with image uploads, form validation, and flash messaging. This project demonstrates a full-stack CRUD application using Laravel, MySQL, and Bootstrap 5.

---

## 🚀 Features

* **Create Products:** Add new products with **Name, SKU/Product Number, Price, Description, and Image**.
* **Read Products:** View all products in a **responsive table** with product details and images.
* **Update Products:** Edit existing product information with **real-time validation**.
* **Delete Products:** Remove products with a **confirmation prompt** to avoid accidental deletion.
* **Form Validation:** All fields are validated from the backend to ensure **data integrity**.
* **Flash Messaging:** Display **success/error alerts** (e.g., "Product Added Successfully") after CRUD operations.

---

## 🛠️ Tech Stack

* **Framework:** Laravel 10
* **Language:** PHP 8.x
* **Frontend:** Blade Templating Engine (HTML/CSS) with **Bootstrap 5**
* **Database:** MySQL
* **Image Handling:** Laravel's built-in file upload features
* **Alerts:** Bootstrap alert classes for flash messages

---

## 📦 Project Structure

```
JOBPORTAL/
├── app/
│   └── Http/
│       └── Controllers/
│           └── ProductController.php
├── resources/
│   └── views/
│       ├── products/
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── index.blade.php
├── routes/
│   └── web.php
├── public/
│   └── images/          # Uploaded product images
├── database/
│   └── migrations/
│       └── create_products_table.php
├── .env                 # Environment variables
└── README.md
```

---

## ⚙️ Installation

1. **Clone the repository:**

```bash
git clone https://github.com/your-username/product-management-laravel.git
cd product-management-laravel
```

2. **Install dependencies via Composer:**

```bash
composer install
```

3. **Copy `.env` file and set environment variables:**

```bash
cp .env.example .env
```

* Configure database credentials (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) in `.env`.

4. **Generate application key:**

```bash
php artisan key:generate
```

5. **Run migrations to create database tables:**

```bash
php artisan migrate
```

6. **Start the local development server:**

```bash
php artisan serve
```

* Access the app at: `http://127.0.0.1:8000`

---

## 📝 Usage

* **Add Product:** Click on **"Add Product"** → fill in the form → click **Save**.
* **View Products:** Navigate to the product list to see all products.
* **Edit Product:** Click **Edit** → update details → click **Update**.
* **Delete Product:** Click **Delete** → confirm deletion → product removed.
* **Validation:** All required fields must be filled; price must be numeric.
* **Images:** Uploaded images are stored in `public/images/` and displayed in the table.

---

## 💡 Notes

* Product images must be **JPEG, PNG, or GIF** and should not exceed **2MB**.
* Flash messages are displayed for **all CRUD operations** to improve user experience.
* Bootstrap 5 is used to make the UI **responsive and mobile-friendly**.

---

## 👨‍💻 Contributing

1. Fork the repository.
2. Create a new branch: `git checkout -b feature-name`
3. Commit your changes: `git commit -m "Add feature"`
4. Push to the branch: `git push origin feature-name`
5. Open a Pull Request.

---

## 📜 License

This project is **open-source** and available under the [MIT License](https://opensource.org/licenses/MIT).

---

## 📸 Screenshots

**Product List View:**

![Product List](screenshots/product-list.png)

**Add/Edit Product Form:**

![Add Product](screenshots/add-product.png)
