<?php

namespace Database\Seeders;

use App\Models\Master\Geografis\Province;
use File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File as FacadesFile;

class ProvinceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path('database/seeders/json/province.json');
        $json = FacadesFile::get($path);
        $data = json_decode($json);

        $this->generate($data);
    }

    public function generate($data)
    {
        foreach ($data as $val) {
            $prov = Province::where('name', $val->name)->first();
            if (!$prov) {
                $prov = new Province;
                // $prov->id = $val->id;
                $prov->name = $val->name;
                $prov->code = $val->id;
                $prov->created_by = NULL;
                $prov->created_at = \Carbon\Carbon::now();
                $prov->save();
            }
        }
    }
}
