<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Placed</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card shadow-sm">
          <div class="card-body text-center p-5">
            <div class="display-6 text-success mb-3">✔ Order Placed Successfully</div>
            <p class="text-muted mb-4">Thank you for your purchase. We have received your order and will process it shortly.</p>
            <a href="{{ url('/') }}" class="btn btn-primary">Continue Shopping</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
