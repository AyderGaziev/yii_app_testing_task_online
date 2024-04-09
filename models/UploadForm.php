<?php

namespace app\models;

use Ramsey\Uuid\Uuid;
use Yii;
use yii\base\Model;
use yii\helpers\Inflector;
use yii\log\Logger;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $imageFiles;

    public function rules()
    {
        return [
            [['imageFiles'], 'image', 'skipOnEmpty' =>false,'extensions'=>'png, jpg', 'maxFiles' => 5]
        ];
    }

    public function upload() {
        if ($this->validate()) {


            foreach ($this->imageFiles as $file) {
                $fileRecord = new File();

                $fileName = (strtolower(Inflector::transliterate($file->baseName)));
                // check on transliterate and lower_case
                $fileRecord->fileName = $fileName;
                $now = new \DateTime('now');
                $fileRecord->dateTime = ($now->getTimestamp());
                $fileRecord->path = ('uploads/'. $fileRecord->fileName . '.' . $file->extension);

                // check on unique - uuid generator
                $existFile = File::findOne(['fileName' => $fileName]);
                if ($existFile) {
                    $fileRecord->fileName = (string)(Uuid::uuid4());
                    $fileRecord->path = ('uploads/'. $fileRecord->fileName . '.' . $file->extension);
                }
                Yii::error($fileRecord->fileName);
                // one directory (done)
                $file->saveAs('uploads/'. $fileRecord->fileName . '.' . $file->extension);

                // files to db
                if ($fileRecord->validate()) {
                    $fileRecord->save();
                } else {
                    Yii::error('NEW ERROR');
                    foreach ($fileRecord->errors as $error) {
                        Yii::error($error);
                    }
                }
            }

            return true;
        } else {
            return false;
        }
    }
}