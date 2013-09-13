<?php

class Pyz_Zed_Installer_Component_Model_Installer extends ProjectA_Zed_Installer_Component_Model_Installer implements
    ProjectA_Zed_Acl_Component_Dependency_Facade_Interface,
    ProjectA_Zed_Catalog_Component_Dependency_Facade_Interface,
    ProjectA_Zed_Cms_Component_Dependency_Facade_Interface,
    ProjectA_Zed_Misc_Component_Dependency_Facade_Interface,
    ProjectA_Zed_Price_Component_Dependency_Facade_Interface,
    ProjectA_Zed_Stock_Component_Dependency_Facade_Interface
{

    use ProjectA_Zed_Acl_Component_Dependency_Facade_Trait;
    use ProjectA_Zed_Catalog_Component_Dependency_Facade_Trait;
    use ProjectA_Zed_Cms_Component_Dependency_Facade_Trait;
    use ProjectA_Zed_Misc_Component_Dependency_Facade_Trait;
    use ProjectA_Zed_Price_Component_Dependency_Facade_Trait;
    use ProjectA_Zed_Stock_Component_Dependency_Facade_Trait;

    /**
     * @return array
     */
    protected function getInstaller()
    {
        return array(
            $this->facadeAcl->getInternalInstall(),
            $this->facadeCatalog->getInternalInstall(),
            $this->facadeCms->getInternalInstall(),
            $this->facadeMisc->getInternalInstall(),
            $this->facadePrice->getInternalInstall(),
            $this->facadeStock->getInternalInstall(),
        );
    }
}
