<?php
namespace Magecomp\Pagenotfound\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
   
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
		$installer = $setup;
        $installer->startSetup();

        /**
         * Create table 'mc_pagenotfound'
         */
		$table = $installer->getConnection()
            ->newTable($installer->getTable('mc_pagenotfound'))
			->addColumn(
                'mc_pagenotfound_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true,'nullable' => false,'primary' => true,'unsigned' => true],
                'Entity ID'
			)
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Store Id'
            )
            ->addColumn(
                'url',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'URL'
            )
			->addColumn(
                'client_ip',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Client Ip'
            )
			 ->addColumn(
                'creation_date',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
               	[],
                'Creation Date'
			);
        $installer->getConnection()->createTable($table);
    }
}