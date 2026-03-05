<?php defined('ZVELE_CMS') or die(); ?>

<form method="POST"
      action="<?= url('admin/member/save' . ($item['id'] ? '/' . $item['id'] : '')) ?>"
      enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Main fields -->
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Název *</label>
                    <input type="text" id="title" name="title" value="<?= e($item['title']) ?>" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Popis</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"><?= e($item['description']) ?></textarea>
                </div>
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Kategorie</label>
                    <input type="text" id="category" name="category" value="<?= e($item['category']) ?>"
                           placeholder="např. Základy, Pro rodiče, Terapie…"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
            </div>

            <!-- File upload -->
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-3">
                    <?= $item['id'] ? 'Nahradit soubor' : 'Soubor *' ?>
                </h2>
                <?php if ($item['id'] && !empty($item['original_name'])): ?>
                <p class="text-sm text-gray-500 mb-3">
                    Aktuální: <strong><?= e($item['original_name']) ?></strong>
                    <?php if ($item['file_size']): ?>
                    (<?= round($item['file_size'] / 1048576, 1) ?> MB)
                    <?php endif; ?>
                </p>
                <?php endif; ?>
                <input type="file" id="file" name="file"
                       <?= !$item['id'] ? 'required' : '' ?>
                       accept=".mp4,video/mp4,.pdf,application/pdf,.doc,.docx"
                       class="w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-xs text-gray-400 mt-1">Povolené formáty: MP4, PDF, DOC, DOCX. Max. 500 MB.</p>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-4">
            <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Typ obsahu</label>
                    <select id="type" name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="video"    <?= ($item['type'] ?? '') === 'video'    ? 'selected' : '' ?>>Video (MP4)</option>
                        <option value="pdf"      <?= ($item['type'] ?? '') === 'pdf'      ? 'selected' : '' ?>>PDF dokument</option>
                        <option value="document" <?= ($item['type'] ?? '') === 'document' ? 'selected' : '' ?>>Dokument (Word aj.)</option>
                    </select>
                </div>
                <div>
                    <label for="required_level" class="block text-sm font-medium text-gray-700 mb-1">Minimální úroveň přístupu</label>
                    <select id="required_level" name="required_level" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="1" <?= (int)($item['required_level'] ?? 1) === 1 ? 'selected' : '' ?>>1 – základní</option>
                        <option value="2" <?= (int)($item['required_level'] ?? 1) === 2 ? 'selected' : '' ?>>2 – pokročilý</option>
                        <option value="3" <?= (int)($item['required_level'] ?? 1) === 3 ? 'selected' : '' ?>>3 – plný přístup</option>
                    </select>
                </div>
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Pořadí</label>
                    <input type="number" id="sort_order" name="sort_order"
                           value="<?= (int)($item['sort_order'] ?? 0) ?>" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Stav</label>
                    <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="draft"     <?= ($item['status'] ?? 'draft') === 'draft'     ? 'selected' : '' ?>>Koncept</option>
                        <option value="published" <?= ($item['status'] ?? 'draft') === 'published' ? 'selected' : '' ?>>Publikováno</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors">
                    Uložit
                </button>
                <a href="<?= url('admin/member') ?>"
                   class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Zrušit
                </a>
            </div>
        </div>

    </div>
</form>
