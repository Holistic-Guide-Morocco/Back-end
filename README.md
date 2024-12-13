# Laravel API Documentation

## Introduction

Welcome to the backend API for our project! This API is built with Laravel and provides functionality for managing users, professionals, services, reservations, feedback, and more. Authentication is handled using Laravel Sanctum, and routes are role-protected to ensure proper access control.

---

## Table of Contents

-   [Getting Started](#getting-started)
-   [Authentication](#authentication)
-   [Client Routes](#client-routes)
-   [Professional Routes](#professional-routes)
-   [Admin Routes](#admin-routes)
-   [General Routes](#general-routes)
-   [Examples](#examples)

---

## Getting Started

### Requirements

-   PHP 8.0+
-   Composer
-   Laravel 10+
-   Sanctum Package

### Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/Holistic-Guide-Morocco/Back-end.git
    ```
2. Navigate to the project directory:
    ```bash
    cd Back-end
    ```
3. Install dependencies:
    ```bash
    composer install
    ```
4. Configure the `.env` file:
    - Set your database credentials.
    - Add `OPENCAGE_API_KEY` for testing the locations (contact me).
    - Add `DB_DATABASE` => "HGM" .
5. Run migrations:
    ```bash
    php artisan migrate
    ```
6. Start the development server:
    ```bash
    php artisan serve
    ```

---

## Authentication

The following routes handle user authentication:

| Method | Endpoint    | Description         |
| ------ | ----------- | ------------------- |
| POST   | `/register` | Register a new user |
| POST   | `/login`    | Log in a user       |
| POST   | `/logout`   | Log out a user      |

-   **Note:** `logout` is protected and requires an authenticated user.

---

## Client Routes

These routes are accessible to users with the `user` role and require authentication:

| Method | Endpoint             | Description                     |
| ------ | -------------------- | ------------------------------- |
| GET    | `/show/client`       | View client profile             |
| PUT    | `/client`            | Update client profile           |
| DELETE | `/client`            | Delete client account           |
| GET    | `/services`          | View all services               |
| GET    | `/services/{id}`     | View a specific service         |
| GET    | `/favorites`         | View all favorites              |
| GET    | `/favorites/{id}`    | View a specific favorite        |
| POST   | `/favorites`         | Add a service to favorites      |
| DELETE | `/favorites/{id}`    | Remove a service from favorites |
| POST   | `/feedbacks`         | Add feedback for a service      |
| PUT    | `/feedbacks/{id}`    | Update feedback                 |
| DELETE | `/feedbacks/{id}`    | Delete feedback                 |
| POST   | `/reservations`      | Create a reservation            |
| PUT    | `/reservations/{id}` | Update a reservation            |
| DELETE | `/reservations/{id}` | Cancel a reservation            |

---

## Professional Routes

Accessible to users with the `professional` role:

| Method | Endpoint                    | Description                               |
| ------ | --------------------------- | ----------------------------------------- |
| GET    | `/show/professional`        | View professional profile                 |
| PUT    | `/professional`             | Update professional profile               |
| DELETE | `/professional`             | Delete professional account               |
| GET    | `/MyServices`               | View all services created by professional |
| POST   | `/services`                 | Create a new service                      |
| PUT    | `/services/{id}`            | Update a service                          |
| DELETE | `/services/{id}`            | Delete a service                          |
| PUT    | `/reservations/{id}/status` | Update reservation status                 |

---

## Admin Routes

Accessible to users with the `admin` role:

| Method | Endpoint            | Description             |
| ------ | ------------------- | ----------------------- |
| GET    | `/admin/users`      | View all users          |
| GET    | `/admin/users/{id}` | View a specific user    |
| PUT    | `/admin/users/{id}` | Update a user's details |
| DELETE | `/admin/users/{id}` | Delete a user           |
| GET    | `/admins`           | View all admins         |

---

## General Routes

The following routes are accessible to any authenticated user:

| Method | Endpoint             | Description                 |
| ------ | -------------------- | --------------------------- |
| GET    | `/reservations`      | View all reservations       |
| GET    | `/reservations/{id}` | View a specific reservation |

---

## Examples

### 1. Register a New User

```bash
POST /register
{
    "username": "John Doe",
    "email": "john.doe@example.com",
    "password": "password",
    "role": "user"
}
```

### 2. Create a New Reservation (Client Role)

```bash
POST /reservations
{
    "service_id": 123,
    "date": "2024-12-15"
}
```

### 3. Create a New Service (Professional Role)

```bash
POST /services
{
    "name": "Haircut",
    "description": "A professional haircut service.",
    "price": 50.00,
    "availability": true,
    "location_name":"iberia, Tangier, Morocco"
}
```

---

## Notes

-   **Middleware:** Most routes are protected by `auth:sanctum`. Ensure you include an `Authorization` header with a valid Bearer token for authenticated requests.
-   **Roles:** Access is role-restricted (`user`, `professional`, `admin`). Ensure your account is assigned the appropriate role.

## Contributing

Feel free to contribute to this API by submitting a pull request. Ensure your code is clean and adheres to Laravel's coding standards.

## Contact

If you have any questions, feel free to contact me at `redahaloubi8@gmail.com`.
