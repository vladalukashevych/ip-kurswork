<?php
/** @var array $category */
/** @var array $recipes */

/** @var array $favourites */

use core\Core;
use models\Favourite;
use models\User;

$authenticated = User::isUserAuthenticated();
Core::getInstance()->pageParams['title'] = $category['name'];
?>

<h1 class="mb-3"><?= $category['name'] ?></h1>
<?php if (User::isAdmin()) : ?>
    <div class="mb-3">
        <a href="/recipe/add/<?= $category['id'] ?>" class="btn btn-success">Add Recipe</a>
    </div>
<?php endif; ?>

<div class="m-lg-auto recipes-list" id="recipes-list">
    <?php echo "<script>";
    foreach ($recipes as $recipe) {
        $rowJs = "addRecipeCard(\"{$recipe['id']}\", \"{$recipe['name']}\", \"{$recipe['description']}\", 
            \"{$recipe['time']}\"";
        $filePath = 'files/recipe/' . $recipe['photo'];
        if (is_file($filePath))
            $rowJs .= ", '{$filePath}'";
        else
            $rowJs .= ", null";
        if ($authenticated)
            $rowJs .= ", true";
        if (!empty($favourites)) {
            foreach ($favourites as $favourite)
                if (isset($favourite['recipe_id']) && $favourite['recipe_id'] == $recipe['id'])
                    $rowJs .= ", true";
        }
        $rowJs = $rowJs . ");\n";
        echo $rowJs;
    }
    echo "</script>";
    ?>
</div>
