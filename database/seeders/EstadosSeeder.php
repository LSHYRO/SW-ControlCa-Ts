<?php

namespace Database\Seeders;

use App\Models\estados;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estado1 = new estados();
        $estado1->estado = "Aguascalientes";
        $estado1->save();

        $estado2 = new estados();
        $estado2->estado = "Baja California N";
        $estado2->save();

        $estado3 = new estados();
        $estado3->estado = "Baja california S";
        $estado3->save();

        $estado4 = new estados();
        $estado4->estado = "Campeche";
        $estado4->save();

        $estado5 = new estados();
        $estado5->estado = "Chiapas";
        $estado5->save();

        $estado6 = new estados();
        $estado6->estado = "Chihuahua";
        $estado6->save();

        $estado7 = new estados();
        $estado7->estado = "Coahuila";
        $estado7->save();

        $estado8 = new estados();
        $estado8->estado = "Colima";
        $estado8->save();

        $estado9 = new estados();
        $estado9->estado = "Durango";
        $estado9->save();

        $estado10 = new estados();
        $estado10->estado = "Guanajuato";
        $estado10->save();

        $estado11 = new estados();
        $estado11->estado = "Guerrero";
        $estado11->save();

        $estado12 = new estados();
        $estado12->estado = "Hidalgo";
        $estado12->save();

        $estado13 = new estados();
        $estado13->estado = "Jalisco";
        $estado13->save();

        $estado14 = new estados();
        $estado14->estado = "México";
        $estado14->save();

        $estado15 = new estados();
        $estado15->estado = "Michoacán";
        $estado15->save();

        $estado16 = new estados();
        $estado16->estado = "Morelos";
        $estado16->save();

        $estado17 = new estados();
        $estado17->estado = "Nayarit";
        $estado17->save();

        $estado18 = new estados();
        $estado18->estado = "Nuevo León";
        $estado18->save();

        $estado19 = new estados();
        $estado19->estado = "Oaxaca";
        $estado19->save();

        $estado20 = new estados();
        $estado20->estado = "Puebla";
        $estado20->save();

        $estado21 = new estados();
        $estado21->estado = "Querétaro";
        $estado21->save();

        $estado22 = new estados();
        $estado22->estado = "Quintana Roo";
        $estado22->save();

        $estado23 = new estados();
        $estado23->estado = "San Luis Potosí";
        $estado23->save();

        $estado24 = new estados();
        $estado24->estado = "Sinaloa";
        $estado24->save();

        $estado25 = new estados();
        $estado25->estado = "Sonora";
        $estado25->save();

        $estado26 = new estados();
        $estado26->estado = "Tabasco";
        $estado26->save();

        $estado27 = new estados();
        $estado27->estado = "Tamaulipas";
        $estado27->save();

        $estado28 = new estados();
        $estado28->estado = "Tlaxcala";
        $estado28->save();

        $estado29 = new estados();
        $estado29->estado = "Veracruz";
        $estado29->save();

        $estado30 = new estados();
        $estado30->estado = "Yucatán";
        $estado30->save();

        $estado31 = new estados();
        $estado31->estado = "Zacatecas";
        $estado31->save();
    }
}
