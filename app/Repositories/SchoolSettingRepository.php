<?php

namespace App\Repositories;

use App\Interfaces\SchoolSettingInterface;
use App\Models\SchoolSetting;

class SchoolSettingRepository implements SchoolSettingInterface
{
    public function index()
    {
        return SchoolSetting::all();
    }

    public function store(array $data)
    {
        return SchoolSetting::create($data);
    }

    public function show(string $id)
    {
        return SchoolSetting::find($id);
    }

    public function update(array $data, string $id)
    {
        SchoolSetting::find($id)->update($data);
    }

    public function destroy(string $id)
    {
        SchoolSetting::find($id)->delete();
    }
}
