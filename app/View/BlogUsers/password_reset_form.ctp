<?php
echo '新しいパスワードを入力してください';
/* ↓の記述では新パスワードを入力して、再度passwordResetFormに戻った時にkeyが定義されていないことになる */
/* echo $this->Form->create('BlogUser', array('url' => array('controller' => 'blog_users', 'action' => 'passwordResetForm')));*/
/* ↓の記述であれば、↑の問題は見られない */
echo $this->Form->create('BlogUser');
echo $this->Form->input('password');
echo '<br>';
echo $this->Form->end('更新');
echo '<br>';
?>
