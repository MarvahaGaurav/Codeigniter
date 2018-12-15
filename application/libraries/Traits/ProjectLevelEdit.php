<?php 

trait ProjectLevelEdit
{
    /**
     * Undocumented function
     *
     * @param int $initialLevels
     * @param int $targetLevels
     * @param int $projectId
     * @return void
     */
    private function editLevel($initialLevels, $targetLevels, $projectId, $userType)
    {
        if ($initialLevels < $targetLevels) {
            $levels = range($initialLevels + 1, $targetLevels);
            $levelData = array_map(function ($level) {
                $levelData['level'] = $level;
                $levelData['status'] = 0;
                return $levelData;
            }, $levels);

            $this->load->model(['ProjectLevel']);

            $this->ProjectLevel->addProjectLevels($levelData, $projectId);
        } else if ($targetLevels < $initialLevels) {
            $levelsToDelete = range($targetLevels + 1, $initialLevels);
            $this->UtilModel->deleteData('project_levels', [
                'where_in' => ['level' => $levelsToDelete, 'project_id' => $projectId]
            ]);

            $projectRoomData = $this->UtilModel->selectQuery('id', 'project_rooms', [
                'where_in' => ['level' => $levelsToDelete, 'project_id' => $projectId]
            ]);

            if (!empty($projectRoomData)) {
                $projectRoomIds = array_column($projectRoomData, 'id');
                $this->UtilModel->deleteData('project_rooms', [
                    'where_in' => ['level' => $levelsToDelete, 'project_id' => $projectId]
                ]);

                if ((int)$userType === INSTALLER) {
                    $this->UtilModel->deleteData('project_room_quotations', [
                        'where_in' => ['project_room_id' => $projectRoomIds]
                    ]);
                }
            }

            
        }
    }
}
