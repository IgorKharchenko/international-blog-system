<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "post".
 *
 * @property integer $id
 * @property string $title
 * @property string $anons
 * @property string $content
 * @property integer $category_id
 * @property integer $author_id
 * @property integer $publish_status
 * @property integer $publish_date
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['anons', 'content', 'publish_status'], 'string'],
            [['category_id', 'author_id'], 'integer'],
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
            'anons' => 'Anons',
            'content' => 'Content',
            'category_id' => 'Category ID',
            'author_id' => 'Author ID',
            'publish_status' => 'Publish Status',
            'publish_date' => 'Publish Date',
        ];
    }

    /**
     * getLastInsertedID doesn't work there,
     * that's why I wrote this bibycle because we need to use the value
     * which has been inserted previously,
     * but it's not guaranteed that insertion has been done by OWN user
     */
    public function getNewId() {
        $query = Post::find()
            ->max('id');
        return $query + 1;
    }

    /**
     * Gets all the data of the post (in creation time)
     */
    public function getPostData()
    {
        $post = new Post;
        $post->title = $this->title;
        $post->anons = $this->anons;
        $post->content = $this->content;
        $post->category_id = $this->category_id;
        $post->author_id = Yii::$app->user->id;
        $post->publish_status = $this->publish_status;
        $post->publish_date = time();
        $post->save();

        return $post;
    }

    /**
     * Finds author username of this post
     * @return mixed|null if error is occured
     */
    public function findAuthorUsername($post_id)
    {
        $author_id = Post::find()
            ->select('author_id')
            ->where('id=:post_id', [':post_id' => $post_id])
            ->one();
        $user_model = User::find()
            ->where('id=:author_id', ['author_id' => $author_id->author_id])
            ->one();
        return !is_null($user_model) ? $user_model : null;
    }

    /**
     * Checks if user has Create Post privilegies
     * @return bool
     */
    public function checkCPrivilegies()
    {
        return (\Yii::$app->user->can('createPost'));
    }

    /**
     * Checks if user has Update/Delete Post privilegies
     * @param $model
     * @return bool
     */
    public function checkUDPrivilegies($model)
    {
        return ((Yii::$app->user->can('updatePost') && Yii::$app->user->can('deletePost'))
            || (Yii::$app->user->can('updateOwnPost', ['post' => $model]) && Yii::$app->user->can('deleteOwnPost', ['post' => $model]) ));
    }

}
