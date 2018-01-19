IBS: International Blog System
==============================

The blog system contains the basic features including user login/logout, user registration.

Anyone who was being registered can make own blog with an admin rights.

An admin of our blog(author privilegies) can create post, update and delete own posts; also he can update own user information: nickname, email, timezone, about yourself and so on.
Authors can make drafts and published posts.

An admin of site(admin privilegies) can do anything he needed excluding, obviously, viewing user password hashes :D

Also, all the time stamps are displayed depending on user timezone.
The time saves in the database in GMT +0000 timezone and any manipulations with time is doing under influence of local time zone.
To init GMT +0000 server timezone you need to run the command `yii site/init-greenwich-timezone` on the console.

Also the blog contains admin privilegies assignment page that available for admins. 
For assign first admin, you need run the command `yii rbac/init-first-admin [id]`, where id(without brackets) is an user ID.

Installation
============

1. Import database dump (`database_dump.sql`) and set database name in `config/web.php` file.

2. Install vendor packages:
```bash
composer install && composer update
```
