<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Taxi Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="manifest" href="/car-booking-system/manifest.json">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Welcome to XMTĐ Management System</h1>
    <p>Please select your role:</p>
    <form action="auth/login.php" method="post">
        <button type="submit" class="btn btn-primary">Go to Login</button>
    </form>
</div>
<script>
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/car-booking-system/sw.js')
      .then(registration => {
        console.log('Service Worker registered with scope:', registration.scope);
      })
      .catch(error => {
        console.error('Service Worker registration failed:', error);
      });
  }
</script>

</body>
</html>
