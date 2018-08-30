<?php
?>
<div class='template-header'>
    <h2 class='template-heading'><?php echo elgg_echo('register:confirmationHeading', array($_GET['name']));?></h2>
</div>

<div class="row">
    <div class="col-md-8">
        <p class='body-content'><?php echo elgg_echo('register:confirmationBody', array($_GET['email']));?></p>
    </div>
</div>