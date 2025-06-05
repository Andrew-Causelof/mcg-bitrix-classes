<?php

namespace Seogravity\HLB;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Entity;

class HLBlockTable
{
    protected $hlblockId;
    protected $entityDataClass;

    public function __construct($hlblockId)
    {
        $this->hlblockId = $hlblockId;
        $this->initDataClass();
    }

    protected function initDataClass()
    {
        if ($this->hlblockId) {
            if (\Bitrix\Main\Loader::includeModule('highloadblock')) {
                $hlblock = HighloadBlockTable::getById($this->hlblockId)->fetch();
                $entity = HighloadBlockTable::compileEntity($hlblock);
                $this->entityDataClass = $entity->getDataClass();
            } else {
                throw new \Exception("Не удалось загрузить модуль highloadblock.");
            }
        }
    }

    public function add($fields)
    {
        $result = $this->entityDataClass::add($fields);
        if (!$result->isSuccess()) {
            return $result->getErrorMessages(); // возвращаем ошибки, если есть
        }
        return true;
    }

    public function update($id, $fields)
    {
        return $this->entityDataClass::update($id, $fields);
    }

    public function delete($id)
    {
        $result = $this->entityDataClass::delete($id);
        return $result->isSuccess();
    }

    public function getList($filter = [], $select = [])
    {
        return $this->entityDataClass::getList([
            'filter' => $filter,
            'select' => $select,
        ])->fetchAll();
    }

    public function getByKey($key)
    {
        return $this->entityDataClass::getList([
            'filter' => ['UF_KEY' => $key]
        ])->fetch();
    }

    public function getByKeyAndLang($key, $lang)
    {
        return $this->entityDataClass::getList([
            'filter' => [
                'UF_KEY' => $key,
                'UF_LANG' => $lang
            ]
        ])->fetch();
    }


    public function getOneByFilter(array $filter)
    {
        return $this->entityDataClass::getList([
            'filter' => $filter,
            'limit' => 1
        ])->fetch();
    }
}
