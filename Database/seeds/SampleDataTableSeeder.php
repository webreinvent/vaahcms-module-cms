<?php
namespace VaahCms\Modules\Cms\Database\Seeds;


use Illuminate\Database\Seeder;

class SampleDataTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->seedPosts();

    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    function seedPosts()
    {
        $list = [
            [
                'name' => 'Sample Page',
                'title' => 'Welcome page',
                'slug' => 'welcome-page',
                'content' => 'Sample Content',
                'status' => 'draft',
                'published_at' => null,

            ],
        ];

        foreach($list as $item)
        {
            $exist = \DB::table( 'vh_cms_pages' )
                ->where( 'slug', $item['slug'] )
                ->first();

            if (!$exist){
                \DB::table( 'vh_cms_pages' )->insert( $item );
            }
        }

    }


}
