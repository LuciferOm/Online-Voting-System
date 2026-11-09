# Online Voting System

A secure web-based voting platform built with **HTML, CSS, JavaScript, PHP and MySQL**.

## Features
- Voter registration and login with **password hashing** (`password_hash`) and **session management**
- One-person-one-vote enforced in PHP and by a `UNIQUE` constraint in MySQL
- Admin panel to add/delete candidates and manage voters
- Live election results (auto-refresh every 5 seconds)
- SQL injection protection (PDO prepared statements), XSS escaping, CSRF tokens

## Setup (XAMPP / WAMP / LAMP)
1. Copy this folder into `htdocs` (XAMPP) or `www` (WAMP).
2. Start **Apache** and **MySQL**.
3. Open phpMyAdmin → **Import** → select `database.sql`.
4. Edit the DB username/password in `config.php` if yours differ from `root` / empty.
5. Visit `http://localhost/online-voting-system/` and register an account.
6. Make yourself admin in phpMyAdmin → SQL tab:
   ```sql
   UPDATE users SET role = 'admin' WHERE email = 'your@email.com';
   ```
7. Log in again and open **Admin** to add candidates.

## Structure
```
config.php        database connection + helpers
database.sql      schema
register.php / login.php / logout.php
vote.php          ballot
results.php       live results page (data from results_api.php)
admin.php         admin panel
css/ js/ includes/
```
