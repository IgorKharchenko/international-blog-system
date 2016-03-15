<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property integer $blog_id
 * @property Blog $blog
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'title', 'blog_id'], 'required'],
            [['blog_id'], 'integer'],
            [['name'], 'string', 'max' => 50], // Maximum 10 categories with 50 symbols per one post
            [['title'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'title' => 'Title',
            'blog_id' => 'Blog ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlog()
    {
        return $this->hasOne(Blog::className(), ['id' => 'blog_id']);
    }

    /**
     * Gets all the blog categories
     * @param $blog
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getBlogCategories($blog)
    {
        return $query = Category::find()
            ->select(['id', 'name'])
            ->where(['blog_id' => $blog->id])
            ->asArray()
            ->all();
    }

    /**
     * Gets a category by her ID
     * @param $id
     * @return null|static
     */
    public function getCategoryById($id)
    {
        return $this->findOne(['id' => $id]);
    }

    /**
     * Gets all the data from the comment form
     * @return mixed
     */
    public function getData()
    {
        $category = new Category;
        $category->name = $this->name;
        $category->title = $this->title;
        $category->blog_id = Blog::getBlogByAuthorId(Yii::$app->user->id)->id;
        return $category;
    }

    /**
     * Checks a string with all the categories if they still kept in 'category' DB table
     * @param Post $post Post model
     * @param string $categories_all Query with all the categories
     * @return array|string
     */
    public function checkPostCategories($post, $categories_all)
    {
        $categories_string = $post->categories;
        if($categories_string == '') return '';
        $categories_string = explode(',', $categories_string);

        $categories_checked = Array();
        $blog = Blog::getBlogByAuthorId($post->author_id);
        for($i=0;$i<count($categories_all);$i++)
        {
            if(in_array($categories_all[$i]['id'], $categories_string)
                && $blog->id == $categories_all[$i]['blog_id'])
                    array_push($categories_checked, $categories_all[$i]['id']);
        }
        return $categories_checked;
    }

    /**
     * Validates all the posts categories in DB table
     * @param $posts
     * @return \yii\db\Query|null
     */
    public function checkAllPostsCategories($posts)
    {
        $categories_all = ''; # <- is a string with all the posts categories
        if(count($posts) == 1)
            if ($posts->categories == '')
                return NULL;
            else
                $categories_all .= $posts->categories;
        else { # if post is not a single
            foreach ($posts as $post) {
                if ($post->categories) {
                    $categories_all .= $post->categories;
                    $categories_all .= ',';
                }
            }
            $categories_all = substr($categories_all, 0, strrpos($categories_all, ',')); # deletes all the symbols after the last comma in string
            $categories_all = explode(',', $categories_all);
            $categories_all = array_unique($categories_all); # Deletes all repeating categories
            $categories_all = implode(',', $categories_all);

            if($categories_all == '') return null;
        }

        # find all the posts categories with unique indexes of categories
        $query = Category::find()
            ->where('id IN ('.$categories_all.')')
            ->asArray()
            ->all();
        return $query;
    }

    /**
     * Cleans all already deleted categories in DB
     *      and updates 'categories' property of post model
     * @param $post
     */
    public function cleanAllDeletedCategoriesInPost($post)
    {
        $categories = $post->categories;
        $query = Category::find()
            ->where('id IN ('.$categories.')')
            ->all();
        $categories = explode(',',$categories);

        $categories_checked = Array();
        $blog = Blog::getBlogByAuthorId($post->author_id);
        for($i=0;$i<count($query);$i++)
        {
            if(in_array($query[$i]['id'], $categories)
                && $blog->id == $query[$i]['blog_id'])
                array_push($categories_checked, $query[$i]['id']);
        }
        $post->categories = implode(',', $categories_checked);
    }

    /**
     * Searches a category name by her ID
     * @param $array
     * @param $id
     * @return mixed
     */
    public function searchCategoryById($array, $id)
    {
        $i = 0;
        do
        if($array[$i]['id'] == $id)
            return $array[$i]['name'];
        while(++$i < count($array));
    }

    /**
     * Do transaction which saves a model
     * @param $model
     * @return bool true if a transaction is successful
     */
    public function saveCategory($model)
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
     * Do transaction which saves a model
     * @param $model
     * @return bool true if a transaction is successful
     */
    public function deleteCategory($model)
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
}
