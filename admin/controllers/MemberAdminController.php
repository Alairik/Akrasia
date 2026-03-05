<?php
defined('ZVELE_CMS') or die('Direct access forbidden.');

/**
 * MemberAdminController – manage protected member content (videos, PDFs).
 */
class MemberAdminController
{
    public function index(): void
    {
        Auth::requireAdmin();
        $db    = Database::getInstance();
        $items = $db->fetchAll(
            "SELECT mc.*, u.name as author_name
             FROM zvele_member_content mc
             LEFT JOIN zvele_users u ON mc.created_by = u.id
             ORDER BY mc.sort_order ASC, mc.created_at DESC"
        );

        $pageTitle = 'Členská sekce – obsah';
        $section   = 'member';

        ob_start();
        require ADMIN_PATH . '/views/member/index.php';
        $content = ob_get_clean();

        require ADMIN_PATH . '/views/layout.php';
    }

    public function create(): void
    {
        Auth::requireAdmin();

        $item = [
            'id'             => null,
            'title'          => '',
            'description'    => '',
            'type'           => 'video',
            'original_name'  => '',
            'required_level' => 1,
            'category'       => '',
            'sort_order'     => 0,
            'status'         => 'draft',
        ];

        $pageTitle = 'Přidat obsah';
        $section   = 'member';

        ob_start();
        require ADMIN_PATH . '/views/member/edit.php';
        $content = ob_get_clean();

        require ADMIN_PATH . '/views/layout.php';
    }

    public function edit(?string $id = null): void
    {
        Auth::requireAdmin();
        if (!$id) redirect(url('admin/member'));

        $db   = Database::getInstance();
        $item = $db->fetchOne("SELECT * FROM zvele_member_content WHERE id = ?", [(int) $id]);
        if (!$item) {
            flash('error', 'Obsah nenalezen.');
            redirect(url('admin/member'));
        }

        $pageTitle = 'Upravit: ' . $item['title'];
        $section   = 'member';

        ob_start();
        require ADMIN_PATH . '/views/member/edit.php';
        $content = ob_get_clean();

        require ADMIN_PATH . '/views/layout.php';
    }

    public function save(?string $id = null): void
    {
        Auth::requireAdmin();
        require_once CORE_PATH . '/MemberContent.php';

        $db            = Database::getInstance();
        $title         = Security::sanitize($_POST['title'] ?? '');
        $description   = Security::sanitize($_POST['description'] ?? '');
        $type          = in_array($_POST['type'] ?? '', ['video', 'pdf', 'document']) ? $_POST['type'] : 'video';
        $requiredLevel = max(1, min(3, (int) ($_POST['required_level'] ?? 1)));
        $category      = Security::sanitize($_POST['category'] ?? '');
        $sortOrder     = (int) ($_POST['sort_order'] ?? 0);
        $status        = in_array($_POST['status'] ?? '', ['draft', 'published']) ? $_POST['status'] : 'draft';

        if (!$title) {
            flash('error', 'Název je povinný.');
            redirect($id ? url('admin/member/edit/' . $id) : url('admin/member/create'));
        }

        // Handle file upload
        $filename     = null;
        $originalName = null;
        $fileSize     = null;

        if (!empty($_FILES['file']['name'])) {
            try {
                $filename     = MemberContent::upload($_FILES['file'], $type);
                $originalName = $_FILES['file']['name'];
                $fileSize     = (int) $_FILES['file']['size'];
            } catch (RuntimeException $e) {
                flash('error', 'Chyba při nahrávání: ' . $e->getMessage());
                redirect($id ? url('admin/member/edit/' . $id) : url('admin/member/create'));
            }
        }

        $data = [
            'title'          => $title,
            'description'    => $description,
            'type'           => $type,
            'required_level' => $requiredLevel,
            'category'       => $category,
            'sort_order'     => $sortOrder,
            'status'         => $status,
            'created_by'     => Auth::id(),
        ];

        if ($filename) {
            $data['filename']      = $filename;
            $data['original_name'] = $originalName;
            $data['file_size']     = $fileSize;
        }

        if ($id) {
            // On update, filename is optional (keep existing if not re-uploaded)
            if (!$filename) {
                unset($data['filename'], $data['original_name'], $data['file_size']);
            }
            $db->update('zvele_member_content', $data, 'id = ?', [(int) $id]);
            flash('success', 'Obsah byl upraven.');
            redirect(url('admin/member/edit/' . $id));
        } else {
            if (!$filename) {
                flash('error', 'Soubor je povinný při vytváření obsahu.');
                redirect(url('admin/member/create'));
            }
            $newId = $db->insert('zvele_member_content', $data);
            flash('success', 'Obsah byl přidán.');
            redirect(url('admin/member/edit/' . $newId));
        }
    }

    public function delete(?string $id = null): void
    {
        Auth::requireAdmin();
        if (!$id) redirect(url('admin/member'));

        $db   = Database::getInstance();
        $item = $db->fetchOne("SELECT * FROM zvele_member_content WHERE id = ?", [(int) $id]);
        if ($item) {
            // Delete physical file
            $subdir = $item['type'] === 'video' ? 'videos' : 'documents';
            $file   = PROTECTED_PATH . '/' . $subdir . '/' . $item['filename'];
            if (file_exists($file)) {
                @unlink($file);
            }
            $db->delete('zvele_member_content', 'id = ?', [(int) $id]);
            flash('success', 'Obsah byl smazán.');
        }
        redirect(url('admin/member'));
    }
}
