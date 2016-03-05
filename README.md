Yii2 Simple Blog
================

The blog contains the basic features including user login/logout, user registration.

An author can create post, update and delete own posts; also he can update own user information: nickname and email.

An admin can do anything he need excluding, obviously, viewing user password hash :D

Authors can make drafts and published posts.

Also the blog contains admin privilegies assignment, which works for authors if there aren't any admins in the blog.

This Yii2 Blog is not using transactions for DB interaction, only single queries without rollbacks. In the nearest future by all means I'll correct this!

Refactoring
-----------

Optimisation of the SQL queries:

### post\index view

In this view I make at least 3 queries per 1 post. I need to correct this.

```php
$user_model = $tmp_user->findAuthorUsername($post->id); // For showing author_id
$hasPrivilegies_Post = $tmp_user->checkUDPrivilegies($post); // For checking Update/Delete privilegies
$comments_count = $tmp_comment->commentsCount($post->id, $pagination); // For counting all the comments in a post
```


### post\view view

There is so much extra queries for showing comment info. 

```php
$comment_author = $tmp_comment->findAuthorUsername($comment->id);
$hasPrivilegies_Comment = $tmp_comment->checkUDPrivilegies($comment);

```

P.S. 
----

I've invented many bicycles that I forgot to tie any wheels. >_<

I'm going to refactor this completely in the near future.