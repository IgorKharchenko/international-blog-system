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
 * @property integer $author_id
 * @property integer $blog_id
 * @property integer $publish_status
 * @property integer $publish_date
 * @property string $categories
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

    public $categories_selection = Array();

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['anons', 'content', 'publish_status'], 'string'],
            [['publish_date', 'categories_selection'], 'safe'],
            [['title'], 'string', 'max' => 255],
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
            'author_id' => 'Author ID',
            'publish_status' => 'Publish Status',
            'publish_date' => 'Publish Date',
        ];
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
        $post->blog_id = $_GET['blog_id'];
        $post->author_id = Yii::$app->user->id;
        $post->publish_status = $this->publish_status;
        $post->publish_date = time();
        $post->categories = ($this->categories_selection != '') ? implode(',', $this->categories_selection) : '';

        return $post;
    }

    /**
     * Gets all the post categories
     * @param $blog Blog model
     * @param $post Post model
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getPostCategoriesAsString($blog, $post)
    {
        $query = Post::find()
            ->select(['categories'])
            ->where(['id' => $post->id])
            ->andWhere(['blog_id' => $blog->id])
            ->asArray()
            ->one();
        return $categories = implode(',',$query->categories);
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
     * Gets selected categories
     * @param $model
     * @return array
     */
    public function getCategories($model)
    {
        return $this->categories_selection;
    }

    /**
     * Creates an array for multiple categories selection
     * @return array
     */
    public function createArrayForCategoriesSelection()
    {
        return ArrayHelper::map(Category::getBlogCategories(Blog::getBlogByAuthorId(Yii::$app->user->id)), 'id', 'name');
    }

    /**
     * Finds all published posts with given offset and limit
     * @param $pagination| Gives offset and limit of this query
     * @return array|\yii\db\ActiveRecord[]|
     */
    public function findAllDisplayedPosts($pagination, $blog_id, $category='')
    {
        if($category == '')
            return Post::find()
            ->where(['publish_status' => 'publish'])
            ->andWhere(['blog_id' => $blog_id])
            ->orderBy('publish_date')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        else
            return Post::find()
            ->where(['publish_status' => 'publish'])
            ->andWhere(['blog_id' => $blog_id])
            ->andWhere(['LIKE', 'categories', $category])
            ->orderBy('publish_date')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
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
     * Gets a defined in $limit amount of latest posts
     * @param int $limit Limit of displayed posts
     * @return \yii\db\ActiveQuery
     */
    public static function findLatestPosts($limit)
    {
        // Firstly select all public blogs
        $public_blogs = Blog::find()
            ->select('id')
            ->where('is_private=0')
            ->limit($limit)
            ->all();
        $public_blogs_string = '';
        foreach($public_blogs as $blog)
            $public_blogs_string .= $blog->id . ',';
        $public_blogs_string = substr($public_blogs_string, 0, strrpos($public_blogs_string, ',')); # deletes all the symbols after the last comma in string

        // Secondly select all latest posts in public blogs
        return $query = Post::find()
            ->where('publish_date < :now', [':now' => time()])
            ->andWhere('blog_id IN ('.$public_blogs_string.')')
            ->limit($limit)
            ->orderBy('publish_date DESC')
            ->all();
    }

    /**
     * Do transaction which saves a model
     * @param $model
     * @return bool true if a transaction is successful
     */
    public function savePost($model)
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
     * Do transaction which deletes a model
     * @param $model
     * @return bool true if a transaction is successful
     */
    public function deletePost($model)
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
     * Checks if user has Create Post privilegies
     * @return bool
     */
    public function checkCPrivilegies()
    {
        $can_create_blog = (Blog::getBlogByAuthorId(Yii::$app->user->id)->author_id === Yii::$app->user->id);
        return (\Yii::$app->user->can('createPost') && $can_create_blog);
    }

    /**
     * Checks if user has Update/Delete Post privilegies
     * @param $model
     * @return bool
     */
    public function checkUDPrivilegies($post)
    {
        return ((Yii::$app->user->can('updatePost') && Yii::$app->user->can('deletePost'))
            || (Yii::$app->user->can('updateOwnPost', ['post' => $post]) && Yii::$app->user->can('deleteOwnPost', ['post' => $post]) ));
    }
}
