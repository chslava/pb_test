<?php

namespace app\models\db;

use Yii;

class DBFile extends \yii\base\Object
{
    const DB_FILE_PATH = '/data/user.xml';

    public static function getUsers(){
        if (file_exists(Yii::getAlias('@app') . self::DB_FILE_PATH)) {
            $xmlObj = new \SimpleXMLElement(Yii::getAlias('@app') . self::DB_FILE_PATH, null, true);

            return $xmlObj->xpath('/members/member');
        }
        return false;
    }
}