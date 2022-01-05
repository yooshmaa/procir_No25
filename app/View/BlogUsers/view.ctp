<h1>ユーザー情報</h1>
<p><?php echo 'username : ' . h($blog_user['BlogUser']['username']); ?></p>
<?php if (!empty($blog_user['BlogUser']['image'])): ?>
<p><?php echo 'image :'; ?></p>
<?php echo $this->Html->image('../user_images_for_cakephp/' . $blog_user['BlogUser']['image'], array('height' => '150', 'width' => '200')); ?>
<?php else: ?>
<p><?php echo 'user image : 未登録'; ?></p>
<?php endif; ?>
<br>
<br>
<p><?php echo 'email : ' . h($blog_user['BlogUser']['email']); ?></p>
<?php if (!empty($blog_user['BlogUser']['message'])): ?>
<p><?php echo 'message : ' . h($blog_user['BlogUser']['message']); ?></p>
<?php else: ?>
<p><?php echo 'message : 未登録'; ?></p>
<?php endif; ?>
<?php if ($blog_user['BlogUser']['id'] == $blog_user_id): ?>
<?php echo ' ' . $this->Html->link('ユーザー情報編集', array('controller' => 'blog_users', 'action' => 'edit', $blog_user['BlogUser']['id'])); ?>
<br>
<?php endif; ?>
<?php echo $this->Html->link('投稿一覧', array('controller' => 'blog_posts', 'action' => 'index')); ?>
