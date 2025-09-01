{{-- <h3>New contact request</h3>

<p><strong>Service:</strong> {{ $data['service'] ?? '—' }}</p>
<p><strong>Phone:</strong> {{ $data['phone'] ?? '—' }}</p>
<p><strong>Location:</strong> {{ $data['location'] ?? '—' }}</p>
<p><strong>Message:</strong></p>
<p>{{ $data['message'] ?? '—' }}</p> --}}

{{-- beutify this view --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contact Us</title>
</head>
<body>
    <h3>New contact request</h3>
    <p><strong>Service:</strong> {{ $data['service'] ?? '—' }}</p>
    <p><strong>Phone:</strong> {{ $data['phone'] ?? '—' }}</p>
    <p><strong>Location:</strong> {{ $data['location'] ?? '—' }}</p>
    <p><strong>Message:</strong></p>
    <p>{{ $data['message'] ?? '—' }}</p> <br>
    <p><strong>Support:</strong></p>
    <p>{{ env('SUPPORT_EMAIL') }} | {{ env('SUPPORT_PHONE') }} | {{ env('SUPPORT_WEBSITE') }}</p>
</body>
</html>
