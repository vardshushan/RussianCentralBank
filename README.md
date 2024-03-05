# Exchange Rate REST API Documentation

## Overview

This file provides information about how to use the projects REST API to fetch and store exchange rates in local database.

## Endpoints

# 1.Fetch and Store Exchange Rates

#### Endpoint

```
GET /api/exchange-rates
```

#### Description

This endpoint is used to fetch exchange rates from a third-party service and store them in the application database.

#### Request Parameters

```None```

#### Response Format - status code 200


```json
{
  "message": "Exchange rates fetched and stored successfully",
  "data": [
    {
      "currency_code": "USD",
      "date": "2024-03-05",
      "exchange_rate": 1.25
    },
    {
      "currency_code": "EUR",
      "date": "2024-03-05",
      "exchange_rate": 1.12
    },
      ....
  ]
}
```
#### Response Format - status code 500

```json
{
    "error": "Failed to fetch and store exchange rates."
}

```

# 2. Retrieve Rates from local DB
#### Endpoint

```
GET /api/get-rates
```

#### Description

This endpoint is used to retrieve rates from my local database.

#### Request Parameters

```None```

#### Response Format - status code 200


```json
{
  "data": [
    {
      "currency_code": "USD",
      "date": "2024-03-05",
      "exchange_rate": 1.25
    },
    {
      "currency_code": "EUR",
      "date": "2024-03-05",
      "exchange_rate": 1.12
    },
      ....
  ]
}
