<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=[
            ['organization_id'=>0,'uuid'=>'1','category_code'=>'FEATURE','title'=>'新聞與活動','intro'=>'新聞部分是用來發布組織的最新消息和事件，向公眾展示其在社會和環境領域中所做的工作，並提高對其使命的認識和理解。 ','tags'=>'["通告"]','url'=>'/','thumbnail'=>'/features/1_news_events.png','user_id'=>1,'public'=>0,'published'=>0,'sequence'=>0],
            ['organization_id'=>0,'uuid'=>'2','category_code'=>'FEATURE','title'=>'表格及報名','intro'=>'包括各種類型的表格和報名表，方便訪問者提交相關資訊並表達他們的參與意願。','tags'=>'["報名"]','url'=>'/forms','thumbnail'=>'/features/2_form_application.png','user_id'=>1,'public'=>0,'published'=>0,'sequence'=>0],
            ['organization_id'=>0,'uuid'=>'3','category_code'=>'FEATURE','title'=>'學習興分享','intro'=>'提供教育資源、知識分享和學習機會的區域。提供有價值的學習內容，並促進知識交流和社群互動。','tags'=>'["學習","分享"]','url'=>'#','thumbnail'=>'/features/3_learn_share.png','user_id'=>1,'public'=>0,'published'=>0,'sequence'=>0],
            ['organization_id'=>0,'uuid'=>'4','category_code'=>'FEATURE','title'=>'虛擬社區','intro'=>'交流和參與組織活動的線上平台。讓志願者、支持者和參與者能夠連結起來，分享資源、經驗和想法。','tags'=>'["學習","交流"]','url'=>'#','thumbnail'=>'/features/4_community.png','user_id'=>1,'public'=>0,'published'=>0,'sequence'=>0],
            ['organization_id'=>11,'uuid'=>'11111111','category_code'=>'FEATURE','title'=>'新聞與活動','intro'=>'新聞部分是用來發布組織的最新消息和事件，向公眾展示其在社會和環境領域中所做的工作，並提高對其使命的認識和理解。 ','tags'=>'["通告"]','url'=>'/','thumbnail'=>'/features/1_news_events.png','user_id'=>1,'public'=>0,'published'=>1,'sequence'=>0],
            ['organization_id'=>11,'uuid'=>'22222222','category_code'=>'FEATURE','title'=>'表格及報名','intro'=>'包括各種類型的表格和報名表，方便訪問者提交相關資訊並表達他們的參與意願。','tags'=>'["報名"]','url'=>'/forms','thumbnail'=>'/features/2_form_application.png','user_id'=>1,'public'=>0,'published'=>1,'sequence'=>0],
            ['organization_id'=>11,'uuid'=>'33333333','category_code'=>'FEATURE','title'=>'學習興分享','intro'=>'提供教育資源、知識分享和學習機會的區域。提供有價值的學習內容，並促進知識交流和社群互動。','tags'=>'["學習","分享"]','url'=>'#','thumbnail'=>'/features/3_learn_share.png','user_id'=>1,'public'=>0,'published'=>1,'sequence'=>0],
            ['organization_id'=>11,'uuid'=>'44444444','category_code'=>'FEATURE','title'=>'虛擬社區','intro'=>'交流和參與組織活動的線上平台。讓志願者、支持者和參與者能夠連結起來，分享資源、經驗和想法。','tags'=>'["學習","交流"]','url'=>'#','thumbnail'=>'/features/4_community.png','user_id'=>1,'public'=>0,'published'=>1,'sequence'=>0],
            ['organization_id'=>11,
                'category_code'=>'NEWS',
                'uuid'=>'55555555',
                'title'=>'敏捷項目管理講座圓滿舉行',
                'intro'=>'疫情後首個線下講座「敏捷項目管理講座」圓滿舉行。',
                'content'=>'<p>疫情後首個線下講座「敏捷項目管理講座」圓滿舉行。感謝 許朝妍女士深入淺出為大家講解敏捷項目管理要點</p><figure class="image"><img src="https://mpmpa.org.mo/images/2022/11/26/WeChat_%E5%9C%96%E7%89%87_20221128112124.jpg"></figure>',
                'tags'=>'["學習","交流"]',
                'url'=>'#',
                'thumbnail'=>'/features/4_community.png',
                'user_id'=>1,
                'public'=>0,
                'published'=>1,
                'sequence'=>0
            ],
            ['organization_id'=>11,
            'category_code'=>'NEWS',
            'uuid'=>'66666666',
            'title'=>'（活動報名）慶祝六一兒童節填色比賽',
            'intro'=>'',
            'content'=>'澳門公務人員聯合總會青年委員會及行政公職局聯合舉辦『慶祝六一兒童節填色比賽』。優勝者將有豐富的獎賞及獎狀。每位參賽者交稿時可獲紀念品一份，歡迎本澳公務人員就讀幼稚園/小學之子女參加。',
            'tags'=>'["學習","交流"]',
            'url'=>'/forms/3',
            'thumbnail'=>'',
            'user_id'=>1,
            'public'=>1,
            'published'=>1,
            'sequence'=>0
        ]

            
            // ['organization_id'=>11,'sequence'=>1,'title_zh'=>'新聞與活動','content_zh'=>'新聞部分是用來發布組織的最新消息和事件，向公眾展示其在社會和環境領域中所做的工作，並提高對其使命的認識和理解。 ','tags_zh'=>'["通告"]','url'=>'#','image'=>'/features/1_news_events.png'],
            // ['organization_id'=>11,'sequence'=>1,'title_zh'=>'表格及報名','content_zh'=>'包括各種類型的表格和報名表，方便訪問者提交相關資訊並表達他們的參與意願。','tags_zh'=>'["報名"]','url'=>'#','image'=>'/features/2_form_application.png'],
            // ['organization_id'=>11,'sequence'=>1,'title_zh'=>'學習興分享','content_zh'=>'提供教育資源、知識分享和學習機會的區域。提供有價值的學習內容，並促進知識交流和社群互動。','tags_zh'=>'["學習","分享"]','url'=>'#','image'=>'/features/3_learn_share.png'],
            // ['organization_id'=>11,'sequence'=>1,'title_zh'=>'虛擬社區','content_zh'=>'交流和參與組織活動的線上平台。讓志願者、支持者和參與者能夠連結起來，分享資源、經驗和想法。','tags_zh'=>'["學習","交流"]','url'=>'#','image'=>'/features/4_community.png'],
        ];
        foreach($data as $d){
            DB::table('articles')->insert($d);
        }
    }
}
