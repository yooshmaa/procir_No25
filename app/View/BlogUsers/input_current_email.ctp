<h1>パスワードリセットリンク送信用メールアドレス入力画面</h1>
<?php
echo '現在のパスワードを入力してください';
echo $this->Form->create(null, array('url' => array('controller' => 'blog_users', 'action' => 'passwordReset')));
echo $this->Form->input('email');
echo '<br>';
echo $this->Form->end('送信');
echo '<br>';
?>
