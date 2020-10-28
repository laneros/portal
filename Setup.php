<?php

namespace Laneros\Portal;

use XF\Db\Schema\Alter;
use XF\Db\Schema\Create;

class Setup extends \XF\AddOn\AbstractSetup
{
	use \XF\AddOn\StepRunnerInstallTrait;
	use \XF\AddOn\StepRunnerUpgradeTrait;
	use \XF\AddOn\StepRunnerUninstallTrait;

	public function installStep1()
	{
		$this->schemaManager()->alterTable('xf_forum', function(Alter $table)
		{
			$table->addColumn('laneros_portal_auto_feature', 'tinyint')->setDefault(0);
		});
	}

	public function installStep2()
	{
		$this->schemaManager()->alterTable('xf_thread', function(Alter $table)
		{
			$table->addColumn('laneros_portal_featured', 'tinyint')->setDefault(0);
		});
	}

	public function installStep3()
	{
		$this->schemaManager()->createTable('xf_laneros_portal_featured_thread', function(Create $table)
		{
			$table->addColumn('thread_id', 'int');
			$table->addColumn('featured_date', 'int');
			$table->addColumn('featured_title', 'text');
			$table->addColumn('snippet', 'text');
			$table->addColumn('sticky', 'tinyint')->setDefault(0);
			$table->addPrimaryKey('thread_id');
			$table->addKey(['featured_date', 'sticky'], 'featured_date_sticky');
			$table->addKey('sticky');
		});
	}
	
	public function installStep4()
	{
		$this->createWidget('laneros_portal_view_members_online', 'members_online', [
			'positions' => ['laneros_portal_view_sidebar' => 10]
		]);

		$this->createWidget('laneros_portal_view_new_posts', 'new_posts', [
			'positions' => ['laneros_portal_view_sidebar' => 20]
		]);

		$this->createWidget('laneros_portal_view_new_profile_posts', 'new_profile_posts', [
			'positions' => ['laneros_portal_view_sidebar' => 30]
		]);

		$this->createWidget('laneros_portal_view_forum_statistics', 'forum_statistics', [
			'positions' => ['laneros_portal_view_sidebar' => 40]
		]);

		$this->createWidget('laneros_portal_view_share_page', 'share_page', [
			'positions' => ['laneros_portal_view_sidebar' => 50]
		]);
	}

	public function uninstallStep1()
	{
		$this->schemaManager()->alterTable('xf_forum', function(Alter $table)
		{
			$table->dropColumns('laneros_portal_auto_feature');
		});
	}

	public function uninstallStep2()
	{
		$this->schemaManager()->alterTable('xf_thread', function(Alter $table)
		{
			$table->dropColumns('laneros_portal_featured');
		});
	}

	public function uninstallStep3()
	{
		$this->schemaManager()->dropTable('xf_laneros_portal_featured_thread');
	}
}