# blogPlatform at intermediate level. . . . . . . . . . . . . . . . .  

blogPlatform

"blogPlatform is a modern, secure PHP-based blogging platform where developers, tech enthusiasts, and learners can explore coding tutorials, trending tech posts, and industry insights — all in one place."

## Acknowledgements

 - [Awesome Readme Templates](https://awesomeopensource.com/project/elangosundar/awesome-README-templates)
 - [Awesome README](https://github.com/matiassingers/awesome-readme)
 - [How to write a Good readme](https://bulldogjob.com/news/449-how-to-write-a-good-readme-for-your-github-project)


## Authors

- [@pawanpatel](https://github.com/student-codingworld)


## Badges

Add badges from somewhere like: [shields.io](https://shields.io/)

[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](https://choosealicense.com/licenses/mit/)
[![GPLv3 License](https://img.shields.io/badge/License-GPL%20v3-yellow.svg)](https://opensource.org/licenses/)



## Contributing

Contributions are always welcome!

See `contributing.md` for ways to get started.

Please adhere to this project's `code of conduct`.


## Documentation

[Documentation](https://linktodocumentation)

**PHP Blogging Website Documentation**

---

### 📄 Project Overview

A full-featured blogging website built using **PHP, MySQL**, and **Bootstrap**. It includes:

* User registration and login
* Admin panel to manage posts, categories, and users
* Blog post creation, editing, and deletion
* Search and popular filters
* Commenting system (users only)
* User banning/deactivation feature
* SEO and sitemap support

---

### 📖 Website Description

**Short Description (Meta):**

> "MyBlog is a modern, secure PHP-based blogging platform where developers, tech enthusiasts, and learners can explore coding tutorials, trending tech posts, and industry insights — all in one place."

**Extended Description:**

> Welcome to **MyBlog** – your trusted destination for quality content in technology, programming, web development, and more. Built with PHP and MySQL, MyBlog offers a seamless and secure experience for both readers and writers. Our platform allows registered users to post blogs, engage through comments, and browse through categories such as Web Development, Android, Data Science, and trending topics. Designed with SEO in mind and powered by a robust admin panel, MyBlog ensures content moderation, user safety, and high performance.

---

### 📂 Folder Structure

```
blog-site/
|-- admin/
|   |-- dashboard.php
|   |-- posts.php
|   |-- add_post.php
|   |-- edit_post.php
|   |-- categories.php
|   |-- users.php
|   |-- login.php
|   |-- logout.php
|
|-- includes/
|   |-- db.php
|   |-- header.php
|   |-- footer.php
|
|-- uploads/
|
|-- user/
|   |-- register.php
|   |-- login.php
|   |-- logout.php
|
|-- post.php
|-- search.php
|-- popular.php
|-- sitemap.php
|-- index.php
|-- robots.txt
```

---

### 🔒 Admin Credentials

```
Username: error
Password: error1
```

Stored in: `admin_credentials` table (hashed password)

---

### 🔐 Security Features

* Passwords are hashed using `password_hash()`
* Login uses `password_verify()`
* Session-based authentication
* Admin panel restricted via `$_SESSION['admin']`
* Input sanitization using `htmlspecialchars()`
* SQL injection prevention using PDO prepared statements
* Ban/Deactivate user accounts from admin panel

---

### 📅 Database Tables

#### `admin_credentials`

```sql
CREATE TABLE admin_credentials (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### `users`

```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100),
  email VARCHAR(150),
  password VARCHAR(255),
  role VARCHAR(10) DEFAULT 'user',
  status ENUM('active', 'banned') DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### `categories`

```sql
CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100)
);
```

#### `posts`

```sql
CREATE TABLE posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200),
  content TEXT,
  image VARCHAR(255),
  views INT DEFAULT 0,
  category_id INT,
  user_id INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id),
  FOREIGN KEY (user_id) REFERENCES users(id)
);
```

#### `comments`

```sql
CREATE TABLE comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  post_id INT,
  user_id INT,
  comment TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

---

### ✏️ Features Summary

| Feature         | Description                                    |
| --------------- | ---------------------------------------------- |
| User Auth       | Register, login, logout                        |
| Blog Posts      | Create, edit, delete, view posts               |
| Categories      | Manage via admin                               |
| Comments        | Only by logged-in users                        |
| Post View Count | Increments when post is viewed                 |
| Popular Posts   | Filter by most viewed                          |
| Search Posts    | By title                                       |
| Admin Panel     | Access to manage posts, users, categories      |
| Ban Users       | Admin can deactivate/banned users              |
| Security        | Hashed passwords, input sanitization, sessions |
| SEO Support     | Meta tags, Open Graph, sitemap, robots.txt     |

---

### 🚀 Setup Instructions

1. Import `blogsite.sql` into your MySQL database.
2. Update database credentials in `includes/db.php`:

```php
$host = 'localhost';
$db = 'blogsite';
$user = 'root';
$pass = '';
```

3. Start XAMPP and place the `blog-site` folder in `htdocs`.
4. Access via browser: `http://localhost/blog-site`
5. Login to admin at: `http://localhost/blog-site/admin/login.php`

---

### 📆 Future Improvements (Optional)

* AJAX-based comments
* WYSIWYG editor for post content
* Profile page for users
* Pagination for posts and comments
* Comment moderation
* SEO-friendly URLs with .htaccess
* Social sharing preview for each post

---

### 💼 Service Level Agreement (SLA)

**Project:** PHP Blogging Website
**Owner:** Pawan Patel
**Delivery Date:** Within 7 working days from final approval
**Maintenance Period:** 30 days post-handover support for bug fixes and minor enhancements
**Response Time:** Within 24 hours for critical issues during the support period
**Availability:** Admin dashboard and user features will be tested and delivered as per the above documentation

---

Prepared for: **Admin (Username: error)**
System: **PHP Blogging Platform**

## FAQ

👤 User Accounts
Q3: How can users register and post content?
A: Users can register via user/register.php, then login and write posts or comments depending on their permissions.

Q4: Can users be banned or blocked?
A: Yes, the admin panel includes a user management module where admins can deactivate or ban users.

📝 Blog Posts & Comments
Q5: Who can create blog posts?
A: Only authenticated users (with proper roles) or the admin can create blog posts from the dashboard.

Q6: Are comments moderated?
A: Currently, all logged-in users can comment on posts. Comment moderation is a planned feature in the next phase.

⚙️ Website Features
Q7: What are the main features of this blog?
A:

User & Admin login system

Post creation/editing

Categories

Comments

Search and popular filters

SEO-friendly structure

Secure database interaction (PDO)

Sitemap and robots.txt for SEO

Q8: Is this website responsive and mobile-friendly?
A: Yes, it uses Bootstrap 5 to ensure full mobile responsiveness and modern UI.

🔒 Security
Q9: Are user passwords stored securely?
A: Yes, all passwords (user and admin) are stored using password_hash() for strong encryption.

Q10: Is SQL injection possible?
A: No. All database queries use prepared statements via PDO to protect against SQL injection attacks.

🌐 SEO & Indexing
Q11: Is this website SEO-optimized?
A: Yes. It includes meta tags, Open Graph/Twitter tags, robots.txt, and a dynamically generated sitemap (sitemap.php).

Q12: How do I submit my website to Google?
A:

Generate a sitemap at yourdomain.com/sitemap.php

Add it to robots.txt

Submit your site via Google Search Console

🛠️ Setup & Hosting
Q13: How do I install this on XAMPP?
A:

Place the project folder inside htdocs

Import the SQL file into phpMyAdmin

Update DB credentials in includes/db.php

Visit http://localhost/blog-site in your browser

Q14: Can this be deployed online?
A: Yes. You can host this on any LAMP-based hosting provider (e.g., Hostinger, GoDaddy, etc.) that supports PHP & MySQL.

❓ Troubleshooting
Q15: Why am I seeing a foreign key error when submitting a post?
A: Make sure the user_id exists in the users table. All foreign key relationships must be valid.

Q16: Why aren’t my changes showing after upload?
A: Clear your browser cache or press Ctrl + Shift + R. Also, confirm that the correct .php files were uploaded
## Features


- Fullscreen mode
- Cross platform


## Feedback

If you have any feedback, please reach out to us at https://techtitansadv.rf.gd


## 🚀 About Me
👋 "Final year CSE diploma student working from home while studying. Completed an internship at CODSOFT COMPANY, developing skills in Python, web design.
## 🔗 Links
[![portfolio](https://img.shields.io/badge/my_portfolio-000?style=for-the-badge&logo=ko-fi&logoColor=white)](https://kids.rf.gd/?i=1)
[![linkedin](https://img.shields.io/badge/linkedin-0A66C2?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/pawan-patel-081721252/)



![Logo](https://drive.google.com/file/d/1UqbDe-GwnJ15K0hGnOd5iVlwmvXSmipC/view?usp=sharing)


## Used By

This project is used by the following usrers:

- Satyendra
- Aaditya


