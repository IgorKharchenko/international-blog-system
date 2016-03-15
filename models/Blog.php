<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%blog}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property integer $created_at
 *
 * @property BlogAuthor[] $blogAuthors
 */
class Blog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%blog}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'title', 'created_at'], 'required'],
            [['created_at', 'is_private'], 'integer'],
            [['name'], 'string', 'max' => 127],
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
            'name' => 'Name of Your Blog',
            'title' => 'Title of Your Blog',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Updates posts_count on Blog
     * @param $post| Blog model required to this function
     * @param $string| string contains mode: count++ is increment, count-- is decrement
     * @return mixed| Returns an executed query
     */
    public function updatePostsCount($blog, $string)
    {
        $string === 'count++' ? $inc = 1 : $inc = -1;

        $command = Yii::$app->db->createCommand();
        $result = $command->update( #sql UPDATE
            $this->tableName(), #table
            ['posts_count' => $blog->posts_count + $inc], #sql update SET
            ['id' => $blog->id] #sql WHERE
        )->execute();
        return $result;
    }

    /**
     * Get the primary key value
     * @return mixed
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * Returns blog model with defined author ID
     * @param $id
     * @return $this
     */
    public static function getBlogByAuthorId($id)
    {
        return Blog::findOne(['author_id' => $id]);
    }

    /**
     * Gets all the data from the comment form
     * @return mixed
     */
    public function getData()
    {
        $blog = new Blog;
        $blog->name = $this->name;
        $blog->title = $this->title;
        $blog->author_id = Yii::$app->user->id;
        $blog->created_at = time();
        return $blog;
    }

    /**
     * Finds all public blogs with given offset and limit
     * @param $pagination| Gives offset and limit of this query
     * @return array|\yii\db\ActiveRecord[]|
     */
    public static function findAllDisplayedBlogs($pagination)
    {
        return Blog::find()
            ->where(['is_private' => '0'])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
    }

    /**
     * Gets a defined in $limit amount of latest blogs
     * @param $limit
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findLatestBlogs($limit)
    {
        return $query = Blog::find()
            ->where(['created_at < :now', ['now' => time()]])
            ->limit($limit)
            ->orderBy('created_at DESC')
            ->all();
    }

    /**
     * Finds author username of this blog
     * @return mixed|null if error is occured
     */
    public static function findAuthorOfBlog($blog_id)
    {
        $blog = Blog::find()
            ->select('author_id')
            ->where(['id' => $blog_id])
            ->one();
        $user_model = User::find()
            ->where(['id' => $blog->author_id])
            ->one();
        return !is_null($user_model) ? $user_model : null;
    }



    /**
     * Saves a blog info
     * @param $model
     * @return bool
     */
    public function saveBlog($model)
    {
        $transaction = Yii::$app->db->beginTransaction();
        if($model->save())
        {
            $transaction->commit();
            return true;
        } else {
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * Deletes a blog completely
     * @param $model
     * @return bool
     */
    public function deleteBlog($model)
    {
        $transaction = Yii::$app->db->beginTransaction();
        if($model->delete())
        {
            $transaction->commit();
            return true;
        } else {
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * Checks if user has Create Blog privilegies
     * @return bool
     */
    public function checkCPrivilegies()
    {
        return (\Yii::$app->user->can('createBlog'));
    }

    /**
     * Checks if user has Update/Delete Blog privilegies
     * @param $model
     * @return bool
     */
    public function checkUDPrivilegies($model)
    {
        return ((Yii::$app->user->can('updateBlog') && Yii::$app->user->can('deleteBlog'))
            || (Yii::$app->user->can('updateOwnBlog', ['blog' => $model]) && Yii::$app->user->can('deleteOwnBlog', ['blog' => $model]) ));
    }
}
