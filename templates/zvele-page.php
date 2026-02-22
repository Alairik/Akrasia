<?php
/**
 * Renders a ZveleCMS page (zvele_pages) inside the old Akrasia frontend.
 * $cmsPage is already set by index.php with keys: title, blocks (array), meta_description
 */

$blocks = $cmsPage['blocks'] ?? [];
?>

<main id="main-content">

<?php if (empty($blocks)): ?>
    <section class="section">
        <div class="container">
            <h1><?= h($pageTitle) ?></h1>
        </div>
    </section>
<?php else: ?>
    <?php foreach ($blocks as $block):
        $type = $block['type'] ?? '';
        $data = $block['data'] ?? [];
        switch ($type):

            case 'hero': ?>
                <section class="hero">
                    <div class="container">
                        <?php if (!empty($data['title'])): ?>
                            <h1><?= h($data['title']) ?></h1>
                        <?php endif; ?>
                        <?php if (!empty($data['subtitle'])): ?>
                            <p class="hero-subtitle"><?= h($data['subtitle']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($data['cta_text']) && !empty($data['cta_url'])): ?>
                            <div class="hero-btns">
                                <a href="<?= h($data['cta_url']) ?>" class="btn btn-primary"><?= h($data['cta_text']) ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            <?php break;

            case 'text': ?>
                <section class="section">
                    <div class="container">
                        <div class="prose">
                            <?= $data['content'] ?? '' ?>
                        </div>
                    </div>
                </section>
            <?php break;

            case 'cta': ?>
                <section class="section section--alt">
                    <div class="container" style="text-align:center">
                        <?php if (!empty($data['title'])): ?>
                            <h2><?= h($data['title']) ?></h2>
                        <?php endif; ?>
                        <?php if (!empty($data['text'])): ?>
                            <p><?= h($data['text']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($data['btn_text']) && !empty($data['btn_url'])): ?>
                            <a href="<?= h($data['btn_url']) ?>" class="btn btn-primary"><?= h($data['btn_text']) ?></a>
                        <?php endif; ?>
                    </div>
                </section>
            <?php break;

            case 'features': ?>
                <section class="section">
                    <div class="container">
                        <?php if (!empty($data['title'])): ?>
                            <div class="section-header"><h2><?= h($data['title']) ?></h2></div>
                        <?php endif; ?>
                        <div class="stats-grid">
                            <?php foreach ($data['items'] ?? [] as $item): ?>
                                <div class="stat-card">
                                    <div class="stat-number"><?= h($item['icon'] ?? '') ?></div>
                                    <div class="stat-label"><strong><?= h($item['title'] ?? '') ?></strong><br><?= h($item['text'] ?? '') ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>
            <?php break;

            case 'faq': ?>
                <section class="section">
                    <div class="container">
                        <?php if (!empty($data['title'])): ?>
                            <div class="section-header"><h2><?= h($data['title']) ?></h2></div>
                        <?php endif; ?>
                        <?php foreach ($data['items'] ?? [] as $item): ?>
                            <details style="margin-bottom:1rem;padding:1rem;background:var(--surface);border-radius:8px">
                                <summary style="cursor:pointer;font-weight:600"><?= h($item['q'] ?? '') ?></summary>
                                <p style="margin-top:.5rem"><?= h($item['a'] ?? '') ?></p>
                            </details>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php break;

            default:
                // Neznámý typ bloku — ignorovat
            break;
        endswitch;
    endforeach; ?>
<?php endif; ?>

</main>
