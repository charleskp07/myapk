<?php

namespace App\Interfaces;

interface NoteInterface
{
    public function index();
    public function store(array $data);
    public function show(string $id);
    public function update(array $data);
    public function destroy(string $id);
}
