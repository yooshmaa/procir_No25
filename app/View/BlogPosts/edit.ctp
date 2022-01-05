<h1>編集画面</h1>
<?php
echo $this->Form->create('BlogPost');
echo $this->Form->input('title');
echo $this->Form->input('body', array('rows' => '3'));
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->end('編集');
echo $this->Html->link('投稿一覧', array('controller' => '/'));
?>



