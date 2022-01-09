<div class="blog_users form">
<?php echo $this->Flash->render('auth'); ?>
<?php echo $this->Form->create('BlogUser'); ?>
<fieldset>
<legend>
<?php echo __('メールアドレスとパスワードを入力してください'); ?>
</legend>
<?php
echo $this->Form->input('email');
echo $this->Form->input('password');
?>
</fieldset>
<?php echo $this->Form->end(__('ログイン')); ?>
<?php echo $this->Html->link('パスワードを忘れた方はこちら', array('action' => 'inputCurrentEmail')); ?>
</div>
