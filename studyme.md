# Product Management Service — Complete project study guide

This document explains **your** Laravel project in depth: what each file does, what the code means, and how data moves from the browser to the database and back. Use it to prepare for your internship interview.

---

## 1. What this project is (big picture)

You built a **Product Management** web app where a user can:

- See a **home** page with shortcuts.
- **List** all products in a table.
- **Create** a new product (name, SKU, price, description, optional image).
- **Edit** an existing product.
- **Delete** a product (with a confirmation dialog).

That is **CRUD**:

| Letter | Meaning   | In your app |
|--------|-----------|-------------|
| **C**  | Create    | Add a new product (`store`) |
| **R**  | Read      | Show all products (`index`) and show one for editing (`edit`) |
| **U**  | Update    | Save changes to a product (`update`) |
| **D**  | Delete    | Remove a product (`destroy`) |

**Extra:** You also handle **file upload** (product image) and store only the **filename** in MySQL; the actual image file lives under `public/uploads/products/`.

---

## 2. How Laravel fits together (concepts you should know)

- **Route** (`routes/web.php`): Maps a URL + HTTP method to PHP code (a controller method or a closure).
- **Controller** (`app/Http/Controllers/ProductController.php`): Contains the logic for each action (validate input, talk to database, redirect).
- **Model** (`app/Models/Product.php`): Represents one table (`products`) and lets you use friendly PHP instead of raw SQL (`Product::find(1)`).
- **Migration** (`database/migrations/...create_products_table.php`): Defines the **structure** of the `products` table (columns, types). Running `php artisan migrate` creates the table in MySQL.
- **Blade views** (`resources/views/...`): HTML templates with `@` and `{{ }}` for dynamic content.
- **Request** (`Illuminate\Http\Request`): Holds everything the user sent (form fields, files, URL).

**Typical flow:** Browser → **Route** → **Controller method** → **Model** (database) + **View** (HTML) → response back to browser.

---

## 3. Database: migration — what table you created

File: `database/migrations/2026_03_21_070524_create_products_table.php`

| Code | Meaning |
|------|---------|
| `$table->id();` | Auto-increment primary key column `id` (1, 2, 3, …). |
| `$table->string('name');` | Short text column `name` (required). |
| `$table->string('sku');` | `sku` = Stock Keeping Unit — a product code (string). |
| `$table->double('price', 10, 2);` | Decimal number for price (10 digits total, 2 after decimal). |
| `$table->text('description')->nullable();` | Long text; `nullable()` means it can be empty in the database. |
| `$table->string('image')->nullable();` | Stores **only the file name** of the image, not the binary file. |
| `$table->timestamps();` | Adds `created_at` and `updated_at` (Laravel manages these automatically on save). |

**`down()`** drops the table when you rollback migrations — keeps database changes reversible.

---

## 4. Model: `Product.php`

File: `app/Models/Product.php`

```php
class Product extends Model
{
    //
}
```

**Meaning:** By extending `Illuminate\Database\Eloquent\Model`, Laravel assumes:

- Table name: **`products`** (snake_case plural of `Product`).
- Primary key: **`id`**.

You did not set `$fillable` or `$guarded`; you assign fields manually in the controller (`$product->name = ...`). That is valid. In larger apps, people often use `$fillable` with `Product::create($request->only(...))` for mass assignment protection.

---

## 5. Routes: every line explained

File: `routes/web.php`

```php
Route::get('/', function () {
    return view('index');
});
```

- **GET `/`** → No controller; returns the Blade view named **`index`**, which is file `resources/views/index.blade.php`. This is your **landing / home** page.

```php
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
```

- **GET `/products`** → `ProductController@index` → list all products.
- **`->name('products.index')`** gives this URL a **name** so in Blade you write `route('products.index')` instead of hardcoding `/products`. If the URL changes later, links still work.

```php
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
```

- **GET** shows the **empty form** to create a product (does not save yet).

```php
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
```

- **POST `/products`** → **`store`**: receives form submit and **inserts** a new row. Browsers submit forms with POST for “create” actions.

```php
Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
```

- **GET** with **`{id}`** in the URL → Laravel passes that segment as `$id` to `edit($id)` to load one product for the edit form.

```php
Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
```

- **PUT** is the REST convention for **update**. HTML forms only support GET/POST, so the edit form uses **`@method('put')`** (see views) to fake PUT.

```php
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
```

- **DELETE** for removing a record. The list page uses a hidden form with **`@method('delete')`** because browsers cannot send DELETE from a plain link.

---

## 6. Controller: `ProductController` — method by method

### 6.1 `index()` — Read (list)

```php
$products = Product::orderBy('created_at', 'DESC')->get();
return view('products.list', ['products' => $products]);
```

- **`orderBy('created_at', 'DESC')`**: Newest products first.
- **`get()`**: Fetch **all** matching rows as a collection.
- **`view('products.list', [...])`**: Renders `resources/views/products/list.blade.php` and passes variable **`$products`** to the template.

---

### 6.2 `create()` — Show create form only

```php
return view('products.create');
```

- No database read; just shows the form. Saving happens in `store()`.

---

### 6.3 `store(Request $request)` — Create

**Validation:**

```php
$rules = [
    'name'  => 'required|min:3',
    'sku'   => 'required|min:3',
    'price' => 'required|numeric',
];
if ($request->image != "") {
    $rules['image'] = 'image';
}
$validator = Validator::make($request->all(), $rules);
```

- **`required`**: Field must be present and not empty.
- **`min:3`**: At least 3 characters (for name/sku).
- **`numeric`**: Price must be a number.
- **`image`**: If user chose a file, it must be an image type (jpeg, png, gif, etc.).
- **`Validator::make`**: If rules fail, you redirect back with errors and **old input** (`withInput()`).

**Saving the product:**

```php
$product = new Product();
$product->name = $request->name;
$product->sku = $request->sku;
$product->price = $request->price;
$product->description = $request->description;
$product->save();
```

- **`new Product()`** then assign fields then **`save()`** inserts one row. Laravel sets `created_at` / `updated_at`.

**Image (optional):**

```php
if ($request->image != "") {
    $image = $request->image;
    $ext = $image->getClientOriginalExtension();
    $imageName = time() . '.' . $ext;
    $image->move(public_path('uploads/products'), $imageName);
    $product->image = $imageName;
    $product->save();
}
```

- **`time()`** in the filename avoids collisions (two uploads get different names).
- **`move(...)`** saves the file under **`public/uploads/products/`** so the web server can serve it as `http://yoursite/uploads/products/filename.jpg`.
- Second **`save()`** updates the same row with `image` column.

**Redirect with flash message:**

```php
return redirect()->route('products.index')->with('success', 'product added sucessfully.');
```

- **`with('success', ...)`** puts a one-time message in the **session**. The list view reads it to show a green alert.

---

### 6.4 `edit($id)` — Show edit form

```php
$product = Product::findOrFail($id);
return view('products.edit', ['product' => $product]);
```

- **`findOrFail($id)`**: Loads the product or returns **404** if `id` does not exist — safer than `find()` which could return `null`.

---

### 6.5 `update($id, Request $request)` — Update

Same validation idea as `store`. Then:

```php
$product->name = $request->name;
// ...
$product->save();
```

If a **new image** is uploaded:

```php
File::delete(public_path('uploads/products/' . $product->image));
```

- Deletes the **old** file from disk (so you do not fill the server with unused images).

Then same move + save new filename as in `store`.

---

### 6.6 `destroy($id)` — Delete

```php
File::delete(public_path('uploads/products/' . $product->image));
$product->delete();
```

- Removes file from `public/uploads/products/`, then **deletes the database row**.

---

## 7. Views (Blade) — what you used and why

### 7.1 `@csrf`

In forms:

```blade
@csrf
```

- Laravel requires a **CSRF token** for POST/PUT/DELETE from the browser. It proves the form was submitted from **your** site, not a malicious site. Without it, Laravel rejects the request.

### 7.2 `old()` and validation errors

```blade
<input value="{{ old('name') }}" ...>
@error('name')
  <div class="invalid-feedback">{{ $message }}</div>
@enderror
```

- **`old('name')`**: After validation fails, refills the field so the user does not lose what they typed.
- **`@error`**: Shows the error message from the validator next to the field.

On **edit**, you combine old input with saved data:

```blade
{{ old('name', $product->name) }}
```

- If validation failed, show `old('name')`; otherwise show **`$product->name`** from the database.

### 7.3 `@method('put')` and `@method('delete')`

HTML forms only support GET and POST. Laravel reads a hidden `_method` field:

- **`PUT`** → route `Route::put(..., 'update')`
- **`DELETE`** → route `Route::delete(..., 'destroy')`

### 7.4 `route()` and `asset()`

- **`route('products.store')`**: Generates the correct URL for that named route.
- **`asset('uploads/products/'.$product->image)`**: Builds a URL to a file under **`public/`** (e.g. `/uploads/products/123.jpg`).

### 7.5 List page: loop and delete pattern

```blade
@foreach ($products as $product)
```

- Renders one table row per product.

Delete uses a **hidden form** + JavaScript **`confirm()`**:

- Clicking “Delete” runs `deleteProduct(id)`, which asks for confirmation, then **`form.submit()`** POSTs to the destroy route with **`@method('delete')`**.

This is a common pattern because `<a href="...">` cannot send POST/DELETE with CSRF safely without a form.

### 7.6 Session success message

```blade
@if (Session::has('success'))
    {{ Session::get('success') }}
@endif
```

- Shows the flash message set by `->with('success', ...)` after redirect.

### 7.7 Dates

```blade
{{ \Carbon\Carbon::parse($product->created_at)->format('d M, Y') }}
```

- Formats `created_at` as a readable date (e.g. `22 Mar, 2026`).

---

## 8. Front-end: Bootstrap

You used **Bootstrap 5** from a CDN for:

- Layout (`container`, `row`, `col-*`)
- Cards, tables, buttons, alerts
- Responsive table (`table-responsive`)

No custom CSS build step is required for the assignment — CDN is enough for a demo.

---

## 9. Home page: `index.blade.php`

- Same Bootstrap look as the rest of the app.
- Links use **`route('products.index')`** and **`route('products.create')`** so navigation stays correct.

---

## 10. Things interviewers often ask (with short answers)

**Q: What is MVC?**  
**A:** Model (data), View (HTML), Controller (handles request, uses Model, returns View). Laravel follows this pattern.

**Q: Why Eloquent instead of raw SQL?**  
**A:** Less error-prone, readable (`Product::find(1)`), and easy to add relationships later.

**Q: What is middleware?**  
**A:** Code that runs before/after a request (auth, CSRF). Your forms rely on CSRF middleware for POST routes.

**Q: Where are uploaded files stored?**  
**A:** On disk under `public/uploads/products/`; only the **filename** is in the `products` table.

**Q: What is REST?**  
**A:** A style of URLs and HTTP verbs: GET (read), POST (create), PUT/PATCH (update), DELETE (delete). Your routes follow this idea.

**Q: What could you improve next?**  
**A:** Examples: add `$fillable` on the model, use **Form Request** classes for validation, handle delete when image is missing, add authentication, pagination for large lists, image size validation.

---

## 11. File map (quick reference)

| Path | Role |
|------|------|
| `routes/web.php` | All URLs and HTTP verbs |
| `app/Http/Controllers/ProductController.php` | CRUD + validation + file handling |
| `app/Models/Product.php` | Eloquent model for `products` table |
| `database/migrations/...create_products_table.php` | Table structure |
| `resources/views/index.blade.php` | Home page |
| `resources/views/products/list.blade.php` | List + delete + success message |
| `resources/views/products/create.blade.php` | Create form |
| `resources/views/products/edit.blade.php` | Edit form |
| `public/uploads/products/` | Uploaded images (created when you save uploads) |

---

## 12. How to run the app (for your own demo)

1. Start **Apache** and **MySQL** in XAMPP.
2. Create database **`productdb`** (or match `.env` `DB_DATABASE`).
3. In project folder: `php artisan migrate`
4. Open the app URL (e.g. `http://localhost/productManagement/public` depending on your setup).

If `.env` has `SESSION_DRIVER=database`, sessions use MySQL — MySQL must be running or you will get connection errors.

---

Good luck with your interview. Being able to walk through **routes → controller → model → view** and explain **validation**, **CSRF**, and **file storage** shows you understand the assignment beyond copying code.
