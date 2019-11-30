<?php declare(strict_types=1);


namespace Database\Migration;

use Swoft\Db\Schema\Blueprint;
use Swoft\Devtool\Annotation\Mapping\Migration;
use Swoft\Devtool\Migration\Migration as BaseMigration;

/**
 * Class BaiduYun
 *
 * @since 2.0
 *
 * @Migration(time=20191130183822)
 */
class BaiduYun extends BaseMigration
{
    /**
     * @return void
     */
    public function up(): void
    {
        //
        $this->schema->createIfNotExists('baiduyun', function (Blueprint $blueprint) {
            $blueprint->comment('百度云资源表');

            $blueprint->increments('id')->comment('primary');
            $blueprint->integer('cate_id')->default(0)->comment('分类');
            $blueprint->string('title')->default('')->comment('标题');
            $blueprint->string('desc')->default('')->comment('描述');
            $blueprint->string('url')->default('')->comment('地址');
            $blueprint->string('code', 10)->default('')->comment('提取码');
            $blueprint->integer('number')->default(0)->comment('下载次数');
            $blueprint->tinyInteger('status')->default(1)->comment('状态，0 审核中，1 正常，2 禁用 ，9删除');
            $blueprint->timestamps();
            $blueprint->index(['created_at']);

            $blueprint->engine  = 'Innodb';
            $blueprint->charset = 'utf8mb4';
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        $this->schema->dropIfExists('baiduyun');
    }
}
