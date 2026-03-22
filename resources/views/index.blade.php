<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Product Management — Home</title>
  </head>

  <body class="bg-light min-vh-100 d-flex flex-column">

    <div class="bg-dark py-3 mb-0 shadow">
      <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h3 class="text-white mb-0">Product Management</h3>
        <span class="text-white-50 small">Welcome</span>
      </div>
    </div>

    <main class="flex-grow-1 d-flex align-items-center py-5">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8 col-xl-7">
            <div class="card shadow-lg border-0 rounded-3">
              <div class="card-body p-4 p-md-5 text-center">
                <h1 class="h2 mb-3">Manage your products</h1>
                <p class="text-muted mb-4">
                  View all products, add new items, or jump straight to the product list.
                </p>
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                  <a href="{{ route('products.index') }}" class="btn btn-dark btn-lg px-4">
                    View product list
                  </a>
                  <a href="{{ route('products.create') }}" class="btn btn-outline-dark btn-lg px-4">
                    Create product
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <footer class="py-3 text-center text-muted small border-top bg-white">
      <div class="container">
        <a href="{{ route('products.index') }}" class="text-decoration-none text-muted">Products</a>
        <span class="mx-2">·</span>
        <a href="{{ route('products.create') }}" class="text-decoration-none text-muted">Create</a>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>
