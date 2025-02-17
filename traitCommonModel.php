<?php
trait CommonModel 
{
    public function getByUserId($userId = null)
    {
        $count = $this->count("{$this->_fieldPrefix}user_id = '{$userId}'");
        if($count > 0)
        {
            $data =  $this->first([],["{$this->_fieldPrefix}user_id" => $userId]);
            return $this->parseEntity($data);
        }
        return false;
    }
}