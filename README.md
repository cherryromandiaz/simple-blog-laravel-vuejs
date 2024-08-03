# Simple Blog Platform Using Laravel, Tailwind and Vue JS, Rest API
![Front Page](https://i.ibb.co.com/T40nLCw/front-page.jpg) ![Admin Dashboard](https://i.ibb.co.com/J2zfRnh/admin-dashboard.jpg)

## Introduction

This is a simple blog project built using Backend Rest API, Laravel Framework 11.16.0, Tailwind and Vue 3 as Front End, which is consume API. It allows users to register, log in, create, edit, delete, and view blog posts. create, delete, and view comments. as well as like and dislike posts and comments.Real time search function by title or content

## Prerequisites

- [ ] Composer
- [ ] Node.js and npm
- [ ] Git
- [ ] XAMPP

## Installation

- Clone the repository:
```
git clone git@github.com:cherryromandiaz/simple-blog-laravel-vuejs.git
```
- Install PHP dependencies:
```
composer install
```
- Install JavaScript dependencies:
```
npm install
```
- Set up environment variables and configure the database and other settings.
- Generate application key:
```
php artisan key:generate
```
- Run database migrations and seeders:
```
php artisan migrate --seed
```
- Run backend:
```
cd /path/backend
php artisan serve
http://localhost:8000
```
- Run frontend:
```
cd /path/frontend
npm run serve
http://localhost:8080
```


## Features

- User registration and authentication
- Create, edit, delete, and view blog posts
- Like and dislike posts and comments
- Manage comments
- Real time search function by title or content

# API Endpoints



## Authentication
- POST /api/register: Register a new user
- POST /api/login: Log in a user

## Posts
- GET /api/posts: List all posts
- GET /api/posts/{post_id}: Get a single post
- POST /api/posts: Create a new post
- PUT /api/posts/{post_id}: Update a post
- DELETE /api/posts/{post_id}: Delete a post
- POST /api/posts/{post_id}/like: Like a post
- POST /api/posts/{post_id}/dislike: Dislike a post

## Comments
- GET /api/posts/{post_id}/comments: List all comments for a post
- POST /api/posts/{post_id}/comments: Create a new comment
- DELETE /api/comments/{comment_id}: Delete a comment
- POST /api/comments/{comment_id}/like: Like a comment
- POST /api/comments/{comment_id}/dislike: Dislike a comment



