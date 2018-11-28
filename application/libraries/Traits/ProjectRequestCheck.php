<?php

trait ProjectRequestCheck
{
    private function checkProjectRequest($projectId)
    {
        $requestData = $this->UtilModel->selectQuery('id', 'project_requests', [
            'where' => ['project_id' => $projectId], 'single_row' => true
        ]);

        return $requestData;
    }

    /**
     * Handles reqeust check for web and api
     *
     * @param integer $projectId
     * @param string $for
     * @return void
     */
    private function handleRequestCheck($projectId, $for)
    {
        $requestData = $this->checkProjectRequest($projectId);

        if (!empty($requestData)) {
            if ($for === 'web') {
                show404($this->lang->line('request_sent_for_this_project'), base_url('home/projects'));
            } elseif ($for === 'api') {
                $this->response([
                    'code' => HTTP_BAD_REQUEST,
                    'msg' => $this->lang->line('request_sent_for_this_project')
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
