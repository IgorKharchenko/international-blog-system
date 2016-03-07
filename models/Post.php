<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;


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
     * Gets all the data of the post (post/create)
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
     * Finds all published posts with given offset and limit
     * @param $pagination| Gives offset and limit of this query
     * @return array|\yii\db\ActiveRecord[]|
     */
    public function findAllDisplayedPosts($pagination)
    {
        return Post::find()
            ->where('publish_status=:publish_status', [':publish_status' => 'publish'])
            ->orderBy('publish_date')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
    }

    /**
     * Counts all author ID's of displayed posts
     * and deletes all repeating values from that array
     * @param $posts| Array with all posts that have been displayed
     * @return string|
     */
    public function getAuthorIDs($posts)
    {
        # 2 step
        $array = Array();
        $i=0;
        foreach($posts as $post)
        {
            $array[$i] = ['id' => $post->author_id];
            $i++;
        }
        $arrayLastAmount = count($array);

        # 3 step
        $array = $this->deleteRepeatingValues($array);
        return $temp_str = $this->getAuthorIDsInString($array, $arrayLastAmount);
    }

    /**
     * Deletes all repeating values in the authorID array
     * @param $array| Array with author ID values
     * @return array|
     */
    private function deleteRepeatingValues($array)
    {
        return $array = array_map("unserialize", array_unique( array_map("serialize", $array) ));
    }

    /**
     * Returns a string contains all author ID's separated by comma
     * @param $array| Array that contains all user ID's
     * @param int $arrayLastAmount| Amount of elements of array before moving off repeating values
     * @return string|
     */
    private function getAuthorIDsInString($array, $arrayLastAmount)
    {
        $temp = '';
        for ($i = 0; $i < $arrayLastAmount; $i++) {
            if($array[$i] != null)
                $temp .= $array[$i]['id'] . ', ';
        }
        return substr($temp, 0, strrpos($temp, ',')); # deletes all the symbols after the last comma in string
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
     * Finds all or only published/drafted posts of current user
     * @param $status
     * @return $this
     */
    public function findOurPosts($status)
    {
        $query = Post::find();

        if($status == 'all') {
            return $posts = $query
                ->where('author_id=:author_id', [':author_id' => Yii::$app->user->id]);
        } else {
            return $posts = $query
                ->where('publish_status=:status', [':status' => $status])
                ->andWhere('author_id=:author_id', [':author_id' => Yii::$app->user->id]);
        }
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
