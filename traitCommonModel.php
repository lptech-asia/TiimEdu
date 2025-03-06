<?php
trait CommonModel 
{
    public $where = [];
    public $order = '';
    public $limit = 15;
    public $operator = 'AND';

    public function addModel($name, $model)
    {
        $this->model = $model;
        $this->_entity->$name = $model;
        return $this;
    }
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

    public function getLoggined()
    {
        $modelUser = VSModel::getInstance()->load('User');
        $user = $modelUser->getLoggedIn();
        return $user;
    }
    
    public function processFileUpload($inputFiles , $folderId, $folderName = 'files')
	{
        $this->modelTypes       = VSFileType::getInstance();
        $this->modelFile        = VSFile::getInstance();

        if (is_array($inputFiles) && !empty($inputFiles)) 
        {
            if ($inputFiles['error'] == UPLOAD_ERR_OK) 
            {
                $fileNameSafe = VSFile::filterFileName($inputFiles['name']);
                $fileExt      = VSFile::getExtension($fileNameSafe);
                $dataFiles    = array(
                    'name'          => VSFile::getName($fileNameSafe),
                    'extension'     => $fileExt,
                    'types_id'      => $this->modelTypes->getIdByExt($fileExt),
                    'size'          => intval($inputFiles['size']),
                    'real_folder'   => date('Y-m-d'),
                    'categories_id' => $folderId,
                );
				$filesDir = VS_UPLOAD_PATH . $folderName  .DS. str_replace('-', DS, $dataFiles['real_folder']) . DS;
                // If file type not exists
                if (!$dataFiles['types_id']) 
                {
                    $msg = sprintf('Loại file %s không tồn tại', $fileExt);
                    VSDebug::logError($msg);
                    return false;
                }
                
                // if file folders not exists then create it
                if (!is_dir($filesDir) || !file_exists($filesDir)) 
                {
                    mkdir($filesDir, 0777, true); // create files folders
                }
                
                $filesPath = $filesDir . $dataFiles['name'] . '.' . $dataFiles['extension'];
				if (move_uploaded_file($inputFiles['tmp_name'], $filesPath)) 
                {
                    $new_name = md5($dataFiles['name'].'-'.time());
                    $new_path = $filesDir . $new_name . '.' . $dataFiles['extension'];
                    if (rename($filesPath, $new_path)) 
                    {
                        $dataFiles['name'] = $new_name;
                        $id = $this->modelFile->add($dataFiles, true);
                        if (!$id) {
                            @unlink($new_path);
                            VSDebug::logError(sprintf('Lỗi thêm dữ liệu file [%s] vào database.'), $inputFiles['name']);
                        }
                        return VS_UPLOAD_URL . $folderName .DS. str_replace('-', DS, $dataFiles['real_folder']) .DS. $new_name  . '.' . $dataFiles['extension'];
                    }
				}  else  {
					VSDebug::logError(sprintf('Lỗi di chuyển file [%s] từ thư mục tạm đến thư mục upload.'), $inputFiles['name']);
				}
            }
        }
	}

    public function isExist($name = null, $value = null)
    {
        $name = $name ? $this->_fieldPrefix . $name :  $this->_primaryKey;
        $count = $this->count("{$name} = {$this->doQuote($value)}");
        if($count)
        {
            $data = $this->get([],["{$name}" => $value]);
            $data = reset($data);
            $item = $this->parseEntity($data);
            return $item;
        }
        return false;
    }

    public function limit($limit = 15)
    {
        $this->limit = $limit;
        return $this;
    }

    public function where($key, $value)
    {
        $this->where[$this->getFieldPrefix() . $key] = $value;
        return $this;
    }

    public function orderBy($key = '', $value = 'DESC')
    {
        if($key == '')
        {
            $key = $this->_primaryKey;
        }
        $this->order = $this->_fieldPrefix . $key . " " . $value;
        return $this;
    }

    public function getOne()
    {
        $data = $this->first([], $this->where);
        unset($this->where);
        if($data) return $this->parseEntity($data);
        return false;
    }
    public function getAll()
    {
        $data = $this->get([], $this->where);
        unset($this->where);
        if($data) return $this->parseEntities($data);
        return false;
    }

    public function getPagination()
    {
        $elms = array(
            'columns' => array(),
            'where' => $this->where,
            'order' => $this->order,
            'pagesize' => $this->limit ?? 15
        );
        unset($this->where);
        $data = $this->getPaging($elms);
        return $this->parseEntities($data);
    }

    public function setOperator($operator = 'AND')
    {
        $this->operator = $operator;
        return $this;
    }

    public function searchLike($where = [])
    {
        if(!$where) return false;
        $sql = "SELECT * FROM {$this->_tableName} WHERE ";
        foreach($where as $key => $value)
        {
            $sql .= "{$this->_fieldPrefix}{$key} LIKE " . $this->doQuote('%' . $value . '%');
            if($key !== array_key_last($where))
            {
                $sql .= " {$this->operator} ";
            }
        }
        $data = $this->query($sql);
        $items = $this->parseEntities($data);
        return $items;
    }


    public function setNull($column = '')
    {
        $sql = "UPDATE {$this->_tableName} SET {$this->_fieldPrefix}{$column} = NULL WHERE ";
        $sql .= $this->__parseWhere();
        return $this->query($sql, [$column]);
    }


    // delete all
    public function reset()
    {
        $this->truncate();
    }

    public function countItem()
    {
        $sql = $this->__parseWhere();
        return $this->count($sql);
    }


    protected function __parseWhere()
    {
        $sql = '';
        if(empty($this->where)) return false;
        foreach($this->where as $key => $value)
        {
            $sql .= $key . ' = ' . $this->doQuote($value);
            if($key !== array_key_last($this->where))
            {
                $sql .= " {$this->operator} ";
            }
        }
        unset($this->where);
        return $sql;
    }
}