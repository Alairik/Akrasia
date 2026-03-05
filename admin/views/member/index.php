<?php defined('ZVELE_CMS') or die(); ?>

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500"><?= count($items) ?> položek</p>
    <a href="<?= url('admin/member/create') ?>"
       class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors">
        + Přidat obsah
    </a>
</div>

<?php if (empty($items)): ?>
<div class="bg-white rounded-xl border border-gray-200 p-8 text-center text-gray-500">
    Zatím žádný obsah. <a href="<?= url('admin/member/create') ?>" class="text-blue-600 hover:underline">Přidejte první položku.</a>
</div>
<?php else: ?>
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Název</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600 hidden md:table-cell">Typ</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600 hidden md:table-cell">Úroveň</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600 hidden lg:table-cell">Kategorie</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Stav</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php foreach ($items as $item): ?>
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 font-medium text-gray-900">
                    <a href="<?= url('admin/member/edit/' . $item['id']) ?>" class="hover:text-blue-600">
                        <?= e($item['title']) ?>
                    </a>
                    <div class="text-xs text-gray-400 mt-0.5"><?= e($item['original_name']) ?></div>
                </td>
                <td class="px-4 py-3 hidden md:table-cell">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium
                        <?= $item['type'] === 'video' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' ?>">
                        <?= e($item['type']) ?>
                    </span>
                </td>
                <td class="px-4 py-3 hidden md:table-cell text-gray-600">
                    <?= (int)$item['required_level'] ?>
                </td>
                <td class="px-4 py-3 hidden lg:table-cell text-gray-500">
                    <?= e($item['category']) ?: '—' ?>
                </td>
                <td class="px-4 py-3">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                        <?= $item['status'] === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' ?>">
                        <?= $item['status'] === 'published' ? 'Publikováno' : 'Koncept' ?>
                    </span>
                </td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="<?= url('admin/member/edit/' . $item['id']) ?>"
                           class="text-gray-400 hover:text-blue-600 transition-colors" title="Upravit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                            </svg>
                        </a>
                        <form method="POST" action="<?= url('admin/member/delete/' . $item['id']) ?>"
                              onsubmit="return confirm('Opravdu smazat tuto položku?')" class="inline">
                            <?= csrf_field() ?>
                            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors" title="Smazat">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
