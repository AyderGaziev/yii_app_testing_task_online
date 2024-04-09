<?php

namespace app\models;

use yii\base\Model;
use yii\db\ActiveRecord;

class File extends ActiveRecord
{

    public function rules()
    {
        return [
            [['fileName', 'dateTime', 'path'], 'safe'],
            [['fileName', 'dateTime', 'path'], 'required'],
            [['fileName', 'path'], 'string'],
            [['dateTime'], 'integer'],
        ];
    }
    public static function tableName()
    {
        return '{{%file}}';
    }

}