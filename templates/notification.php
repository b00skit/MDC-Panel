<?php
<?php
if ($g->getSettings('special-notification') && ($g->findCookie('specialNotification') != $g->getSettings('special-notification'))) {
?>
<div id="special-notification">
	<div class="container">
		<i class="mr-1 fas fa-fw fa-bullhorn"></i><?= $g->getSettings('special-notification-msg') ?>
	</div>
</div>
<?php
}
?>
