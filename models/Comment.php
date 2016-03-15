<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

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
            [['title', 'content', 'author_id', 'post_id'], 'required'],
            [['author_id', 'post_id'], 'integer'],
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
        $comment->title = $this->title;
        $comment->content = $this->content;
        $comment->post_id = $_GET['id'];
        $comment->author_id = Yii::$app->user->id;
        $comment->publish_date = time();
        return $comment;
    }

    /**
     * Counts all author ID's of displayed comments
     * and deletes all repeating values from that array
     * @param $posts| Array with all posts that have been displayed
     * @return string|
     */
    public function getAuthorIDs($comments)
    {
        # 2 step
        $array = Array();
        $i=0;
        foreach($comments as $comment)
        {
            $array[$i] = ['id' => $comment->author_id];
            $i++;
        }
        # 3 step
        $array = $this->deleteRepeatingValues($array);
        $temp_str = $this->getAuthorIDsInString($array);
        return $temp_str;
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
     * @return string|
     */
    private function getAuthorIDsInString($array)
    {
        $temp = '';
        for ($i = 0; $i < count($array); $i++) {
            $temp .= $array[$i]['id'];
            if ($i != count($array) - 1)
                $temp .= ', ';
        }
        return $temp;
    }

    /**
     * Gets all the comments which are belongs to post ID
     * @param int $id Post ID
     * @param Pagination $pagination
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findPostComments($id, $pagination)
    {
        return Comment::find()
            ->where(['post_id' => $id])
            ->orderBy('publish_date')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
    }


    /**
     * Finds all comments where 'post_id' IN (string).
     * @param $post_IDs| String contains all post ID's
     * @return array|\yii\db\ActiveRecord[] Returns an array contains info about all comments
     */
    public function findByPostIDs($post_IDs)
    {
        $query = Comment::find()
            ->select('id, post_id')
            ->where('post_id IN('.$post_IDs.')')
            ->asArray()
            ->all();
        return $query;
    }


    /**
     * Finds author username by comment id
     * @param $comment_id| Comment id
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
     * Returns query which finds all comments by post ID
     * @param $id| Post ID
     * @return mixed| Query
     */
    public function queryFindByPostID($id)
    {
        return Comment::find()->where(['post_id' => $id]);
    }

    /**
     * Updates comment_count on Post
     * @param $post| Post model required to this function
     * @param $string| string contains mode: count++ is increment, count-- is decrement
     * @return mixed| Returns an executed query
     */
    public function updateCommentsCount($post, $string)
    {
        $string === 'count++' ? $inc = 1 : $inc = -1;

        $command = Yii::$app->db->createCommand();
        $result = $command->update( #sql UPDATE
            'post', #table
            ['comments_count' => $post->comments_count + $inc], #sql update SET
            ['id' => $post->id] #sql WHERE
        )->execute();
        return $result;
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
     * Do transaction which saves a model
     * @param $model
     * @return bool true if a transaction is successful
     */
    public function saveComment($model)
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
    public function deleteComment($model)
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
