<?php 
http_response_code(404);

  $emojis = ["😶", "😐","😑","🙄","😫","😴","🤐","🤔","🤨","😂","😲","🙁","😖","😞","😟","🙃","😕","😩","😵","🤯","🤪","😳","😱","😰"];
  $randE = $emojis[array_rand($emojis)];
?>

<div class="error-page">
  <h1><?= _t('errors.404.title') ?></h1>
  <div class="hrerr404-emoji" aria-label="Confused face emoji" role="img"><?= $randE ?></div>
  <p><?= _t('errors.404.message_line1') ?></p>
  <p><?= _t('errors.404.message_line2') ?></p>
  <a href="/" class="btn-home"><?= _t('errors.404.home_button') ?></a>
</div>
