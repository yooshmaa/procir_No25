<?php
App::uses('AppController', 'Controller');

class BlogUsersController extends AppController {
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('add', 'logout', 'inputCurrentEmail', 'sendMail', 'passwordResetForm', 'passwordReset');
	}

	public function index() {
		$this->BlogUser->recursive = 0;

		$this->set('blog_users', $this->BlogUser->find('all'));
		return $this->redirect(array('controller' => 'blog_posts', 'action' => 'index'));
	}

	public function view($id = null) {
		$this->BlogUser->id = $id;
		if (!$this->BlogUser->exists()) {
			throw new NotFoundException(__('ユーザーが見つかりません。'));
		}
		$this->set('blog_user', $this->BlogUser->findById($id));
	}

	public function add() {
		if ($this->Auth->user('id')) {
			return $this->redirect(array('controller' => 'blog_posts', 'action' => 'index'));
		}

		if ($this->request->is('post')) {
			$this->BlogUser->create();
			if ($this->BlogUser->save($this->request->data)) {
				$this->Flash->success(__('新規登録完了'));
				return $this->redirect(array('controller' => 'blog_posts', 'action' => 'index'));
			}
			$this->Flash->error(__('新規登録できませんでした。再度お試しください。'));
		}
	}

	public function edit($id = null) {
		if (!$id) {
			return $this->redirect(array('action' => 'blog_posts', 'action' => 'index'));
		}
		$blog_user = $this->BlogUser->findById($id);
		if (!$blog_user) {
			throw new NotFoundException(__('ユーザーは存在しません。'));
			return $this->redirect(array('action' => 'blog_posts', 'action' => 'index'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$tmp_name = $this->request->data['BlogUser']['image']['tmp_name'];
			$message = $this->request->data['BlogUser']['message'];
			$this->BlogUser->set($this->request->data);
			if ($this->BlogUser->validates(array('fieldList' => array('image')))) {
				$image = uniqid(mt_rand(), true);
				switch (@exif_imagetype($tmp_name)) {
					case 1:
						$image .= '.gif';
						break;
					case 2:
						$image .= '.jpg';
						break;
					case 3:
						$image .= '.png';
						break;
					default:
						$this->Flash->error(__('jpeg, jpg, png, gifファイルを選択してください。'));
						return $this->redirect(array('action' => 'view', $id));
						break;
				}
				move_uploaded_file($tmp_name, '../webroot/user_images_for_cakephp/' . $image);
				if ($blog_user['BlogUser']['image']) {
					unlink('../webroot/user_images_for_cakephp/' . $blog_user['BlogUser']['image']);
				}
			} else {
				$type = $this->request->data['BlogUser']['image']['type'];
				$size = $this->request->data['BlogUser']['image']['size'];
				$image = $blog_user['BlogUser']['image'];

				if ($type != '' && $type != 'image/jpeg' && $type != 'image/jpg' && $type != 'image/png' && $type != 'image/gif') {
					$this->Flash->error(__('jpeg, jpg, png, gifファイルを選択してください。'));
				}

				if ($size > 100000) {
					$this->Flash->error(__('画像ファイルは100KB以下にしてください。'));
				}
			}

			$data = array(
				'BlogUser' => array(
					'id' => $id,
					'image' => $image,
					'message' => $message
				)
			);

			$update_columns = array('image', 'message');
			$this->BlogUser->save($data, false, $update_columns);
			return $this->redirect(array('action' => 'view', $id));
		} else {
			$this->request->data = $blog_user;
		}
		$this->set('blog_user', $this->BlogUser->findById($id));
	}

	public function imageDelete($id = null) {
		if ($this->request->is('get')) {
			return $this->redirect(array('action' => 'index'));
		}

		if (!$id) {
			throw new NotFoundException(__('ユーザーが見つかりません。'));
		}

		$blog_user = $this->BlogUser->findById($id);

		if (!$blog_user) {
			throw new NotFoundException(__('ユーザーが見つかりません。'));
		}

		$data = array(
			'BlogUser' => array(
				'id' => $id,
				'image' => NULL
			)
		);

		$update_columns = array('id', 'image');

		if ($this->BlogUser->save($data, false, $update_columns)) {
			$this->Flash->success(__('ユーザー画像を削除しました。'));
			if (file_exists('../webroot/user_images_for_cakephp/' . $blog_user['BlogUser']['image'])) {
				unlink('../webroot/user_images_for_cakephp/' . $blog_user['BlogUser']['image']);
			}
		} else {
			$this->Flash->error(__('ユーザー画像の削除に失敗しました。'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function login() {
		if ($this->Auth->user('id')) {
			return $this->redirect(array('controller' => 'blog_posts', 'action' => 'index'));
		}

		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$this->redirect($this->Auth->redirectUrl());
			} else {
				$this->Flash->error(__('ユーザー名もしくはパスワードが無効です。'));
			}
		}
	}

	public function inputCurrentEmail() {
		if ($this->Auth->user('id')) {
			return $this->redirect(array('controller' => 'blog_posts', 'action' => 'index'));
		}
	}

	public function sendMail() {
		if ($this->Auth->user('id')) {
			return $this->redirect(array('controller' => 'blog_posts', 'action' => 'index'));
		}

		$current_user_info = $this->BlogUser->find('first', array(
			'conditions' => array(
				'BlogUser.email' => $this->request->data['BlogUser']['email'] )
			)
		);


		if ($current_user_info) {
			$to = $current_user_info['BlogUser']['email'];
			echo $this->request->data['BlogUser']['email'] . 'にメールを送る処理';
			$url = 'https://procir-study.site/maki453/No25/blog_users/passwordResetForm?key=';
			$password_reset_key = md5(uniqid(rand(), true));
			$url .= $password_reset_key;
			$send_url_date = date('Y-m-d H:i:s');
			$main_message = 'パスワード再発行再発行URLは以下です。有効期限は30分です。' . "\r\n" . $url . "\r\n" . '30分を超えると無効になりますのでご注意ください。';

			$data = array(
				'BlogUser' => array(
					'id' => $current_user_info['BlogUser']['id'],
					'password_reset_key' => $password_reset_key,
					'send_url_date' => $send_url_date
				)
			);

			$update_columns = array('password_reset_key', 'send_url_date');
			$this->BlogUser->save($data, false, $update_columns);

			$Email = new CakeEmail('default');
			$Email->from(array('from@hoge.co.jp' => 'プロサー掲示板'))
				->to($to)
				->subject('パスワード再発行')
				->send($main_message);
			echo $message;
		} else {
			echo $message;
		}
	}

	public function passwordResetForm() {
		//ログインしていたらこちらに飛ばす
		if ($this->Auth->user('id')) {
			return $this->redirect(array('controller' => 'blog_posts', 'action' => 'index'));

		}
		//データベースからGETされたkeyを取得。取得できれば処理を進める。
		$password_reset_info = $this->BlogUser->find('first', array(
			'conditions' => array(
				'BlogUser.password_reset_key' => $this->request->query['key'])
			)
		);

		if ($password_reset_info['BlogUser']['password_reset_key'] == $this->request->query['key']) {
			$current_time = strtotime('now');
			$send_url_date = strtotime($password_reset_info['BlogUser']['send_url_date']);
			if ($current_time - $send_url_date <= 30 * 60) {
				$this->Flash->success(__('以下のフォームに新しいパスワードを入力してください。'));

			} else {
				$this->Flash->error(__('パスワード再発行用URLの有効期限が切れています'));
				return $this->redirect(array('action' => 'login'));
			}
		} else {
			$this->Flash->error(__('パスワード再発行用URLが正しくありません。'));
			return $this->redirect(array('action' => 'login'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$new_password = $this->request->data['BlogUser']['password'];
			$id = $password_reset_info['BlogUser']['id'];
			$data = array(
				'BlogUser' => array(
					'id' => $id,
					'password' => $new_password,
					'send_url_date' => NULL,
					'password_reset_key' => NULL
				)
			);

			$update_columns = array('id', 'password', 'password_reset_key', 'send_url_date');

			if ($this->BlogUser->save($data, false, $update_columns)) {
				$this->Flash->success(__('パスワードを再設定しました。'));
			} else {
				$this->Flash->error(__('パスワードの再設定に失敗しました。'));
			}
		}
	}
/*
	public function passwordReset() {
		if ($this->Auth->user('id')) {
			return $this->redirect(array('controller' => 'blog_posts', 'action' => 'index'));

		}

		$new_password = $this->request->data['BlogUser']['password'];

		debug($new_password);
		debug($this->request->data);
		exit();
	}
 */

	public function logout() {
		$this->redirect($this->Auth->logout());
	}

	public function isAuthorized($blog_user) {
		/*addは承認されていなくても、ログインされていなくてもアクセスできる
		if ($this->action === 'add') {
			return TRUE;
		}
		 */

		$user_id = $this->Auth->user('id');
		if (in_array($this->action, array('edit', 'imageDelete'))) {
			$blog_user_id = (int) $this->request->params['pass'][0];
			if ($user_id == $blog_user_id) {
				return TRUE;
			}
		}
		return $this->redirect(array('action' => 'index'));
	}
}

