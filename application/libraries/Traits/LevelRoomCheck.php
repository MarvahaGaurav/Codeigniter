<?php

trait LevelRoomCheck 
{
    private function isAllRoomsAdded($projectId)
    {
        $this->load->model(['ProjectLevel']);

        $results = $this->ProjectLevel->levelRoomCheck($projectId);

        $level = array_filter($results, function ($result) {
            return empty($result['project_room_id']) || is_null($result['project_room_id']);
        });
        
        if (!empty($level)) {
            return false;
        }

        return true;
    }
}
