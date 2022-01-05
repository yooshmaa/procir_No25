<?php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class BlogUser extends AppModel {
	public $hasMany = array(
		'BlogPost' => array(
			'className' => 'BlogPost',
			'foreignKey' => 'blog_user_id'
		)
	);

	public $validate = array(
		'username' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'ユーザー名は必須です。'
			)
		),
		'email'=> array(
			'required' => array(
				'rule' => '/\A([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}\z/i',
				'message' => 'メールアドレスは正しい形式で入力してください。'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'このメールアドレスは既に使用されています。'
			)
		),
		'password' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'パスワードは必須です。'
			)
		),
		'image' => array(
			'rule1' => array(
				'rule' => array('extension', array('jpg', 'jpeg', 'png', 'gif')),
				'message' => '画像ファイルはgif, jpg, png形式を選択してください。',
				'allowEmpty' => TRUE
			),
			'rule2' => array(
				'rule' => array('fileSize', '<=', '100000'),
				'message' => '画像は100KB以下にしてください。'
			)
		)
	);

	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['password'])) {
			$passwordHasher = new BlowfishPasswordHasher();
			$this->data[$this->alias]['password'] = $passwordHasher->hash($this->data[$this->alias]['password']);
		}
		return TRUE;
	}
}
