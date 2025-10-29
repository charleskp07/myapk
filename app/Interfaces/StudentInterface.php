<?php

namespace App\Interfaces;

interface StudentInterface
{
    public function index();
    public function store(array $data);
    public function show(string $id);
    public function update(array $data, string $id);
    public function destroy(string $id);
}
