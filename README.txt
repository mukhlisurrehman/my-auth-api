# 🔐 PHP JWT Authentication API (Core PHP)

This is a lightweight, secure REST API built with **Core PHP** (no frameworks) that uses **JWT (JSON Web Tokens)** for user authentication. It includes user registration, login, protected routes, token refreshing, logout, and a custom PHP `callAPI()` function for internal/external requests.

---

## 🚀 Features

- ✅ User registration (`/api/register.php`)
- ✅ User login (`/api/login.php`)
- ✅ Secure access to protected routes (`/api/protected.php`)
- ✅ JWT-based access token + Refresh token via HTTP-only cookies
- ✅ Logout and token invalidation
- ✅ Custom `callAPI()` helper using cURL
- ✅ Frontend-friendly with browser `fetch()` support
- ✅ Simple file-based or MySQL token blacklist

---

## 📁 Folder Structure
/my-auth-api/
│
├── /api/
│ ├── login.php
│ ├── register.php
│ ├── protected.php
│ ├── refresh.php
│ └── logout.php
| └── callAPI.php # Reusable PHP cURL API caller
| └── test.php # How to call api

│
├── /includes/
│ ├── config.php # JWT + token logic
│ └── db.php # DB connection (excluded from repo)
│

└── index.html # Optional test UI using JS fetch()
│
├── .gitignore
└── README.md



## 🔧 Setup Instructions

1. **Clone the repo**

   ```bash
   git clone https://github.com/your-username/php-auth-api.git
   cd php-auth-api

2. Configure your database

Create a MySQL DB and import the database jwt-api.sql included in the main.

3. Start the local PHP server
bash
Copy
Edit
php -S localhost:8000
Visit http://localhost:8000/my-auth-api/index.html (optional test UI)

4. Example Frontend Usage
// JS fetch() example
fetch("/api/login.php", {
  method: "POST",
  headers: { "Content-Type": "application/json" },
  body: JSON.stringify({ username: "admin", password: "1234" })
})
.then(res => res.json())
.then(data => console.log(data));

5. 🔐 JWT + Refresh Token Logic
Access token: sent in Authorization header (Bearer <token>)

Refresh token: stored in an HTTP-only secure cookie

Tokens are verified and rotated securely

6.Built With
PHP (Core)
MySQL
JSON Web Tokens (JWT)
JavaScript (optional frontend)
