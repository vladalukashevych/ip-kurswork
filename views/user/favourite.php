<?php
/** @var array $recipes */

use core\Core;
use models\Favourite;
use models\User;

Core::getInstance()->pageParams['title'] = 'Favourites';
?>

<h1 class="mb-3">Favourites</h1>
<?php if (empty($recipes)) : ?>
    <div class="display-5 mx-auto sorry-message w-100 text-center">Looks like you haven't liked anything yet.<br>Want to
        take a look on some some <a href="/recipe"
                                    class="fw-semibold fst-italic link-dark text-decoration-none">recipes</a>?
    </div>
<?php else : ?>
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
        $rowJs .= ", true, true);\n";
        echo $rowJs;
    }
    echo "</script>";
    endif;
    ?>
</div>