# Complete PHP JWT API

## Setup Instructions

1. Unzip this project into your server directory (e.g., htdocs).
2. Run `composer install` to install dependencies.
3. Test the API:
   - POST /api/login.php with JSON:
     {
       "username": "john",
       "password": "1234"
     }
   - Use the returned token to access /api/profile.php by sending it in the Authorization header:
     Authorization: Bearer YOUR_TOKEN

## Files
- api/login.php     - Login and get JWT
- api/profile.php   - Protected endpoint
- config/config.php - Configuration values
- utils/functions.php - Simple user authentication

Make sure PHP has the `zip` extension and Composer is installed.
