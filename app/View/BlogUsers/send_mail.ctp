<h1>メールアドレス入力</h1>
<?php
echo $this->Form->create('BlogUser');
echo '現在登録されているメールアドレスを入力して下さい';
echo $this->Form->input('email');
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->end('送信');
echo $this->Html->link('投稿一覧', array('controller' => '/'));
?>
