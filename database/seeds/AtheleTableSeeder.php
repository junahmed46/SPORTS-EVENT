<?php

use Illuminate\Database\Seeder;
use App\Athletes;

class AtheleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $athletes = array(
            array('first_name' => 'Raymund','sur_name' => 'Osgood'),
            array('first_name' => 'Mauricio','sur_name' => 'Culham'),
            array('first_name' => 'Ericka','sur_name' => 'Coonan'),
            array('first_name' => 'Delmore','sur_name' => 'O\'Hartnedy'),
            array('first_name' => 'Richy','sur_name' => 'Reace'),
            array('first_name' => 'Valaree','sur_name' => 'Artinstall'),
            array('first_name' => 'Bran','sur_name' => 'Tidey'),
            array('first_name' => 'Juliet','sur_name' => 'Lehrer'),
            array('first_name' => 'Nonah','sur_name' => 'Gracewood'),
            array('first_name' => 'Velvet','sur_name' => 'Dax'),
            array('first_name' => 'Kameko','sur_name' => 'Eriksson'),
            array('first_name' => 'Delmor','sur_name' => 'Birtle'),
            array('first_name' => 'Missy','sur_name' => 'Lambird'),
            array('first_name' => 'Jonie','sur_name' => 'Longfut'),
            array('first_name' => 'Theodoric','sur_name' => 'Tofanini'),
            array('first_name' => 'Chere','sur_name' => 'Gummoe'),
            array('first_name' => 'Phillis','sur_name' => 'Baumann'),
            array('first_name' => 'Dina','sur_name' => 'Roll'),
            array('first_name' => 'Leonardo','sur_name' => 'Christmas'),
            array('first_name' => 'Waylen','sur_name' => 'Hennington'),
            array('first_name' => 'Patricia','sur_name' => 'Tremoulet'),
            array('first_name' => 'Corbie','sur_name' => 'Salkeld'),
            array('first_name' => 'Homere','sur_name' => 'Cantos'),
            array('first_name' => 'Jermaine','sur_name' => 'Gudeman'),
            array('first_name' => 'Tim','sur_name' => 'Skellen'),
            array('first_name' => 'Belita','sur_name' => 'Sanderson'),
            array('first_name' => 'Modestia','sur_name' => 'Dinesen'),
            array('first_name' => 'Alexi','sur_name' => 'Gorke'),
            array('first_name' => 'Lib','sur_name' => 'Cardus'),
            array('first_name' => 'Roley','sur_name' => 'Govett'),
            array('first_name' => 'Prentiss','sur_name' => 'Yitzhakof'),
            array('first_name' => 'Ham','sur_name' => 'Roundtree'),
            array('first_name' => 'Wynn','sur_name' => 'Gross'),
            array('first_name' => 'Antoinette','sur_name' => 'Wilkowski'),
            array('first_name' => 'Rae','sur_name' => 'Couser'),
            array('first_name' => 'Agace','sur_name' => 'Springtorpe'),
            array('first_name' => 'Alexei','sur_name' => 'Amesbury'),
            array('first_name' => 'Loretta','sur_name' => 'Marsie'),
            array('first_name' => 'Stephannie','sur_name' => 'Zoane'),
            array('first_name' => 'Simeon','sur_name' => 'Summersby'),
            array('first_name' => 'Rabi','sur_name' => 'Chance'),
            array('first_name' => 'Robert','sur_name' => 'Kingaby'),
            array('first_name' => 'Kayle','sur_name' => 'Marmon'),
            array('first_name' => 'Shaylyn','sur_name' => 'Hazeldene'),
            array('first_name' => 'Maren','sur_name' => 'Syrett'),
            array('first_name' => 'Marchelle','sur_name' => 'Janikowski'),
            array('first_name' => 'Arlin','sur_name' => 'Nekrews'),
            array('first_name' => 'Warren','sur_name' => 'Morcomb'),
            array('first_name' => 'Alex','sur_name' => 'Mowatt'),
            array('first_name' => 'Cletus','sur_name' => 'Dundredge'),
            array('first_name' => 'Cathie','sur_name' => 'Crunkhurn'),
            array('first_name' => 'Neila','sur_name' => 'Gobolos'),
            array('first_name' => 'Emlyn','sur_name' => 'List'),
            array('first_name' => 'Sheila','sur_name' => 'Orris'),
            array('first_name' => 'Aimee','sur_name' => 'Antosch'),
            array('first_name' => 'Demeter','sur_name' => 'Fullerd'),
            array('first_name' => 'Frederick','sur_name' => 'Shaplin'),
            array('first_name' => 'Melissa','sur_name' => 'Dollimore'),
            array('first_name' => 'Vivyanne','sur_name' => 'Pude'),
            array('first_name' => 'Leonid','sur_name' => 'Ferry'),
            array('first_name' => 'Frank','sur_name' => 'McFee'),
            array('first_name' => 'Adah','sur_name' => 'Hargreave'),
            array('first_name' => 'Carlee','sur_name' => 'Leary'),
            array('first_name' => 'Lyon','sur_name' => 'Trodler'),
            array('first_name' => 'Somerset','sur_name' => 'Dickons'),
            array('first_name' => 'Moe','sur_name' => 'Annwyl'),
            array('first_name' => 'Gaelan','sur_name' => 'Ryves'),
            array('first_name' => 'Maribeth','sur_name' => 'Dugan'),
            array('first_name' => 'Germayne','sur_name' => 'Scapens'),
            array('first_name' => 'Rebeca','sur_name' => 'Purkess'),
            array('first_name' => 'Francklyn','sur_name' => 'Girardeau'),
            array('first_name' => 'Latisha','sur_name' => 'Cutteridge'),
            array('first_name' => 'Minerva','sur_name' => 'MacKeig'),
            array('first_name' => 'Niki','sur_name' => 'Gelly'),
            array('first_name' => 'Leisha','sur_name' => 'Scholey'),
            array('first_name' => 'Caleb','sur_name' => 'Strother'),
            array('first_name' => 'Sly','sur_name' => 'Berk'),
            array('first_name' => 'Demetri','sur_name' => 'Schusterl'),
            array('first_name' => 'Reuben','sur_name' => 'Stretton'),
            array('first_name' => 'Aylmer','sur_name' => 'Pepall'),
            array('first_name' => 'Ric','sur_name' => 'Ciciotti'),
            array('first_name' => 'Zsazsa','sur_name' => 'Dani'),
            array('first_name' => 'Luca','sur_name' => 'Larkby'),
            array('first_name' => 'Laurent','sur_name' => 'St Ange'),
            array('first_name' => 'Corine','sur_name' => 'Birchwood'),
            array('first_name' => 'Giacopo','sur_name' => 'Krale'),
            array('first_name' => 'Danya','sur_name' => 'Buck'),
            array('first_name' => 'Cheston','sur_name' => 'Durtnell'),
            array('first_name' => 'Isak','sur_name' => 'Rapin'),
            array('first_name' => 'Heath','sur_name' => 'Privost'),
            array('first_name' => 'Jeth','sur_name' => 'Michel'),
            array('first_name' => 'Yolanda','sur_name' => 'Lowman'),
            array('first_name' => 'George','sur_name' => 'Swyer-Sexey'),
            array('first_name' => 'Peyter','sur_name' => 'Deboick'),
            array('first_name' => 'Lissa','sur_name' => 'Marflitt'),
            array('first_name' => 'Lynelle','sur_name' => 'Phibb'),
            array('first_name' => 'Gwynne','sur_name' => 'Odhams'),
            array('first_name' => 'Kellia','sur_name' => 'Hedderly'),
            array('first_name' => 'Chalmers','sur_name' => 'Rosini'),
            array('first_name' => 'Barron','sur_name' => 'Lutwyche')
        );

        foreach($athletes as $row)
        {
            Athletes::insert($row);
        }
    }
}
