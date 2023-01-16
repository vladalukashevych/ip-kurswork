<?php
/** @var array $recipes */
/** @var array $favourites */

/** @var string $search */

use core\Core;
use models\Favourite;
use models\User;

$authenticated = User::isUserAuthenticated();


Core::getInstance()->pageParams['title'] = 'Recipes';
?>

<h1 class="mb-3">Recipes</h1>
<?php if (User::isAdmin()) : ?>
    <div class="mb-2">
        <a href="/recipe/add" class="btn btn-primary btn-success">Add Recipe</a>
    </div>
<?php endif; ?>
<?php if (!empty($search) && empty($recipes)) : ?>
    <div class="display-5 mx-auto sorry-message w-100 text-center">Sorry, looks like there is no match for "<span
                class="fw-bold"><?= $search ?></span>" :(
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
