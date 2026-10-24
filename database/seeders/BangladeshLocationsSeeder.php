<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Division;
use App\Models\Thana;
use Illuminate\Database\Seeder;

class BangladeshLocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            'Dhaka' => [
                'bn' => 'ঢাকা',
                'districts' => [
                    'Dhaka' => [
                        'bn' => 'ঢাকা',
                        'thanas' => ['Dhanmondi', 'Gulshan', 'Banani', 'Uttara', 'Mirpur', 'Mohammadpur', 'Motijheel', 'Tejgaon', 'Badda', 'Khilgaon', 'Rampura', 'Paltan', 'Shahbagh', 'New Market', 'Lalbagh', 'Kotwali', 'Sutrapur', 'Demra', 'Jatrabari', 'Keraniganj', 'Savar', 'Dhamrai', 'Nawabganj', 'Dohar'],
                    ],
                    'Gazipur' => [
                        'bn' => 'গাজীপুর',
                        'thanas' => ['Gazipur Sadar', 'Tongi', 'Kaliakair', 'Kapasia', 'Sreepur', 'Kaliganj'],
                    ],
                    'Narayanganj' => [
                        'bn' => 'নারায়ণগঞ্জ',
                        'thanas' => ['Narayanganj Sadar', 'Bandar', 'Fatullah', 'Siddhirganj', 'Araihazar', 'Sonargaon', 'Rupganj'],
                    ],
                    'Tangail' => [
                        'bn' => 'টাঙ্গাইল',
                        'thanas' => ['Tangail Sadar', 'Mirzapur', 'Ghatail', 'Madhupur', 'Sakhipur', 'Kalihati', 'Basail', 'Gopalpur', 'Delduar', 'Bhuapur', 'Nagarpur', 'Dhanbari'],
                    ],
                    'Faridpur' => [
                        'bn' => 'ফরিদপুর',
                        'thanas' => ['Faridpur Sadar', 'Boalmari', 'Alfadanga', 'Madhukhali', 'Bhanga', 'Nagarkanda', 'Charbhadrasan', 'Sadarpur', 'Saltha'],
                    ],
                    'Gopalganj' => [
                        'bn' => 'গোপালগঞ্জ',
                        'thanas' => ['Gopalganj Sadar', 'Kashiani', 'Kotalipara', 'Muksudpur', 'Tungipara'],
                    ],
                    'Kishoreganj' => [
                        'bn' => 'কিশোরগঞ্জ',
                        'thanas' => ['Kishoreganj Sadar', 'Bhairab', 'Bajitpur', 'Kuliarchar', 'Pakundia', 'Katiadi', 'Karimganj', 'Tarail', 'Hossainpur', 'Itna', 'Mithamain', 'Nikli', 'Ashtagram'],
                    ],
                    'Madaripur' => [
                        'bn' => 'মাদারীপুর',
                        'thanas' => ['Madaripur Sadar', 'Shibchar', 'Kalkini', 'Rajoir', 'Dasar'],
                    ],
                    'Manikganj' => [
                        'bn' => 'মানিকগঞ্জ',
                        'thanas' => ['Manikganj Sadar', 'Singair', 'Saturia', 'Ghior', 'Shivalaya', 'Harirampur', 'Daulatpur'],
                    ],
                    'Munshiganj' => [
                        'bn' => 'মুন্সীগঞ্জ',
                        'thanas' => ['Munshiganj Sadar', 'Sreenagar', 'Sirajdikhan', 'Louhajang', 'Tongibari', 'Gazaria'],
                    ],
                    'Narsingdi' => [
                        'bn' => 'নরসিংদী',
                        'thanas' => ['Narsingdi Sadar', 'Palash', 'Shibpur', 'Raipura', 'Belabo', 'Monohardi'],
                    ],
                    'Rajbari' => [
                        'bn' => 'রাজবাড়ী',
                        'thanas' => ['Rajbari Sadar', 'Goalanda', 'Pangsha', 'Baliakandi', 'Kalukhali'],
                    ],
                    'Shariatpur' => [
                        'bn' => 'শরীয়তপুর',
                        'thanas' => ['Shariatpur Sadar', 'Zajira', 'Naria', 'Bhedarganj', 'Damudya', 'Gosairhat'],
                    ],
                ],
            ],
            'Chattogram' => [
                'bn' => 'চট্টগ্রাম',
                'districts' => [
                    'Chattogram' => [
                        'bn' => 'চট্টগ্রাম',
                        'thanas' => ['Kotwali', 'Panchlaish', 'Pahartali', 'Halishahar', 'Agrabad', 'Khulshi', 'Chandgaon', 'Bakalia', 'Bayazid', 'Patenga', 'Double Mooring', 'Hathazari', 'Sitakunda', 'Mirsharai', 'Patiya', 'Raozan', 'Rangunia', 'Boalkhali', 'Anwara', 'Chandanaish', 'Satkania', 'Lohagara', 'Banshkhali', 'Fatikchhari', 'Karnaphuli', 'Sandwip'],
                    ],
                    'Cox\'s Bazar' => [
                        'bn' => 'কক্সবাজার',
                        'thanas' => ['Cox\'s Bazar Sadar', 'Ramu', 'Chakaria', 'Pekua', 'Maheshkhali', 'Kutubdia', 'Ukhiya', 'Teknaf'],
                    ],
                    'Cumilla' => [
                        'bn' => 'কুমিল্লা',
                        'thanas' => ['Cumilla Adarsha Sadar', 'Cumilla Sadar Dakshin', 'Barura', 'Brahmanpara', 'Burichang', 'Chandina', 'Chauddagram', 'Daudkandi', 'Debidwar', 'Homna', 'Laksam', 'Monohorgonj', 'Meghna', 'Muradnagar', 'Nangalkot', 'Titas', 'Lalmai'],
                    ],
                    'Feni' => [
                        'bn' => 'ফেনী',
                        'thanas' => ['Feni Sadar', 'Chhagalnaiya', 'Daganbhuiyan', 'Parshuram', 'Fulgazi', 'Sonagazi'],
                    ],
                    'Brahmanbaria' => [
                        'bn' => 'ব্রাহ্মণবাড়িয়া',
                        'thanas' => ['Brahmanbaria Sadar', 'Kasba', 'Nasirnagar', 'Nabinagar', 'Bancharampur', 'Sarail', 'Ashuganj', 'Akhaura', 'Bijoynagar'],
                    ],
                    'Chandpur' => [
                        'bn' => 'চাঁদপুর',
                        'thanas' => ['Chandpur Sadar', 'Faridganj', 'Haimchar', 'Haziganj', 'Kachua', 'Matlab Dakshin', 'Matlab Uttar', 'Shahrasti'],
                    ],
                    'Noakhali' => [
                        'bn' => 'নোয়াখালী',
                        'thanas' => ['Noakhali Sadar', 'Begumganj', 'Chatkhil', 'Companiganj', 'Hatiya', 'Senbagh', 'Sonaimuri', 'Subarnachar', 'Kabirhat'],
                    ],
                    'Lakshmipur' => [
                        'bn' => 'লক্ষ্মীপুর',
                        'thanas' => ['Lakshmipur Sadar', 'Raipur', 'Ramganj', 'Ramgati', 'Kamalnagar'],
                    ],
                    'Rangamati' => [
                        'bn' => 'রাঙ্গামাটি',
                        'thanas' => ['Rangamati Sadar', 'Kaptai', 'Bagaichhari', 'Barkal', 'Langadu', 'Rajasthali', 'Belaichhari', 'Juraichhari', 'Naniarchar'],
                    ],
                    'Bandarban' => [
                        'bn' => 'বান্দরবান',
                        'thanas' => ['Bandarban Sadar', 'Ruma', 'Thanchi', 'Rowangchhari', 'Lama', 'Alikadam', 'Naikhongchhari'],
                    ],
                    'Khagrachhari' => [
                        'bn' => 'খাগড়াছড়ি',
                        'thanas' => ['Khagrachhari Sadar', 'Dighinala', 'Panchhari', 'Mahalchhari', 'Matiranga', 'Manikchhari', 'Ramgarh', 'Lakshmichhari', 'Guimara'],
                    ],
                ],
            ],
            'Sylhet' => [
                'bn' => 'সিলেট',
                'districts' => [
                    'Sylhet' => [
                        'bn' => 'সিলেট',
                        'thanas' => ['Sylhet Sadar', 'Kotwali', 'South Surma', 'Beanibazar', 'Golapganj', 'Balaganj', 'Fenchuganj', 'Bishwanath', 'Zakiganj', 'Kanaighat', 'Companiganj', 'Gowainghat', 'Jaintiapur', 'Osmani Nagar'],
                    ],
                    'Moulvibazar' => [
                        'bn' => 'মৌলভীবাজার',
                        'thanas' => ['Moulvibazar Sadar', 'Sreemangal', 'Kamalganj', 'Kulaura', 'Rajnagar', 'Barlekha', 'Juri'],
                    ],
                    'Habiganj' => [
                        'bn' => 'হবিগঞ্জ',
                        'thanas' => ['Habiganj Sadar', 'Nabiganj', 'Madhabpur', 'Chunarughat', 'Bahubal', 'Baniachong', 'Lakhai', 'Ajmiriganj', 'Sayestaganj'],
                    ],
                    'Sunamganj' => [
                        'bn' => 'সুনামগঞ্জ',
                        'thanas' => ['Sunamganj Sadar', 'Chhatak', 'Jagannathpur', 'Derai', 'Dharampasha', 'Tahirpur', 'Jamalganj', 'Bishwamvarpur', 'Shantiganj', 'Dowarabazar', 'Shalla', 'Madhyanagar'],
                    ],
                ],
            ],
            'Rajshahi' => [
                'bn' => 'রাজশাহী',
                'districts' => [
                    'Rajshahi' => [
                        'bn' => 'রাজশাহী',
                        'thanas' => ['Boalia', 'Motihar', 'Rajpara', 'Shah Makhdum', 'Paba', 'Godagari', 'Tanore', 'Mohanpur', 'Bagmara', 'Durgapur', 'Puthia', 'Charghat', 'Bagha'],
                    ],
                    'Bogura' => [
                        'bn' => 'বগুড়া',
                        'thanas' => ['Bogura Sadar', 'Shajahanpur', 'Sherpur', 'Shibganj', 'Kahaloo', 'Nandigram', 'Gabtali', 'Sariakandi', 'Sonatala', 'Dhunat', 'Adamdighi', 'Dupchanchia'],
                    ],
                    'Pabna' => [
                        'bn' => 'পাবনা',
                        'thanas' => ['Pabna Sadar', 'Ishwardi', 'Sujanagar', 'Santhia', 'Bera', 'Chatmohar', 'Bhangura', 'Faridpur', 'Atgharia'],
                    ],
                    'Sirajganj' => [
                        'bn' => 'সিরাজগঞ্জ',
                        'thanas' => ['Sirajganj Sadar', 'Shahjadpur', 'Ullapara', 'Belkuchi', 'Kazipur', 'Kamarkhanda', 'Raiganj', 'Tarash', 'Chauhali'],
                    ],
                    'Naogaon' => [
                        'bn' => 'নওগাঁ',
                        'thanas' => ['Naogaon Sadar', 'Manda', 'Mohadevpur', 'Patnitala', 'Dhamoirhat', 'Niamatpur', 'Raninagar', 'Atrai', 'Badalgachhi', 'Porsha', 'Sapahar'],
                    ],
                    'Natore' => [
                        'bn' => 'নাটোর',
                        'thanas' => ['Natore Sadar', 'Singra', 'Baraigram', 'Bagatipara', 'Gurudaspur', 'Lalpur', 'Naldanga'],
                    ],
                    'Chapai Nawabganj' => [
                        'bn' => 'চাঁপাইনবাবগঞ্জ',
                        'thanas' => ['Chapai Nawabganj Sadar', 'Shibganj', 'Gomastapur', 'Nachole', 'Bholahat'],
                    ],
                    'Joypurhat' => [
                        'bn' => 'জয়পুরহাট',
                        'thanas' => ['Joypurhat Sadar', 'Panchbibi', 'Kalai', 'Khetlal', 'Akkelpur'],
                    ],
                ],
            ],
            'Khulna' => [
                'bn' => 'খুলনা',
                'districts' => [
                    'Khulna' => [
                        'bn' => 'খুলনা',
                        'thanas' => ['Khulna Sadar', 'Sonadanga', 'Khalishpur', 'Daulatpur', 'Khan Jahan Ali', 'Rupsha', 'Dighalia', 'Dumuria', 'Batiaghata', 'Terokhada', 'Phultala', 'Paikgachha', 'Koyra', 'Dacope'],
                    ],
                    'Jashore' => [
                        'bn' => 'যশোর',
                        'thanas' => ['Jashore Sadar', 'Jhikargachha', 'Manirampur', 'Abhaynagar', 'Keshabpur', 'Bagherpara', 'Chaugachha', 'Sharsha'],
                    ],
                    'Kushtia' => [
                        'bn' => 'কুষ্টিয়া',
                        'thanas' => ['Kushtia Sadar', 'Kumarkhali', 'Khoksa', 'Mirpur', 'Bheramara', 'Daulatpur'],
                    ],
                    'Bagerhat' => [
                        'bn' => 'বাগেরহাট',
                        'thanas' => ['Bagerhat Sadar', 'Mongla', 'Fakirhat', 'Rampal', 'Morrelganj', 'Kachua', 'Mollahat', 'Chitalmari', 'Sarankhola'],
                    ],
                    'Satkhira' => [
                        'bn' => 'সাতক্ষীরা',
                        'thanas' => ['Satkhira Sadar', 'Kalaroa', 'Tala', 'Kaliganj', 'Shyamnagar', 'Debhata', 'Assasuni'],
                    ],
                    'Chuadanga' => [
                        'bn' => 'চুয়াডাঙ্গা',
                        'thanas' => ['Chuadanga Sadar', 'Alamdanga', 'Damurhuda', 'Jibannagar'],
                    ],
                    'Jhenaidah' => [
                        'bn' => 'ঝিনাইদহ',
                        'thanas' => ['Jhenaidah Sadar', 'Kaliganj', 'Kotchandpur', 'Maheshpur', 'Shailkupa', 'Harinakunda'],
                    ],
                    'Magura' => [
                        'bn' => 'মাগুরা',
                        'thanas' => ['Magura Sadar', 'Sreepur', 'Mohammadpur', 'Shalikha'],
                    ],
                    'Meherpur' => [
                        'bn' => 'মেহেরপুর',
                        'thanas' => ['Meherpur Sadar', 'Gangni', 'Mujibnagar'],
                    ],
                    'Narail' => [
                        'bn' => 'নড়াইল',
                        'thanas' => ['Narail Sadar', 'Lohagara', 'Kalia'],
                    ],
                ],
            ],
            'Barishal' => [
                'bn' => 'বরিশাল',
                'districts' => [
                    'Barishal' => [
                        'bn' => 'বরিশাল',
                        'thanas' => ['Kotwali', 'Airport', 'Kawnia', 'Bakerganj', 'Babuganj', 'Wazirpur', 'Banaripara', 'Gournadi', 'Agailjhara', 'Muladi', 'Hizla', 'Mehendiganj'],
                    ],
                    'Bhola' => [
                        'bn' => 'ভোলা',
                        'thanas' => ['Bhola Sadar', 'Daulatkhan', 'Borhanuddin', 'Lalmohan', 'Char Fasson', 'Tazumuddin', 'Monpura'],
                    ],
                    'Patuakhali' => [
                        'bn' => 'পটুয়াখালী',
                        'thanas' => ['Patuakhali Sadar', 'Dumki', 'Bauphal', 'Galachipa', 'Dashmina', 'Kalapara', 'Mirzaganj', 'Rangabali'],
                    ],
                    'Pirojpur' => [
                        'bn' => 'পিরোজপুর',
                        'thanas' => ['Pirojpur Sadar', 'Nesarabad (Swarupkati)', 'Bhandaria', 'Mathbaria', 'Kawkhali', 'Nazirpur', 'Zianagar (Indurkani)'],
                    ],
                    'Barguna' => [
                        'bn' => 'বরগুনা',
                        'thanas' => ['Barguna Sadar', 'Amtali', 'Patharghata', 'Betagi', 'Bamna', 'Taltali'],
                    ],
                    'Jhalokati' => [
                        'bn' => 'ঝালকাঠি',
                        'thanas' => ['Jhalokati Sadar', 'Nalchity', 'Rajapur', 'Kathalia'],
                    ],
                ],
            ],
            'Rangpur' => [
                'bn' => 'রংপুর',
                'districts' => [
                    'Rangpur' => [
                        'bn' => 'রংপুর',
                        'thanas' => ['Kotwali', 'Tajhat', 'Mahiganj', 'Haragach', 'Parshuram', 'Hazirhat', 'Badarganj', 'Gangachhara', 'Kaunia', 'Mithapukur', 'Pirgachha', 'Pirganj', 'Taraganj'],
                    ],
                    'Dinajpur' => [
                        'bn' => 'দিনাজপুর',
                        'thanas' => ['Dinajpur Sadar', 'Birganj', 'Kaharole', 'Bochaganj', 'Birol', 'Parbatipur', 'Phulbari', 'Nawabganj', 'Ghoraghat', 'Hakimpur', 'Chirirbandar', 'Khansama', 'Biral'],
                    ],
                    'Gaibandha' => [
                        'bn' => 'গাইবান্ধা',
                        'thanas' => ['Gaibandha Sadar', 'Sadullapur', 'Palashbari', 'Gobindaganj', 'Sundarganj', 'Saghata', 'Phulchhari'],
                    ],
                    'Kurigram' => [
                        'bn' => 'কুড়িগ্রাম',
                        'thanas' => ['Kurigram Sadar', 'Nageshwari', 'Bhurungamari', 'Phulbari', 'Rajarhat', 'Ulipur', 'Chilmari', 'Rowmari', 'Char Rajibpur'],
                    ],
                    'Lalmonirhat' => [
                        'bn' => 'লালমনিরহাট',
                        'thanas' => ['Lalmonirhat Sadar', 'Aditmari', 'Kaliganj', 'Hatibandha', 'Patgram'],
                    ],
                    'Nilphamari' => [
                        'bn' => 'নীলফামারী',
                        'thanas' => ['Nilphamari Sadar', 'Saidpur', 'Jaldhaka', 'Kishoreganj', 'Domar', 'Dimla'],
                    ],
                    'Panchagarh' => [
                        'bn' => 'পঞ্চগড়',
                        'thanas' => ['Panchagarh Sadar', 'Boda', 'Debiganj', 'Atwari', 'Tetulia'],
                    ],
                    'Thakurgaon' => [
                        'bn' => 'ঠাকুরগাঁও',
                        'thanas' => ['Thakurgaon Sadar', 'Pirganj', 'Ranisankail', 'Baliadangi', 'Haripur'],
                    ],
                ],
            ],
            'Mymensingh' => [
                'bn' => 'ময়মনসিংহ',
                'districts' => [
                    'Mymensingh' => [
                        'bn' => 'ময়মনসিংহ',
                        'thanas' => ['Kotwali', 'Muktagachha', 'Fulbaria', 'Trishal', 'Bhaluka', 'Gaffargaon', 'Nandail', 'Ishwarganj', 'Haluaghat', 'Dhobaura', 'Gouripur', 'Phulpur', 'Tara Khanda'],
                    ],
                    'Jamalpur' => [
                        'bn' => 'জামালপুর',
                        'thanas' => ['Jamalpur Sadar', 'Sarishabari', 'Melandaha', 'Islampur', 'Dewanganj', 'Madarganj', 'Bakshiganj'],
                    ],
                    'Netrokona' => [
                        'bn' => 'নেত্রকোণা',
                        'thanas' => ['Netrokona Sadar', 'Kendua', 'Atpara', 'Barhatta', 'Mohanganj', 'Madan', 'Khaliajuri', 'Durgapur', 'Kalmakanda', 'Purbadhala'],
                    ],
                    'Sherpur' => [
                        'bn' => 'শেরপুর',
                        'thanas' => ['Sherpur Sadar', 'Nalitabari', 'Nakla', 'Sreebardi', 'Jhenaigati'],
                    ],
                ],
            ],
        ];

        foreach ($locations as $divName => $divData) {
            $division = Division::firstOrCreate(
                ['name' => $divName],
                ['bn_name' => $divData['bn']]
            );

            foreach ($divData['districts'] as $distName => $distData) {
                $district = District::firstOrCreate(
                    [
                        'division_id' => $division->id,
                        'name' => $distName,
                    ],
                    ['bn_name' => $distData['bn']]
                );

                foreach ($distData['thanas'] as $thanaName) {
                    Thana::firstOrCreate([
                        'district_id' => $district->id,
                        'name' => $thanaName,
                    ]);
                }
            }
        }
    }
}
