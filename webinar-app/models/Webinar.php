<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Webinar extends ActiveRecord
{
    public static function tableName()
    {
        return 'webinars';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }
}
