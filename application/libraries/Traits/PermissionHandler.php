<?php

trait PermissionHandler
{

    private function comparePermissions($oldPermission, $newPermission)
    {
        $oldP = [
            "quote_view" => $oldPermission['quote_view'],
            "quote_add" => $oldPermission['quote_add'],
            "quote_edit" => $oldPermission['quote_edit'],
            "quote_delete" => $oldPermission['quote_delete'],
            "insp_view" => $oldPermission['insp_view'],
            "insp_delete" => $oldPermission['insp_delete'],
            "insp_add" => $oldPermission['insp_add'],
            "insp_edit" => $oldPermission['insp_edit'],
            "project_view" => $oldPermission['project_view'],
            "project_add" => $oldPermission['project_add'],
            "project_edit" => $oldPermission['project_edit'],
            "project_delete" => $oldPermission['project_delete']
        ];

        $newP = [
            'quote_view' => $newPermission['quote_view'],
            'quote_add' => $newPermission['quote_add'],
            'quote_edit' => $newPermission['quote_edit'],
            'quote_delete' => $newPermission['quote_delete'],
            'insp_view' => $newPermission['insp_view'],
            'insp_delete' => $newPermission['insp_delete'],
            'insp_add' => $newPermission['insp_add'],
            'insp_edit' => $newPermission['insp_edit'],
            'project_view' => $newPermission['project_view'],
            'project_add' => $newPermission['project_add'],
            'project_edit' => $newPermission['project_edit'],
            'project_delete' => $newPermission['project_delete']
        ];

        $differenceArray = [];
        foreach ($newP as $key => $value) {
            if (((int)$value !== (int)$oldP[$key]) && (int)$value === 1) {
                $differenceArray[] = $key;
            }
        }
        $projectFlag = false;
        $inspirationFlag = false;
        $quotationFlag = false;
        $message = [];

        $message[] = $this->permissionMessageHandler($differenceArray, 'project');
        $message[] = $this->permissionMessageHandler($differenceArray, 'quote');
        $message[] = $this->permissionMessageHandler($differenceArray, 'insp');

        $message = array_filter($message);
        $message = implode(", ", $message);

        return $message;
    }

    private function permissionMessageHandler($differenceArray, $module)
    {
        $message = '';
        $moduleFlag = false;
        $permissionString = [];

        foreach ($differenceArray as $value) {
            if (preg_match("/^{$module}.*$/", $value) && preg_match('/^.+_view$/', $value)) {
                $permissionString[] = $this->lang->line('view_text');
            }
            if (preg_match("/^{$module}.*$/", $value) && preg_match('/^.+_add$/', $value)) {
                $permissionString[] = $this->lang->line('add_text');
            }
            if (preg_match("/^{$module}.*$/", $value) && preg_match('/^.+_edit$/', $value)) {
                $permissionString[] = $this->lang->line('edit_text');
            }
            if (preg_match("/^{$module}.*$/", $value) && preg_match('/^.+_delete$/', $value)) {
                $permissionString[] = $this->lang->line('delete_text');
            }
        }

        if (empty($permissionString)) {
            return '';
        }

        return $this->lang->line($module . '_text') . ": " . implode(", ", $permissionString) . "";
    }

}