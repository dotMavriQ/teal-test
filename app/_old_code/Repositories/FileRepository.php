<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class FileRepository
{
    protected $dataPath;
    
    public function __construct($dataFile)
    {
        $this->dataPath = storage_path("app/{$dataFile}.json");
        $this->ensureFileExists();
    }
    
    protected function ensureFileExists()
    {
        if (!file_exists($this->dataPath)) {
            file_put_contents($this->dataPath, json_encode([]));
        }
    }
    
    public function all()
    {
        return json_decode(file_get_contents($this->dataPath), true);
    }
    
    public function find($id)
    {
        $items = $this->all();
        
        foreach ($items as $item) {
            if (isset($item['id']) && $item['id'] == $id) {
                return $item;
            }
        }
        
        return null;
    }
    
    public function findBy($field, $value)
    {
        $items = $this->all();
        
        foreach ($items as $item) {
            if (isset($item[$field]) && $item[$field] == $value) {
                return $item;
            }
        }
        
        // Debug 
        error_log("FileRepository::findBy - Failed to find item with {$field} = {$value}");
        
        return null;
    }
    
    public function save(array $data)
    {
        $items = $this->all();
        
        if (!isset($data['id'])) {
            $data['id'] = $this->getNextId();
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            
            $items[] = $data;
        } else {
            $found = false;
            
            foreach ($items as $key => $item) {
                if (isset($item['id']) && $item['id'] == $data['id']) {
                    $data['updated_at'] = date('Y-m-d H:i:s');
                    $data['created_at'] = $item['created_at'] ?? date('Y-m-d H:i:s');
                    $items[$key] = $data;
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['updated_at'] = date('Y-m-d H:i:s');
                $items[] = $data;
            }
        }
        
        file_put_contents($this->dataPath, json_encode($items, JSON_PRETTY_PRINT));
        
        return $data;
    }
    
    public function delete($id)
    {
        $items = $this->all();
        $result = false;
        
        foreach ($items as $key => $item) {
            if (isset($item['id']) && $item['id'] == $id) {
                unset($items[$key]);
                $result = true;
                break;
            }
        }
        
        $items = array_values($items);
        file_put_contents($this->dataPath, json_encode($items, JSON_PRETTY_PRINT));
        
        return $result;
    }
    
    protected function getNextId()
    {
        $items = $this->all();
        $maxId = 0;
        
        foreach ($items as $item) {
            if (isset($item['id']) && $item['id'] > $maxId) {
                $maxId = $item['id'];
            }
        }
        
        return $maxId + 1;
    }
    
    public function createAdmin()
    {
        $user = $this->findBy('email', 'dotmavriq@dotmavriq.life');
        
        if (!$user) {
            $this->save([
                'name' => 'Admin',
                'email' => 'dotmavriq@dotmavriq.life',
                'password' => Hash::make('TEALAdmin@2025#Secure'),
            ]);
            
            return true;
        }
        
        return false;
    }
}