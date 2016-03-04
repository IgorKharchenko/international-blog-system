Yii2 Simple Blog
================

The blog contains the basic features including user login/logout, user registration.

An author can create post, update and delete own posts; also he can update own user information: nickname and email.

An admin can do anything he need excluding, obviously, viewing user password hash :D

Authors can make drafts and published posts.

Also the blog contains admin privilegies assignment, which works for authors if there aren't any admins in the blog.

This Yii2 Blog is not using transactions for DB interaction, only single queries without rollbacks. In the nearest future I'm going to correct this!

P.S. 
----

I've invented many so many bicycles that I forgot to tie any wheels. >_<

I'm going to refactor this completely in the near future.