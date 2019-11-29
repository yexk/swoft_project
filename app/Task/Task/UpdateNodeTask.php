<?php declare(strict_types=1);
/**
 * my task update my_node git 
 */

namespace App\Task\Task;

use Swoft\Task\Annotation\Mapping\Task;
use Swoft\Task\Annotation\Mapping\TaskMapping;

/**
 * Class UpdateNodeTask
 *
 * @since 2.0
 *
 * @Task(name="UpdateNodeTask")
 */
class UpdateNodeTask
{
    /**
     * 更新 my_node 操作
     * @TaskMapping(name="build")
     * 
     * @param string $cmd 执行命令
     * 
     * @return void
     */
    public function updateBuild(string $cmd = ''): void
    {
        if (!empty($cmd)) {
            exec($cmd);    
        }else{
            exec('cd /www/my_notes && git pull && vuepress build .');
        }
    }

}
