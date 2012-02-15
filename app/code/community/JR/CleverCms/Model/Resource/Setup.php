<?php

class JR_CleverCms_Model_Resource_Setup extends Mage_Core_Model_Resource_Setup
{
    /**
     * Do not install module if Magento is not installed yet.
     * This prevents error when Mage_Cms data install.
     *
     * @see Mage_Core_Model_Resource_Setup::applyUpdates()
     */
    public function applyUpdates()
    {
        if (!Mage::isInstalled()) {
            $modules = Mage::getConfig()->getNode('modules')->children();
            foreach ($modules as $moduleName => $moduleNode) {
                if ($moduleName != 'JR_CleverCms') {
                    Mage::getConfig()->addAllowedModules($moduleName);
                }
            }
            Mage::getConfig()->reinit();

            return $this;
        }

        return parent::applyUpdates();
    }
}