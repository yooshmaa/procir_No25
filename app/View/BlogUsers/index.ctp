<h1>ユーザーリスト</h1>
<table>
<tr>
<th>id</th>
<th>username</th>
<th>email</th>
</tr>
<?php foreach ($blog_users as $blog_user): ?>
<tr>
<td>
<?php echo $this->Html->link($blog_user['BlogUser']['id'], array('controller' => 'blog_users', 'action' => 'view', $blog_user['BlogUser']['id'])); ?>
</td>
<td>
<?php echo $blog_user['BlogUser']['username']; ?>
</td>
<td>
<?php echo $blog_user['BlogUser']['email']; ?>
</td>
<?php endforeach; ?>
</table>
