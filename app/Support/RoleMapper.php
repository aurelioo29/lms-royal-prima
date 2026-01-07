<?php

namespace App\Support;

class RoleMapper
{
    public static function map(?string $slug): string
    {
        return match ($slug) {
            'director'       => 'direktur',
            'head_training'  => 'kabid-diklat',
            'instructor'     => 'narasumber',
            'developer'      => 'developer',
            'admin'          => 'admin',
            'karyawan'       => 'karyawan',
            default          => 'karyawan',
        };
    }
}
