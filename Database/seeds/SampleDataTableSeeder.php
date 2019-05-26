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

        $this->seedBlogs();

    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    function seedBlogs()
    {
        $list = [
            [
                'title' => 'Sample Data',
                'slug' => 'sample-data',
                'details' => 'Sample Data',

            ],
        ];


        foreach($list as $item)
        {
            $exist = \DB::table( 'vh_blog_posts' )
                ->where( 'slug', $item['slug'] )
                ->first();

            if (!$exist){
                \DB::table( 'vh_blog_posts' )->insert( $item );
            }
        }

    }


}
