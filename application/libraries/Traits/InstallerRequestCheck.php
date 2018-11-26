<?php

/**
 * Used to check if  particular installer has recieved a particular quote
 */
trait InstallerRequestCheck
{
    private function isRequestedTo($requestId, $userData)
    {
        if(!isset($userData['company_id'])) {
            return false;
        }

        $installerRequestData = $this->UtilModel->selectQuery('request_id, project_id, pri.company_id', 'project_request_installers as pri', [
            'join' => ['project_requests as pr' => 'pr.id=pri.request_id'],
            'where' => ['company_id' => $userData['company_id'], 'request_id' => $requestId], 'single_row' => true
        ]);

        if (empty($installerRequestData)) {
            return [];
        }

        return $installerRequestData;
    } 
}
