<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Product List</h2>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#productModal">
            Create Product
        </button>
        <table class="table table-bordered" id="productTable">
            <thead class="table-dark">
                <tr>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr id="product-{{ $product->id }}">
                    <td>{{ $product->title }}</td>
                    <td>{{ $product->content }}</td>
                    <td>${{ $product->price }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        <button class="btn btn-danger btn-sm delete-product" data-id="{{ $product->id }}">
                            Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="productForm">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Product Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control" id="content" name="content" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock" required>
                        </div>
                        <button type="submit" class="btn btn-success">Save Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#productForm").submit(function(e) {
                e.preventDefault();

                let formData = {
                    _token: $("input[name=_token]").val()
                    , title: $("#title").val()
                    , content: $("#content").val()
                    , price: $("#price").val()
                    , stock: $("#stock").val()
                , };

                $.ajax({
                    type: "POST"
                    , url: "{{ route('products.store') }}"
                    , data: formData
                    , success: function(response) {
                        $("#productModal").modal("hide");

                        $("#productForm")[0].reset();

                        console.log('success');

                        $("#productTable tbody").append(`
                            <tr id="product-${response.id}">
                                <td>${response.title}</td>
                                <td>${response.content}</td>
                                <td>$${response.price}</td>
                                <td>${response.stock}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm delete-product" data-id="${response.id}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        `);
                    }
                    , error: function(xhr) {
                        alert("Error: " + xhr.responseJSON.message);
                    }
                });
            });

            $(document).on('click', '.delete-product', function() {
                let productId = $(this).data('id');

                if (confirm('Are you sure you want to delete this product?')) {
                    $.ajax({
                        type: "DELETE"
                        , url: "/products/" + productId
                        , data: {
                            _token: $("input[name=_token]").val()
                        }
                        , success: function(response) {
                            $("#product-" + productId).remove();
                        }
                        , error: function(xhr) {
                            alert("Error: " + xhr.responseJSON.message);
                        }
                    });
                }
            });
        });

    </script>
</body>
</html>