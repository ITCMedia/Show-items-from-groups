<?
// Добавляется в код настроек типовой динамической страницы магазина перед:
// $Shop_Controller_Show = new Shop_Controller_Show($oShop);

// Код:

class My_Shop_Controller_Show extends Shop_Controller_Show
{
   protected function _groupCondition()
   {
      $oShop = $this->getEntity();
   
      if ($this->group)
      {
         // если ID группы не 0, т.е. не корневая группа
         // получаем подгруппы
         $aSubGroupsID = $this->fillShopGroup($oShop->id, $this->group); // добавляем текущую группу в массив
         $aSubGroupsID[] = $this->group;

         $this->shopItems()
            ->queryBuilder()
            ->where('shop_items.shop_group_id', 'IN', $aSubGroupsID); // получаем все товары из подгрупп
      }
      else
      {
         $this->shopItems()
			->queryBuilder()
			->where('shop_items.shop_group_id', 'NOT IN', Core_QueryBuilder::select('id')->from('shop_groups')->where('shop_id', '=', $oShop->id)->where('active', '=', 0));
      }

      return $this;
   }

   protected $_aGroupTree = array();

   public function fillShopGroup($iShopId, $iShopGroupParentId = 0, $iLevel = 0)
   {
      $iShopId = intval($iShopId);
      $iShopGroupParentId = intval($iShopGroupParentId);
      $iLevel = intval($iLevel);

      if ($iLevel == 0)
      {
         $aTmp = Core_QueryBuilder::select('id', 'parent_id')
            ->from('shop_groups')
            ->where('shop_id', '=', $iShopId)
            ->where('deleted', '=', 0)
            ->execute()->asAssoc()->result();

         foreach ($aTmp as $aGroup)
         {
            $this->_aGroupTree[$aGroup['parent_id']][] = $aGroup;
         }
      }

      $aReturn = array();

      if (isset($this->_aGroupTree[$iShopGroupParentId]))
      {
         foreach ($this->_aGroupTree[$iShopGroupParentId] as $childrenGroup)
         {
            $aReturn[] = $childrenGroup['id'];
            $aReturn = array_merge($aReturn, $this->fillShopGroup($iShopId, $childrenGroup['id'], $iLevel + 1));
         }
      }

      $iLevel == 0 && $this->_aGroupTree = array();

      return $aReturn;
   }
}

// Сама строка 
$Shop_Controller_Show = new Shop_Controller_Show($oShop); 
// меняется на 
$Shop_Controller_Show = new My_Shop_Controller_Show($oShop);


/************************************  Для ИС  ************************************/


class My_Informationsystem_Controller_Show extends Informationsystem_Controller_Show
{
   protected function _groupCondition()
   {
      $oInformationsystem = $this->getEntity();
   
      if ($this->group)
      {
         // если ID группы не 0, т.е. не корневая группа
         // получаем подгруппы
         $aSubGroupsID = $this->fillInformationsystemGroup($oInformationsystem->id, $this->group); // добавляем текущую группу в массив
         $aSubGroupsID[] = $this->group;

         $this->informationsystemItems()
            ->queryBuilder()
            ->where('informationsystem_items.informationsystem_group_id', 'IN', $aSubGroupsID); // получаем все товары из подгрупп
      }
      else
      {
         $this->informationsystemItems()
            ->queryBuilder()
            ->where('informationsystem_items.informationsystem_group_id', 'NOT IN', Core_QueryBuilder::select('id')->from('informationsystem_groups')->where('informationsystem_id', '=', $oInformationsystem->id)->where('active', '=', 0));
      }

      return $this;
   }

   protected $_aGroupTree = array();

   public function fillInformationsystemGroup($iInformationsystemId, $iInformationsystemGroupParentId = 0, $iLevel = 0)
   {
      $iInformationsystemId = intval($iInformationsystemId);
      $iInformationsystemGroupParentId = intval($iInformationsystemGroupParentId);
      $iLevel = intval($iLevel);

      if ($iLevel == 0)
      {
         $aTmp = Core_QueryBuilder::select('id', 'parent_id')
            ->from('informationsystem_groups')
            ->where('informationsystem_id', '=', $iInformationsystemId)
            ->where('deleted', '=', 0)
            ->execute()->asAssoc()->result();

         foreach ($aTmp as $aGroup)
         {
            $this->_aGroupTree[$aGroup['parent_id']][] = $aGroup;
         }
      }

      $aReturn = array();

      if (isset($this->_aGroupTree[$iInformationsystemGroupParentId]))
      {
         foreach ($this->_aGroupTree[$iInformationsystemGroupParentId] as $childrenGroup)
         {
            $aReturn[] = $childrenGroup['id'];
            $aReturn = array_merge($aReturn, $this->fillInformationsystemGroup($iInformationsystemId, $childrenGroup['id'], $iLevel + 1));
         }
      }

      $iLevel == 0 && $this->_aGroupTree = array();

      return $aReturn;
   }
}

$Informationsystem_Controller_Show = new My_Informationsystem_Controller_Show($oInformationsystem);