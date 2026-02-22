<?php defined('ZVELE_CMS') or die(); ?>
<?php
// Pole: content (HTML), section_class (volitelné: 'section--alt', 'section--mint', ...)
$sectionClass = $section_class ?? '';
?>

<section class="section <?= e($sectionClass) ?>">
    <div class="container">
        <div class="page-content-body prose">
            <?= $content ?? '' ?>
        </div>
    </div>
</section>
