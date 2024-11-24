<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code for {{ $transactionNumber }}</title>
</head>
<body>
    <h1>QR Code for Transaction: {{ $transactionNumber }}</h1>
    <img src="data:image/png;base64,{{ base64_encode($qrCode) }}" alt="QR Code">
</body>
</html>
