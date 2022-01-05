<div class="blog_users form">
<?php echo $this->Form->create('BlogUser'); ?>
<fieldset>
<legend>
<?php
echo $this->Form->input('username');
echo $this->Form->input('email');
echo $this->Form->input('password');
?>
</fieldset>
<?php
echo $this->Form->end(__('送信'));
?>
</div>
