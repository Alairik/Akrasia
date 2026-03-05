<?php defined('ZVELE_CMS') or die(); ?>

<form method="POST" action="<?= url('admin/users/save' . ($user['id'] ? '/' . $user['id'] : '')) ?>">
    <?= csrf_field() ?>
    <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4 max-w-lg">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Jméno</label>
            <input type="text" id="name" name="name" value="<?= e($user['name']) ?>" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
            <input type="email" id="email" name="email" value="<?= e($user['email']) ?>" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                Heslo <?= $user['id'] ? '(ponechte prázdné pro zachování)' : '(min. 8 znaků)' ?>
            </label>
            <input type="password" id="password" name="password" <?= $user['id'] ? '' : 'required minlength="8"' ?>
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <div>
            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
            <select id="role" name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                    onchange="document.getElementById('member-level-row').style.display = this.value === 'member' ? '' : 'none'">
                <option value="editor" <?= ($user['role'] ?? 'editor') === 'editor' ? 'selected' : '' ?>>Editor</option>
                <option value="admin" <?= ($user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="member" <?= ($user['role'] ?? '') === 'member' ? 'selected' : '' ?>>Člen (member)</option>
            </select>
        </div>
        <div id="member-level-row" <?= ($user['role'] ?? '') !== 'member' ? 'style="display:none"' : '' ?>>
            <label for="member_level" class="block text-sm font-medium text-gray-700 mb-1">Úroveň přístupu</label>
            <select id="member_level" name="member_level" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="0" <?= (int)($user['member_level'] ?? 0) === 0 ? 'selected' : '' ?>>0 – žádný přístup</option>
                <option value="1" <?= (int)($user['member_level'] ?? 0) === 1 ? 'selected' : '' ?>>1 – základní</option>
                <option value="2" <?= (int)($user['member_level'] ?? 0) === 2 ? 'selected' : '' ?>>2 – pokročilý</option>
                <option value="3" <?= (int)($user['member_level'] ?? 0) === 3 ? 'selected' : '' ?>>3 – plný přístup</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-6 rounded-lg transition-colors">Uložit</button>
            <a href="<?= url('admin/users') ?>" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">Zrušit</a>
        </div>
    </div>
</form>
