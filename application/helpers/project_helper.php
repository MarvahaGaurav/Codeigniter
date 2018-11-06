<?php

if (!function_exists('level_activity_status_handler')) {
    /**
     * Adds the active key to all the levels listing based on the completed status
     * of the room sequence
     *
     * @param array $projectLevels
     * @return array
     */
    function level_activity_status_handler($projectLevels)
    {
        $projectLevelStatus = array_column($projectLevels, 'status');
            $indexOfLastCompletedLevel = array_search(1, array_reverse($projectLevelStatus));
        if (!is_bool($indexOfLastCompletedLevel)) {
            $indexOfLastCompletedLevel = count($projectLevels) - $indexOfLastCompletedLevel;
        }

        if ((count($projectLevels) === 1) ||
                (count($projectLevels) > 1 &&
                (bool)$indexOfLastCompletedLevel &&
                count($projectLevels) === (int)$indexOfLastCompletedLevel)
            ) {
            $projectLevels = array_map(function ($levels) {
                $levels['active'] = true;
                return $levels;
            }, $projectLevels);
        } elseif (count($projectLevels) > 1 && !(bool)$indexOfLastCompletedLevel) {
            foreach ($projectLevels as $key => $levelData) {
                if ((int)$key === 0) {
                    $projectLevels[$key]['active'] = true;
                } else {
                    $projectLevels[$key]['active'] = false;
                }
            }
        } elseif (count($projectLevels) > 1 &&
              (bool)$indexOfLastCompletedLevel &&
              count($projectLevels) > (int)$indexOfLastCompletedLevel
            ) {
            foreach ($projectLevels as $key => $levelData) {
                if ((int)$key <= $indexOfLastCompletedLevel) {
                    $projectLevels[$key]['active'] = true;
                } else {
                    $projectLevels[$key]['active'] = false;
                }
            }
        }

        return $projectLevels;
    }
}
