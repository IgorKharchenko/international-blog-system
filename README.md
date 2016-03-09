Yii2 Simple Blog
================

The blog contains the basic features including user login/logout, user registration.

An author can create post, update and delete own posts; also he can update own user information: nickname and email.

An admin can do anything he need excluding, obviously, viewing user password hashes :D

Authors can make drafts and published posts.

Also the blog contains admin privilegies assignment, which works if the command `yii rbac/init-first-admin [id]` was send.

TODO
----
	— I need to combine User and Users CRUD's, because I'm very layed an egg with this;
	— Timezone check is needed;
	— $user->last_login not must be NULL in the DataBase;
	— Country selecting will be maked tomorrow

P.S. 
----

I've invented many bicycles that I forgot to tie any wheels. >_<

I'm going to refactor this completely in the near future.