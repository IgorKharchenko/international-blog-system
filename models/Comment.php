<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property string $id
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

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * getLastInsertedID doesn't work there,
     * that's why I wrote this bibycle because we need to use the value
     * which has been inserted previously,
     * but it's not guaranteed that insertion has been done by OWN user
     */
    public function getNewId() {
        $query = Comment::find()
            ->max('id');
        return $query + 1;
    }

    /**
     * Gets all the data from the comment form
     */
    public function getData()
    {
        $comment = new Comment;
        $comment->id = $this->getId();
        $comment->title = $this->title;
        $comment->content = $this->content;
        $comment->post_id = $_GET['id'];
        $comment->author_id = Yii::$app->user->id;
        $comment->publish_date = time();

        $comment->save();

        return $comment;
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
}
