<?php
/**
 * Created by PhpStorm.
 * User: alegz
 * Date: 12/18/15
 * Time: 11:15 AM
 */

namespace TRS\AsyncNotification\models;


use yii\base\Model;

/**
 * Class MailParameters
 * @package TRS\AsyncNotification\models
 *
 * Can be changed to ActiveRecord for storing parameters in database
 */
class MailParameters extends Model {
	public $from;
	public $subjectCategory = 'mail/subject';
	public $viewPath;

	public function rules () {
		return [
			[['from'], 'required'],
			[['from'], 'email', 'skipInEmpty' => false]
		];
	}
} 