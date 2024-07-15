
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
        <div class="row">
            <div class="col-md-6">
                <img src="https://cdn-icons-png.freepik.com/512/4129/4129528.png" alt="{{ $product->name }}" class="img-fluid">
            </div>
            <div class="col-md-6">
                <h2>{{ $product->name }}</h2>
                <p>Price: ${{ $product->price }}</p>
                <p>Stock : {{ $product->stock }}</p>
                <button class="btn btn-primary">Add to Cart</button>
            </div>
        </div>
</div>

</body>
</html>
