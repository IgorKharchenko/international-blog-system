<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $author_id
 * @property integer $post_id
 * @property string $publish_date
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'title', 'content', 'author_id', 'post_id'], 'required'],
            [['id', 'author_id', 'post_id'], 'integer'],
            [['content'], 'string'],
            [['publish_date'], 'safe'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'author_id' => 'Author ID',
            'post_id' => 'Post ID',
            'publish_date' => 'Publish Date',
        ];
    }

    /**
     * Gets ID of the
     * @return mixed
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * Bicycle for getting user ID
     * @return mixed
     */
    public function getNewId()
    {
        $query = Comment::find()
            ->max('id');
        return $query + 1;
    }

    /**
     * Gets all the data from the comment form
     * @return mixed
     */
    public function getData()
    {
        $comment = new Comment;
        $comment->id = $this->getNewId();
        $comment->title = $this->title;
        $comment->content = $this->content;
        $comment->post_id = $_GET['id'];
        $comment->author_id = Yii::$app->user->id;
        $comment->publish_date = time();
        $comment->save();

        return $comment;
    }

    /**
     * Gets all the comments which are belongs to post ID
     * @param $id Post ID
     * @param $pagination Pagination info for offset+limit
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getPostComments($id, $pagination)
    {
        return Comment::find()
            ->where(['post_id' => $id])
            ->orderBy('publish_date')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
    }

    /**
     * Finds author username by comment id
     * @param $comment_id Comment id
     * @return mixed|null if a comment isn't found
     */
    public function findAuthorUsername($comment_id) {

        $comment = Comment::find()
            ->select('author_id')
            ->where('id=:comment_id', [':comment_id' => $comment_id])
            ->one();
        $user_model = User::find()
            ->where('id=:author_id', ['author_id' => $comment->author_id])
            ->one();
        return !is_null($user_model) ? $user_model : false;
    }

    /**
     * Counts the comments that refers to post ID
     * @param $post_id
     * @param $pagination
     * @return mixed
     */
    public function commentsCount($post_id, $pagination)
    {
        $comments_count = Comment::find()
            ->where(['post_id' => $post_id])
            ->orderBy('publish_date')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->count();
        return $comments_count;
    }

    /**
     * Checks if user has Create Comment privilegies
     * @return bool
     */
    public function checkCPrivilegies()
    {
        return (\Yii::$app->user->can('createComment'));
    }

    /**
     * Checks if user has Update/Delete Comment privilegies
     * @param $comment
     * @return bool
     */
    public function checkUDPrivilegies($comment)
    {
        return ((Yii::$app->user->can('updateComment') && Yii::$app->user->can('deleteComment'))
            || (Yii::$app->user->can('updateOwnComment', ['post' => $comment]) && Yii::$app->user->can('deleteOwnComment', ['post' => $comment]) ));
    }
}
