<?php

namespace models;

use core\Core;
use core\Utils;

class Recipe
{
    protected static $tableName = 'recipe';

    public static function addRecipe($row)
    {
        $fieldsList = ['name', 'description', 'time', 'servings', 'calories', 'instructions', 'category_id'];
        $row = Utils::filterArray($row, $fieldsList);
        Core::getInstance()->db->insert(self::$tableName, $row);
    }

    public static function deleteRecipe($id)
    {
        self::deleteRecipePhoto($id);
        Core::getInstance()->db->delete(self::$tableName, [
            'id' => $id
        ]);
    }

    public static function updateRecipe($id, $row)
    {
        $fieldsList = ['name', 'description', 'time', 'servings', 'calories', 'instructions', 'category_id'];
        $row = Utils::filterArray($row, $fieldsList);
        Core::getInstance()->db->update(self::$tableName, $row, [
            'id' => $id
        ]);
    }

    public static function changePhoto($id, $newPhotoPath)
    {
        if (!empty($newPhotoPath)) {
            self::deleteRecipePhoto($id);
            $fileName = Utils::moveUploadedFileWithNewName('recipe', $newPhotoPath);
            Core::getInstance()->db->update(self::$tableName, [
                'photo' => $fileName
            ], [
                'id' => $id
            ]);
        }
    }

    public static function deleteRecipePhoto($id)
    {
        $recipe = self::getRecipeById($id);
        $photoPath = 'files/recipe/' . $recipe['photo'];
        if (is_file($photoPath))
            unlink($photoPath);
    }

    public static function getRecipes()
    {
        $rows = Core::getInstance()->db->select(self::$tableName);
        return $rows;
    }

    public static function getRecipeById($id)
    {
        $row = Core::getInstance()->db->select(self::$tableName, '*', [
            'id' => $id
        ]);
        if (!empty($row))
            return $row[0];
        return null;
    }

    public static function getRecipesInCategory($category_id)
    {
        $rows = Core::getInstance()->db->select(self::$tableName, '*', [
            'category_id' => $category_id
        ]);
        return $rows;
    }

    public static function isRecipeExists($recipe)
    {
        $fieldsList = ['name', 'description', 'time', 'servings', 'calories', 'instructions'];
        $recipe = Utils::filterArray($recipe, $fieldsList);
        $row = Core::getInstance()->db->select(self::$tableName, '*', $recipe);
        return !empty($row);
    }

    public static function getRecipeId($row)
    {
        $fieldsList = ['name', 'description', 'time', 'servings', 'calories', 'instructions'];
        $row = Utils::filterArray($row, $fieldsList);
        $id = Core::getInstance()->db->select(self::$tableName, 'id', $row);
        if (!empty($id))
            return intval($id[0]['id']);
        return null;
    }

    public static function getRecipesNameMatch($likePart)
    {
        $rows = Core::getInstance()->db->selectLike(self::$tableName, '*', 'name', $likePart);
        return $rows;
    }

    public static function getRandomRecipes($amount)
    {
        $rows = Core::getInstance()->db->select(self::$tableName);
        shuffle($rows);
        $rows = array_slice($rows, 0, $amount, true);
        return $rows;
    }
}