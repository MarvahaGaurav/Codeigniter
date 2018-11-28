<?php

trait TechnicianChargesCheck
{
    private function technicianChargeCheck($projectId)
    {
        $requestData = $this->UtilModel->selectQuery('id', 'project_technician_charges', [
            'where' => ['project_id' => $projectId], 'single_row' => true
        ]);

        return $requestData;
    }

    /**
     * Check whether technician has added final price for a given project
     *
     * @param int $projectId
     * @return boolean
     */
    private function hasTechnicianAddedFinalPrice($projectId) 
    {
        $requestData = $this->technicianChargeCheck($projectId);

        if (empty($requestData)) {
            return false;
        }

        return true;
    }

    /**
     * Handles project technician charges check for web and api
     *
     * @param integer $projectId
     * @param string $for
     * @return void
     */
    private function handleTechnicianChargesCheck($projectId, $for)
    {
        $requestData = $this->technicianChargeCheck($projectId);

        if (!empty($requestData)) {
            if ($for === 'web') {
                show404($this->lang->line('project_marked_completed'), base_url('home/projects'));
            } elseif ($for === 'api') {
                $this->response([
                    'code' => HTTP_BAD_REQUEST,
                    'msg' => $this->lang->line('project_marked_completed')
                ]);
            } elseif ($for === 'xhr') {
                json_dump([
                    'success' => false,
                    'error' => $this->lang->line('forbidden_action')
                ]);
            }
        }
    }
}
