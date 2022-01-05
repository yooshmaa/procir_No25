<h1>新規投稿</h1>
<?php
echo $this->Form->create('BlogPost');
echo $this->Form->input('title');
echo $this->Form->input('body', array('rows' => '3'));
echo $this->Form->end('投稿する');
echo $this->Html->link('投稿一覧', array('controller' => '/'));
?>
