<?php

namespace models;

use core\Core;
use core\Utils;

class Category
{
    protected static $tableName = 'category';

    public static function addCategory($name, $photoPath)
    {
        if (!empty($photoPath)) {
            $fileName = Utils::moveUploadedFileWithNewName('category', $photoPath);
            Core::getInstance()->db->insert(self::$tableName, [
                'name' => $name,
                'photo' => $fileName
            ]);
        } else {
            Core::getInstance()->db->insert(self::$tableName, [
                'name' => $name
            ]);
        }
    }

    public static function getCategoryById($id)
    {
        $rows = Core::getInstance()->db->select(self::$tableName, '*', [
            'id' => $id
        ]);
        if (!empty($rows))
            return $rows[0];
        return null;
    }

    public static function deleteCategory($id)
    {
        self::deleteCategoryPhoto($id);
        Core::getInstance()->db->delete(self::$tableName, [
            'id' => $id
        ]);
    }

    public static function updateCategory($id, $newName)
    {
        Core::getInstance()->db->update(self::$tableName, [
            'name' => $newName
        ], [
            'id' => $id
        ]);
    }

    public static function changePhoto($id, $newPhotoPath)
    {
        if (!empty($newPhotoPath)) {
            self::deleteCategoryPhoto($id);
            $fileName = Utils::moveUploadedFileWithNewName('category', $newPhotoPath);
            Core::getInstance()->db->update(self::$tableName, [
                'photo' => $fileName
            ], [
                'id' => $id
            ]);
        }
    }

    public static function deleteCategoryPhoto($id)
    {
        $category = self::getCategoryById($id);
        $photoPath = 'files/category/' . $category['photo'];
        if (is_file($photoPath))
            unlink($photoPath);
    }

    public static function getCategories()
    {
        $rows = Core::getInstance()->db->select(self::$tableName);
        return $rows;
    }

    public static function isCategoryExistsByName($name)
    {
        $category = Core::getInstance()->db->select(self::$tableName, '*', [
            'name' => $name
        ]);
        return !empty($category);
    }

    public static function isCategoryExists($id)
    {
        $category = Core::getInstance()->db->select(self::$tableName, '*', [
            'id' => $id
        ]);
        return !empty($category);
    }

}