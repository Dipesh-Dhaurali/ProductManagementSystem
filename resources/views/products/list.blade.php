<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" 
      integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>ProductManagement</title>
  </head>

  <body class="bg-light">

    <!-- Header -->
    <div class="bg-dark py-3 mb-4 shadow">
      <h3 class="text-white mx-4">Product Management</h3>
    </div>

    <div class="container py-4">

        <!-- Create Button -->
        <div class="row justify-content-center">
            <div class="col-md-10 d-flex justify-content-end mb-3">
                <a href="{{route('products.create')}}" class="btn btn-dark">
                    + Create Product
                </a>
            </div>
        </div>


            <!-- Success Message -->
            <div class="row justify-content-center">
                <div class="col-md-10">
                    @if (Session::has('success'))
                        <!-- Changed to col-md-4 for a smaller box, and added ps-0 to align left -->
                        <div class="col-md-4 ps-0"> 
                            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                                {{ Session::get('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        <!-- Product Table -->
        <div class="row justify-content-center">
            <div class="col-md-10">

                <div class="card shadow-lg border-0 rounded-3 border border-dark">

                    <!-- Card Header -->
                    <div class="card-header bg-dark text-white text-center">
                        <h4 class="mb-0">Products</h4>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped align-middle text-center">

                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>SKU</th>
                                        <th>Price</th>
                                        <th>Created at</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                @if ($products->isNotEmpty())

                                    @foreach ($products as $product)
                                    <tr>

                                        <td>{{$product->id}}</td>

                                        <td>
                                            @if ($product->image != "")
                                                <img src="{{asset('uploads/products/'.$product->image)}}" 
                                                     width="50" height="50" class="rounded shadow-sm">
                                            @endif
                                        </td>

                                        <td class="fw-bold">{{$product->name}}</td>
                                        <td>{{$product->sku}}</td>

                                        <td class="text-success fw-semibold">
                                            Rs. {{$product->price}}
                                        </td>

                                        <td>
                                            {{ \Carbon\Carbon::parse($product->created_at)->format('d M, Y') }}
                                        </td>

                                        <td>
                                            <a href="{{route('products.edit',$product->id)}}" class="btn btn-sm btn-primary me-2">Edit</a>

                                            <a href="#" onclick="deleteProduct({{ $product->id }});" class="btn btn-sm btn-danger">Delete</a>
                                            <form id='delete-product-form-{{$product->id}}' action="{{route('products.destroy',$product->id)}}" method="post">
                                                @csrf
                                                @method('delete')
                                            </form>
                                        </td>

                                    </tr>
                                    @endforeach

                                @else
                                    <tr>
                                        <td colspan="7" class="text-muted">
                                            No products found
                                        </td>
                                    </tr>
                                @endif
                                </tbody>

                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>

    
<script>
    function deleteProduct(id) {
        // Confirm before deleting
        if (confirm("Are you sure you want to delete this product?")) {
            // Find the form using the ID and submit it
            const form = document.getElementById("delete-product-form-" + id);
            if (form) {
                form.submit();
            } else {
                console.error("Form not found for ID: " + id);
            }
        }
    }
</script>
    

  </body>
</html>