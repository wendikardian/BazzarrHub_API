
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <!-- import bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>
<body>
<div class="container">
    <div class="card">
        <div class="row no-gutters">
            <div class="col-md-4">
                <img src="https://cdn-icons-png.freepik.com/512/4129/4129528.png" alt="{{ $product->name }}" class="card-img">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text">Price: ${{ $product->price }}</p>
                    <p class="card-text">Stock: {{ $product->stock }}</p>
                    <button class="btn btn-primary">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
