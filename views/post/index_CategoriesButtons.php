<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\Blog;
use app\models\Category;
use kartik\icons\Icon;

/*
 * How it works:
 * 1. We need to find all the elements in the array of all the posts categories
 *      and delete ones who were deleted in DB
 * 2. Next: for every post we need to provide own categories
 *
 */
?>

<?php

if($categories_all == null) return '';

$category_model = new Category;
$categories = $category_model->checkPostCategories($post, $categories_all);
if($categories != '')
{
    echo ' | ' . Icon::show('tags');
    foreach((object)$categories as $category) {
        $this_category_name = $category_model->searchCategoryById($categories_all, $category); # function returns a category name
        echo Html::a($this_category_name,
            ['post/index', 'blog_id' => $blog->id, 'category' => $category],
            ['class' => 'btn-xs btn-primary'])
            . '  '; // ...with double space in a separator role
    }
}
?>

