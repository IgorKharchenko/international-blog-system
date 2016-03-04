<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        /* Rules:
            @var $authorRule — for associating this post\comment author ID to user ID,
            i.e. only author of post excluding an admin can update\delete this;
            @var $userRule — for associating this user ID to user which it belongs to */
        $authorRule = new \app\rbac\AuthorRule;
        $userRule = new \app\rbac\UserRule;
        $auth->add($authorRule);
        $auth->add($userRule);

        # ========================================================================
        # Post permissions
        // add "createPost" permission
        $createPost = $auth->createPermission('createPost');
        $createPost->description = 'Create a post';
        $auth->add($createPost);
        #
        // add "updatePost" permission
        $updatePost = $auth->createPermission('updatePost');
        $updatePost->description = 'Update any post';
        $auth->add($updatePost);
        #
        // and add the "updateOwnPost" permission and associate the rule with it
        $updateOwnPost = $auth->createPermission('updateOwnPost');
        $updateOwnPost->description = 'Update own post';
        $updateOwnPost->ruleName = $authorRule->name;
        $auth->add($updateOwnPost);
        #
        // add "deletePost" permission
        $deletePost = $auth->createPermission('deletePost');
        $deletePost->description = 'Delete any post';
        $auth->add($deletePost);
        #
        // and add "deleteOwnPost" permission and associate the rule with it
        $deleteOwnPost = $auth->createPermission('deleteOwnPost');
        $deleteOwnPost->description = 'Delete own post';
        $deleteOwnPost->ruleName = $authorRule->name;
        $auth->add($deleteOwnPost);

        # --------------------------
        # User permissions
        // add "createUser" permission
        $createUser = $auth->createPermission('createUser');
        $createUser->description = 'Create a user';
        $auth->add($createUser);
        #
        // add "readUser" permission
        $readUser = $auth->createPermission('readUser');
        $readUser->description = 'Read a user';
        $auth->add($readUser);
        #
        // add "updateUser" permission
        $updateUser = $auth->createPermission('updateUser');
        $updateUser->description = 'Update any user';
        $auth->add($updateUser);
        #
        // and add "updateOwnUser" permission and associate the rule with it
        $updateOwnUser = $auth->createPermission('updateOwnUser');
        $updateOwnUser->description = 'Update own user info';
        $updateOwnUser->ruleName = $userRule->name;
        $auth->add($updateOwnUser);
        #
        // add "deleteUser" permission
        $deleteUser = $auth->createPermission('deleteUser');
        $deleteUser->description = 'Delete any user';
        $auth->add($deleteUser);
        #
        // and add "deleteOwnUser" permission and associate the rule with it
        $deleteOwnUser = $auth->createPermission('deleteOwnUser');
        $deleteOwnUser->description = 'Delete own user info';
        $deleteOwnUser->ruleName = $userRule->name;
        $auth->add($deleteOwnUser);

        # ----------------------------------
        # Comment permissions
        // add "createComment" permission
        $createComment = $auth->createPermission('createComment');
        $createComment->description = 'Create a comment';
        $auth->add($createComment);
        #
        // add "updateComment" permission
        $updateComment = $auth->createPermission('updateComment');
        $updateComment->description = 'Update any comment';
        $auth->add($updateComment);
        #
        // add "updateOwnComment" permission and associate the rule with it
        $updateOwnComment = $auth->createPermission('updateOwnComment');
        $updateOwnComment->description = 'Update own comment';
        $updateOwnComment->ruleName = $authorRule->name;
        $auth->add($updateOwnComment);
        #
        // add "deleteComment" permission
        $deleteComment = $auth->createPermission('deleteComment');
        $deleteComment->description = 'Delete any comment';
        $auth->add($deleteComment);
        #
        // add "deleteOwnComment" permission and associate the rule with it
        $deleteOwnComment = $auth->createPermission('deleteOwnComment');
        $deleteOwnComment->description = 'Delete own comment';
        $deleteOwnComment->ruleName = $authorRule->name;
        $auth->add($deleteOwnComment);

        # ======================================================================
        # Roles
        // Unapproved role, only info reading is available
        $unapproved = $auth->createRole('unapproved');
        $unapproved->description = 'Unapproved';
        $auth->add($unapproved);
        $auth->addChild($unapproved, $readUser);

        // Author role
        // Author can only update\delete THEIR OWN post/comment/user_info
        $author = $auth->createRole('author');
        $author->description = 'Author';
        $auth->add($author);
        $auth->addChild($author, $unapproved);
        $auth->addChild($author, $createPost);
        $auth->addChild($author, $updateOwnPost);
        $auth->addChild($author, $deleteOwnPost);
        $auth->addChild($author, $createComment);
        $auth->addChild($author, $updateOwnComment);
        $auth->addChild($author, $deleteOwnComment);
        $auth->addChild($author, $updateOwnUser);
        $auth->addChild($author, $deleteOwnUser);

        // Admin role
        // Admin can update\delete ALL the info, that's why I've used replacing childs
        $admin = $auth->createRole('admin');
        $admin->description = 'Admin';
        $auth->add($admin);
        $auth->addChild($admin, $author);
        #
        $auth->removeChild($admin, $updateOwnPost);
        $auth->removeChild($admin, $deleteOwnPost);
        $auth->removeChild($admin, $updateOwnComment);
        $auth->removeChild($admin, $deleteOwnComment);
        $auth->removeChild($admin, $updateOwnUser);
        $auth->removeChild($admin, $deleteOwnUser);
        $auth->addChild($admin, $updatePost);
        $auth->addChild($admin, $deletePost);
        $auth->addChild($admin, $updateComment);
        $auth->addChild($admin, $deleteComment);
        $auth->addChild($admin, $updateUser);
        $auth->addChild($admin, $deleteUser);
        #
        $auth->addChild($admin, $createUser);
    }
}