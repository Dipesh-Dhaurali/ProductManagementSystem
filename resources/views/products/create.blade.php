<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" 
      integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>ProductManagement</title>
  </head>
  <body>
    <!-- Header -->
    <div class="bg-dark py-3 mb-5">
      <h3 class="text-white mx-4">Product Management</h3>
    </div>
        <!-- Create button -->
    <div class="container">
              <div class="row justify-content-center mt-4">
            <div class="col-md-8 col-lg-6 d-flex justify-content-end mb-3 px-4" >
                <a href="{{route('products.index')}}" class="btn btn-dark">Back</a>
            </div>
        </div>
      <div class="row d-flex justify-content-center">
        <div class="col-md-8 col-lg-6">
          <div class="card shadow-lg border-0">
            <!-- Card Header -->
            <div class="card-header bg-dark">
              <h4 class="text-white text-center mb-0">Create Product</h4>
            </div>

            <!-- Create Form -->
            <form action="{{ route('products.store') }}" method="post" enctype="multipart/form-data">
              @csrf
              <div class="card-body">
                
                <!-- Name -->
                <div class="mb-3">
                  <label class="form-label fw-bold">Name</label>
                  <input value="{{ old('name') }}" type="text" name="name" placeholder="Enter product name" 
                    class="form-control form-control-lg @error('name') is-invalid @enderror">
                  @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <!-- SKU -->
                <div class="mb-3">
                  <label class="form-label fw-bold">SKU</label>
                  <input value="{{ old('sku') }}" type="text" name="sku" placeholder="Enter SKU" 
                    class="form-control form-control-lg @error('sku') is-invalid @enderror">
                  @error('sku')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Price -->
                <div class="mb-3">
                  <label class="form-label fw-bold">Price</label>
                  <input value="{{ old('price') }}" type="text" name="price" placeholder="Enter price" 
                    class="form-control form-control-lg @error('price') is-invalid @enderror">
                  @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Description -->
                <div class="mb-3">
                  <label class="form-label fw-bold">Description</label>
                  <textarea name="description" class="form-control" placeholder="Enter description" rows="4">{{ old('description') }}</textarea>
                </div>

                <!-- Image -->
                <div class="mb-4">
                  <label class="form-label fw-bold">Image</label>
                  <input type="file" name="image" class="form-control form-control-lg">
                </div>

                <!-- Submit Button -->
                <div class="d-grid">
                  <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                </div>

              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
      integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>