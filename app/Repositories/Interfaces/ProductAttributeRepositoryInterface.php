<?php

namespace App\Repositories\Interfaces;

interface ProductAttributeRepositoryInterface
{
    public function getByProductId(int $productId);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function find(int $id);
}