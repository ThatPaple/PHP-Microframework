<?php
use App\Core\Lang;


echo '<footer style="margin-top: 4rem; text-align: center; font-size: 0.9rem; color: #666;"> &copy; '
    . date('Y') . ' '
    . \App\Core\Config::APP_NAME
    . ' | ' . Lang::get('footer.footer_text');
