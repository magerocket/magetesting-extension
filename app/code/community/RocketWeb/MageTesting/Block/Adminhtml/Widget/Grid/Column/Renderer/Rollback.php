<?php

class RocketWeb_MageTesting_Block_Adminhtml_Widget_Grid_Column_Renderer_Rollback extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action 
{
    public function render(Varien_Object $row)
    {
        $actions = $this->getColumn()->getActions();
        if ( empty($actions) || !is_array($actions) ) {
            return '&nbsp;';
        }

        $customBackupTypes = array(
            RocketWeb_MageTesting_Helper_Data::TYPE_DB_CLEAN,
            RocketWeb_MageTesting_Helper_Data::TYPE_SNAPSHOT_WITHOUT_MEDIA_WITHOUT_DB,
        );

        // don't allow to use rollback for custom MT backup types
        if (count($actions) == 1 && isset($actions[0]['caption']) && $actions[0]['caption'] == 'Rollback'
            && in_array($row->getType(), $customBackupTypes)) {
            return '&nbsp;';
        }

        if(sizeof($actions)==1 && !$this->getColumn()->getNoLink()) {
            foreach ($actions as $action) {
                if ( is_array($action) ) {
                    return $this->_toLinkHtml($action, $row);
                }
            }
        }

        $out = '<select class="action-select" onchange="varienGridAction.execute(this);">'
            . '<option value=""></option>';
        $i = 0;
        foreach ($actions as $action){
            $i++;
            if ( is_array($action) ) {
                $out .= $this->_toOptionHtml($action, $row);
            }
        }
        $out .= '</select>';
        return $out;
    }
}