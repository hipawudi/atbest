<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use App\Models\Member;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Organization::factory()->count(10)->create();
        // $organization=Organization::find(1);
        // $organization->abbr="MJA";
        // $organization->full_name="澳門柔道協會";
        // $organization->save();  

  

        $data=[
            ["registration_code"=>"","parish"=>"M","name_zh"=>"預留1","abbr"=>"RSV1","name_en"=>"Reserve 1","address"=>"","phone"=>"","href"=>"atbest.net","email"=>"","president"=>"--","card_style"=>"card_01"],
            ["registration_code"=>"","parish"=>"M","name_zh"=>"預留1","abbr"=>"RSV1","name_en"=>"Reserve 1","address"=>"","phone"=>"","href"=>"atbest.net","email"=>"","president"=>"--","card_style"=>"card_01"],
            ["registration_code"=>"","parish"=>"M","name_zh"=>"預留1","abbr"=>"RSV1","name_en"=>"Reserve 1","address"=>"","phone"=>"","href"=>"atbest.net","email"=>"","president"=>"--","card_style"=>"card_01"],
            ["registration_code"=>"","parish"=>"M","name_zh"=>"預留1","abbr"=>"RSV1","name_en"=>"Reserve 1","address"=>"","phone"=>"","href"=>"atbest.net","email"=>"","president"=>"--","card_style"=>"card_01"],
            ["registration_code"=>"","parish"=>"M","name_zh"=>"預留1","abbr"=>"RSV1","name_en"=>"Reserve 1","address"=>"","phone"=>"","href"=>"atbest.net","email"=>"","president"=>"--","card_style"=>"card_01"],
            ["registration_code"=>"","parish"=>"M","name_zh"=>"預留1","abbr"=>"RSV1","name_en"=>"Reserve 1","address"=>"","phone"=>"","href"=>"atbest.net","email"=>"","president"=>"--","card_style"=>"card_01"],
            ["registration_code"=>"","parish"=>"M","name_zh"=>"預留1","abbr"=>"RSV1","name_en"=>"Reserve 1","address"=>"","phone"=>"","href"=>"atbest.net","email"=>"","president"=>"--","card_style"=>"card_01"],
            ["registration_code"=>"","parish"=>"M","name_zh"=>"澳門教育發展研究學會","abbr"=>"MEDRA","name_en"=>"Macao Education Development and Research Association","address"=>"","phone"=>"","href"=>"atbest.net","email"=>"","president"=>"--","card_style"=>"card_01"],
            ["registration_code"=>"","parish"=>"M","name_zh"=>"澳門科技教育協會","abbr"=>"STEAM","name_en"=>"Science and Technology Education Association of Macau","address"=>"","phone"=>"","href"=>"atbest.net","email"=>"","president"=>"--","card_style"=>"card_01"],
            ["registration_code"=>"","parish"=>"S","name_zh"=>"澳門項目管理師協會","abbr"=>"MPMPA","name_en"=>"Macao Project Management Professional Association","address"=>"澳門羅理基博士大馬路600-E號, 第一國際商業中心P22-08","phone"=>"","href"=>"mpmpa.org.mo","email"=>"info@mpmpa.org.mo","president"=>"--","card_style"=>"card_01"],
            ["registration_code"=>"112233","parish"=>"S","name_zh"=>"公務人員聯合總會","abbr"=>"MCSF","name_en"=>"Macau Civil Servants Federation","address"=>"澳門荷蘭園二馬路十一號荷蘭園大廈三樓A單位","phone"=>"","href"=>"atbest.net","email"=>"","president"=>"--","card_style"=>"card_01"],
            ["registration_code"=>"","parish"=>"S","name_zh"=>"公務華員職工會","abbr"=>"MCCSA","name_en"=>"Macao Chinese Civil Servants Association","address"=>"澳門荷蘭園二馬路十一號荷蘭園大廈三樓A單位","phone"=>"","href"=>"atbest.net","email"=>"","president"=>"--","card_style"=>"card_01"],
            ["registration_code"=>"","parish"=>"S","name_zh"=>"公務高級技術員協會","abbr"=>"ATSFPM","name_en"=>"Associação dos Técnicos Superiores da Função Pública de Macau",'address'=>"澳門荷蘭園二馬路十一號荷蘭園大廈三樓A單位","phone"=>"","href"=>"atbest.net","email"=>"","president"=>"--","card_style"=>"card_01"],
            ["registration_code"=>"","parish"=>"S","name_zh"=>"公務文職人員協會","abbr"=>"CCSAM","name_en"=>"Clerical Civil Servants Association of Macau",'address'=>"澳門荷蘭園二馬路十一號荷蘭園大廈三樓A單位","phone"=>"","href"=>"atbest.net","email"=>"","president"=>"--","card_style"=>"card_01"],
            ["registration_code"=>"","parish"=>"S","name_zh"=>"市政署員工協進會","abbr"=>"AWCMAI","name_en"=>"Association of Workers of Civil and Municipal Affairs Institute",'address'=>"澳門荷蘭園二馬路十一號荷蘭園大廈三樓A單位","phone"=>"","href"=>"atbest.net","email"=>"","president"=>"--","card_style"=>"card_01"],
            ["registration_code"=>"","parish"=>"S","name_zh"=>"中國澳門電子競技運動大聯盟總會","abbr"=>"MESUF","name_en"=>"Macao eSport Channel",'address'=>"","phone"=>"","href"=>"atbest.net","email"=>"","president"=>"--","card_style"=>"card_01"]
        ];
        foreach($data as $org){
            Organization::create($org);
        }
        $organization=Organization::find(11);
        $user=User::where('email','organizer@example.com')->get();
        $organization->users()->attach($user);
        $user=User::where('email','member1@example.com')->get();
        $organization->users()->attach($user);

        Member::whereBetween('id',[1,3])->update(['organization_id'=>$organization->id]);
        // $member=Member::find(1);
        // $organization->members()->attach($member);
        // $member=Member::find(2);
        // $organization->members()->attach($member);
        // $member=Member::find(3);
        // $organization->members()->attach($member);


    }
}

