<h1>ユーザー情報編集</h1>
<?php
echo $this->Form->create('BlogUser', array('type'=>'file', 'enctype' => 'multipart/form-data'));
echo $this->Form->input('message', array('label' => '一言メッセージ'));
echo $this->Form->input('image', array('label' => false, 'type' => 'file'));
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->end('更新する');
if ($blog_user['BlogUser']['image']) {
echo '※↓ユーザ画像を削除する場合はこちら↓';
echo '<br>';
echo $this->Form->postLink('ユーザ画像を削除', array('controller' => 'blog_users', 'action' => 'imageDelete', $blog_user['BlogUser']['id']), array('confirm' => '本当に削除しますか？'));
echo '<br>';
echo '<br>';
};
echo $this->Html->link('投稿一覧', array('controller' => 'blog_posts', 'action' => 'index'));
?>
