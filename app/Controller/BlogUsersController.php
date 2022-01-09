<?php
App::uses('AppController', 'Controller');

class BlogUsersController extends AppController {
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('add', 'logout', 'inputCurrentEmail', 'passwordReset');
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

	public function passwordReset() {
		$current_user_info = $this->BlogUser->find('first', array(
			'conditions' => array(
				'BlogUser.email' => $this->request->data['BlogUser']['email'] )
			)
		);

		if ($current_user_info) {
			echo $this->request->data['BlogUser']['email'] . 'にメールを送る処理';
		} else {
			echo 'メールを送ったふり';
		}
	}

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

