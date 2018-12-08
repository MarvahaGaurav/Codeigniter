<?php

trait ProjectDelete 
{
    /**
     * Project ID
     *
     * @var int
     */
    private $projectId;

    private function canCustomerDeleteProject()
    {
        $quotesData = $this->UtilModel->selectQuery('pr.id', 'project_requests as pr', [
            'where' => ['project_id' => $this->projectId], 'single_row' => true
        ]);

        if (!empty($quoteData)) {
            return false;
        }

        return true;
    }

    /**
     * @todo handling not needed yet.
     *
     * @return boolean
     */
    private function canTechnicianDeleteProject()
    {

    }

    private function deleteProject()
    {
        $this->db->trans_begin();

        $this->UtilModel->deleteData('projects', [
            'where' => ['id' => $this->projectId]
        ]);
        
        $this->db->trans_commit();
    }

    private function fetchProduct() {
        $project = $this->UtilModel->selectQuery('id, user_id, company_id', 'projects', [
            'where' => ['id' => $this->projectId], 'single_row' => true
        ]);

        return $project;
    }
}
