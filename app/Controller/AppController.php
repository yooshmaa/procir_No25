<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 */

App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		https://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller{
	public $helpers = array('Html', 'Form', 'Flash');
	public $components = array(
		'Session',
		'Flash',
		'Auth' => array(
			'authenticate' => array(
				'Form' => array(
					'passwordHasher' => 'Blowfish',
					'userModel' => 'BlogUser',
					'fields' => array(
						'username' => 'email',
						'password' => 'password'
					)
				)
			),
			'loginAction' => array('controller' => 'blog_users','action' => 'login'),
			'loginRedirect' => array('controller' => 'pages', 'action' => 'index'),
			'logoutRedirect' => array('controller' => 'blog_posts', 'action' => 'index'),
			'authorize' => array('Controller')
		)
	);

	public function beforeFilter() {
		$this->Auth->allow('index', 'view');
		$this->set('username', $this->Auth->user('username'));
		$this->set('blog_user_id', $this->Auth->user('id'));
	}
}
