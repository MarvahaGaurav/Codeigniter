<?php

/**
 * Used to check whether installer has added prices where neccessary 
 */
trait InstallerPriceCheck
{
    private function projectCheckPrice($projectId)
    {
        $this->load->model('ProjectRooms');

        $results = $this->ProjectRooms->projectPriceCheck($projectId);

        $data = array_filter($results, function ($result) {
            return empty($result['project_room_quotation']) || is_null($result['project_room_quotation']);
        });

        if (empty($results)) {
            return false;
        }
        
        if (!empty($data)) {
            return false;
        }

        return true;
    }
}

