<h1>投稿内容</h1>
<table>
<tr>
<th>投稿者</th>
<th>タイトル</th>
<th>本文</th>
<th>投稿日時</th>
</tr>
<td>
<?php echo $blog_post['BlogUser']['username']; ?>
</td>
<td>
<?php echo $blog_post['BlogPost']['title']; ?>
</td>
<td>
<?php echo $blog_post['BlogPost']['body']; ?>
</td>
<td>
<?php if (!empty($blog_post['BlogPost']['modified'])): ?>
<?php echo $blog_post['BlogPost']['modified']; ?>
<?php else: ?>
<?php echo $blog_post['BlogPost']['created']; ?>
<?php endif; ?>
</td>
</table>
<?php echo $this->Html->link('投稿一覧', array('controller' => 'blog_posts', 'action' => 'index')); ?>
