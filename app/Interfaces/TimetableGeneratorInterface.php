<?php

namespace App\Interfaces;

interface TimetableGeneratorInterface
{
    public function generate(string $classroomId);
}
