<?php defined('ZVELE_CMS') or die(); ?>
<?php
// Pole: title, items[] = {q, a}
$sectionClass = $section_class ?? '';
?>

<section class="section <?= e($sectionClass) ?>">
    <div class="container">
        <?php if (!empty($title)): ?>
        <div class="section-header"><h2><?= e($title) ?></h2></div>
        <?php endif; ?>
        <div class="faq-list">
            <?php foreach ($items ?? [] as $item): ?>
            <details style="margin-bottom:1rem;padding:1.25rem 1.5rem;background:var(--surface,#f7f8fc);border-radius:8px;border:1px solid var(--border,#e8eaf0)">
                <summary style="cursor:pointer;font-weight:600;color:var(--navy,#4e5699);list-style:none;display:flex;justify-content:space-between;align-items:center">
                    <?= e($item['q'] ?? '') ?>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-left:.5rem"><path d="m6 9 6 6 6-6"/></svg>
                </summary>
                <p style="margin-top:.75rem;color:var(--text-muted,#6b7280)"><?= e($item['a'] ?? '') ?></p>
            </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>
