<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

namespace philippfrenzel\comments\models;

use Yii;
use yii\components;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

use app\models\User;

/**
* This is the model class for table "tbl_comment".
*
    * @property integer $id
    * @property string $model_type
    * @property integer $model_id
    * @property string $comment
    * @property integer $state_id
    * @property string $create_time
    * @property integer $create_user_id
*/
class Comment extends ActiveRecord
{
	/**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

	/**
	 * [__toString description]
	 * @return string [description]
	 */
	public  function __toString()
	{
		return (string)$this->model_type;
	}
	const STATUS_DRAFT 		= 0;
	const STATUS_PUBLISHED 	= 1;
	const STATUS_ARCHIEVED 	= 2;
	
	/**
	 * [getStateOptions description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public static function getStateOptions($id = null)
	{
		$list = array(
			self::STATUS_DRAFT 			=> "Draft",
			self::STATUS_PUBLISHED 		=> "Published" ,
			self::STATUS_ARCHIEVED 		=> "Archieved",
		);
		
		if ($id == null )
			return $list;
		
		if ( is_numeric( $id )) 
			return $list [ $id % count($list) ];
		
		return $id;
	}

	/**
	 * [getStateBadge description]
	 * @return [type] [description]
	 */
	public function getStateBadge()
	{
		$list = array(
				self::STATUS_DRAFT 			=> "default",
				self::STATUS_PUBLISHED 		=> "success" ,
				self::STATUS_ARCHIEVED 		=> "danger",
		);
		//return \yii\helpers\Html::tag('span', self::getStateOptions( $this->state_id), ['class' => 'badge vd_bg-' . $list[$this->state_id]]);
		return \yii\helpers\Html::tag('span', self::getStateOptions( $this->state_id), ['class' => 'label label-' . $list[$this->state_id]]);
	}

	/**
	 * [beforeValidate description]
	 * @return [type] [description]
	 */
	public function beforeValidate()
	{
		if($this->isNewRecord)
		{
			if ( !isset( $this->create_user_id )) $this->create_user_id = Yii::$app->user->id;
		}
		return parent::beforeValidate();
	}

	/**
	* @inheritdoc
	*/
	public static function tableName()
	{
		return '{{%comment}}';
	}

	/**
	* @inheritdoc
	*/
	public function rules()
	{
		return [
            [['model_type', 'model_id'], 'required'],
            [['model_id', 'state_id', 'create_user_id','created_at','updated_at','deleted_at'], 'integer'],
            [['comment'], 'string'],
            [['model_type'], 'string', 'max' => 128]
        ];
	}

	/**
	* @inheritdoc
	*/
	public function attributeLabels()
	{
		return [
		    'id' => Yii::t('app', 'ID'),
		    'model_type' => Yii::t('app', 'Model Type'),
		    'model_id' => Yii::t('app', 'Model ID'),
		    'comment' => Yii::t('app', 'Comment'),
		    'state_id' => Yii::t('app', 'State ID'),
		    'created_at' => Yii::t('app', 'Create Time'),
		    'updated_at' => Yii::t('app', 'Update Time'),
		    'deleted_at' => Yii::t('app', 'Delete Time'),
		    'create_user_id' => Yii::t('app', 'Create User ID'),
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreateUser()
	{
	    return $this->hasOne(User::className(), ['id' => 'create_user_id']);
	}
	
}