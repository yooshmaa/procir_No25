<h1>投稿一覧</h1>
<?php echo $this->Html->link('新規投稿', array('controller' => 'blog_posts', 'action' => 'add')); ?>
<?php if ($username): ?>
<?php echo ' ' . $this->Html->link('ログアウト', array('controller' => 'blog_users', 'action' => 'logout')); ?>
<?php echo ' ' . $username . 'さんでログイン中です'; ?>
<?php else: ?>
<?php echo ' ' . $this->Html->link('新規会員登録', array('controller' => 'blog_users', 'action' => 'add')); ?>
<?php echo ' ' . $this->Html->link('ログイン', array('controller' => 'blog_users', 'action' => 'login')); ?>
<?php endif; ?>
<table>
<tr>
<th>id</th>
<th>投稿者</th>
<th>タイトル</th>
<th>本文</th>
<th>編集・削除</th>
<th>投稿日時</th>
</tr>
<?php foreach ($blog_posts as $blog_post): ?>
<tr>
<td>
<?php echo $blog_post['BlogPost']['id']; ?>
</td>
<td>
<?php echo $this->Html->link($blog_post['BlogUser']['username'], array('controller' => 'blog_users', 'action' => 'view', $blog_post['BlogUser']['id'])); ?>
</td>
<td>
<?php echo $this->Html->link($blog_post['BlogPost']['title'], array('controller' => 'blog_posts', 'action' => 'view', $blog_post['BlogPost']['id'])); ?>
</td>
<td>
<?php echo $blog_post['BlogPost']['body']; ?>
</td>
<td>
<?php if ($blog_post['BlogPost']['blog_user_id'] == $blog_user_id): ?>
<?php echo $this->Html->link('編集', array('controller' => 'blog_posts', 'action' => 'edit', $blog_post['BlogPost']['id'])); ?>
<?php echo ' ' . $this->Form->postLink('削除', array('controller' => 'blog_posts', 'action' => 'delete', $blog_post['BlogPost']['id']), array('confirm' => '本当に削除しますか？')); ?>
<?php endif; ?>
</td>
<td>
<?php if (!empty($blog_post['BlogPost']['modified'])): ?>
<?php echo $blog_post['BlogPost']['modified']; ?>
<?php else: ?>
<?php echo $blog_post['BlogPost']['created']; ?>
<?php endif; ?>
</td>
<?php endforeach; ?>
</table>
