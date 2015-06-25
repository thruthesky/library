<?php
namespace Drupal\library;
use Drupal\mall\HTML;
use Drupal\user\Entity\User;

use Drupal\mall\Entity\Member;
use Drupal\mall\Entity\Category;

/**
 * Class X
 * @package Drupal\mall
 * @short Helper library class for mall module.
 * @short Difference from Mall.php is that Mall.php is a library that is only used for mall module. x.php holds more generic functions.
 */

class x {

  const ERROR_CATEGORY_EXIST = 'ERROR_CATEGORY_EXIST';
  const ERROR_BLANK_CATEGORY_NAME = 'ERROR_BLANK_CATEGORY_NAME';
  
  const ERROR_PLEASE_LOGIN_FIRST = 'ERROR_PLEASE_LOGIN_FIRST';
  const ERROR_USER_EXISTS = 'ERROR_USER_EXISTS';
  
  const ERROR_NOT_YOUR_ID = 'ERROR_NOT_YOUR_ID';
  const ERROR_NOT_YOUR_POST = 'ERROR_NOT_YOUR_POST';
  
  const ERROR_MUST_BE_AN_INTEGER = 'ERROR_MUST_BE_AN_INTEGER';


  static $input = [];

    static $months = [
        '1'=>'January',
        '2'=>'February',
        '3'=>'March',
        '4'=>'April',
        '5'=>'May',
        '6'=>'June',
        '7'=>'July',
        '8'=>'August',
        '9'=>'September',
        '10'=>'October',
        '11'=>'November',
        '12'=>'December'
    ];
	
	static $item_status =[
		'U' => 'Second Hand',
		'B' => 'Brand New',
		'D' => 'Defective',
	];
	
	static $default_search_sort =[
		'created_low' => 'Date - Oldest to Newest',
		'created_high' => 'Date - Newest to Oldest',
		'changed_low' => 'Latest Updated - Oldest to Newest',
		'changed_high' => 'Latest Updated -Newest to Oldest',
		'price_low' => 'Price - Cheapest to Most Expensive',		
		'price_high' => 'Price - Most Expensive to Cheapest',
	];
	
	static $default_item_per_page =[ 1, 15, 30, 45 ];
	
	static $cities = ['Metro Manila'=> [ 'Metro Manila'=> 'Entire Metro Manila', 'Metro Manila - Caloocan City'=> 'Caloocan City', 'Metro Manila - Las Pinas City'=> 'Las Pinas City', 'Metro Manila - Makati City'=> 'Makati City', 'Metro Manila - Malabon City'=> 'Malabon City', 'Metro Manila - Mandaluyong City'=> 'Mandaluyong City', 'Metro Manila - Manila'=> 'Manila', 'Metro Manila - Marikina City'=> 'Marikina City', 'Metro Manila - Muntinlupa City'=> 'Muntinlupa City', 'Metro Manila - Navotas'=> 'Navotas', 'Metro Manila - Paranaque City'=> 'Paranaque City', 'Metro Manila - Pasay City'=> 'Pasay City', 'Metro Manila - Pasig City'=> 'Pasig City', 'Metro Manila - Pateros'=> 'Pateros', 'Metro Manila - Quezon City'=> 'Quezon City', 'Metro Manila - San Juan'=> 'San Juan', 'Metro Manila - Taguig City'=> 'Taguig City', 'Metro Manila - Valenzuela City'=> 'Valenzuela City' ], 'Abra'=> [ 'Abra'=> 'Entire Abra', 'Abra - Bangued'=> 'Bangued', 'Abra - Boliney'=> 'Boliney', 'Abra - Bucay'=> 'Bucay', 'Abra - Bucloc'=> 'Bucloc', 'Abra - Daguioman'=> 'Daguioman', 'Abra - Danglas'=> 'Danglas', 'Abra - Dolores'=> 'Dolores', 'Abra - La Paz'=> 'La Paz', 'Abra - Lacub'=> 'Lacub', 'Abra - Lagangilang'=> 'Lagangilang', 'Abra - Lagayan'=> 'Lagayan', 'Abra - Langiden'=> 'Langiden', 'Abra - Licuan-Baay'=> 'Licuan-Baay', 'Abra - Luba'=> 'Luba', 'Abra - Malibcong'=> 'Malibcong', 'Abra - Manabo'=> 'Manabo', 'Abra - Penarrubia'=> 'Penarrubia', 'Abra - Pidigan'=> 'Pidigan', 'Abra - Pilar'=> 'Pilar', 'Abra - Sallapadan'=> 'Sallapadan', 'Abra - San Isidro'=> 'San Isidro', 'Abra - San Juan'=> 'San Juan', 'Abra - San Quintin'=> 'San Quintin', 'Abra - Tayum'=> 'Tayum', 'Abra - Tineg'=> 'Tineg', 'Abra - Tubo'=> 'Tubo', 'Abra - Villaviciosa'=> 'Villaviciosa' ], 'Agusan del Norte'=> [ 'Agusan del Norte'=> 'Entire Agusan del Norte', 'Agusan del Norte - Buenavista'=> 'Buenavista', 'Agusan del Norte - Butuan City'=> 'Butuan City', 'Agusan del Norte - Cabadbaran'=> 'Cabadbaran', 'Agusan del Norte - Carmen'=> 'Carmen', 'Agusan del Norte - Jabonga'=> 'Jabonga', 'Agusan del Norte - Kitcharao'=> 'Kitcharao', 'Agusan del Norte - Las Nieves'=> 'Las Nieves', 'Agusan del Norte - Magallanes'=> 'Magallanes', 'Agusan del Norte - Nasipit'=> 'Nasipit', 'Agusan del Norte - Remedios T. Romualdez'=> 'Remedios T. Romualdez', 'Agusan del Norte - Santiago'=> 'Santiago', 'Agusan del Norte - Tubay'=> 'Tubay' ], 'Agusan del Sur'=> [ 'Agusan del Sur'=> 'Entire Agusan del Sur', 'Agusan del Sur - Bayugan City'=> 'Bayugan City', 'Agusan del Sur - Bunawan'=> 'Bunawan', 'Agusan del Sur - Esperanza'=> 'Esperanza', 'Agusan del Sur - La Paz'=> 'La Paz', 'Agusan del Sur - Loreto'=> 'Loreto', 'Agusan del Sur - Prosperidad'=> 'Prosperidad', 'Agusan del Sur - Rosario'=> 'Rosario', 'Agusan del Sur - San Francisco'=> 'San Francisco', 'Agusan del Sur - San Luis'=> 'San Luis', 'Agusan del Sur - Santa Josefa'=> 'Santa Josefa', 'Agusan del Sur - Sibagat'=> 'Sibagat', 'Agusan del Sur - Talacogon'=> 'Talacogon', 'Agusan del Sur - Trento'=> 'Trento', 'Agusan del Sur - Veruela'=> 'Veruela' ], 'Aklan'=> [ 'Aklan'=> 'Entire Aklan', 'Aklan - Altavas'=> 'Altavas', 'Aklan - Balete'=> 'Balete', 'Aklan - Banga'=> 'Banga', 'Aklan - Batan'=> 'Batan', 'Aklan - Burungan'=> 'Burungan', 'Aklan - Ibajay'=> 'Ibajay', 'Aklan - Kalibo'=> 'Kalibo', 'Aklan - Lezo'=> 'Lezo', 'Aklan - Libacao'=> 'Libacao', 'Aklan - Madalag'=> 'Madalag', 'Aklan - Makato'=> 'Makato', 'Aklan - Malay'=> 'Malay', 'Aklan - Malinao'=> 'Malinao', 'Aklan - Nabas'=> 'Nabas', 'Aklan - New Washington'=> 'New Washington', 'Aklan - Numancia'=> 'Numancia', 'Aklan - Tangalan'=> 'Tangalan' ], 'Albay'=> [ 'Albay'=> 'Entire Albay', 'Albay - Bacacay'=> 'Bacacay', 'Albay - Camalig'=> 'Camalig', 'Albay - Daraga'=> 'Daraga', 'Albay - Guinobatan'=> 'Guinobatan', 'Albay - Jovellar'=> 'Jovellar', 'Albay - Legazpi City'=> 'Legazpi City', 'Albay - Libon'=> 'Libon', 'Albay - Ligao City'=> 'Ligao City', 'Albay - Malilipot'=> 'Malilipot', 'Albay - Malinao'=> 'Malinao', 'Albay - Manito'=> 'Manito', 'Albay - Oas'=> 'Oas', 'Albay - Pio Duran'=> 'Pio Duran', 'Albay - Polangui'=> 'Polangui', 'Albay - Rapu-Rapu'=> 'Rapu-Rapu', 'Albay - Santo Domingo'=> 'Santo Domingo', 'Albay - Tabaco City'=> 'Tabaco City', 'Albay - Tiwi'=> 'Tiwi' ], 'Antique'=> [ 'Antique'=> 'Entire Antique', 'Antique - Anini-y'=> 'Anini-y', 'Antique - Barbaza'=> 'Barbaza', 'Antique - Belison'=> 'Belison', 'Antique - Bugasong'=> 'Bugasong', 'Antique - Caluya'=> 'Caluya', 'Antique - Culasi'=> 'Culasi', 'Antique - Hamtic'=> 'Hamtic', 'Antique - Laua-An'=> 'Laua-An', 'Antique - Libertad'=> 'Libertad', 'Antique - Pandan'=> 'Pandan', 'Antique - Patnongon'=> 'Patnongon', 'Antique - San Jose'=> 'San Jose', 'Antique - San Remigio'=> 'San Remigio', 'Antique - Sebaste'=> 'Sebaste', 'Antique - Sibalom'=> 'Sibalom', 'Antique - Tibiao'=> 'Tibiao', 'Antique - Tobias Fornier (Dao)'=> 'Tobias Fornier (Dao)', 'Antique - Valderrama'=> 'Valderrama' ], 'Apayao'=> [ 'Apayao'=> 'Entire Apayao', 'Apayao - Calanasan (Bayag)'=> 'Calanasan (Bayag)', 'Apayao - Conner'=> 'Conner', 'Apayao - Flora'=> 'Flora', 'Apayao - Kabugao'=> 'Kabugao', 'Apayao - Luna'=> 'Luna', 'Apayao - Pudtol'=> 'Pudtol', 'Apayao - Santa Marcela'=> 'Santa Marcela' ], 'Aurora'=> [ 'Aurora'=> 'Entire Aurora', 'Aurora - Baler'=> 'Baler', 'Aurora - Casiguran'=> 'Casiguran', 'Aurora - Dilasag'=> 'Dilasag', 'Aurora - Dinalungan'=> 'Dinalungan', 'Aurora - Dingalan'=> 'Dingalan', 'Aurora - Dipaculao'=> 'Dipaculao', 'Aurora - Maria Aurora'=> 'Maria Aurora', 'Aurora - San Luis'=> 'San Luis' ], 'Basilan'=> [ 'Basilan'=> 'Entire Basilan', 'Basilan - Akbar'=> 'Akbar', 'Basilan - Al-Barka'=> 'Al-Barka', 'Basilan - Hadji Mohammad Ajul'=> 'Hadji Mohammad Ajul', 'Basilan - Isabela City'=> 'Isabela City', 'Basilan - Lamitan City'=> 'Lamitan City', 'Basilan - Lantawan'=> 'Lantawan', 'Basilan - Maluso'=> 'Maluso', 'Basilan - Sumisip'=> 'Sumisip', 'Basilan - Tipo-Tipo'=> 'Tipo-Tipo', 'Basilan - Tuburan'=> 'Tuburan', 'Basilan - Ungkaya Pukan'=> 'Ungkaya Pukan' ], 'Bataan'=> [ 'Bataan'=> 'Entire Bataan', 'Bataan - Abucay'=> 'Abucay', 'Bataan - Bagac'=> 'Bagac', 'Bataan - Balanga City'=> 'Balanga City', 'Bataan - Dinalupihan'=> 'Dinalupihan', 'Bataan - Hermosa'=> 'Hermosa', 'Bataan - Limay'=> 'Limay', 'Bataan - Mariveles'=> 'Mariveles', 'Bataan - Morong'=> 'Morong', 'Bataan - Orani'=> 'Orani', 'Bataan - Orion'=> 'Orion', 'Bataan - Pilar'=> 'Pilar', 'Bataan - Samal'=> 'Samal' ], 'Batanes'=> [ 'Batanes'=> 'Entire Batanes', 'Batanes - Basco'=> 'Basco', 'Batanes - Itbayat'=> 'Itbayat', 'Batanes - Ivana'=> 'Ivana', 'Batanes - Mahatao'=> 'Mahatao', 'Batanes - Sabtang'=> 'Sabtang', 'Batanes - Uyugan'=> 'Uyugan' ], 'Batangas'=> [ 'Batangas'=> 'Entire Batangas', 'Batangas - Agoncillo'=> 'Agoncillo', 'Batangas - Alitagtag'=> 'Alitagtag', 'Batangas - Balayan'=> 'Balayan', 'Batangas - Balete'=> 'Balete', 'Batangas - Batangas City'=> 'Batangas City', 'Batangas - Bauan'=> 'Bauan', 'Batangas - Calaca'=> 'Calaca', 'Batangas - Calatagan'=> 'Calatagan', 'Batangas - Cuenca'=> 'Cuenca', 'Batangas - Ibaan'=> 'Ibaan', 'Batangas - Laurel'=> 'Laurel', 'Batangas - Lemery'=> 'Lemery', 'Batangas - Lian'=> 'Lian', 'Batangas - Lipa City'=> 'Lipa City', 'Batangas - Lobo'=> 'Lobo', 'Batangas - Mabini'=> 'Mabini', 'Batangas - Malvar'=> 'Malvar', 'Batangas - Mataas Na Kahoy'=> 'Mataas Na Kahoy', 'Batangas - Nasugbu'=> 'Nasugbu', 'Batangas - Padre Garcia'=> 'Padre Garcia', 'Batangas - Rosario'=> 'Rosario', 'Batangas - San Jose'=> 'San Jose', 'Batangas - San Juan'=> 'San Juan', 'Batangas - San Luis'=> 'San Luis', 'Batangas - San Nicolas'=> 'San Nicolas', 'Batangas - San Pascual'=> 'San Pascual', 'Batangas - Santa Teresita'=> 'Santa Teresita', 'Batangas - Santo Tomas'=> 'Santo Tomas', 'Batangas - Taal'=> 'Taal', 'Batangas - Talisay'=> 'Talisay', 'Batangas - Tanauan City'=> 'Tanauan City', 'Batangas - Taysan'=> 'Taysan', 'Batangas - Tingloy'=> 'Tingloy', 'Batangas - Tuy'=> 'Tuy' ], 'Benguet'=> [ 'Benguet'=> 'Entire Benguet', 'Benguet - Atok'=> 'Atok', 'Benguet - Baguio City'=> 'Baguio City', 'Benguet - Bakun'=> 'Bakun', 'Benguet - Bokod'=> 'Bokod', 'Benguet - Buguias'=> 'Buguias', 'Benguet - Itogon'=> 'Itogon', 'Benguet - Kabayan'=> 'Kabayan', 'Benguet - Kapangan'=> 'Kapangan', 'Benguet - Kibungan'=> 'Kibungan', 'Benguet - La Trinidad'=> 'La Trinidad', 'Benguet - Mankayan'=> 'Mankayan', 'Benguet - Sablan'=> 'Sablan', 'Benguet - Tuba'=> 'Tuba', 'Benguet - Tublay'=> 'Tublay' ], 'Biliran'=> [ 'Biliran'=> 'Entire Biliran', 'Biliran - Almeria'=> 'Almeria', 'Biliran - Biliran'=> 'Biliran', 'Biliran - Cabucgayan'=> 'Cabucgayan', 'Biliran - Caibiran'=> 'Caibiran', 'Biliran - Culaba'=> 'Culaba', 'Biliran - Kawayan'=> 'Kawayan', 'Biliran - Maripipi'=> 'Maripipi', 'Biliran - Naval'=> 'Naval' ], 'Bohol'=> [ 'Bohol'=> 'Entire Bohol', 'Bohol - Albuquerque'=> 'Albuquerque', 'Bohol - Alicia'=> 'Alicia', 'Bohol - Anda'=> 'Anda', 'Bohol - Antequera'=> 'Antequera', 'Bohol - Baclayon'=> 'Baclayon', 'Bohol - Balilihan'=> 'Balilihan', 'Bohol - Batuan'=> 'Batuan', 'Bohol - Bien Unido'=> 'Bien Unido', 'Bohol - Bilar'=> 'Bilar', 'Bohol - Buenavista'=> 'Buenavista', 'Bohol - Calape'=> 'Calape', 'Bohol - Candijay'=> 'Candijay', 'Bohol - Carmen'=> 'Carmen', 'Bohol - Catigbian'=> 'Catigbian', 'Bohol - Clarin'=> 'Clarin', 'Bohol - Corella'=> 'Corella', 'Bohol - Cortes'=> 'Cortes', 'Bohol - Dagohoy'=> 'Dagohoy', 'Bohol - Danao'=> 'Danao', 'Bohol - Dauis'=> 'Dauis', 'Bohol - Dimiao'=> 'Dimiao', 'Bohol - Duero'=> 'Duero', 'Bohol - Garcia Hernandez'=> 'Garcia Hernandez', 'Bohol - Getafe (Jetafe)'=> 'Getafe (Jetafe)', 'Bohol - Guindulman'=> 'Guindulman', 'Bohol - Inabanga'=> 'Inabanga', 'Bohol - Jagna'=> 'Jagna', 'Bohol - Lila'=> 'Lila', 'Bohol - Loay'=> 'Loay', 'Bohol - Loboc'=> 'Loboc', 'Bohol - Loon'=> 'Loon', 'Bohol - Mabini'=> 'Mabini', 'Bohol - Maribojoc'=> 'Maribojoc', 'Bohol - Panglao'=> 'Panglao', 'Bohol - Pilar'=> 'Pilar', 'Bohol - Pres. Carlos P. Garcia (Pitogo)'=> 'Pres. Carlos P. Garcia (Pitogo)', 'Bohol - Sagbayan (Borja)'=> 'Sagbayan (Borja)', 'Bohol - San Isidro'=> 'San Isidro', 'Bohol - San Miguel'=> 'San Miguel', 'Bohol - Sevilla'=> 'Sevilla', 'Bohol - Sierra Bullones'=> 'Sierra Bullones', 'Bohol - Sikatuna'=> 'Sikatuna', 'Bohol - Tagbilaran City'=> 'Tagbilaran City', 'Bohol - Talibon'=> 'Talibon', 'Bohol - Trinidad'=> 'Trinidad', 'Bohol - Tubigon'=> 'Tubigon', 'Bohol - Ubay'=> 'Ubay', 'Bohol - Valencia'=> 'Valencia' ], 'Bukidnon'=> [ 'Bukidnon'=> 'Entire Bukidnon', 'Bukidnon - Baungon'=> 'Baungon', 'Bukidnon - Cabanglasan'=> 'Cabanglasan', 'Bukidnon - Damulog'=> 'Damulog', 'Bukidnon - Dangcagan'=> 'Dangcagan', 'Bukidnon - Don Carlos'=> 'Don Carlos', 'Bukidnon - Impasug-Ong'=> 'Impasug-Ong', 'Bukidnon - Kadingilan'=> 'Kadingilan', 'Bukidnon - Kalilangan'=> 'Kalilangan', 'Bukidnon - Kibawe'=> 'Kibawe', 'Bukidnon - Kitaotao'=> 'Kitaotao', 'Bukidnon - Lantapan'=> 'Lantapan', 'Bukidnon - Libona'=> 'Libona', 'Bukidnon - Malaybalay City'=> 'Malaybalay City', 'Bukidnon - Malitbog'=> 'Malitbog', 'Bukidnon - Manolo Fortich'=> 'Manolo Fortich', 'Bukidnon - Maramag'=> 'Maramag', 'Bukidnon - Pangantucan'=> 'Pangantucan', 'Bukidnon - Quezon'=> 'Quezon', 'Bukidnon - San Fernando'=> 'San Fernando', 'Bukidnon - Sumilao'=> 'Sumilao', 'Bukidnon - Talakag'=> 'Talakag', 'Bukidnon - Valencia City'=> 'Valencia City' ], 'Bulacan'=> [ 'Bulacan'=> 'Entire Bulacan', 'Bulacan - Angat'=> 'Angat', 'Bulacan - Balagtas (Bigaa)'=> 'Balagtas (Bigaa)', 'Bulacan - Baliuag'=> 'Baliuag', 'Bulacan - Bocaue'=> 'Bocaue', 'Bulacan - Bulacan'=> 'Bulacan', 'Bulacan - Bustos'=> 'Bustos', 'Bulacan - Calumpit'=> 'Calumpit', 'Bulacan - Dona Remedios Trinidad'=> 'Dona Remedios Trinidad', 'Bulacan - Guiguinto'=> 'Guiguinto', 'Bulacan - Hagonoy'=> 'Hagonoy', 'Bulacan - Malolos City'=> 'Malolos City', 'Bulacan - Marilao'=> 'Marilao', 'Bulacan - Meycauayan City'=> 'Meycauayan City', 'Bulacan - Norzagaray'=> 'Norzagaray', 'Bulacan - Obando'=> 'Obando', 'Bulacan - Pandi'=> 'Pandi', 'Bulacan - Paombong'=> 'Paombong', 'Bulacan - Plaridel'=> 'Plaridel', 'Bulacan - Pulilan'=> 'Pulilan', 'Bulacan - San Ildefonso'=> 'San Ildefonso', 'Bulacan - San Jose Del Monte City'=> 'San Jose Del Monte City', 'Bulacan - San Miguel'=> 'San Miguel', 'Bulacan - San Rafael'=> 'San Rafael', 'Bulacan - Santa Maria'=> 'Santa Maria' ], 'Cagayan'=> [ 'Cagayan'=> 'Entire Cagayan', 'Cagayan - Abulug'=> 'Abulug', 'Cagayan - Alcala'=> 'Alcala', 'Cagayan - Allacapan'=> 'Allacapan', 'Cagayan - Amulung'=> 'Amulung', 'Cagayan - Aparri'=> 'Aparri', 'Cagayan - Baggao'=> 'Baggao', 'Cagayan - Ballesteros'=> 'Ballesteros', 'Cagayan - Buguey'=> 'Buguey', 'Cagayan - Calayan'=> 'Calayan', 'Cagayan - Camalaniugan'=> 'Camalaniugan', 'Cagayan - Claveria'=> 'Claveria', 'Cagayan - Enrile'=> 'Enrile', 'Cagayan - Gattaran'=> 'Gattaran', 'Cagayan - Gonzaga'=> 'Gonzaga', 'Cagayan - Iguig'=> 'Iguig', 'Cagayan - Lal-Lo'=> 'Lal-Lo', 'Cagayan - Lasam'=> 'Lasam', 'Cagayan - Pamplona'=> 'Pamplona', 'Cagayan - Penablanca'=> 'Penablanca', 'Cagayan - Piat'=> 'Piat', 'Cagayan - Rizal'=> 'Rizal', 'Cagayan - Sanchez-Mira'=> 'Sanchez-Mira', 'Cagayan - Santa Ana'=> 'Santa Ana', 'Cagayan - Santa Praxedes'=> 'Santa Praxedes', 'Cagayan - Santa Teresita'=> 'Santa Teresita', 'Cagayan - Santo Nino (Faire)'=> 'Santo Nino (Faire)', 'Cagayan - Solana'=> 'Solana', 'Cagayan - Tuao'=> 'Tuao', 'Cagayan - Tuguegarao City'=> 'Tuguegarao City' ], 'Camarines Norte'=> [ 'Camarines Norte'=> 'Entire Camarines Norte', 'Camarines Norte - Basud'=> 'Basud', 'Camarines Norte - Capalonga'=> 'Capalonga', 'Camarines Norte - Daet'=> 'Daet', 'Camarines Norte - Jose Panganiban'=> 'Jose Panganiban', 'Camarines Norte - Labo'=> 'Labo', 'Camarines Norte - Mercedes'=> 'Mercedes', 'Camarines Norte - Paracale'=> 'Paracale', 'Camarines Norte - San Lorenzo Ruiz (Imelda)'=> 'San Lorenzo Ruiz (Imelda)', 'Camarines Norte - San Vicente'=> 'San Vicente', 'Camarines Norte - Santa Elena'=> 'Santa Elena', 'Camarines Norte - Talisay'=> 'Talisay', 'Camarines Norte - Vinzons'=> 'Vinzons' ], 'Camarines Sur'=> [ 'Camarines Sur'=> 'Entire Camarines Sur', 'Camarines Sur - Baao'=> 'Baao', 'Camarines Sur - Balatan'=> 'Balatan', 'Camarines Sur - Bato'=> 'Bato', 'Camarines Sur - Bombon'=> 'Bombon', 'Camarines Sur - Buhi'=> 'Buhi', 'Camarines Sur - Bula'=> 'Bula', 'Camarines Sur - Cabusao'=> 'Cabusao', 'Camarines Sur - Calabanga'=> 'Calabanga', 'Camarines Sur - Camaligan'=> 'Camaligan', 'Camarines Sur - Canaman'=> 'Canaman', 'Camarines Sur - Caramoan'=> 'Caramoan', 'Camarines Sur - Del Gallego'=> 'Del Gallego', 'Camarines Sur - Gainza'=> 'Gainza', 'Camarines Sur - Goa'=> 'Goa', 'Camarines Sur - Iriga City'=> 'Iriga City', 'Camarines Sur - Lagonoy'=> 'Lagonoy', 'Camarines Sur - Libmanan'=> 'Libmanan', 'Camarines Sur - Lupi'=> 'Lupi', 'Camarines Sur - Magarao'=> 'Magarao', 'Camarines Sur - Milaor'=> 'Milaor', 'Camarines Sur - Minalabac'=> 'Minalabac', 'Camarines Sur - Nabua'=> 'Nabua', 'Camarines Sur - Naga City'=> 'Naga City', 'Camarines Sur - Ocampo'=> 'Ocampo', 'Camarines Sur - Pamplona'=> 'Pamplona', 'Camarines Sur - Pasacao'=> 'Pasacao', 'Camarines Sur - Pili'=> 'Pili', 'Camarines Sur - Presentacion (Parubcan)'=> 'Presentacion (Parubcan)', 'Camarines Sur - Ragay'=> 'Ragay', 'Camarines Sur - Sagnay'=> 'Sagnay', 'Camarines Sur - San Fernando'=> 'San Fernando', 'Camarines Sur - San Jose'=> 'San Jose', 'Camarines Sur - Sipocot'=> 'Sipocot', 'Camarines Sur - Siruma'=> 'Siruma', 'Camarines Sur - Tigaon'=> 'Tigaon', 'Camarines Sur - Tinambac'=> 'Tinambac' ], 'Camiguin'=> [ 'Camiguin'=> 'Entire Camiguin', 'Camiguin - Catarman'=> 'Catarman', 'Camiguin - Guinsiliban'=> 'Guinsiliban', 'Camiguin - Mahinog'=> 'Mahinog', 'Camiguin - Mambajao'=> 'Mambajao', 'Camiguin - Sagay'=> 'Sagay' ], 'Capiz'=> [ 'Capiz'=> 'Entire Capiz', 'Capiz - Cuartero'=> 'Cuartero', 'Capiz - Dao'=> 'Dao', 'Capiz - Dumalag'=> 'Dumalag', 'Capiz - Dumarao'=> 'Dumarao', 'Capiz - Ivisan'=> 'Ivisan', 'Capiz - Jamindan'=> 'Jamindan', 'Capiz - Ma-Ayon'=> 'Ma-Ayon', 'Capiz - Mambusao'=> 'Mambusao', 'Capiz - Panay'=> 'Panay', 'Capiz - Panitan'=> 'Panitan', 'Capiz - Pilar'=> 'Pilar', 'Capiz - Pontevedra'=> 'Pontevedra', 'Capiz - President Roxas'=> 'President Roxas', 'Capiz - Roxas City'=> 'Roxas City', 'Capiz - Sapi-An'=> 'Sapi-An', 'Capiz - Sigma'=> 'Sigma', 'Capiz - Tapaz'=> 'Tapaz' ], 'Catanduanes'=> [ 'Catanduanes'=> 'Entire Catanduanes', 'Catanduanes - Bagamanoc'=> 'Bagamanoc', 'Catanduanes - Baras'=> 'Baras', 'Catanduanes - Bato'=> 'Bato', 'Catanduanes - Caramoran'=> 'Caramoran', 'Catanduanes - Gigmoto'=> 'Gigmoto', 'Catanduanes - Pandan'=> 'Pandan', 'Catanduanes - Panganiban (Payo)'=> 'Panganiban (Payo)', 'Catanduanes - San Andres (Calolbon)'=> 'San Andres (Calolbon)', 'Catanduanes - San Miguel'=> 'San Miguel', 'Catanduanes - Viga'=> 'Viga', 'Catanduanes - Virac'=> 'Virac' ], 'Cavite'=> [ 'Cavite'=> 'Entire Cavite', 'Cavite - Alfonso'=> 'Alfonso', 'Cavite - Amadeo'=> 'Amadeo', 'Cavite - Bacoor'=> 'Bacoor', 'Cavite - Carmona'=> 'Carmona', 'Cavite - Cavite City'=> 'Cavite City', 'Cavite - Dasmarinas'=> 'Dasmarinas', 'Cavite - Gen. Mariano Alvarez'=> 'Gen. Mariano Alvarez', 'Cavite - Gen. Emilio Aguinaldo'=> 'Gen. Emilio Aguinaldo', 'Cavite - Gen. Trias'=> 'Gen. Trias', 'Cavite - Imus'=> 'Imus', 'Cavite - Indang'=> 'Indang', 'Cavite - Kawit'=> 'Kawit', 'Cavite - Magallanes'=> 'Magallanes', 'Cavite - Maragondon'=> 'Maragondon', 'Cavite - Mendez (Mendez-Nunez)'=> 'Mendez (Mendez-Nunez)', 'Cavite - Naic'=> 'Naic', 'Cavite - Noveleta'=> 'Noveleta', 'Cavite - Rosario'=> 'Rosario', 'Cavite - Silang'=> 'Silang', 'Cavite - Tagaytay City'=> 'Tagaytay City', 'Cavite - Tanza'=> 'Tanza', 'Cavite - Ternate'=> 'Ternate', 'Cavite - Trece Martires City'=> 'Trece Martires City' ], 'Cebu'=> [ 'Cebu'=> 'Entire Cebu', 'Cebu - Alcantara'=> 'Alcantara', 'Cebu - Alcoy'=> 'Alcoy', 'Cebu - Alegria'=> 'Alegria', 'Cebu - Aloguinsa'=> 'Aloguinsa', 'Cebu - Argao'=> 'Argao', 'Cebu - Asturias'=> 'Asturias', 'Cebu - Badian'=> 'Badian', 'Cebu - Balamban'=> 'Balamban', 'Cebu - Bantayan'=> 'Bantayan', 'Cebu - Barili'=> 'Barili', 'Cebu - Bogo'=> 'Bogo', 'Cebu - Boljoon'=> 'Boljoon', 'Cebu - Borbon'=> 'Borbon', 'Cebu - Carcar'=> 'Carcar', 'Cebu - Carmen'=> 'Carmen', 'Cebu - Catmon'=> 'Catmon', 'Cebu - Cebu City'=> 'Cebu City', 'Cebu - Compostela'=> 'Compostela', 'Cebu - Consolacion'=> 'Consolacion', 'Cebu - Cordova'=> 'Cordova', 'Cebu - Daanbantayan'=> 'Daanbantayan', 'Cebu - Dalaguete'=> 'Dalaguete', 'Cebu - Danao City'=> 'Danao City', 'Cebu - Dumanjug'=> 'Dumanjug', 'Cebu - Ginatilan'=> 'Ginatilan', 'Cebu - Lapu-Lapu City'=> 'Lapu-Lapu City', 'Cebu - Liloan'=> 'Liloan', 'Cebu - Madridejos'=> 'Madridejos', 'Cebu - Malabuyoc'=> 'Malabuyoc', 'Cebu - Mandaue City'=> 'Mandaue City', 'Cebu - Medellin'=> 'Medellin', 'Cebu - Minglanilla'=> 'Minglanilla', 'Cebu - Moalboal'=> 'Moalboal', 'Cebu - Naga'=> 'Naga', 'Cebu - Oslob'=> 'Oslob', 'Cebu - Pilar'=> 'Pilar', 'Cebu - Pinamungahan'=> 'Pinamungahan', 'Cebu - Poro'=> 'Poro', 'Cebu - Ronda'=> 'Ronda', 'Cebu - Samboan'=> 'Samboan', 'Cebu - San Fernando'=> 'San Fernando', 'Cebu - San Francisco'=> 'San Francisco', 'Cebu - San Remigio'=> 'San Remigio', 'Cebu - Santa Fe'=> 'Santa Fe', 'Cebu - Santander'=> 'Santander', 'Cebu - Sibonga'=> 'Sibonga', 'Cebu - Sogod'=> 'Sogod', 'Cebu - Tabogon'=> 'Tabogon', 'Cebu - Tabuelan'=> 'Tabuelan', 'Cebu - Talisay City'=> 'Talisay City', 'Cebu - Toledo City'=> 'Toledo City', 'Cebu - Tuburan'=> 'Tuburan', 'Cebu - Tudela'=> 'Tudela' ], 'Compostela Valley'=> [ 'Compostela Valley'=> 'Entire Compostela Valley', 'Compostela Valley - Compostela'=> 'Compostela', 'Compostela Valley - Laak (San Vicente)'=> 'Laak (San Vicente)', 'Compostela Valley - Mabini (Dona Alicia)'=> 'Mabini (Dona Alicia)', 'Compostela Valley - Maco'=> 'Maco', 'Compostela Valley - Maragusan (San Mariano)'=> 'Maragusan (San Mariano)', 'Compostela Valley - Mawab'=> 'Mawab', 'Compostela Valley - Monkayo'=> 'Monkayo', 'Compostela Valley - Montevista'=> 'Montevista', 'Compostela Valley - Nabunturan'=> 'Nabunturan', 'Compostela Valley - New Bataan'=> 'New Bataan', 'Compostela Valley - Pantukan'=> 'Pantukan' ], 'Cotabato'=> [ 'Cotabato'=> 'Entire Cotabato', 'Cotabato - Alamada'=> 'Alamada', 'Cotabato - Aleosan'=> 'Aleosan', 'Cotabato - Antipas'=> 'Antipas', 'Cotabato - Arakan'=> 'Arakan', 'Cotabato - Banisilan'=> 'Banisilan', 'Cotabato - Carmen'=> 'Carmen', 'Cotabato - Kabacan'=> 'Kabacan', 'Cotabato - Kidapawan City'=> 'Kidapawan City', 'Cotabato - Libungan'=> 'Libungan', 'Cotabato - Magpet'=> 'Magpet', 'Cotabato - Makilala'=> 'Makilala', 'Cotabato - Matalam'=> 'Matalam', 'Cotabato - Midsayap'=> 'Midsayap', 'Cotabato - M\'Lang'=> 'M\'Lang', 'Cotabato - Pigkawayan'=> 'Pigkawayan', 'Cotabato - Pikit'=> 'Pikit', 'Cotabato - President Roxas'=> 'President Roxas', 'Cotabato - Tulunan'=> 'Tulunan' ], 'Davao del Norte'=> [ 'Davao del Norte'=> 'Entire Davao del Norte', 'Davao del Norte - Asuncion (Saug)'=> 'Asuncion (Saug)', 'Davao del Norte - Braulio E. Dujali'=> 'Braulio E. Dujali', 'Davao del Norte - Carmen'=> 'Carmen', 'Davao del Norte - Kapalong'=> 'Kapalong', 'Davao del Norte - New Corella'=> 'New Corella', 'Davao del Norte - Panabo City'=> 'Panabo City', 'Davao del Norte - Samal Island Garden City'=> 'Samal Island Garden City', 'Davao del Norte - San Isidro'=> 'San Isidro', 'Davao del Norte - Santo Tomas'=> 'Santo Tomas', 'Davao del Norte - Tagum City'=> 'Tagum City', 'Davao del Norte - Talaingod'=> 'Talaingod' ], 'Davao del Sur'=> [ 'Davao del Sur'=> 'Entire Davao del Sur', 'Davao del Sur - Bansalan'=> 'Bansalan', 'Davao del Sur - Davao City'=> 'Davao City', 'Davao del Sur - Digos City'=> 'Digos City', 'Davao del Sur - Don Marcelino'=> 'Don Marcelino', 'Davao del Sur - Hagonoy'=> 'Hagonoy', 'Davao del Sur - Jose Abad Santos (Trinidad)'=> 'Jose Abad Santos (Trinidad)', 'Davao del Sur - Kiblawan'=> 'Kiblawan', 'Davao del Sur - Magsaysay'=> 'Magsaysay', 'Davao del Sur - Malalag'=> 'Malalag', 'Davao del Sur - Malita'=> 'Malita', 'Davao del Sur - Matanao'=> 'Matanao', 'Davao del Sur - Padada'=> 'Padada', 'Davao del Sur - Santa Cruz'=> 'Santa Cruz', 'Davao del Sur - Santa Maria'=> 'Santa Maria', 'Davao del Sur - Sarangani'=> 'Sarangani', 'Davao del Sur - Sulop'=> 'Sulop' ], 'Davao Oriental'=> [ 'Davao Oriental'=> 'Entire Davao Oriental', 'Davao Oriental - Baganga'=> 'Baganga', 'Davao Oriental - Banaybanay'=> 'Banaybanay', 'Davao Oriental - Boston'=> 'Boston', 'Davao Oriental - Caraga'=> 'Caraga', 'Davao Oriental - Cateel'=> 'Cateel', 'Davao Oriental - Governor Geneloso'=> 'Governor Geneloso', 'Davao Oriental - Lupon'=> 'Lupon', 'Davao Oriental - Manay'=> 'Manay', 'Davao Oriental - Mati City'=> 'Mati City', 'Davao Oriental - San Isidro'=> 'San Isidro', 'Davao Oriental - Tarragona'=> 'Tarragona' ], 'Dinagat Islands'=> [ 'Dinagat Islands'=> 'Entire Dinagat Islands', 'Dinagat Islands - Basilisa (Rizal)'=> 'Basilisa (Rizal)', 'Dinagat Islands - Cagdianao'=> 'Cagdianao', 'Dinagat Islands - Dinagat'=> 'Dinagat', 'Dinagat Islands - Libjo (Abjor)'=> 'Libjo (Abjor)', 'Dinagat Islands - Loreto'=> 'Loreto', 'Dinagat Islands - San Jose'=> 'San Jose', 'Dinagat Islands - Tubajon'=> 'Tubajon' ], 'Eastern Samar'=> [ 'Eastern Samar'=> 'Entire Eastern Samar', 'Eastern Samar - Arteche'=> 'Arteche', 'Eastern Samar - Balangiga'=> 'Balangiga', 'Eastern Samar - Balangkayan'=> 'Balangkayan', 'Eastern Samar - Borongan City'=> 'Borongan City', 'Eastern Samar - Can-Avid'=> 'Can-Avid', 'Eastern Samar - Dolores'=> 'Dolores', 'Eastern Samar - General MacArthur'=> 'General MacArthur', 'Eastern Samar - Giporlos'=> 'Giporlos', 'Eastern Samar - Guiuan'=> 'Guiuan', 'Eastern Samar - Hernani'=> 'Hernani', 'Eastern Samar - Jipapad'=> 'Jipapad', 'Eastern Samar - Lawaan'=> 'Lawaan', 'Eastern Samar - Llorente'=> 'Llorente', 'Eastern Samar - Maslog'=> 'Maslog', 'Eastern Samar - Maydolong'=> 'Maydolong', 'Eastern Samar - Mercedes'=> 'Mercedes', 'Eastern Samar - Oras'=> 'Oras', 'Eastern Samar - Quinapondan'=> 'Quinapondan', 'Eastern Samar - Salcedo'=> 'Salcedo', 'Eastern Samar - San Julian'=> 'San Julian', 'Eastern Samar - San Policarpo'=> 'San Policarpo', 'Eastern Samar - Sulat'=> 'Sulat', 'Eastern Samar - Taft'=> 'Taft' ], 'Guimaras'=> [ 'Guimaras'=> 'Entire Guimaras', 'Guimaras - Buenavista'=> 'Buenavista', 'Guimaras - Jordan'=> 'Jordan', 'Guimaras - Nueva Valencia'=> 'Nueva Valencia', 'Guimaras - San Lorenzo'=> 'San Lorenzo', 'Guimaras - Sibunag'=> 'Sibunag' ], 'Ifugao'=> [ 'Ifugao'=> 'Entire Ifugao', 'Ifugao - Aguinaldo'=> 'Aguinaldo', 'Ifugao - Alfonso Lista (Potia)'=> 'Alfonso Lista (Potia)', 'Ifugao - Asipulo'=> 'Asipulo', 'Ifugao - Banaue'=> 'Banaue', 'Ifugao - Hingyon'=> 'Hingyon', 'Ifugao - Hungduan'=> 'Hungduan', 'Ifugao - Kiangan'=> 'Kiangan', 'Ifugao - Lagawe'=> 'Lagawe', 'Ifugao - Lamut'=> 'Lamut', 'Ifugao - Mayoyao'=> 'Mayoyao', 'Ifugao - Tinoc'=> 'Tinoc' ], 'Ilocos Norte'=> [ 'Ilocos Norte'=> 'Entire Ilocos Norte', 'Ilocos Norte - Adams'=> 'Adams', 'Ilocos Norte - Bacarra'=> 'Bacarra', 'Ilocos Norte - Badoc'=> 'Badoc', 'Ilocos Norte - Bangui'=> 'Bangui', 'Ilocos Norte - Banna (Espiritu)'=> 'Banna (Espiritu)', 'Ilocos Norte - Batac City'=> 'Batac City', 'Ilocos Norte - Burgos'=> 'Burgos', 'Ilocos Norte - Carasi'=> 'Carasi', 'Ilocos Norte - Currimao'=> 'Currimao', 'Ilocos Norte - Dingras'=> 'Dingras', 'Ilocos Norte - Dumalneg'=> 'Dumalneg', 'Ilocos Norte - Laoag City'=> 'Laoag City', 'Ilocos Norte - Marcos'=> 'Marcos', 'Ilocos Norte - Nueva Era'=> 'Nueva Era', 'Ilocos Norte - Pagudpud'=> 'Pagudpud', 'Ilocos Norte - Paoay'=> 'Paoay', 'Ilocos Norte - Pasuquin'=> 'Pasuquin', 'Ilocos Norte - Piddig'=> 'Piddig', 'Ilocos Norte - Pinili'=> 'Pinili', 'Ilocos Norte - San Nicolas'=> 'San Nicolas', 'Ilocos Norte - Sarrat'=> 'Sarrat', 'Ilocos Norte - Solsona'=> 'Solsona', 'Ilocos Norte - Vintar'=> 'Vintar' ], 'Ilocos Sur'=> [ 'Ilocos Sur'=> 'Entire Ilocos Sur', 'Ilocos Sur - Alilem'=> 'Alilem', 'Ilocos Sur - Banayoyo'=> 'Banayoyo', 'Ilocos Sur - Bantay'=> 'Bantay', 'Ilocos Sur - Burgos'=> 'Burgos', 'Ilocos Sur - Cabugao'=> 'Cabugao', 'Ilocos Sur - Candon City'=> 'Candon City', 'Ilocos Sur - Caoayan'=> 'Caoayan', 'Ilocos Sur - Cervantes'=> 'Cervantes', 'Ilocos Sur - Galimuyod'=> 'Galimuyod', 'Ilocos Sur - Gregorio Del Pilar (Concepcion)'=> 'Gregorio Del Pilar (Concepcion)', 'Ilocos Sur - Lidlidda'=> 'Lidlidda', 'Ilocos Sur - Magsingal'=> 'Magsingal', 'Ilocos Sur - Nagbukel'=> 'Nagbukel', 'Ilocos Sur - Narvacan'=> 'Narvacan', 'Ilocos Sur - Quirino (Angkaki)'=> 'Quirino (Angkaki)', 'Ilocos Sur - Salcedo (Baugen)'=> 'Salcedo (Baugen)', 'Ilocos Sur - San Emilio'=> 'San Emilio', 'Ilocos Sur - San Esteban'=> 'San Esteban', 'Ilocos Sur - San Ildefonso'=> 'San Ildefonso', 'Ilocos Sur - San Juan (Lapog)'=> 'San Juan (Lapog)', 'Ilocos Sur - San Vicente'=> 'San Vicente', 'Ilocos Sur - Santa'=> 'Santa', 'Ilocos Sur - Santa Catalina'=> 'Santa Catalina', 'Ilocos Sur - Santa Cruz'=> 'Santa Cruz', 'Ilocos Sur - Santa Lucia'=> 'Santa Lucia', 'Ilocos Sur - Santa Maria'=> 'Santa Maria', 'Ilocos Sur - Santiago'=> 'Santiago', 'Ilocos Sur - Santo Domingo'=> 'Santo Domingo', 'Ilocos Sur - Sigay'=> 'Sigay', 'Ilocos Sur - Sinait'=> 'Sinait', 'Ilocos Sur - Sugpon'=> 'Sugpon', 'Ilocos Sur - Suyo'=> 'Suyo', 'Ilocos Sur - Tagudin'=> 'Tagudin', 'Ilocos Sur - Vigan City'=> 'Vigan City' ], 'Iloilo'=> [ 'Iloilo'=> 'Entire Iloilo', 'Iloilo - Ajuy'=> 'Ajuy', 'Iloilo - Alimodian'=> 'Alimodian', 'Iloilo - Anilao'=> 'Anilao', 'Iloilo - Badiangan'=> 'Badiangan', 'Iloilo - Balasan'=> 'Balasan', 'Iloilo - Banate'=> 'Banate', 'Iloilo - Barotac Nuevo'=> 'Barotac Nuevo', 'Iloilo - Barotac Viejo'=> 'Barotac Viejo', 'Iloilo - Batad'=> 'Batad', 'Iloilo - Bingawan'=> 'Bingawan', 'Iloilo - Cabatuan'=> 'Cabatuan', 'Iloilo - Calinog'=> 'Calinog', 'Iloilo - Carles'=> 'Carles', 'Iloilo - Concepcion'=> 'Concepcion', 'Iloilo - Dingle'=> 'Dingle', 'Iloilo - Duenas'=> 'Duenas', 'Iloilo - Dumangas'=> 'Dumangas', 'Iloilo - Estancia'=> 'Estancia', 'Iloilo - Guimbal'=> 'Guimbal', 'Iloilo - Igbaras'=> 'Igbaras', 'Iloilo - Iloilo City'=> 'Iloilo City', 'Iloilo - Janiuay'=> 'Janiuay', 'Iloilo - Lambunao'=> 'Lambunao', 'Iloilo - Leganes'=> 'Leganes', 'Iloilo - Lemery'=> 'Lemery', 'Iloilo - Leon'=> 'Leon', 'Iloilo - Maasin'=> 'Maasin', 'Iloilo - Miagao'=> 'Miagao', 'Iloilo - Mina'=> 'Mina', 'Iloilo - New Lucena'=> 'New Lucena', 'Iloilo - Oton'=> 'Oton', 'Iloilo - Passi City'=> 'Passi City', 'Iloilo - Pavia'=> 'Pavia', 'Iloilo - Pototan'=> 'Pototan', 'Iloilo - San Dionisio'=> 'San Dionisio', 'Iloilo - San Enrique'=> 'San Enrique', 'Iloilo - San Joaquin'=> 'San Joaquin', 'Iloilo - San Miguel'=> 'San Miguel', 'Iloilo - San Rafael'=> 'San Rafael', 'Iloilo - Santa Barbara'=> 'Santa Barbara', 'Iloilo - Sara'=> 'Sara', 'Iloilo - Tigbauan'=> 'Tigbauan', 'Iloilo - Tubungan'=> 'Tubungan', 'Iloilo - Zarraga'=> 'Zarraga' ], 'Isabela'=> [ 'Isabela'=> 'Entire Isabela', 'Isabela - Alicia'=> 'Alicia', 'Isabela - Angadanan'=> 'Angadanan', 'Isabela - Aurora'=> 'Aurora', 'Isabela - Benito Soliven'=> 'Benito Soliven', 'Isabela - Burgos'=> 'Burgos', 'Isabela - Cabagan'=> 'Cabagan', 'Isabela - Cabatuan'=> 'Cabatuan', 'Isabela - Cauayan City'=> 'Cauayan City', 'Isabela - Cordon'=> 'Cordon', 'Isabela - Delfin Albano (Magsaysay)'=> 'Delfin Albano (Magsaysay)', 'Isabela - Dinapigue'=> 'Dinapigue', 'Isabela - Divilican'=> 'Divilican', 'Isabela - Echague'=> 'Echague', 'Isabela - Gamu'=> 'Gamu', 'Isabela - Ilagan'=> 'Ilagan', 'Isabela - Jones'=> 'Jones', 'Isabela - Luna'=> 'Luna', 'Isabela - Maconacon'=> 'Maconacon', 'Isabela - Mallig'=> 'Mallig', 'Isabela - Naguilian'=> 'Naguilian', 'Isabela - Palanan'=> 'Palanan', 'Isabela - Quezon'=> 'Quezon', 'Isabela - Quirino'=> 'Quirino', 'Isabela - Ramon'=> 'Ramon', 'Isabela - Reina Mercedes'=> 'Reina Mercedes', 'Isabela - Roxas'=> 'Roxas', 'Isabela - San Agustin'=> 'San Agustin', 'Isabela - San Guillermo'=> 'San Guillermo', 'Isabela - San Isidro'=> 'San Isidro', 'Isabela - San Manuel'=> 'San Manuel', 'Isabela - San Mariano'=> 'San Mariano', 'Isabela - San Mateo'=> 'San Mateo', 'Isabela - San Pablo'=> 'San Pablo', 'Isabela - Santa Maria'=> 'Santa Maria', 'Isabela - Santiago City'=> 'Santiago City', 'Isabela - Santo Tomas'=> 'Santo Tomas', 'Isabela - Tumauini'=> 'Tumauini' ], 'Kalinga'=> [ 'Kalinga'=> 'Entire Kalinga', 'Kalinga - Balbalan'=> 'Balbalan', 'Kalinga - Lubuagan'=> 'Lubuagan', 'Kalinga - Pasil'=> 'Pasil', 'Kalinga - Pinukpuk'=> 'Pinukpuk', 'Kalinga - Rizal (Liwan)'=> 'Rizal (Liwan)', 'Kalinga - Tabuk City'=> 'Tabuk City', 'Kalinga - Tanudan'=> 'Tanudan', 'Kalinga - Tinglayan'=> 'Tinglayan' ], 'La Union'=> [ 'La Union'=> 'Entire La Union', 'La Union - Agoo'=> 'Agoo', 'La Union - Aringay'=> 'Aringay', 'La Union - Bacnotan'=> 'Bacnotan', 'La Union - Bagulin'=> 'Bagulin', 'La Union - Balaoan'=> 'Balaoan', 'La Union - Bangar'=> 'Bangar', 'La Union - Bauang'=> 'Bauang', 'La Union - Burgos'=> 'Burgos', 'La Union - Caba'=> 'Caba', 'La Union - Luna'=> 'Luna', 'La Union - Naguilian'=> 'Naguilian', 'La Union - Pugo'=> 'Pugo', 'La Union - Rosario'=> 'Rosario', 'La Union - San Fernando City'=> 'San Fernando City', 'La Union - San Gabriel'=> 'San Gabriel', 'La Union - San Juan'=> 'San Juan', 'La Union - Santo Tomas'=> 'Santo Tomas', 'La Union - Santol'=> 'Santol', 'La Union - Sudipen'=> 'Sudipen', 'La Union - Tubao'=> 'Tubao' ], 'Laguna'=> [ 'Laguna'=> 'Entire Laguna', 'Laguna - Alaminos'=> 'Alaminos', 'Laguna - Bay'=> 'Bay', 'Laguna - Binan'=> 'Binan', 'Laguna - Cabuyao'=> 'Cabuyao', 'Laguna - Calamba City'=> 'Calamba City', 'Laguna - Calauan'=> 'Calauan', 'Laguna - Cavinti'=> 'Cavinti', 'Laguna - Famy'=> 'Famy', 'Laguna - Kalayaan'=> 'Kalayaan', 'Laguna - Liliw'=> 'Liliw', 'Laguna - Los Banos'=> 'Los Banos', 'Laguna - Luisiana'=> 'Luisiana', 'Laguna - Lumban'=> 'Lumban', 'Laguna - Mabitac'=> 'Mabitac', 'Laguna - Magdalena'=> 'Magdalena', 'Laguna - Majayjay'=> 'Majayjay', 'Laguna - Nagcarlan'=> 'Nagcarlan', 'Laguna - Paete'=> 'Paete', 'Laguna - Pagsanjan'=> 'Pagsanjan', 'Laguna - Pakil'=> 'Pakil', 'Laguna - Pangil'=> 'Pangil', 'Laguna - Pila'=> 'Pila', 'Laguna - Rizal'=> 'Rizal', 'Laguna - San Pablo City'=> 'San Pablo City', 'Laguna - San Pedro'=> 'San Pedro', 'Laguna - Santa Cruz'=> 'Santa Cruz', 'Laguna - Santa Maria'=> 'Santa Maria', 'Laguna - Santa Rosa City'=> 'Santa Rosa City', 'Laguna - Siniloan'=> 'Siniloan', 'Laguna - Victoria'=> 'Victoria' ], 'Lanao del Norte'=> [ 'Lanao del Norte'=> 'Entire Lanao del Norte', 'Lanao del Norte - Bacolod'=> 'Bacolod', 'Lanao del Norte - Baloi'=> 'Baloi', 'Lanao del Norte - Baroy'=> 'Baroy', 'Lanao del Norte - Iligan City'=> 'Iligan City', 'Lanao del Norte - Kapatagan'=> 'Kapatagan', 'Lanao del Norte - Kauswagan'=> 'Kauswagan', 'Lanao del Norte - Kolambugan'=> 'Kolambugan', 'Lanao del Norte - Lala'=> 'Lala', 'Lanao del Norte - Linamon'=> 'Linamon', 'Lanao del Norte - Magsaysay'=> 'Magsaysay', 'Lanao del Norte - Maigo'=> 'Maigo', 'Lanao del Norte - Matungao'=> 'Matungao', 'Lanao del Norte - Munai'=> 'Munai', 'Lanao del Norte - Nunungan'=> 'Nunungan', 'Lanao del Norte - Pantao Ragat'=> 'Pantao Ragat', 'Lanao del Norte - Pantar'=> 'Pantar', 'Lanao del Norte - Poona Piagapo'=> 'Poona Piagapo', 'Lanao del Norte - Salvador'=> 'Salvador', 'Lanao del Norte - Sapad'=> 'Sapad', 'Lanao del Norte - Sultan Naga Dimaporo (Karomatan)'=> 'Sultan Naga Dimaporo (Karomatan)', 'Lanao del Norte - Tagoloan'=> 'Tagoloan', 'Lanao del Norte - Tangcal'=> 'Tangcal', 'Lanao del Norte - Tubod City'=> 'Tubod City' ], 'Lanao del Sur'=> [ 'Lanao del Sur'=> 'Entire Lanao del Sur', 'Lanao del Sur - Bacolod-Kalawi (Bacolod-Grande)'=> 'Bacolod-Kalawi (Bacolod-Grande)', 'Lanao del Sur - Balabagan'=> 'Balabagan', 'Lanao del Sur - Balindong (Watu)'=> 'Balindong (Watu)', 'Lanao del Sur - Bayang'=> 'Bayang', 'Lanao del Sur - Binidayan'=> 'Binidayan', 'Lanao del Sur - Buadiposo-Buntong'=> 'Buadiposo-Buntong', 'Lanao del Sur - Bubong'=> 'Bubong', 'Lanao del Sur - Bumbaran'=> 'Bumbaran', 'Lanao del Sur - Butig'=> 'Butig', 'Lanao del Sur - Calanogas'=> 'Calanogas', 'Lanao del Sur - Ditsaan-Ramain'=> 'Ditsaan-Ramain', 'Lanao del Sur - Ganassi'=> 'Ganassi', 'Lanao del Sur - Kapai'=> 'Kapai', 'Lanao del Sur - Kapatagan'=> 'Kapatagan', 'Lanao del Sur - Lumba-Bayabao (Maguing)'=> 'Lumba-Bayabao (Maguing)', 'Lanao del Sur - Lumbaca-Unayan'=> 'Lumbaca-Unayan', 'Lanao del Sur - Lumbatan'=> 'Lumbatan', 'Lanao del Sur - Lumbayanague'=> 'Lumbayanague', 'Lanao del Sur - Madalum'=> 'Madalum', 'Lanao del Sur - Madamba'=> 'Madamba', 'Lanao del Sur - Maguing'=> 'Maguing', 'Lanao del Sur - Malabang'=> 'Malabang', 'Lanao del Sur - Marantao'=> 'Marantao', 'Lanao del Sur - Marawi City'=> 'Marawi City', 'Lanao del Sur - Marogong'=> 'Marogong', 'Lanao del Sur - Masiu'=> 'Masiu', 'Lanao del Sur - Mulondo'=> 'Mulondo', 'Lanao del Sur - Pagayawan (Tatarikan)'=> 'Pagayawan (Tatarikan)', 'Lanao del Sur - Piagapo'=> 'Piagapo', 'Lanao del Sur - Picong (Sultan Gumander)'=> 'Picong (Sultan Gumander)', 'Lanao del Sur - Poona Bayabao (Gata)'=> 'Poona Bayabao (Gata)', 'Lanao del Sur - Pualas'=> 'Pualas', 'Lanao del Sur - Saguiaran'=> 'Saguiaran', 'Lanao del Sur - Sultan Dumalondong'=> 'Sultan Dumalondong', 'Lanao del Sur - Tagoloan li'=> 'Tagoloan li', 'Lanao del Sur - Tamparan'=> 'Tamparan', 'Lanao del Sur - Taraka'=> 'Taraka', 'Lanao del Sur - Tubaran'=> 'Tubaran', 'Lanao del Sur - Tugaya'=> 'Tugaya', 'Lanao del Sur - Wao'=> 'Wao' ], 'Leyte'=> [ 'Leyte'=> 'Entire Leyte', 'Leyte - Abuyog'=> 'Abuyog', 'Leyte - Alangalang'=> 'Alangalang', 'Leyte - Albuera'=> 'Albuera', 'Leyte - Babatngon'=> 'Babatngon', 'Leyte - Barugo'=> 'Barugo', 'Leyte - Bato'=> 'Bato', 'Leyte - Baybay'=> 'Baybay', 'Leyte - Burauen'=> 'Burauen', 'Leyte - Calubian'=> 'Calubian', 'Leyte - Capoocan'=> 'Capoocan', 'Leyte - Carigara'=> 'Carigara', 'Leyte - Dagami'=> 'Dagami', 'Leyte - Dulag'=> 'Dulag', 'Leyte - Hilongos'=> 'Hilongos', 'Leyte - Hindang'=> 'Hindang', 'Leyte - Inopacan'=> 'Inopacan', 'Leyte - Isabel'=> 'Isabel', 'Leyte - Jaro'=> 'Jaro', 'Leyte - Javier (Bugho)'=> 'Javier (Bugho)', 'Leyte - Julita'=> 'Julita', 'Leyte - Kananga'=> 'Kananga', 'Leyte - La Paz'=> 'La Paz', 'Leyte - Leyte'=> 'Leyte', 'Leyte - MacArthur'=> 'MacArthur', 'Leyte - Mahaplag'=> 'Mahaplag', 'Leyte - Matag-Ob'=> 'Matag-Ob', 'Leyte - Matalom'=> 'Matalom', 'Leyte - Mayorga'=> 'Mayorga', 'Leyte - Merida'=> 'Merida', 'Leyte - Ormoc City'=> 'Ormoc City', 'Leyte - Palo'=> 'Palo', 'Leyte - Palompon'=> 'Palompon', 'Leyte - Pastrana'=> 'Pastrana', 'Leyte - San Isidro'=> 'San Isidro', 'Leyte - San Miguel'=> 'San Miguel', 'Leyte - Santa Fe'=> 'Santa Fe', 'Leyte - Tabango'=> 'Tabango', 'Leyte - Tabontabon'=> 'Tabontabon', 'Leyte - Tacloban City'=> 'Tacloban City', 'Leyte - Tanauan'=> 'Tanauan', 'Leyte - Tolosa'=> 'Tolosa', 'Leyte - Tunga'=> 'Tunga', 'Leyte - Villaba'=> 'Villaba' ], 'Maguindanao'=> [ 'Maguindanao'=> 'Entire Maguindanao', 'Maguindanao - Ampatuan'=> 'Ampatuan', 'Maguindanao - Barira'=> 'Barira', 'Maguindanao - Buldon'=> 'Buldon', 'Maguindanao - Buluan'=> 'Buluan', 'Maguindanao - Datu Abdullah Sangki'=> 'Datu Abdullah Sangki', 'Maguindanao - Datu Anggal Midtimbang'=> 'Datu Anggal Midtimbang', 'Maguindanao - Datu Blah T. Sinsuat'=> 'Datu Blah T. Sinsuat', 'Maguindanao - Datu Odin Sinsuat (Dinaig)'=> 'Datu Odin Sinsuat (Dinaig)', 'Maguindanao - Datu Paglas'=> 'Datu Paglas', 'Maguindanao - Datu Piang'=> 'Datu Piang', 'Maguindanao - Datu Saudi-Ampatuan'=> 'Datu Saudi-Ampatuan', 'Maguindanao - Datu Unsay'=> 'Datu Unsay', 'Maguindanao - Gen. S. K. Pendatun'=> 'Gen. S. K. Pendatun', 'Maguindanao - Guindulungan'=> 'Guindulungan', 'Maguindanao - Kabuntalan (Tumbao)'=> 'Kabuntalan (Tumbao)', 'Maguindanao - Mamasapano'=> 'Mamasapano', 'Maguindanao - Mangudadatu'=> 'Mangudadatu', 'Maguindanao - Matanog'=> 'Matanog', 'Maguindanao - Northern Kabuntalan'=> 'Northern Kabuntalan', 'Maguindanao - Pagagawan'=> 'Pagagawan', 'Maguindanao - Pagalungan'=> 'Pagalungan', 'Maguindanao - Paglat'=> 'Paglat', 'Maguindanao - Pandag'=> 'Pandag', 'Maguindanao - Parang'=> 'Parang', 'Maguindanao - Rajah Buayan'=> 'Rajah Buayan', 'Maguindanao - Shariff Aguak (Maganoy)'=> 'Shariff Aguak (Maganoy)', 'Maguindanao - South Upi'=> 'South Upi', 'Maguindanao - Sultan Kudarat (Nuling)'=> 'Sultan Kudarat (Nuling)', 'Maguindanao - Sultan Mastura'=> 'Sultan Mastura', 'Maguindanao - Sultan sa Barongis'=> 'Sultan sa Barongis', 'Maguindanao - Talayan'=> 'Talayan', 'Maguindanao - Talitay'=> 'Talitay', 'Maguindanao - Upi'=> 'Upi' ], 'Marinduque'=> [ 'Marinduque'=> 'Entire Marinduque', 'Marinduque - Boac'=> 'Boac', 'Marinduque - Buenavista'=> 'Buenavista', 'Marinduque - Gasan'=> 'Gasan', 'Marinduque - Mogpog'=> 'Mogpog', 'Marinduque - Santa Cruz'=> 'Santa Cruz', 'Marinduque - Torrijos'=> 'Torrijos' ], 'Masbate'=> [ 'Masbate'=> 'Entire Masbate', 'Masbate - Aroroy'=> 'Aroroy', 'Masbate - Baleno'=> 'Baleno', 'Masbate - Balud'=> 'Balud', 'Masbate - Batuan'=> 'Batuan', 'Masbate - Cataingan'=> 'Cataingan', 'Masbate - Cawayan'=> 'Cawayan', 'Masbate - Claveria'=> 'Claveria', 'Masbate - Dimasalang'=> 'Dimasalang', 'Masbate - Esperanza'=> 'Esperanza', 'Masbate - Mandaon'=> 'Mandaon', 'Masbate - Masbate City'=> 'Masbate City', 'Masbate - Milagros'=> 'Milagros', 'Masbate - Mobo'=> 'Mobo', 'Masbate - Monreal'=> 'Monreal', 'Masbate - Palanas'=> 'Palanas', 'Masbate - Pio V. Corpuz (Limbuhan)'=> 'Pio V. Corpuz (Limbuhan)', 'Masbate - Placer'=> 'Placer', 'Masbate - San Fernando'=> 'San Fernando', 'Masbate - San Jacinto'=> 'San Jacinto', 'Masbate - San Pascual'=> 'San Pascual', 'Masbate - Uson'=> 'Uson' ], 'Misamis Occidental'=> [ 'Misamis Occidental'=> 'Entire Misamis Occidental', 'Misamis Occidental - Aloran'=> 'Aloran', 'Misamis Occidental - Baliangao'=> 'Baliangao', 'Misamis Occidental - Bonifacio'=> 'Bonifacio', 'Misamis Occidental - Calamba'=> 'Calamba', 'Misamis Occidental - Clarin'=> 'Clarin', 'Misamis Occidental - Concepcion'=> 'Concepcion', 'Misamis Occidental - Don Victoriano Chiongbian'=> 'Don Victoriano Chiongbian', 'Misamis Occidental - Jimenez'=> 'Jimenez', 'Misamis Occidental - Lopez Jaena'=> 'Lopez Jaena', 'Misamis Occidental - Oroquieta City'=> 'Oroquieta City', 'Misamis Occidental - Ozamis City'=> 'Ozamis City', 'Misamis Occidental - Panaon'=> 'Panaon', 'Misamis Occidental - Plaridel'=> 'Plaridel', 'Misamis Occidental - Sapang Dalaga'=> 'Sapang Dalaga', 'Misamis Occidental - Sinacaban'=> 'Sinacaban', 'Misamis Occidental - Tangub City'=> 'Tangub City', 'Misamis Occidental - Tudela'=> 'Tudela' ], 'Misamis Oriental'=> [ 'Misamis Oriental'=> 'Entire Misamis Oriental', 'Misamis Oriental - Alubijid'=> 'Alubijid', 'Misamis Oriental - Balingasag'=> 'Balingasag', 'Misamis Oriental - Balingoan'=> 'Balingoan', 'Misamis Oriental - Binuangan'=> 'Binuangan', 'Misamis Oriental - Cagayan De Oro City'=> 'Cagayan De Oro City', 'Misamis Oriental - Claveria'=> 'Claveria', 'Misamis Oriental - El Salvador City'=> 'El Salvador City', 'Misamis Oriental - Gingoog City'=> 'Gingoog City', 'Misamis Oriental - Gitagum'=> 'Gitagum', 'Misamis Oriental - Initao'=> 'Initao', 'Misamis Oriental - Jasaan'=> 'Jasaan', 'Misamis Oriental - Kinoguitan'=> 'Kinoguitan', 'Misamis Oriental - Lagonglong'=> 'Lagonglong', 'Misamis Oriental - Laguindingan'=> 'Laguindingan', 'Misamis Oriental - Libertad'=> 'Libertad', 'Misamis Oriental - Lugait'=> 'Lugait', 'Misamis Oriental - Magsaysay (Linugos)'=> 'Magsaysay (Linugos)', 'Misamis Oriental - Manticao'=> 'Manticao', 'Misamis Oriental - Medina'=> 'Medina', 'Misamis Oriental - Naawan'=> 'Naawan', 'Misamis Oriental - Opol'=> 'Opol', 'Misamis Oriental - Salay'=> 'Salay', 'Misamis Oriental - Sugbongcogon'=> 'Sugbongcogon', 'Misamis Oriental - Tagoloan'=> 'Tagoloan', 'Misamis Oriental - Talisayan'=> 'Talisayan', 'Misamis Oriental - Villanueva'=> 'Villanueva' ], 'Mountain Province'=> [ 'Mountain Province'=> 'Entire Mountain Province', 'Mountain Province - Barlig'=> 'Barlig', 'Mountain Province - Bauko'=> 'Bauko', 'Mountain Province - Besao'=> 'Besao', 'Mountain Province - Bontoc'=> 'Bontoc', 'Mountain Province - Natonin'=> 'Natonin', 'Mountain Province - Paracelis'=> 'Paracelis', 'Mountain Province - Sabangan'=> 'Sabangan', 'Mountain Province - Sadanga'=> 'Sadanga', 'Mountain Province - Sagada'=> 'Sagada', 'Mountain Province - Tadian'=> 'Tadian' ], 'Negros Occidental'=> [ 'Negros Occidental'=> 'Entire Negros Occidental', 'Negros Occidental - Bacolod City'=> 'Bacolod City', 'Negros Occidental - Bago City'=> 'Bago City', 'Negros Occidental - Binalbagan'=> 'Binalbagan', 'Negros Occidental - Cadiz City'=> 'Cadiz City', 'Negros Occidental - Calatrava'=> 'Calatrava', 'Negros Occidental - Candoni'=> 'Candoni', 'Negros Occidental - Cauayan'=> 'Cauayan', 'Negros Occidental - Enrique B. Magalona (Saravia)'=> 'Enrique B. Magalona (Saravia)', 'Negros Occidental - Escalante City'=> 'Escalante City', 'Negros Occidental - Himamaylan City'=> 'Himamaylan City', 'Negros Occidental - Hinigaran'=> 'Hinigaran', 'Negros Occidental - Hinoba-An (Asia)'=> 'Hinoba-An (Asia)', 'Negros Occidental - Ilog'=> 'Ilog', 'Negros Occidental - Isabela'=> 'Isabela', 'Negros Occidental - Kabankalan City'=> 'Kabankalan City', 'Negros Occidental - La Carlota City'=> 'La Carlota City', 'Negros Occidental - La Castellana'=> 'La Castellana', 'Negros Occidental - Manapla'=> 'Manapla', 'Negros Occidental - Moises Padilla (Magallon)'=> 'Moises Padilla (Magallon)', 'Negros Occidental - Murcia'=> 'Murcia', 'Negros Occidental - Pontevedra'=> 'Pontevedra', 'Negros Occidental - Pulupandan'=> 'Pulupandan', 'Negros Occidental - Sagay City'=> 'Sagay City', 'Negros Occidental - Salvador Benedicto'=> 'Salvador Benedicto', 'Negros Occidental - San Carlos City'=> 'San Carlos City', 'Negros Occidental - San Enrique'=> 'San Enrique', 'Negros Occidental - Silay City'=> 'Silay City', 'Negros Occidental - Talisay City'=> 'Talisay City', 'Negros Occidental - Toboso'=> 'Toboso', 'Negros Occidental - Valladolid'=> 'Valladolid', 'Negros Occidental - Victorias City'=> 'Victorias City' ], 'Negros Oriental'=> [ 'Negros Oriental'=> 'Entire Negros Oriental', 'Negros Oriental - Amlan (Ayuquitan)'=> 'Amlan (Ayuquitan)', 'Negros Oriental - Ayugon'=> 'Ayugon', 'Negros Oriental - Bacong'=> 'Bacong', 'Negros Oriental - Bais City'=> 'Bais City', 'Negros Oriental - Basay'=> 'Basay', 'Negros Oriental - Bayawan City (Tulong)'=> 'Bayawan City (Tulong)', 'Negros Oriental - Bindoy (Payabon)'=> 'Bindoy (Payabon)', 'Negros Oriental - Canlaon City'=> 'Canlaon City', 'Negros Oriental - Dauin'=> 'Dauin', 'Negros Oriental - Dumaguete City'=> 'Dumaguete City', 'Negros Oriental - Guihulngan'=> 'Guihulngan', 'Negros Oriental - Jimalalud'=> 'Jimalalud', 'Negros Oriental - La Libertad'=> 'La Libertad', 'Negros Oriental - Mabinay'=> 'Mabinay', 'Negros Oriental - Manjuyod'=> 'Manjuyod', 'Negros Oriental - Pamplona'=> 'Pamplona', 'Negros Oriental - San Jose'=> 'San Jose', 'Negros Oriental - Santa Catalina'=> 'Santa Catalina', 'Negros Oriental - Siaton'=> 'Siaton', 'Negros Oriental - Sibulan'=> 'Sibulan', 'Negros Oriental - Tayasan'=> 'Tayasan', 'Negros Oriental - Tanjay City'=> 'Tanjay City', 'Negros Oriental - Valencia (Luzurrizga)'=> 'Valencia (Luzurrizga)', 'Negros Oriental - Vallehermoso'=> 'Vallehermoso', 'Negros Oriental - Zamboanguita'=> 'Zamboanguita' ], 'Northern Samar'=> [ 'Northern Samar'=> 'Entire Northern Samar', 'Northern Samar - Allen'=> 'Allen', 'Northern Samar - Biri'=> 'Biri', 'Northern Samar - Bobon'=> 'Bobon', 'Northern Samar - Capul'=> 'Capul', 'Northern Samar - Catarman'=> 'Catarman', 'Northern Samar - Catubig'=> 'Catubig', 'Northern Samar - Gamay'=> 'Gamay', 'Northern Samar - Laoang'=> 'Laoang', 'Northern Samar - Lapinig'=> 'Lapinig', 'Northern Samar - Las Navas'=> 'Las Navas', 'Northern Samar - Lavezares'=> 'Lavezares', 'Northern Samar - Lope De Vega'=> 'Lope De Vega', 'Northern Samar - Mapanas'=> 'Mapanas', 'Northern Samar - Mondragon'=> 'Mondragon', 'Northern Samar - Palapag'=> 'Palapag', 'Northern Samar - Pambujan'=> 'Pambujan', 'Northern Samar - Rosario'=> 'Rosario', 'Northern Samar - San Antonio'=> 'San Antonio', 'Northern Samar - San Isidro'=> 'San Isidro', 'Northern Samar - San Jose'=> 'San Jose', 'Northern Samar - San Roque'=> 'San Roque', 'Northern Samar - San Vicente'=> 'San Vicente', 'Northern Samar - Silvino Lobos'=> 'Silvino Lobos', 'Northern Samar - Victoria'=> 'Victoria' ], 'Nueva Ecija'=> [ 'Nueva Ecija'=> 'Entire Nueva Ecija', 'Nueva Ecija - Aliaga'=> 'Aliaga', 'Nueva Ecija - Bongabon'=> 'Bongabon', 'Nueva Ecija - Cabanatuan City'=> 'Cabanatuan City', 'Nueva Ecija - Cabiao'=> 'Cabiao', 'Nueva Ecija - Caranglan'=> 'Caranglan', 'Nueva Ecija - Cuyapo'=> 'Cuyapo', 'Nueva Ecija - Gabaldon (Bitulok and Sarabani)'=> 'Gabaldon (Bitulok and Sarabani)', 'Nueva Ecija - Gapan City'=> 'Gapan City', 'Nueva Ecija - General Mamerto Natividad'=> 'General Mamerto Natividad', 'Nueva Ecija - General Tinio (Payapa)'=> 'General Tinio (Payapa)', 'Nueva Ecija - Guimba'=> 'Guimba', 'Nueva Ecija - Jaen'=> 'Jaen', 'Nueva Ecija - Laur'=> 'Laur', 'Nueva Ecija - Licab'=> 'Licab', 'Nueva Ecija - Llanera'=> 'Llanera', 'Nueva Ecija - Lupao'=> 'Lupao', 'Nueva Ecija - Munoz City'=> 'Munoz City', 'Nueva Ecija - Nampicuan'=> 'Nampicuan', 'Nueva Ecija - Palayan City'=> 'Palayan City', 'Nueva Ecija - Pantabangan'=> 'Pantabangan', 'Nueva Ecija - Penaranda'=> 'Penaranda', 'Nueva Ecija - Quezon'=> 'Quezon', 'Nueva Ecija - Rizal'=> 'Rizal', 'Nueva Ecija - San Antonio'=> 'San Antonio', 'Nueva Ecija - San Isidro'=> 'San Isidro', 'Nueva Ecija - San Jose City'=> 'San Jose City', 'Nueva Ecija - San Leonardo'=> 'San Leonardo', 'Nueva Ecija - Santa Rosa'=> 'Santa Rosa', 'Nueva Ecija - Santo Domingo'=> 'Santo Domingo', 'Nueva Ecija - Talavera'=> 'Talavera', 'Nueva Ecija - Talugtug'=> 'Talugtug', 'Nueva Ecija - Zaragoza'=> 'Zaragoza' ], 'Nueva Vizcaya'=> [ 'Nueva Vizcaya'=> 'Entire Nueva Vizcaya', 'Nueva Vizcaya - Alfonso Castaneda'=> 'Alfonso Castaneda', 'Nueva Vizcaya - Ambaguio'=> 'Ambaguio', 'Nueva Vizcaya - Aritao'=> 'Aritao', 'Nueva Vizcaya - Bagabag'=> 'Bagabag', 'Nueva Vizcaya - Bambang'=> 'Bambang', 'Nueva Vizcaya - Bayombong'=> 'Bayombong', 'Nueva Vizcaya - Diadi'=> 'Diadi', 'Nueva Vizcaya - Dupax Del Norte'=> 'Dupax Del Norte', 'Nueva Vizcaya - Dupax Del Sur'=> 'Dupax Del Sur', 'Nueva Vizcaya - Kasibu'=> 'Kasibu', 'Nueva Vizcaya - Kayapa'=> 'Kayapa', 'Nueva Vizcaya - Quezon'=> 'Quezon', 'Nueva Vizcaya - Santa Fe'=> 'Santa Fe', 'Nueva Vizcaya - Solano'=> 'Solano', 'Nueva Vizcaya - Villaverde'=> 'Villaverde' ], 'Occidental Mindoro'=> [ 'Occidental Mindoro'=> 'Entire Occidental Mindoro', 'Occidental Mindoro - Abra de Ilog'=> 'Abra de Ilog', 'Occidental Mindoro - Calintaan'=> 'Calintaan', 'Occidental Mindoro - Looc'=> 'Looc', 'Occidental Mindoro - Lubang'=> 'Lubang', 'Occidental Mindoro - Magsaysay'=> 'Magsaysay', 'Occidental Mindoro - Mamburao'=> 'Mamburao', 'Occidental Mindoro - Paluan'=> 'Paluan', 'Occidental Mindoro - Rizal'=> 'Rizal', 'Occidental Mindoro - Sablayan'=> 'Sablayan', 'Occidental Mindoro - San Jose'=> 'San Jose', 'Occidental Mindoro - Santa Cruz'=> 'Santa Cruz' ], 'Oriental Mindoro'=> [ 'Oriental Mindoro'=> 'Entire Oriental Mindoro', 'Oriental Mindoro - Baco'=> 'Baco', 'Oriental Mindoro - Bansud'=> 'Bansud', 'Oriental Mindoro - Bongabong'=> 'Bongabong', 'Oriental Mindoro - Bulalacao (San Pedro)'=> 'Bulalacao (San Pedro)', 'Oriental Mindoro - Calapan City'=> 'Calapan City', 'Oriental Mindoro - Gloria'=> 'Gloria', 'Oriental Mindoro - Mansalay'=> 'Mansalay', 'Oriental Mindoro - Naujan'=> 'Naujan', 'Oriental Mindoro - Pinamalayan'=> 'Pinamalayan', 'Oriental Mindoro - Pola'=> 'Pola', 'Oriental Mindoro - Puerto Galera'=> 'Puerto Galera', 'Oriental Mindoro - Roxas'=> 'Roxas', 'Oriental Mindoro - San Teodoro'=> 'San Teodoro', 'Oriental Mindoro - Socorro'=> 'Socorro', 'Oriental Mindoro - Victoria'=> 'Victoria' ], 'Palawan'=> [ 'Palawan'=> 'Entire Palawan', 'Palawan - Aborlan'=> 'Aborlan', 'Palawan - Agutaya'=> 'Agutaya', 'Palawan - Araceli'=> 'Araceli', 'Palawan - Balabac'=> 'Balabac', 'Palawan - Bataraza'=> 'Bataraza', 'Palawan - Brooke\'s Point'=> 'Brooke\'s Point', 'Palawan - Busuanga'=> 'Busuanga', 'Palawan - Cagayancillo'=> 'Cagayancillo', 'Palawan - Coron'=> 'Coron', 'Palawan - Culion'=> 'Culion', 'Palawan - Cuyo'=> 'Cuyo', 'Palawan - Dumaran'=> 'Dumaran', 'Palawan - El Nido (Bacuit)'=> 'El Nido (Bacuit)', 'Palawan - Kalayaan'=> 'Kalayaan', 'Palawan - Linapacan'=> 'Linapacan', 'Palawan - Magsaysay'=> 'Magsaysay', 'Palawan - Narra'=> 'Narra', 'Palawan - Puerto Princesa City'=> 'Puerto Princesa City', 'Palawan - Quezon'=> 'Quezon', 'Palawan - Rizal (Marcos)'=> 'Rizal (Marcos)', 'Palawan - Roxas'=> 'Roxas', 'Palawan - San Vicente'=> 'San Vicente', 'Palawan - Sofronio Espanola'=> 'Sofronio Espanola', 'Palawan - Taytay'=> 'Taytay' ], 'Pampanga'=> [ 'Pampanga'=> 'Entire Pampanga', 'Pampanga - Angeles City'=> 'Angeles City', 'Pampanga - Apalit'=> 'Apalit', 'Pampanga - Arayat'=> 'Arayat', 'Pampanga - Bacolor'=> 'Bacolor', 'Pampanga - Candaba'=> 'Candaba', 'Pampanga - Floridablanca'=> 'Floridablanca', 'Pampanga - Guagua'=> 'Guagua', 'Pampanga - Lubao'=> 'Lubao', 'Pampanga - Mabalacat'=> 'Mabalacat', 'Pampanga - Macabebe'=> 'Macabebe', 'Pampanga - Magalang'=> 'Magalang', 'Pampanga - Masantol'=> 'Masantol', 'Pampanga - Mexico'=> 'Mexico', 'Pampanga - Minalin'=> 'Minalin', 'Pampanga - Porac'=> 'Porac', 'Pampanga - San Fernando City'=> 'San Fernando City', 'Pampanga - San Luis'=> 'San Luis', 'Pampanga - San Simon'=> 'San Simon', 'Pampanga - Santa Ana'=> 'Santa Ana', 'Pampanga - Santa Rita'=> 'Santa Rita', 'Pampanga - Santo Tomas'=> 'Santo Tomas', 'Pampanga - Sasmoan'=> 'Sasmoan' ], 'Pangasinan'=> [ 'Pangasinan'=> 'Entire Pangasinan', 'Pangasinan - Agno'=> 'Agno', 'Pangasinan - Aguilar'=> 'Aguilar', 'Pangasinan - Alaminos City'=> 'Alaminos City', 'Pangasinan - Alcala'=> 'Alcala', 'Pangasinan - Anda'=> 'Anda', 'Pangasinan - Asingan'=> 'Asingan', 'Pangasinan - Balungao'=> 'Balungao', 'Pangasinan - Bani'=> 'Bani', 'Pangasinan - Basista'=> 'Basista', 'Pangasinan - Bautista'=> 'Bautista', 'Pangasinan - Bayambang'=> 'Bayambang', 'Pangasinan - Binalonan'=> 'Binalonan', 'Pangasinan - Binmaley'=> 'Binmaley', 'Pangasinan - Bolinao'=> 'Bolinao', 'Pangasinan - Bugallon'=> 'Bugallon', 'Pangasinan - Burgos'=> 'Burgos', 'Pangasinan - Calasiao'=> 'Calasiao', 'Pangasinan - Dagupan City'=> 'Dagupan City', 'Pangasinan - Dasol'=> 'Dasol', 'Pangasinan - Infanta'=> 'Infanta', 'Pangasinan - Labrador'=> 'Labrador', 'Pangasinan - Laoac'=> 'Laoac', 'Pangasinan - Lingayen'=> 'Lingayen', 'Pangasinan - Mabini'=> 'Mabini', 'Pangasinan - Malasiqui'=> 'Malasiqui', 'Pangasinan - Manaoag'=> 'Manaoag', 'Pangasinan - Mangaldan'=> 'Mangaldan', 'Pangasinan - Mangatarem'=> 'Mangatarem', 'Pangasinan - Mapandan'=> 'Mapandan', 'Pangasinan - Natividad'=> 'Natividad', 'Pangasinan - Pozorrubio'=> 'Pozorrubio', 'Pangasinan - Rosales'=> 'Rosales', 'Pangasinan - San Carlos City'=> 'San Carlos City', 'Pangasinan - San Fabian'=> 'San Fabian', 'Pangasinan - San Jacinto'=> 'San Jacinto', 'Pangasinan - San Manuel'=> 'San Manuel', 'Pangasinan - San Nicolas'=> 'San Nicolas', 'Pangasinan - San Quintin'=> 'San Quintin', 'Pangasinan - Santa Barbara'=> 'Santa Barbara', 'Pangasinan - Santa Maria'=> 'Santa Maria', 'Pangasinan - Santo Tomas'=> 'Santo Tomas', 'Pangasinan - Sison'=> 'Sison', 'Pangasinan - Sual'=> 'Sual', 'Pangasinan - Tayug'=> 'Tayug', 'Pangasinan - Umingan'=> 'Umingan', 'Pangasinan - Urbiztondo'=> 'Urbiztondo', 'Pangasinan - Urdaneta City'=> 'Urdaneta City', 'Pangasinan - Villasis'=> 'Villasis' ], 'Quezon'=> [ 'Quezon'=> 'Entire Quezon', 'Quezon - Agdangan'=> 'Agdangan', 'Quezon - Alabat'=> 'Alabat', 'Quezon - Atimonan'=> 'Atimonan', 'Quezon - Buenavista'=> 'Buenavista', 'Quezon - Burdeos'=> 'Burdeos', 'Quezon - Calauag'=> 'Calauag', 'Quezon - Candelaria'=> 'Candelaria', 'Quezon - Catanauan'=> 'Catanauan', 'Quezon - Dolores'=> 'Dolores', 'Quezon - General Luna'=> 'General Luna', 'Quezon - General Nakar'=> 'General Nakar', 'Quezon - Guinayangan'=> 'Guinayangan', 'Quezon - Gumaca'=> 'Gumaca', 'Quezon - Infanta'=> 'Infanta', 'Quezon - Jomalig'=> 'Jomalig', 'Quezon - Lopez'=> 'Lopez', 'Quezon - Lucban'=> 'Lucban', 'Quezon - Lucena City'=> 'Lucena City', 'Quezon - Macalelon'=> 'Macalelon', 'Quezon - Mauban'=> 'Mauban', 'Quezon - Mulanay'=> 'Mulanay', 'Quezon - Padre Burgos'=> 'Padre Burgos', 'Quezon - Pagbilao'=> 'Pagbilao', 'Quezon - Panukulan'=> 'Panukulan', 'Quezon - Patnanungan'=> 'Patnanungan', 'Quezon - Perez'=> 'Perez', 'Quezon - Pitogo'=> 'Pitogo', 'Quezon - Plaridel'=> 'Plaridel', 'Quezon - Polillo'=> 'Polillo', 'Quezon - Quezon'=> 'Quezon', 'Quezon - Real'=> 'Real', 'Quezon - Sampaloc'=> 'Sampaloc', 'Quezon - San Andres'=> 'San Andres', 'Quezon - San Antonio'=> 'San Antonio', 'Quezon - San Francisco (Aurora)'=> 'San Francisco (Aurora)', 'Quezon - San Narciso'=> 'San Narciso', 'Quezon - Sariaya'=> 'Sariaya', 'Quezon - Tagkawayan'=> 'Tagkawayan', 'Quezon - Tayabas City'=> 'Tayabas City', 'Quezon - Tiaong'=> 'Tiaong', 'Quezon - Unisan'=> 'Unisan' ], 'Quirino'=> [ 'Quirino'=> 'Entire Quirino', 'Quirino - Aglipay'=> 'Aglipay', 'Quirino - Cabarroguis'=> 'Cabarroguis', 'Quirino - Diffun'=> 'Diffun', 'Quirino - Maddela'=> 'Maddela', 'Quirino - Nagtipunan'=> 'Nagtipunan', 'Quirino - Saguday'=> 'Saguday' ], 'Rizal'=> [ 'Rizal'=> 'Entire Rizal', 'Rizal - Angono'=> 'Angono', 'Rizal - Antipolo City'=> 'Antipolo City', 'Rizal - Baras'=> 'Baras', 'Rizal - Binangonan'=> 'Binangonan', 'Rizal - Cainta'=> 'Cainta', 'Rizal - Cardona'=> 'Cardona', 'Rizal - Jalajala'=> 'Jalajala', 'Rizal - Morong'=> 'Morong', 'Rizal - Pililla'=> 'Pililla', 'Rizal - Rodriguez (Montalban)'=> 'Rodriguez (Montalban)', 'Rizal - San Mateo'=> 'San Mateo', 'Rizal - Tanay'=> 'Tanay', 'Rizal - Taytay'=> 'Taytay', 'Rizal - Teresa'=> 'Teresa' ], 'Romblon'=> [ 'Romblon'=> 'Entire Romblon', 'Romblon - Alcantara'=> 'Alcantara', 'Romblon - Banton'=> 'Banton', 'Romblon - Cajidiocan'=> 'Cajidiocan', 'Romblon - Calatrava'=> 'Calatrava', 'Romblon - Concepcion'=> 'Concepcion', 'Romblon - Corcuera'=> 'Corcuera', 'Romblon - Ferrol'=> 'Ferrol', 'Romblon - Looc'=> 'Looc', 'Romblon - Magdiwang'=> 'Magdiwang', 'Romblon - Odiongan'=> 'Odiongan', 'Romblon - Romblon'=> 'Romblon', 'Romblon - San Agustin'=> 'San Agustin', 'Romblon - San Andres'=> 'San Andres', 'Romblon - San Fernando'=> 'San Fernando', 'Romblon - San Jose'=> 'San Jose', 'Romblon - Santa Fe'=> 'Santa Fe', 'Romblon - Santa Maria (Imelda)'=> 'Santa Maria (Imelda)' ], 'Samar'=> [ 'Samar'=> 'Entire Samar', 'Samar - Almagro'=> 'Almagro', 'Samar - Basey'=> 'Basey', 'Samar - Calbayog City'=> 'Calbayog City', 'Samar - Calbiga'=> 'Calbiga', 'Samar - Catbalogan City'=> 'Catbalogan City', 'Samar - Daram'=> 'Daram', 'Samar - Gandara'=> 'Gandara', 'Samar - Hinabangan'=> 'Hinabangan', 'Samar - Jiabong'=> 'Jiabong', 'Samar - Marabut'=> 'Marabut', 'Samar - Matuguinao'=> 'Matuguinao', 'Samar - Motiong'=> 'Motiong', 'Samar - Pagsanghan'=> 'Pagsanghan', 'Samar - Paranas (Wright)'=> 'Paranas (Wright)', 'Samar - Pinabacdao'=> 'Pinabacdao', 'Samar - San Jorge'=> 'San Jorge', 'Samar - San Jose De Buan'=> 'San Jose De Buan', 'Samar - San Sebastian'=> 'San Sebastian', 'Samar - Santa Margarita'=> 'Santa Margarita', 'Samar - Santa Rita'=> 'Santa Rita', 'Samar - Santo Nino'=> 'Santo Nino', 'Samar - Tagapul-An'=> 'Tagapul-An', 'Samar - Talalora'=> 'Talalora', 'Samar - Tarangan'=> 'Tarangan', 'Samar - Villareal'=> 'Villareal', 'Samar - Zumarraga'=> 'Zumarraga' ], 'Sarangani'=> [ 'Sarangani'=> 'Entire Sarangani', 'Sarangani - Alabel'=> 'Alabel', 'Sarangani - Glan'=> 'Glan', 'Sarangani - Kiamba'=> 'Kiamba', 'Sarangani - Maasim'=> 'Maasim', 'Sarangani - Maitum'=> 'Maitum', 'Sarangani - Malapatan'=> 'Malapatan', 'Sarangani - Malungon'=> 'Malungon' ], 'Siquijor'=> [ 'Siquijor'=> 'Entire Siquijor', 'Siquijor - Enrique Villanueva'=> 'Enrique Villanueva', 'Siquijor - Larena'=> 'Larena', 'Siquijor - Lazi'=> 'Lazi', 'Siquijor - Maria'=> 'Maria', 'Siquijor - San Juan'=> 'San Juan', 'Siquijor - Siquijor'=> 'Siquijor' ], 'Sorsogon'=> [ 'Sorsogon'=> 'Entire Sorsogon', 'Sorsogon - Barcelona'=> 'Barcelona', 'Sorsogon - Bulan'=> 'Bulan', 'Sorsogon - Bulusan'=> 'Bulusan', 'Sorsogon - Casiguran'=> 'Casiguran', 'Sorsogon - Castilla'=> 'Castilla', 'Sorsogon - Donsol'=> 'Donsol', 'Sorsogon - Gubat'=> 'Gubat', 'Sorsogon - Irosin'=> 'Irosin', 'Sorsogon - Juban'=> 'Juban', 'Sorsogon - Magallanes'=> 'Magallanes', 'Sorsogon - Matnog'=> 'Matnog', 'Sorsogon - Pilar'=> 'Pilar', 'Sorsogon - Prieto Diaz'=> 'Prieto Diaz', 'Sorsogon - Santa Magdalena'=> 'Santa Magdalena', 'Sorsogon - Sorsogon City'=> 'Sorsogon City' ], 'South Cotabato'=> [ 'South Cotabato'=> 'Entire South Cotabato', 'South Cotabato - Banga'=> 'Banga', 'South Cotabato - General Santos City (Dadiangas)'=> 'General Santos City (Dadiangas)', 'South Cotabato - Koronadal City'=> 'Koronadal City', 'South Cotabato - Lake Sebu'=> 'Lake Sebu', 'South Cotabato - Norala'=> 'Norala', 'South Cotabato - Polomolok'=> 'Polomolok', 'South Cotabato - Santo Nino'=> 'Santo Nino', 'South Cotabato - Surallah'=> 'Surallah', 'South Cotabato - Tampakan'=> 'Tampakan', 'South Cotabato - Tantangan'=> 'Tantangan', 'South Cotabato - Tupi'=> 'Tupi', 'South Cotabato - T\'boli'=> 'T\'boli' ], 'Southern Leyte'=> [ 'Southern Leyte'=> 'Entire Southern Leyte', 'Southern Leyte - Anahawan'=> 'Anahawan', 'Southern Leyte - Bontoc'=> 'Bontoc', 'Southern Leyte - Hinunangan'=> 'Hinunangan', 'Southern Leyte - Hinundayan'=> 'Hinundayan', 'Southern Leyte - Libagon'=> 'Libagon', 'Southern Leyte - Liloan'=> 'Liloan', 'Southern Leyte - Limasawa'=> 'Limasawa', 'Southern Leyte - Maasin City'=> 'Maasin City', 'Southern Leyte - Macrohon'=> 'Macrohon', 'Southern Leyte - Malitbog'=> 'Malitbog', 'Southern Leyte - Padre Burgos'=> 'Padre Burgos', 'Southern Leyte - Pintuyan'=> 'Pintuyan', 'Southern Leyte - Saint Bernard'=> 'Saint Bernard', 'Southern Leyte - San Francisco'=> 'San Francisco', 'Southern Leyte - San Juan (Cabalian)'=> 'San Juan (Cabalian)', 'Southern Leyte - San Ricardo'=> 'San Ricardo', 'Southern Leyte - Silago'=> 'Silago', 'Southern Leyte - Sogod'=> 'Sogod', 'Southern Leyte - Tomas Oppus'=> 'Tomas Oppus' ], 'Sultan Kudarat'=> [ 'Sultan Kudarat'=> 'Entire Sultan Kudarat', 'Sultan Kudarat - Bagumbayan'=> 'Bagumbayan', 'Sultan Kudarat - Columbio'=> 'Columbio', 'Sultan Kudarat - Esperanza'=> 'Esperanza', 'Sultan Kudarat - Isulan'=> 'Isulan', 'Sultan Kudarat - Kalamansig'=> 'Kalamansig', 'Sultan Kudarat - Lambayong (Mariano Marcos)'=> 'Lambayong (Mariano Marcos)', 'Sultan Kudarat - Lebak'=> 'Lebak', 'Sultan Kudarat - Lutayan'=> 'Lutayan', 'Sultan Kudarat - Palimbang'=> 'Palimbang', 'Sultan Kudarat - President Quirino'=> 'President Quirino', 'Sultan Kudarat - Sen. Ninoy Aquino'=> 'Sen. Ninoy Aquino', 'Sultan Kudarat - Tacurong City'=> 'Tacurong City' ], 'Sulu'=> [ 'Sulu'=> 'Entire Sulu', 'Sulu - Hadji Panglima Tahil (Marungga)'=> 'Hadji Panglima Tahil (Marungga)', 'Sulu - Indanan'=> 'Indanan', 'Sulu - Jolo'=> 'Jolo', 'Sulu - Kalingalan Caluang'=> 'Kalingalan Caluang', 'Sulu - Lugus'=> 'Lugus', 'Sulu - Luuk'=> 'Luuk', 'Sulu - Maimbung'=> 'Maimbung', 'Sulu - Old Panamao'=> 'Old Panamao', 'Sulu - Pandami'=> 'Pandami', 'Sulu - Panglima Estino (New Panamao)'=> 'Panglima Estino (New Panamao)', 'Sulu - Pangutaran'=> 'Pangutaran', 'Sulu - Parang'=> 'Parang', 'Sulu - Pata'=> 'Pata', 'Sulu - Patikul'=> 'Patikul', 'Sulu - Siasi'=> 'Siasi', 'Sulu - Talipao'=> 'Talipao', 'Sulu - Tapul'=> 'Tapul', 'Sulu - Tongkil'=> 'Tongkil' ], 'Surigao del Norte'=> [ 'Surigao del Norte'=> 'Entire Surigao del Norte', 'Surigao del Norte - Alegria'=> 'Alegria', 'Surigao del Norte - Bacuag'=> 'Bacuag', 'Surigao del Norte - Burgos'=> 'Burgos', 'Surigao del Norte - Cagdianao'=> 'Cagdianao', 'Surigao del Norte - Claver'=> 'Claver', 'Surigao del Norte - Dapa'=> 'Dapa', 'Surigao del Norte - Del Carmen'=> 'Del Carmen', 'Surigao del Norte - General Luna'=> 'General Luna', 'Surigao del Norte - Gigaquit'=> 'Gigaquit', 'Surigao del Norte - Mainit'=> 'Mainit', 'Surigao del Norte - Malimono'=> 'Malimono', 'Surigao del Norte - Pilar'=> 'Pilar', 'Surigao del Norte - Placer'=> 'Placer', 'Surigao del Norte - San Benito'=> 'San Benito', 'Surigao del Norte - San Francisco (Anao-Aon)'=> 'San Francisco (Anao-Aon)', 'Surigao del Norte - San Isidro'=> 'San Isidro', 'Surigao del Norte - San Jose'=> 'San Jose', 'Surigao del Norte - Santa Monica (Sapao)'=> 'Santa Monica (Sapao)', 'Surigao del Norte - Sison'=> 'Sison', 'Surigao del Norte - Socorro'=> 'Socorro', 'Surigao del Norte - Surigao City (Capital)'=> 'Surigao City (Capital)', 'Surigao del Norte - Tagana-an'=> 'Tagana-an', 'Surigao del Norte - Tubod'=> 'Tubod' ], 'Surigao del Sur'=> [ 'Surigao del Sur'=> 'Entire Surigao del Sur', 'Surigao del Sur - Barobo'=> 'Barobo', 'Surigao del Sur - Bayabas'=> 'Bayabas', 'Surigao del Sur - Bislig City'=> 'Bislig City', 'Surigao del Sur - Cagwait'=> 'Cagwait', 'Surigao del Sur - Cantilan'=> 'Cantilan', 'Surigao del Sur - Carmen'=> 'Carmen', 'Surigao del Sur - Carrascal'=> 'Carrascal', 'Surigao del Sur - Cortes'=> 'Cortes', 'Surigao del Sur - Hinatuan'=> 'Hinatuan', 'Surigao del Sur - Lanuza'=> 'Lanuza', 'Surigao del Sur - Lianga'=> 'Lianga', 'Surigao del Sur - Lingig'=> 'Lingig', 'Surigao del Sur - Madrid'=> 'Madrid', 'Surigao del Sur - Marihatag'=> 'Marihatag', 'Surigao del Sur - San Agustin'=> 'San Agustin', 'Surigao del Sur - San Miguel'=> 'San Miguel', 'Surigao del Sur - Tagbina'=> 'Tagbina', 'Surigao del Sur - Tago'=> 'Tago', 'Surigao del Sur - Tandag City'=> 'Tandag City' ], 'Tarlac'=> [ 'Tarlac'=> 'Entire Tarlac', 'Tarlac - Anao'=> 'Anao', 'Tarlac - Bamban'=> 'Bamban', 'Tarlac - Camiling'=> 'Camiling', 'Tarlac - Capas'=> 'Capas', 'Tarlac - Concepcion'=> 'Concepcion', 'Tarlac - Gerona'=> 'Gerona', 'Tarlac - Lapaz'=> 'Lapaz', 'Tarlac - Mayantoc'=> 'Mayantoc', 'Tarlac - Moncada'=> 'Moncada', 'Tarlac - Paniqui'=> 'Paniqui', 'Tarlac - Pura'=> 'Pura', 'Tarlac - Ramos'=> 'Ramos', 'Tarlac - San Clemente'=> 'San Clemente', 'Tarlac - San Jose'=> 'San Jose', 'Tarlac - San Manuel'=> 'San Manuel', 'Tarlac - Santa Ignacia'=> 'Santa Ignacia', 'Tarlac - Tarlac City'=> 'Tarlac City', 'Tarlac - Victoria'=> 'Victoria' ], 'Tawi-tawi'=> [ 'Tawi-tawi'=> 'Entire Tawi-tawi', 'Tawi-tawi - Bongao'=> 'Bongao', 'Tawi-tawi - Languyan'=> 'Languyan', 'Tawi-tawi - Mapun (Cagayan De Tawi-Tawi)'=> 'Mapun (Cagayan De Tawi-Tawi)', 'Tawi-tawi - Panglima Sugala (Balimbing)'=> 'Panglima Sugala (Balimbing)', 'Tawi-tawi - Sapa-Sapa'=> 'Sapa-Sapa', 'Tawi-tawi - Simunul'=> 'Simunul', 'Tawi-tawi - Sitangkai'=> 'Sitangkai', 'Tawi-tawi - South Ubian'=> 'South Ubian', 'Tawi-tawi - Tandubas'=> 'Tandubas', 'Tawi-tawi - Turtle Islands'=> 'Turtle Islands' ], 'Zambales'=> [ 'Zambales'=> 'Entire Zambales', 'Zambales - Botolan'=> 'Botolan', 'Zambales - Cabangan'=> 'Cabangan', 'Zambales - Candelaria'=> 'Candelaria', 'Zambales - Castillejos'=> 'Castillejos', 'Zambales - Iba'=> 'Iba', 'Zambales - Masinloc'=> 'Masinloc', 'Zambales - Olongapo City'=> 'Olongapo City', 'Zambales - Palauig'=> 'Palauig', 'Zambales - San Antonio'=> 'San Antonio', 'Zambales - San Felipe'=> 'San Felipe', 'Zambales - San Marcelino'=> 'San Marcelino', 'Zambales - San Narciso'=> 'San Narciso', 'Zambales - Santa Cruz'=> 'Santa Cruz', 'Zambales - Subic'=> 'Subic' ], 'Zamboanga City'=> [ 'Zamboanga City'=> 'Entire Zamboanga City' ], 'Zamboanga del Norte'=> [ 'Zamboanga del Norte'=> 'Entire Zamboanga del Norte', 'Zamboanga del Norte - Bacungan (Leon T. Postigo)'=> 'Bacungan (Leon T. Postigo)', 'Zamboanga del Norte - Baliguian'=> 'Baliguian', 'Zamboanga del Norte - Dapitan City'=> 'Dapitan City', 'Zamboanga del Norte - Dipolog City'=> 'Dipolog City', 'Zamboanga del Norte - Godod'=> 'Godod', 'Zamboanga del Norte - Gutalac'=> 'Gutalac', 'Zamboanga del Norte - Jose Dalman (Ponot)'=> 'Jose Dalman (Ponot)', 'Zamboanga del Norte - Kalawit'=> 'Kalawit', 'Zamboanga del Norte - Katipunan'=> 'Katipunan', 'Zamboanga del Norte - La Libertad'=> 'La Libertad', 'Zamboanga del Norte - Labason'=> 'Labason', 'Zamboanga del Norte - Liloy'=> 'Liloy', 'Zamboanga del Norte - Manukan'=> 'Manukan', 'Zamboanga del Norte - Mutia'=> 'Mutia', 'Zamboanga del Norte - Pinan (New Pinan)'=> 'Pinan (New Pinan)', 'Zamboanga del Norte - Polanco'=> 'Polanco', 'Zamboanga del Norte - Pres. Manuel A. Roxas'=> 'Pres. Manuel A. Roxas', 'Zamboanga del Norte - Rizal'=> 'Rizal', 'Zamboanga del Norte - Salug'=> 'Salug', 'Zamboanga del Norte - Sergio Osmena Sr.'=> 'Sergio Osmena Sr.', 'Zamboanga del Norte - Siayan'=> 'Siayan', 'Zamboanga del Norte - Sibuco'=> 'Sibuco', 'Zamboanga del Norte - Sibutad'=> 'Sibutad', 'Zamboanga del Norte - Sindangan'=> 'Sindangan', 'Zamboanga del Norte - Siocon'=> 'Siocon', 'Zamboanga del Norte - Sirawai'=> 'Sirawai', 'Zamboanga del Norte - Tampilisan'=> 'Tampilisan' ], 'Zamboanga del Sur'=> [ 'Zamboanga del Sur'=> 'Entire Zamboanga del Sur', 'Zamboanga del Sur - Aurora'=> 'Aurora', 'Zamboanga del Sur - Bayog'=> 'Bayog', 'Zamboanga del Sur - Dimataling'=> 'Dimataling', 'Zamboanga del Sur - Dinas'=> 'Dinas', 'Zamboanga del Sur - Dumalinao'=> 'Dumalinao', 'Zamboanga del Sur - Dumingag'=> 'Dumingag', 'Zamboanga del Sur - Guipos'=> 'Guipos', 'Zamboanga del Sur - Josefina'=> 'Josefina', 'Zamboanga del Sur - Kumalarang'=> 'Kumalarang', 'Zamboanga del Sur - Labangan'=> 'Labangan', 'Zamboanga del Sur - Lakewood'=> 'Lakewood', 'Zamboanga del Sur - Lapuyan'=> 'Lapuyan', 'Zamboanga del Sur - Mahayag'=> 'Mahayag', 'Zamboanga del Sur - Margosatubig'=> 'Margosatubig', 'Zamboanga del Sur - Midsalip'=> 'Midsalip', 'Zamboanga del Sur - Molave'=> 'Molave', 'Zamboanga del Sur - Pagadian City'=> 'Pagadian City', 'Zamboanga del Sur - Pitogo'=> 'Pitogo', 'Zamboanga del Sur - Ramon Magsaysay (Liargo)'=> 'Ramon Magsaysay (Liargo)', 'Zamboanga del Sur - San Miguel'=> 'San Miguel', 'Zamboanga del Sur - San Pablo'=> 'San Pablo', 'Zamboanga del Sur - Sominot (Don Mariano Marcos)'=> 'Sominot (Don Mariano Marcos)', 'Zamboanga del Sur - Tabina'=> 'Tabina', 'Zamboanga del Sur - Tambulig'=> 'Tambulig', 'Zamboanga del Sur - Tigbao'=> 'Tigbao', 'Zamboanga del Sur - Tukuran'=> 'Tukuran', 'Zamboanga del Sur - Vincenzo A. Sagun'=> 'Vincenzo A. Sagun' ], 'Zamboanga Sibugay'=> [ 'Zamboanga Sibugay'=> 'Entire Zamboanga Sibugay', 'Zamboanga Sibugay - Alicia'=> 'Alicia', 'Zamboanga Sibugay - Buug'=> 'Buug', 'Zamboanga Sibugay - Diplahan'=> 'Diplahan', 'Zamboanga Sibugay - Imelda'=> 'Imelda', 'Zamboanga Sibugay - Ipil'=> 'Ipil', 'Zamboanga Sibugay - Kabasalan'=> 'Kabasalan', 'Zamboanga Sibugay - Mabuhay'=> 'Mabuhay', 'Zamboanga Sibugay - Malangas'=> 'Malangas', 'Zamboanga Sibugay - Naga'=> 'Naga', 'Zamboanga Sibugay - Olutanga'=> 'Olutanga', 'Zamboanga Sibugay - Payao'=> 'Payao', 'Zamboanga Sibugay - Roseller Lim'=> 'Roseller Lim', 'Zamboanga Sibugay - Siay'=> 'Siay', 'Zamboanga Sibugay - Talusan'=> 'Talusan', 'Zamboanga Sibugay - Titay'=> 'Titay', 'Zamboanga Sibugay - Tungawan'=> 'Tungawan' ]];
	static $provinces = [ "Abra" => "Abra","Agusan del Norte" => "Agusan del Norte","Agusan del Sur" => "Agusan del Sur","Aklan" => "Aklan","Albay" => "Albay","Antique" => "Antique","Apayao" => "Apayao","Aurora" => "Aurora","Basilan" => "Basilan","Bataan" => "Bataan","Batanes" => "Batanes","Batangas" => "Batangas","Benguet" => "Benguet","Biliran" => "Biliran","Bohol" => "Bohol","Bukidnon" => "Bukidnon","Bulacan" => "Bulacan","Cagayan" => "Cagayan","Camarines Norte" => "Camarines Norte","Camarines Sur" => "Camarines Sur","Camiguin" => "Camiguin","Capiz" => "Capiz","Catanduanes" => "Catanduanes","Cavite" => "Cavite","Cebu" => "Cebu","Compostela Valley" => "Compostela Valley","Cotabato" => "Cotabato","Davao del Norte" => "Davao del Norte","Davao del Sur" => "Davao del Sur","Davao Oriental" => "Davao Oriental","Dinagat Islands" => "Dinagat Islands","Eastern Samar" => "Eastern Samar","Guimaras" => "Guimaras","Ifugao" => "Ifugao","Ilocos Norte" => "Ilocos Norte","Ilocos Sur" => "Ilocos Sur","Iloilo" => "Iloilo","Isabela" => "Isabela","Kalinga" => "Kalinga","La Union" => "La Union","Laguna" => "Laguna","Lanao del Norte" => "Lanao del Norte","Lanao del Sur" => "Lanao del Sur","Leyte" => "Leyte","Maguindanao" => "Maguindanao","Marinduque" => "Marinduque","Masbate" => "Masbate","Metro Manila" => "Metro Manila","Misamis Occidental" => "Misamis Occidental","Misamis Oriental" => "Misamis Oriental","Mountain Province" => "Mountain Province","Negros Occidental" => "Negros Occidental","Negros Oriental" => "Negros Oriental","Northern Samar" => "Northern Samar","Nueva Ecija" => "Nueva Ecija","Nueva Vizcaya" => "Nueva Vizcaya","Occidental Mindoro" => "Occidental Mindoro","Oriental Mindoro" => "Oriental Mindoro","Palawan" => "Palawan","Pampanga" => "Pampanga","Pangasinan" => "Pangasinan","Quezon" => "Quezon","Quirino" => "Quirino","Rizal" => "Rizal","Romblon" => "Romblon","Samar" => "Samar","Sarangani" => "Sarangani","Siquijor" => "Siquijor","Sorsogon" => "Sorsogon","South Cotabato" => "South Cotabato","Southern Leyte" => "Southern Leyte","Sultan Kudarat" => "Sultan Kudarat","Sulu" => "Sulu","Surigao del Norte" => "Surigao del Norte","Surigao del Sur" => "Surigao del Sur","Tarlac" => "Tarlac","Tawi-tawi" => "Tawi-tawi","Zambales" => "Zambales","Zamboanga City" => "Zamboanga City","Zamboanga del Norte" => "Zamboanga del Norte","Zamboanga del Sur" => "Zamboanga del Sur","Zamboanga Sibugay" => "Zamboanga Sibugay" ];
	//60*60*24 = 86400
	static $time =	[ 
						24 => 'Last 24 hours',
						48 => 'Last 2 days',
						168 => 'Last 7 days',
						360 => 'Last 15 days',
						720 => 'Last 30 days',
					];
					
	static $price =	[ 
						[null, 100],
						['100', 250],
						['500', 1000],
						['1000', null],
					];
  
  public static function getThemeName() {
    $uri = \Drupal::request()->getRequestUri();
	    
    //$uri = substr($uri, 1);
    list($uri, $trash) = explode('?', $uri, 2);
	if ( $uri == '/mall' or $uri == '/mall/' ) return 'mall.mall'; // this is the entry key of routing.yml
	
    $uri = trim($uri, '/ ');
    $uri = str_replace('/', '.', $uri);
    $uri = strtolower($uri);
    return $uri;
  }

  public static function getThemeFileName() {
    return self::getThemeName() . '.html.twig';
  }

  public static function isFromSubmit() {
    return \Drupal::request()->get('mode') == 'submit';
  }



  /**
   * @param $username
   * @return int
   *
   *
   * @code if ( ! x::getUserID(x::in('owner')) ) return x::errorInfoArray(x::error_wrong_owner, $data);
   */
  public static function getUserID($username) {
    if ( $username ) {
      $user = user_load_by_name($username);
      if ( $user ) {
        return $user->id();
      }
    }
    return 0;
  }


  /**
   *
   * It simply returns Username
   *
   * @param $id
   * @return array|mixed|null|string - username
   * @code $task->worker = x::getUsernameByID( $task->get('worker_id')->value );
   */
  public static function getUsernameByID($id) {
    if ( $id ) {
      $user = User::load($id);
      if ( $user ) {
        return $user->getUsername();
      }
    }
    return '';
  }


  public static function myUid() {
    return \Drupal::currentUser()->getAccount()->id();
  }

  public static function login() {
    return self::myUid();
  }
  public static function admin()
  {
    return x::myUid() == 1;
  }



  /**
   * Returns TRUE if the user is accessing mall module.
   *
   * @return bool
   *
   */
  public static function isMallPage() {
    $request = \Drupal::request();
    $uri = $request->getRequestUri();
    if ( strpos( $uri, '/mall') !== FALSE ) {
      return TRUE;
    }
    else return FALSE;
  }




  /**
   * Returns TRUE if the user is accessing office task page.
   *
   * @return bool
   *
   */
  public static function isMallAdminCategoryPage() {
    $request = \Drupal::request();
    $uri = $request->getRequestUri();
    if ( strpos( $uri, '/mall/admin/category') !== FALSE ) {
      return TRUE;
    }
    else return FALSE;
  }





  public static function input() {
    return self::getInput();
  }

  /**
   * This is a wrapper of "\Drupal::request()->get($name, $default);" except that the default value is  zero(0) instead of null.
   * @param $name
   * @param int $default
   * @return mixed
   * @code
   *    $parent_id  = x::in('parent_id');
   *    $parent_id  = x::in('parent_id', null);
   *    $parent_id  = x::in('parent_id', '');
   * @code
   */
  public static function in($name, $default=0) {
    return \Drupal::request()->get($name, $default);
  }


  /**
   *
   * 입력 값을 임의로 지정한다.
   *
   * x::getInput() 과 x::in() 함수는 입력 값을 리턴한다.
   *
   * 하지만 이 함수를 통해서 입력 값을 임의로 지정하여 해당 함수들이 임의로 지정한 값을 사용 하게 할 수 있다.
   *
   * 예를 들면, 쿠키에 마지막 검색(폼 전송) 값을 저장해 놓고 다음에 접속 할 때 마지막에 지정한 검색 옵션을 그대로 적용하는 것이다.
   *
   *
   * @param $array
   */
  public static function setInput($array) {
    self::$input = $array;
  }

  /**
   * self::$input 의 값을 리턴한다.
   *
   * @note 주의 할 점은 이 값은 꼭 HTTP 입력 값이 아닐 수 있다.
   *
   *      기본 적으로 HTTP 입력 값을 리턴하지만,
   *
   *      프로그램 적으로 임의로 이 값을 다르게 지정 할 수도 있다.
   *
   *      이 함수는 x::in() 에 영향을 미친다.
   *
   * @return array
   */
  public static function getInput() {

    if ( empty(self::$input) ) {
      $request = \Drupal::request();
      $get = $request->query->all();
      $post = $request->request->all();
      self::$input = array_merge( $get, $post ); 
    }
    return self::$input;
  }


  /**
   *
   * @Note returns an entity ID by User ID.
   *
   * Entity can be any type as long as it has user_id field.
   *
   * @param $type
   * @param $uid
   * @return mixed|null
   */
  public static function loadEntityByUserID($type,$uid) {
    $entities = \Drupal::entityManager()->getStorage($type)->loadByProperties(['user_id'=>$uid]);
    if ( $entities ) $entity = reset($entities);
    else $entity = NULL;
    return $entity;
  }


  public static function log ( $str )
  {
    $path_log = "./x8.log";
    $fp_log = fopen($path_log, 'a+');

    if ( ! is_string($str) ) {
      $str = print_r( $str, true );
    }
    self::$count_log ++;
    fwrite($fp_log, self::$count_log . " : $str\n");

    fclose( $fp_log );
  }












  /**
   * @param $k
   * @param $v
   * @refer the definition of user_cookie_save() and you will know.
   */
  public static function set_cookie($k, $v) {
    user_cookie_save([$k=>$v]);
  }
  /**
   * @param $k - is the key of the cookie.
   * @return mixed
   */
  public static function get_cookie($k) {
    return \Drupal::request()->cookies->get("Drupal_visitor_$k");
  }
  /**
   * @param $k
   */
  public static function delete_cookie($k) {
    user_cookie_delete($k);
  }


    /**
     * @param $code
     * @param array $kvs
     * @param string $return_format
     * @return array
     * @code
     * return x::error(x::ERROR_BLANK_CATEGORY_NAME);
     *  x::error(x::ERROR_CATEGORY_EXIST, ['name'=>$name, 'parent'=>$parent_name]);
     * @endcode
     */
  public static function error($code, $kvs=[], $return_format = 'query_string') {
    $message = self::errorMessage($code);
    foreach( $kvs as $k => $v ) {
      $message = str_replace('#'.$k, $v, $message);
    }
      if ( $return_format == 'query_string' ) return "&error=$code&message=$message";
      else if ( $return_format == 'array' ) return [$code, $message];
      else return "&error=$code&message=$message";
  }

  private static function errorMessage($code) {
    switch( $code ) {
      case self::ERROR_CATEGORY_EXIST : $msg = "The category '#name' is already exists under '#parent'."; break;
      case self::ERROR_BLANK_CATEGORY_NAME : $msg = "Category name cannot be blank!."; break;
      case self::ERROR_PLEASE_LOGIN_FIRST : $msg = "Please login first!."; break;
      case self::ERROR_USER_EXISTS : $msg = "The username [ #name ] already exists!."; break;
      case self::ERROR_NOT_YOUR_ID : $msg = "The account that you are trying to edit/delete is not yours."; break;  
      case self::ERROR_NOT_YOUR_POST : $msg = "The item you are trying to edit/delete is not yours."; break;   
      case self::ERROR_MUST_BE_AN_INTEGER : $msg = "#field must be an integer."; break;      
      default: $msg = 'Unknown'; break;
    }
    return $msg;
  }

  /**
   * Returns true if the input object indicates Error.
   *
   * @note if $re is minus or $re[0] is minus, then it considers as error.
   *
   * @param $re
   * @return bool
   */
  public static function isError($re) {
    if ( is_numeric($re) && $re < 0 ) return true;
    else if ( is_array($re) && strpos($re[0], 'ERROR') !== false ) return true;
    return false;
  }
  
  
  
  
  
  
  
  /*------------*/
  
  public static function my( $field )
    {
        if ( $field == 'uid' ) return \Drupal::currentUser()->getAccount()->id();
        else if ( $field == 'uid' ) return \Drupal::currentUser()->getAccount()->getUsername();
        else if ( $field == 'name' ) return \Drupal::currentUser()->getAccount()->getUsername();
        else if ( $field == 'mail' ) return \Drupal::currentUser()->getAccount()->getEmail();
        else {
	        $user = User::load( \Drupal::currentUser()->getAccount()->id() );
	        return x::getExtraField($field, $user);
        }
    }

	
	public static function getExtraField($field, User &$user)
    {

        global $_getExtraField;

	    if ( ! isset($_getExtraField) ) $_getExtraField = [];


	    $field = "field_$field";
	    $uid = $user->id();

	    /**
	     *
	     */
	    if ( isset( $_getExtraField[$uid][ $field ] ) ) {
		    return $_getExtraField[$uid][ $field ];
	    }

	    if ( $user->hasField($field) ) {
		    $value = $user->get($field)->value;
		    if ( ! $value ) $value = '';
	    }
	    else $value = '';




	    $_getExtraField[$uid][ $field ] = $value;
	    return $_getExtraField[$uid][ $field ];

	    /*


        if ( $user === null ) {
            $user = User::load(self::myUid());
        }

        $uid = $user->id();


        if ( isset( $_getExtraField[$uid][ $field ] ) ) return $_getExtraField[$uid][ $field ];

        $value = null;
        $record = $user->get('field_'.$field);
        if ( $record ) {			
            $record_value = $record->getValue();
            if ($record_value && isset($record_value[0]) && isset($record_value[0]['value'])) {
				$value = $record_value[0]['value'];				
            }
        }
	    */
    }

  public static function getDefaultInformation(array &$data) {	
      $uid = x::myUid();
      if ( empty($uid) ) return [];
      else return x::getDefaultInformationByUid($uid, $data);
  }
  
  public static function getDefaultInformationByUid( $uid, array &$data = [] ) {
      $data['user'] = User::load( $uid );
      $data['member'] = Member::loadByUid( $uid );
      return $data;
  }

    /**
     * @param $username
     * @param $password
     * @return int|mixed|null|string
     *      - returns minus value if there is any error.
     *      - or returns User ID.
     */
  public static function registerDrupalUser($username, $password, $email) {

    $user = user_load_by_name($username);
    if ( $user ) return x::ERROR_USER_EXISTS;
    $id = $username;
    $lang = "en";
    $timezone = "Asia/Manila";
    $user = User::create([
      'name'=>$id, // username
      'mail'=>$email,
      'init'=>$email,
      'status'=>1, // whether the user is active or not. Only anonymous is 0. 이 값은 일반적으로 1 이어야 한다.
      'signature'=>$id.'.sig',
      'signature_format'=>'restricted_html',
      'timezone' => $timezone,
      'default_langcode'=>1, // 참고: 이 값을 0 으로 해도, 자동으로 1로 저장 됨.
      'langcode'=>$lang,
      'preferred_langcode'=>$lang,
      'preferred_admin_langcode'=>$lang,
    ]);
    $user->setPassword($password);
    $user->enforceIsNew();
    $user->save();
	
	//added by benjamin for test.. When and where is the UID field saved inside the mall_member aside from this...?
	//Member::set( $user->id(), 'uid', $user->id() );

      return $user->id();
  }

  public static function loginUser($username) {
        $user = user_load_by_name($username);
        user_login_finalize( $user );
        return $user->id();
  }
  
  
  /*added by benjamin*/
  /*
  *delete by uid
  */
  public static function deleteUserByUid( $uid ){
	//clean up mall_member with the uid	
	$member = Member::loadByUid( $uid );	
	$member->delete();
	
	//delete the user entity
	//$user = User::load( $uid );
	//$user->delete();
  }
  
  /*
  *checks user role if the user is an admin
  *requires $uid
  */
  public static function isAdmin(){
	$user = User::load( x::myUid() );

	if( $user->roles->target_id == 'administrator' ) return 1;
	else return 0;
	 
  }

   /*
  *in('uid') should always be available
  *
  */
  public static function isMyAccount(){
	if( self::in('uid') != self::myUid() ){
		$error = x::error(x::ERROR_NOT_YOUR_ID);
		return "?error=".$error[0]."&message=".$error[1];
	  }
	 else{
		return 0;
	 }
  }
  /***********************/
  
  
  
  
  public static function getCategoryChildren( $no ){
	return Category::loadChildren( $no );
  }
  
  public static function getAllCategoryChildren( $no ){
	return Category::loadAllChildren( $no );
  }
  
  public static function getCategoryRoot( $no ){
	return Category::groupRoot( $no );
  }
  
  public static function getCategoryParents( $no ){
	return Category::loadParents( $no );
  }
  
  public static function getCategoryEntity( $id ){
	return Category::getCategoryById( $id );
  }
  
  public static function getProvinces( $limit = 0){
    if( $limit == 0 ) return self::$provinces;
	else return array_slice( self::$provinces, 0, $limit );
  }
  
  public static function getCitiesOf( $province ){
	return self::$cities[ $province ];
  }
  
  public static function getDefaultTimeRange(){
	return self::$time;
  }
  
  public static function getDefaultPriceRange(){
	return self::$price;
  }
  
  public static function getDefaultItemStatus(){
	return self::$item_status;
  }
  
  
  /*test*/
	public static function LinkFileToEntity( $entity_id, $fid, $type ){
		$tags = null;
		$file = \Drupal::entityManager()->getStorage('file')->load($fid);
		\Drupal::service('file.usage')->add( $file, 'mall', $type, $entity_id );
	}
  /*eo test*/
}