# Index landing page (`index.blade.php`) — what changed

This document describes the work done to use a custom home page (`index.blade.php`) instead of Laravel’s default `welcome.blade.php`.

## New file created

### `resources/views/index.blade.php`

- Main landing page for the site (Bootstrap 5, aligned with the product screens).
- Contains links to:
  - **View product list** → `route('products.index')` (renders `resources/views/products/list.blade.php`).
  - **Create product** → `route('products.create')`.
- Footer links to the same product routes.

Laravel resolves `view('index')` to `resources/views/index.blade.php` by default — no extra provider configuration is required.

---

## Changes required to replace `welcome.blade.php`

### `routes/web.php`

The home route returns the `index` view instead of `welcome`:

```php
Route::get('/', function () {
    return view('index');
});
```

`welcome.blade.php` is **not deleted**; it is simply **no longer used** by the `/` route.

---

## Optional / related change (navigation)

### `resources/views/products/list.blade.php`

The product list header includes a **Home** link to `url('/')` so users can return to the landing page.

---

## Checklist if you redo this on another machine or project

1. Add `resources/views/index.blade.php`.
2. In `routes/web.php`, set `return view('index');` for `/`.
3. Run `php artisan config:clear` or `php artisan view:clear` if views were cached.
4. Ensure MySQL (or your DB) is running if you use `SESSION_DRIVER=database` or other DB-backed features.

---

## File reference

| Item | Role |
|------|------|
| `resources/views/index.blade.php` | New home page UI and links |
| `routes/web.php` | Switches `/` from `welcome` to `index` |
| `resources/views/welcome.blade.php` | Unchanged; unused by default `/` route |
| `resources/views/products/list.blade.php` | Home link to `/` (optional UX) |
