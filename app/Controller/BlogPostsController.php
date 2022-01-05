<?php
class BlogPostsController extends AppController {

	public function index() {
		$this->set('blog_posts', $this->BlogPost->find('all'));
	}

	public function view($id = null) {
		if (!$id) {
			throw new NotFoundException(__('投稿が見つかりません。'));
		}

		$blog_post = $this->BlogPost->findById($id);
		if (!$blog_post) {
			throw new NotFoundException(__('投稿が見つかりません。'));
		}
		$this->set('blog_post', $blog_post);
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->request->data['BlogPost']['blog_user_id'] = $this->Auth->user('id');
			$this->BlogPost->Create();
			if ($this->BlogPost->save($this->request->data)) {
				$this->Flash->success(__('投稿は保存されました。'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Flash->error(__('投稿は失敗しました。'));
		}
	}

	public function edit($id = null) {
		if (!$id) {
			return $this->redirect(array('action' => 'index'));
		}

		$blog_post = $this->BlogPost->findById($id);
		if (!$blog_post) {
			return $this->redirect(array('action' => 'index'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->BlogPost->id = $id;
			if ($this->BlogPost->save($this->request->data)) {
				$this->Flash->success(__('投稿は編集されました。'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Flash->error(__('投稿の編集は失敗しました。'));
		}

		if (!$this->request->data) {
			$this->request->data = $blog_post;
		}
	}

	public function delete($id = null) {
		if ($this->request->is('get')) {
			return $this->redirect(array('action' => 'index'));
		}

		if (!$id) {
			throw new NotFoundException(__('投稿が見つかりません。'));
		}

		$blog_post = $this->BlogPost->findById($id);
		if (!$blog_post) {
			throw new NotFoundException(__('投稿が見つかりません。'));
		}
		if ($this->BlogPost->delete($id)) {
			$this->Flash->success(__('投稿id：%s は削除されました。', h($id)));
		} else {
			$this->Flash->error(__('投稿id：%sは削除できませんでした。', h($id)));
		}

		return $this->redirect(array('action' => 'index'));
	}

	public function isAuthorized($blog_user) {
		if ($this->action === 'add') {
			return TRUE;
		}

		$user_id = $this->Auth->user('id');
		if (in_array($this->action, array('edit', 'delete'))) {
			$blog_post_id = (int) $this->request->params['pass'][0];
			if ($this->BlogPost->isOwnedBy($blog_post_id, $user_id)) {
				return TRUE;
			}
		}
		return $this->redirect(array('action' => 'index'));
	}
}
