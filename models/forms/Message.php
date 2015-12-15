<?php
/**
 * Created by PhpStorm.
 * User: alegz
 * Date: 12/5/15
 * Time: 4:33 PM
 */

namespace TRS\AsyncNotification\models\forms;


use yii\base\Model;

class Message extends Model
{
	public $recipients;
	public $template;
	public $attachments;

	public function rules()
	{
		return [
			[['recipients', 'template'], 'required'],
			[['recipients'], 'each', 'rule' => ['email', 'allowEmpty' => false], 'allowEmpty' => false],
			[['attachments'], 'each', 'rule' => ['file']]
		];
	}
} 