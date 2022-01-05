<?php
class BlogPost extends AppModel {
	public $belongsTo = array(
		'BlogUser' => array(
			'className' => 'BlogUser',
			'foreignKey' => 'blog_user_id'
		)
	);

	public $validate = array(
		'title' => array(
			'rule' => 'notBlank'
		),
		'body' => array(
			'rule' => 'notBlank'
		)
	);

	public $actsAs = array( 'SoftDelete' );

	public function isOwnedBy($blog_post_id, $blog_user_id) {
		return $this->field('id', array('id' => $blog_post_id, 'BlogPost.blog_user_id' => $blog_user_id)) !== false;
	}
}
