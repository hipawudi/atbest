<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Config;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'organization_id' => 0, 'key' => 'article_categories', 'value' =>
                '[{"value":"FEATURE","label":"Feature"},{"value":"BLOG","label":"Blog"},{"value":"NEWS","label":"News"}]'
            ],
            [
                'organization_id' => 0, 'key' => 'bulletin_categories', 'value' =>
                '[{"value": "CMTE","label": "Committee"}, {"value": "GEN","label": "General"}]',
            ],
            [
                'organization_id' => 0, 'key' => 'message_categories', 'value' =>
                '[{"value": "ALL","label": "Everyone"}, 
                  {"value": "ORG","label": "Organization"},
                  {"value": "MEM","label": "Member Only"},
                  {"value": "IND","label": "Individual"}
                 ]',
            ],
            [
                'organization_id' => 0, 'key' => 'event_categories', 'value' =>
                '[{
                    "value": "TRANING",
                    "label": "Traning"
                }, {
                    "value": "COURSE",
                    "label": "Course"
                }]'
            ],
            [
                'organization_id' => 0, 'key' => 'certificate_categories', 'value' =>
                '[{
                    "value": "BELT",
                    "label": "Belt"
                }, {
                    "value": "REFEREE",
                    "label": "Referee"
                }, {
                    "value": "COACH",
                    "label": "Coach"
                }, {
                    "value": "OTHER",
                    "label": "Other"
                }]',
            ],
            [
                'organization_id' => 0, 'key' => 'attendance_status', 'value' =>
                '[{
                        "value": "ATTEND",
                        "label": "Attend"
                    }, {
                        "value": "LATE",
                        "label": "Late"
                    }, {
                        "value": "EXCUSE",
                        "label": "Excuse"
                    }, {
                        "value": "ABSENT",
                        "label": "Absent"
                }]'
            ],
            [
                'organization_id' => 0, 'key' => 'card_styles', 'value' =>
                '{"card_01": {"name":"Card 01","background": "card_01.png","logo": "site_logo.png","font_style": "font-family:sans-serif;color:#c24641"},
                  "card_02": {"name":"Card 02","background": "card_02.png","logo": "site_logo.png","font_style": "font-family:sans-serif;color:white"},
                  "card_03": {"name":"Card 03","background": "card_03.png","logo": "site_logo.png","font_style": "font-family:sans-serif;color:white"},
                  "card_04": {"name":"Card 04","background": "card_03.png","logo": "site_logo.png","font_style": "font-family:sans-serif;color:white"},
                  "card_05": {"name":"Card 05","background": "card_03.png","logo": "site_logo.png","font_style": "font-family:sans-serif;color:white"},
                  "card_06": {"name":"Card 06","background": "card_03.png","logo": "site_logo.png","font_style": "font-family:sans-serif;color:white"},
                  "card_07": {"name":"Card 07","background": "card_03.png","logo": "site_logo.png","font_style": "font-family:sans-serif;color:white"},
                  "card_08": {"name":"Card 08","background": "card_03.png","logo": "site_logo.png","font_style": "font-family:sans-serif;color:white"},
                  "card_09": {"name":"Card 09","background": "card_03.png","logo": "site_logo.png","font_style": "font-family:sans-serif;color:white"},
                  "card_10": {"name":"Card 10","background": "card_03.png","logo": "site_logo.png","font_style": "font-family:sans-serif;color:white"},
                  "card_11": {"name":"Card 11","background": "card_03.png","logo": "site_logo.png","font_style": "font-family:sans-serif;color:white"},
                  "card_12": {"name":"Card 12","background": "card_03.png","logo": "site_logo.png","font_style": "font-family:sans-serif;color:white"},
                  "card_13": {"name":"Card 13","background": "card_03.png","logo": "site_logo.png","font_style": "font-family:sans-serif;color:white"},
                  "card_14": {"name":"Card 14","background": "card_03.png","logo": "site_logo.png","font_style": "font-family:sans-serif;color:white"}
                }'
            ],
            [
                'organization_id' => 0, 'key' => 'parishes', 'value' =>
                '[
                    {"value":"F","label_zh":"花地瑪堂區","label_en":"Nossa Senhora de Fátima"},
                    {"value":"A","label_zh":"聖安多尼堂區","label_en":"Santo António"},
                    {"value":"S","label_zh":"大堂區","label_en":"Sé"},
                    {"value":"Z","label_zh":"望德堂區","label_en":"São Lázaro"},
                    {"value":"L","label_zh":"風順堂區","label_en":"São Lourenço"},
                    {"value":"C","label_zh":"嘉模堂區(氹仔)","label_en":"Novo campus da Universidade de Macau"},
                    {"value":"F","label_zh":"聖方濟各堂區(路環)","label_en":"São Francisco Xavier"}
                ]'
            ],
            [
                'organization_id' => 0, 'key' => 'field_types', 'value' =>
                '[
                    { "value": "input", "label_zh": "單行文字","label_en":"Text","label_pt":"Text"},
                    { "value": "textarea", "label_zh": "多行文字","label_en":"Multi Text","label_pt":"Multi Text" },
                    { "value": "largetext", "label_zh": "長編文字" ,"label_en":"Long Text","label_pt":"Long Text"},
                    { "value": "richtext", "label_zh": "格式文字" ,"label_en":"Rich Text","label_pt":"Rich Text"},
                    { "value": "radio", "label_zh": "單項選擇" ,"label_en":"Radio","label_pt":"Radio"},
                    { "value": "checkbox", "label_zh": "多項選擇" ,"label_en":"Checkbox","label_pt":"Checkbox"},
                    { "value": "dropdown", "label_zh": "下拉清單" ,"label_en":"Dropdown","label_pt":"Dropdown"},
                    { "value": "true_false", "label_zh": "真/偽" ,"label_en":"True/False","label_pt":"True/False"},
                    { "value": "date", "label_zh": "日期格式" ,"label_en":"Date","label_pt":"Date"},
                    { "value": "datetime", "label_zh": "日期時間" ,"label_en":"Date time","label_pt":"Date time"},
                    { "value": "email", "label_zh": "電郵欄位" ,"label_en":"E-mail","label_pt":"E-mail"},
                    { "value": "number", "label_zh": "數值欄位" ,"label_en":"Number","label_pt":"Numero"},
                    { "value": "photo", "label_zh": "上傳相片" ,"label_en":"Photo","label_pt":"Photo"}
                ]'
            ],
            [
                'organization_id' => 0, 'key' => 'parishes', 'value' =>
                '[
                    {
                        "value": "agree", "label": "Level of Agreement", "template": [
                            {"value": "5","label": "Strongly Agree"},
                            {"value": "4","label": "Argree"},
                            {"value": "3","label": "Neutual"},
                            {"value": "2","label": "Disagree"},
                            {"value": "1","label": "Strongly Disagree"},
                            {"value": "0","label": "Not Applicable"}
                        ]
                    },
                    {
                        "value": "gender","label": "Gender","template": [
                            {"value": "M","label": "Male"},
                            {"value": "F","label": "Female"}
                        ]
                    },
                    {"value": "clear","label": "Reset","template": [
                            {"value": "option_1","label": "option_1"}
                        ]
                    }
                ]'
            ],
            [
                'organization_id' => 0, 'key' => 'template_options', 'value' =>
                '[{"value":"gender","label":"Gender","template":[{"value":"M","label":"男"},{"value":"F","label":"女"}]}]'
            ]
        ];
        foreach ($data as $d) {
            DB::table('configs')->insert($d);
            //Config::create($d);
        }
    }
}
