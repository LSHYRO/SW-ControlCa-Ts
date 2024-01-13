<?php

namespace Database\Seeders;

use App\Models\asentamientos;
use App\Models\codigoPostal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AsentamientosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $codpos = codigoPostal::where('codigoPostal', 20000)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Colonia Aguascalientes Centro'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos -> idCodigoPostal]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20010)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento Colinas del Rio', 'Colonia Olivares Santana', 'Fraccionamiento Las Brisas', 'Fraccionamiento Ramon Romo Franco', 'Fraccionamiento San Cayetano'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20016)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento Colinas de San Ignacio', 'Fraccionamiento La Fundición', 'Fraccionamiento Fundición II', 'Fraccionamiento Los Sauces'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20018)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Colonia Línea de Fuego'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20020)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Colonia Buenos Aires', 'Fraccionamiento Circunvalación Norte', 'Fraccionamiento Las Arboledas', 'Fraccionamiento Villas de San Francisco'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20029)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento Villas de La Universidad'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20030)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento El Sol', 'Colonia Gremial', 'Colonia Industrial'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20040)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Colonia Altavista', 'Colonia Curtidores', 'Fraccionamiento La Concordia', 'Colonia Miravalle', 'Fraccionamiento Panorama', 'Fraccionamiento Residencial Guadalupe'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20049)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento Colinas del Poniente'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20050)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Condominio Bugambilias', 'Colonia Del Carmen', 'Colonia La Fe', 'Colonia Primavera', 'Colonia San Pablo'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20059)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Colonia Guadalupe', 'Colonia Heliodoro Garcia'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20060)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento Gómez', 'Fraccionamiento Moderno'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20064)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento Valle del Rio San Pedro'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20070)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Unidad habitacional Guadalupe Posada', 'Colonia San Marcos', 'Barrio San Marcos'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20078)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento San Marcos'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }
        $codpos = codigoPostal::where('codigoPostal', 20080)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento Modelo', 'Fraccionamiento Residencial del Valle I'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20089)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento Residencial del Valle II'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }
        $codpos = codigoPostal::where('codigoPostal', 20100)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento La Herradura', 'Fraccionamiento Club Campestre', 'Condominio Jardines del Campestre', 'Fraccionamiento Los Vergeles', 'Equipamiento Ciudad Universitaria', 'Condominio Rancho San Antonio'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20110)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Colonia Talamantes Ponce', 'Fraccionamiento Granjas del Campestre', 'Condominio Puerto las Hadas', 'Fraccionamiento Valle del Campestre', 'Fraccionamiento Villas de Montenegro', 'Fraccionamiento Las Cavas', 'Fraccionamiento La Enramada'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20115)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento Trojes de Oriente', 'Fraccionamiento Valle de las Trojes', 'Fraccionamiento Villas de San Nicolás', 'Fraccionamiento San Telmo', 'Condominio La Paloma', 'Fraccionamiento Barrio de Santiago', 'Fraccionamiento Villa de las Trojes', 'Fraccionamiento Valle de Santa Teresa'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20116)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento La Troje', 'Colonia Trojes de Alonso', 'Fraccionamiento San Telmo Residencial', 'Condominio Santa Fe'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20118)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Condominio Las Trojes', 'Fraccionamiento Misión del Campanario', 'Fraccionamiento Trojes de Cristal', 'Condominio Trojes del Sol', 'Fraccionamiento Residencial Santa Clara', 'Fraccionamiento Misión de Santiago', 'Condominio Andora Residencial', 'Condominio Cadaqués Residencial', 'Fraccionamiento Valle del Campanario', 'Fraccionamiento Los Calicantos', 'Fraccionamiento Las Misiones', 'Condominio Los Jarales', 'Condominio Cerrada El Molino', 'Fraccionamiento Valle Real', 'Condominio Terzetto', 'Fraccionamiento Cerrada de La Misión', 'Fraccionamiento Cerrada del Valle', 'Fraccionamiento Cerrada de la Mezquitera'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20119)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento Lomas del Campestre 2a Sección', 'Fraccionamiento Villas del Campestre'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20120)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento Jardines de la Concepción', 'Fraccionamiento Los Bosques', 'Condominio Rinconada los Bosques'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20123)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Condominio La Perla Norte', 'Fraccionamiento Arroyo El Molino'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20124)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Zona comercial Galerías', 'Fraccionamiento Residencial Altaria'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20126)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento Constitución', 'Fraccionamiento Libertad', 'Fraccionamiento Pozo Bravo Norte', 'Fraccionamiento Soberana Convención Revolucionaria', 'Fraccionamiento Villa Montaña', 'Fraccionamiento Villas de Don Antonio', 'Fraccionamiento Los Ángeles', 'Fraccionamiento Capittala', 'Fraccionamiento Recinto de la Macarena', 'Condominio Nápoli', 'Rancho La Soledad', 'Fraccionamiento Los Naranjos', 'Fraccionamiento Villa de Nuestra Señora de La Asunción Sector Guadalupe', 'Fraccionamiento Villa Teresa', 'Fraccionamiento Cartagena 1947', 'Fraccionamiento Villas de La Convención', 'Fraccionamiento Lomas de La Asunción', 'Fraccionamiento Villa de Nuestra Señora de La Asunción Sector Encino', 'Fraccionamiento Villa de Nuestra Señora de La Asunción Sector Alameda', 'Fraccionamiento San José de Pozo Bravo', 'Fraccionamiento Villa de Nuestra Señora de La Asunción Sector San Marcos', 'Fraccionamiento Villa de Nuestra Señora de La Asunción Sector Estación', 'Condominio Las Plazas', 'Fraccionamiento El Rosedal', 'Fraccionamiento Natura', 'Fraccionamiento Montebello Della Stanza', 'Condominio Villa Notre Dame', 'Condominio Privada Guadalupe', 'Fraccionamiento Rinconada Pozo Bravo', 'Fraccionamiento Pozo Bravo Sur', 'Fraccionamiento Villa Loma Dorada', 'Fraccionamiento Jardines de Montebello', 'Fraccionamiento Villas del Río', 'Fraccionamiento El Puertecito', 'Fraccionamiento Rinconada del Puertecito'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20127)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento Bosques del Prado Norte'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }
        
        $codpos = codigoPostal::where('codigoPostal', 20128)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Rancho Sartenejo'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }
        
        $codpos = codigoPostal::where('codigoPostal', 20129)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento Lomas del Campestre 1a Sección'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }
        
        $codpos = codigoPostal::where('codigoPostal', 20130)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento Bosques del Prado Sur', 'Fraccionamiento El Roble', 'Colonia Fátima', 'Fraccionamiento Independencia de México', 'Fraccionamiento Nueva Rinconada', 'Colonia Primo Verdad', 'Colonia San José del Arenal', 'Colonia Unidad Ganadera', 'Condominio San Xavier', 'Fraccionamiento Residencial del Real', 'Condominio Puerta Navarra', 'Condominio Residencial Campestre Club de Golf', 'Fraccionamiento Palma Real', 'Fraccionamiento Muralia'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20135)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Zona comercial Agropecuario', 'Zona comercial Centro Distribuidor de Básicos'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20136)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento La Rinconada'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }
        
        $codpos = codigoPostal::where('codigoPostal', 20137)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento El Plateado'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }
        
        $codpos = codigoPostal::where('codigoPostal', 20138)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Condominio Residencial Pulgas Pandas Norte', 'Condominio Residencial Pulgas Pandas Sur', 'Condominio Villas del Vergel', 'Fraccionamiento Cerrada San Miguel'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20140)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento Las Hadas', 'Colonia Morelos', 'Condominio Andrea'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20146)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento Los Arcos'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20149)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento Industrial'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20150)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Colonia Buenavista', 'Fraccionamiento C.T.M.', 'Fraccionamiento La Estrella', 'Colonia Macias Arellano', 'Condominio Trento', 'Condominio Nueva Andalucia Coto Residencial', 'Condominio Váltica', 'Colonia Lomas del Cobano'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20157)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Colonia La Higuerilla', 'Fraccionamiento Parras'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20158)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento El Cobano', 'Fraccionamiento Hacienda el Cobano', 'Fraccionamiento Trojes del Cobano', 'Fraccionamiento Villas del Cobano'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20159)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento Alianza Ferrocarrilera', 'Fraccionamiento Bosques del Prado Oriente'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20160)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Colonia Francisco Guel Jimenez', 'Unidad habitacional Las Viñas INFONAVIT'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20164)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Colonia Santa Anita 4a Sección'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20169)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento Santa Anita', 'Fraccionamiento Santa Anita 2a Sección'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20170)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento El Maguey', 'Condominio Las Cumbres', 'Unidad habitacional Lic Benito Juárez', 'Fraccionamiento Nazario Ortiz Garza', 'Fraccionamiento Rodolfo Landeros Gallegos', 'Fraccionamiento S.T.E.M.A.', 'Colonia Zona Militar', 'Fraccionamiento Villa Bonita'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20172)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento Lic Benito Palomino Dena', 'Fraccionamiento Anexo Benito Palomino Dena (Cumbres II)', 'Fraccionamiento La Florida l', 'Fraccionamiento La Florida ll', 'Fraccionamiento Claustros Loma Dorada', 'Colonia Cumbres III'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20174)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento Lomas de Bellavista', 'Fraccionamiento Lomas de las Fuentes', 'Fraccionamiento Colinas de Oriente', 'Fraccionamiento Vista de las Cumbres', 'Fraccionamiento Los Laureles', 'Fraccionamiento Mirador de las Culturas', 'Ranchería El Rocío', 'Fraccionamiento Villas de la Loma', 'Colonia Los Pericos', 'Fraccionamiento Paseos del Sol', 'Fraccionamiento Miradores de Santa Elena', 'Fraccionamiento Villas de las Fuentes'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20175)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Condominio La Hojarasca', 'Fraccionamiento Ejido las Cumbres', 'Colonia J Refugio Esparza Reyes', 'Fraccionamiento Rinconadas las Cumbres', 'Fraccionamiento Lomas de Oriente'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20177)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento C.N.O.P. Oriente'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20179)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento Las Cumbres', 'Fraccionamiento Luis Ortega Douglas', 'Fraccionamiento Pensadores Mexicanos', 'Fraccionamiento Pintores Mexicanos', 'Colonia Progreso', 'Fraccionamiento Santa Regina', 'Fraccionamiento Cerro Alto', 'Fraccionamiento Santa Margarita'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20180)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Colonia Del Trabajo', 'Colonia Ferronales', 'Fraccionamiento Luis Gómez Zepeda (ferronales)', 'Colonia Lomas de Santa Anita', 'Fraccionamiento Alameda', 'Condominio Rinconada de La Alameda', 'Condominio Bosques de La Alameda', 'Fraccionamiento Nueva Alameda'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20190)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Colonia Héroes', 'Condominio La Hacienda', 'Condominio La Mancha'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }

        $codpos = codigoPostal::where('codigoPostal', 20196)->first();
        $municipio = $codpos->idMunicipio;
        $asentamientos = ['Fraccionamiento Ojocaliente', 'Unidad habitacional Ojocaliente INEGI', 'Fraccionamiento Solidaridad 1a Sección', 'Fraccionamiento Sol Naciente', 'Fraccionamiento Villa de las Norias', 'Fraccionamiento Camino Real', 'Fraccionamiento Ribera del Sol', 'Fraccionamiento Ambrosía', 'Fraccionamiento Molino del Rey', 'Colonia El Polvorín (Mirador TV Azteca)', 'Fraccionamiento José Guadalupe Peralta Gámez', 'Fraccionamiento Haciendas de Aguascalientes', 'Fraccionamiento Villerías', 'Fraccionamiento Vistas de Oriente', 'Fraccionamiento Real de Haciendas', 'Fraccionamiento Valle de los Cactus', 'Fraccionamiento Colinas de San Patricio', 'Fraccionamiento Balcones de Oriente', 'Fraccionamiento Terra Nova', 'Fraccionamiento José Guadalupe Posada', 'Colonia Comunidad el Rocío', 'Fraccionamiento Paseo de los Cactus', 'Fraccionamiento Balcones del Valle', 'Fraccionamiento Real del Sol'];
        foreach ($asentamientos as $value) {
            asentamientos::create(['asentamiento' => $value, "idMunicipio" => $municipio, "idcodigoPostal" => $codpos]);
        }
    }
}
