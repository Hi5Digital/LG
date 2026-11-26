<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (DB::table('model')->count() > 0) {
            return;
        }

        $countryId = DB::table('lcountry')->insertGetId(['name' => 'South Africa']);

        $gautengId = DB::table('lprovince')->insertGetId(['cid' => $countryId, 'name' => 'Gauteng']);
        $westernCapeId = DB::table('lprovince')->insertGetId(['cid' => $countryId, 'name' => 'Western Cape']);
        $kznId = DB::table('lprovince')->insertGetId(['cid' => $countryId, 'name' => 'KwaZulu-Natal']);

        $towns = [
            'Johannesburg' => DB::table('ltown')->insertGetId(['cid' => $countryId, 'pid' => $gautengId, 'name' => 'Johannesburg']),
            'Pretoria' => DB::table('ltown')->insertGetId(['cid' => $countryId, 'pid' => $gautengId, 'name' => 'Pretoria']),
            'Sandton' => DB::table('ltown')->insertGetId(['cid' => $countryId, 'pid' => $gautengId, 'name' => 'Sandton']),
            'Cape Town' => DB::table('ltown')->insertGetId(['cid' => $countryId, 'pid' => $westernCapeId, 'name' => 'Cape Town']),
            'Stellenbosch' => DB::table('ltown')->insertGetId(['cid' => $countryId, 'pid' => $westernCapeId, 'name' => 'Stellenbosch']),
            'Durban' => DB::table('ltown')->insertGetId(['cid' => $countryId, 'pid' => $kznId, 'name' => 'Durban']),
        ];

        $bodies = [
            'Slim' => DB::table('mbodytype')->insertGetId(['name' => 'Slim']),
            'Athletic' => DB::table('mbodytype')->insertGetId(['name' => 'Athletic']),
            'Curvy' => DB::table('mbodytype')->insertGetId(['name' => 'Curvy']),
            'Average' => DB::table('mbodytype')->insertGetId(['name' => 'Average']),
        ];

        foreach (['A', 'B', 'C', 'D', 'DD'] as $bust) {
            DB::table('mbust')->insert(['name' => $bust]);
        }

        $ethnics = [
            'Black' => DB::table('methnic')->insertGetId(['name' => 'Black']),
            'White' => DB::table('methnic')->insertGetId(['name' => 'White']),
            'Coloured' => DB::table('methnic')->insertGetId(['name' => 'Coloured']),
            'Indian' => DB::table('methnic')->insertGetId(['name' => 'Indian']),
        ];

        $orientations = [
            'Straight' => DB::table('morientation')->insertGetId(['name' => 'Straight']),
            'Bisexual' => DB::table('morientation')->insertGetId(['name' => 'Bisexual']),
        ];

        $profiles = [
            [
                'name' => 'Amahle',
                'surname' => 'Dlamini',
                'email' => 'amahle.dlamini@example.com',
                'mobile' => '0820001001',
                'username' => 'amahle',
                'screenname' => 'Amahle JHB',
                'profile' => 'Based in Johannesburg and available on weekday evenings.',
                'age' => 26,
                'bust' => 'C',
                'btype' => $bodies['Curvy'],
                'orientation' => $orientations['Straight'],
                'ethnic' => $ethnics['Black'],
                'weight' => '62',
                'height' => '168',
                'smoke' => 'n',
                'drink' => 'y',
                'travel' => 'y',
                'verified' => 1,
                'top' => 1,
                'imageverified' => 1,
                'town' => 'Johannesburg',
                'province' => $gautengId,
                'active' => true,
                'days_ago' => 1,
            ],
            [
                'name' => 'Nina',
                'surname' => 'Botha',
                'email' => 'nina.botha@example.com',
                'mobile' => '0820001002',
                'username' => 'nina',
                'screenname' => 'Nina CPT',
                'profile' => 'Cape Town profile. Weekends only, by appointment.',
                'age' => 29,
                'bust' => 'B',
                'btype' => $bodies['Slim'],
                'orientation' => $orientations['Bisexual'],
                'ethnic' => $ethnics['White'],
                'weight' => '55',
                'height' => '172',
                'smoke' => 'n',
                'drink' => 'y',
                'travel' => 'n',
                'verified' => 1,
                'top' => 0,
                'imageverified' => 0,
                'town' => 'Cape Town',
                'province' => $westernCapeId,
                'active' => true,
                'days_ago' => 3,
            ],
            [
                'name' => 'Priya',
                'surname' => 'Naidoo',
                'email' => 'priya.naidoo@example.com',
                'mobile' => '0820001003',
                'username' => 'priya',
                'screenname' => 'Priya DBN',
                'profile' => 'Durban profile. New signup, documents not checked yet.',
                'age' => 24,
                'bust' => 'D',
                'btype' => $bodies['Average'],
                'orientation' => $orientations['Straight'],
                'ethnic' => $ethnics['Indian'],
                'weight' => '64',
                'height' => '165',
                'smoke' => 'n',
                'drink' => 'n',
                'travel' => 'y',
                'verified' => 0,
                'top' => 0,
                'imageverified' => 0,
                'town' => 'Durban',
                'province' => $kznId,
                'active' => true,
                'days_ago' => 5,
            ],
            [
                'name' => 'Leah',
                'surname' => 'Jacobs',
                'email' => 'leah.jacobs@example.com',
                'mobile' => '0820001004',
                'username' => 'leah',
                'screenname' => 'Leah PTA',
                'profile' => 'Pretoria profile. Subscription lapsed last month.',
                'age' => 31,
                'bust' => 'C',
                'btype' => $bodies['Athletic'],
                'orientation' => $orientations['Straight'],
                'ethnic' => $ethnics['Coloured'],
                'weight' => '60',
                'height' => '170',
                'smoke' => 'y',
                'drink' => 'y',
                'travel' => 'n',
                'verified' => 1,
                'top' => 0,
                'imageverified' => 1,
                'town' => 'Pretoria',
                'province' => $gautengId,
                'active' => false,
                'days_ago' => 12,
            ],
            [
                'name' => 'Thandi',
                'surname' => 'Mokoena',
                'email' => 'thandi.mokoena@example.com',
                'mobile' => '0820001005',
                'username' => 'thandi',
                'screenname' => 'Thandi Sandton',
                'profile' => 'Sandton profile. Photos still need to be uploaded.',
                'age' => 27,
                'bust' => 'DD',
                'btype' => $bodies['Curvy'],
                'orientation' => $orientations['Bisexual'],
                'ethnic' => $ethnics['Black'],
                'weight' => '70',
                'height' => '174',
                'smoke' => 'n',
                'drink' => 'y',
                'travel' => 'y',
                'verified' => 0,
                'top' => 1,
                'imageverified' => 0,
                'town' => 'Sandton',
                'province' => $gautengId,
                'active' => true,
                'days_ago' => 8,
            ],
            [
                'name' => 'Chloe',
                'surname' => 'van Wyk',
                'email' => 'chloe.vanwyk@example.com',
                'mobile' => '0820001006',
                'username' => 'chloe',
                'screenname' => 'Chloe Stellies',
                'profile' => 'Stellenbosch profile. Payment is not current.',
                'age' => 34,
                'bust' => 'A',
                'btype' => $bodies['Slim'],
                'orientation' => $orientations['Straight'],
                'ethnic' => $ethnics['White'],
                'weight' => '52',
                'height' => '163',
                'smoke' => 'n',
                'drink' => 'n',
                'travel' => 'n',
                'verified' => 0,
                'top' => 0,
                'imageverified' => 0,
                'town' => 'Stellenbosch',
                'province' => $westernCapeId,
                'active' => false,
                'days_ago' => 18,
            ],
        ];

        foreach ($profiles as $index => $profile) {
            $created = now()->subDays($profile['days_ago'])->setTime(10 + $index, 15);
            $modelId = DB::table('model')->insertGetId([
                'name' => $profile['name'],
                'surname' => $profile['surname'],
                'email' => $profile['email'],
                'mobile' => $profile['mobile'],
                'createdate' => $created,
                'modifydate' => $created,
            ]);

            DB::table('mdetails')->insert([
                'mid' => $modelId,
                'profile' => $profile['profile'],
                'screenname' => $profile['screenname'],
                'age' => $profile['age'],
                'bust' => $profile['bust'],
                'btype' => $profile['btype'],
                'orientation' => $profile['orientation'],
                'ethnic' => $profile['ethnic'],
                'weight' => $profile['weight'],
                'height' => $profile['height'],
                'smoke' => $profile['smoke'],
                'drink' => $profile['drink'],
                'travel' => $profile['travel'],
                'verified' => $profile['verified'],
                'top' => $profile['top'],
                'imageverified' => $profile['imageverified'],
            ]);

            DB::table('mlocation')->insert([
                'mid' => $modelId,
                'lid' => $towns[$profile['town']],
                'pid' => $profile['province'],
                'cid' => $countryId,
            ]);

            DB::table('login')->insert([
                'uid' => $modelId,
                'username' => $profile['username'],
            ]);

            $paidOn = $created->copy()->addDay();
            DB::table('payments')->insert([
                'mid' => $modelId,
                'transactionid' => 'TXN-100'.$modelId,
                'subscriptionid' => 'SUB-200'.$modelId,
                'paymentdate' => $paidOn,
                'validdate' => $profile['active'] ? now()->addDays(20) : now()->subDays(15),
            ]);
        }
    }
}
