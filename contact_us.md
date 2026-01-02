# Contact Us API Integration

This document outlines how to integrate the "Contact Us" functionality from other applications using the Skypush API.

## Overview

**Base URL**: `https://skypush.skylinksolutions.co.tz`  
**Endpoint**: `/api/v1/contact/contact`  
**Method**: `POST`

## Authentication

All API requests require an API Key to be passed in the headers.

**Header Name**: `X-API-KEY`  
**Value**: *Your Internal API Key* (Contact System Administrator to obtain this)

## Request Specification

### Headers
| Header | Value | Required |
|--------|-------|----------|
| `Content-Type` | `application/json` | Yes |
| `Accept` | `application/json` | Yes |
| `X-API-KEY` | `<YOUR_SECRET_KEY>` | Yes |

### Body Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `name` | String | Yes | Full name of the person contacting. |
| `email` | String | Yes | Valid email address. |
| `phone` | String | Yes | Phone number (e.g., `07xxx` or `+255xxx`). |
| `message` | String | Yes | The content of the message. |
| `source` | String | Yes | Must be one of: `contact`, `subscription`. Use `contact` for standard contact forms. |

### Example Request (cURL)

```bash
curl -X POST https://skypush.skylinksolutions.co.tz/api/v1/contact/contact \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "X-API-KEY: your_super_secret_key" \
  -d '{
    "name": "Jane Doe",
    "email": "jane@example.com",
    "phone": "0755123456",
    "message": "I would like to inquire about your services.",
    "source": "contact"
  }'
```

### Example Request (PHP/Guzzle)

```php
$client = new \GuzzleHttp\Client();
$response = $client->post('https://skypush.skylinksolutions.co.tz/api/v1/contact/contact', [
    'headers' => [
        'X-API-KEY' => 'your_super_secret_key',
        'Accept'    => 'application/json',
    ],
    'json' => [
        'name'    => 'Jane Doe',
        'email'   => 'jane@example.com',
        'phone'   => '0755123456',
        'message' => 'Inquiry message here...',
        'source'  => 'contact',
    ]
]);
```

## Response Specification

### Success Response (200 OK)

```json
{
    "success": true,
    "message": "Thank you for contacting us, we will get back to you shortly."
}
```

### Error Response (422 Unprocessable Entity)
Occurs when validation fails (e.g., missing fields or invalid email).

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": [
            "The email field is required."
        ]
    }
}
```

### Error Response (401 Unauthorized)
Occurs when the API Key is missing or incorrect.

```json
{
    "message": "Unauthorized"
}
```
