<?php


namespace App\Interfaces;


interface Crudable
{
    public function resetFields();

    public function store();

    public function edit($id);

    public function update();

    public function delete($id);
}
