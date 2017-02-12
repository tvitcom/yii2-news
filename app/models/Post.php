<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "post".
 *
 * @property string $id
 * @property string $author_id
 * @property string $title
 * @property string $tags
 * @property string $content
 * @property string $created_at
 * @property string $source_uri
 * @property string $picture_uri
 * @property integer $ratings
 *
 * @property Person $author
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['author_id', 'ratings'], 'integer'],
            [['title', 'content', 'created_at'], 'required'],
            [['content'], 'string'],
            [['created_at'], 'safe'],
            [['title', 'tags'], 'string', 'max' => 128],
            [['source_uri', 'picture_uri'], 'string', 'max' => 255],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'author_id' => Yii::t('app', 'Author ID'),
            'title' => Yii::t('app', 'Title'),
            'tags' => Yii::t('app', 'Tags'),
            'content' => Yii::t('app', 'Content'),
            'created_at' => Yii::t('app', 'Created At'),
            'source_uri' => Yii::t('app', 'Source Uri'),
            'picture_uri' => Yii::t('app', 'Picture Uri'),
            'ratings' => Yii::t('app', 'Ratings'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Person::className(), ['id' => 'author_id']);
    }
}
