<?php

namespace App\Models;

use PDO;
use PDOException;

class Post extends \Core\Model
{
    /**
     * Get all the posts as an associative array
     */
    public static function getAll()
    {
        $results = array();
        try
        {
            $db = self::getDB();
            $sql = 'SELECT id, title, content FROM posts ORDER BY created_at';
            $stmt = $db->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($results))
            {
                return $results;
            }
        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
        }

        return null;
    }
}
