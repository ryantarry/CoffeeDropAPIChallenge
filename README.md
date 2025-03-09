CoffeeDrop API
==============

A Laravel-based API for managing coffee recycling locations and cashback calculations.

Features Added
--------------

-   **Authentication** using Laravel Sanctum (register, login, logout)
-   **Location Management**
    -   Find nearest location by postcode
    -   Add new recycling locations
-   **Cashback Calculations**
    -   Calculate cashback based on coffee pod quantities
    -   View calculation history
-   **Basic Frontend** for testing API endpoints

Setup Instructions
------------------

1.  **Clone the repository**

    ```
    git clone [repository-url]
    cd [repository-name]

    ```

2.  **Install dependencies**

    ```
    composer install

    ```

3.  **Set up environment**

    -   Copy `.env.example` to `.env`

    -   Configure your database credentials in `.env`:

        ```
        DB_DATABASE=your_database_name
        DB_USERNAME=your_database_user
        DB_PASSWORD=your_database_password

        ```

4.  **Generate application key**

    ```
    php artisan key:generate

    ```

5.  **Run migrations and seeders**

    ```
    php artisan migrate --seed

    ```

6.  **Start the server**

    ```
    php artisan serve

    ```

Testing the API
---------------

-   **Postman Collection**: Included in the repository

-   **Frontend**: Visit the root URL after starting the server (e.g., `http://localhost:8000`)

-   **Example Auth Credentials**:

    ```
    {
      "email": "user@example.com",
      "password": "password"
    }

    ```
