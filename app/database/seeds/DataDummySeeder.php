<?php

class DataDummySeeder extends Seeder {

    public function run()
    {
        DB::table('stats')->delete();
        DB::table('link')->delete();
        DB::table('rss')->delete();
        DB::table('newspaper')->delete();
        DB::table('tags')->delete();

        $group = array(
            array(
                'slug'      => 'us',
                'name'   => 'Test',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
            )
        );
        DB::table('group')->insert( $group );

        $tags = array(
            array(
                'name'      => 'Sports',
                'color'   => '#3f9b45',
                'id_group' => DB::table('group')->first()->slug,
                'created_at' => new DateTime,
                'updated_at' => new DateTime
            ),
            array(
                'name'      => 'Latest',
                'color'   => '#f51717',
                'id_group' => DB::table('group')->first()->slug,
                'created_at' => new DateTime,
                'updated_at' => new DateTime
            )
        );
        DB::table('tags')->insert( $tags );

        $newspaper = array(
            array(
                'name'      => 'New York Times',
                'logo'   => 'https://pbs.twimg.com/profile_images/2044921128/finals.png',
                'url'   => 'http://www.nytimes.com/',
                'id_group' => DB::table('group')->first()->slug,
                'created_at' => new DateTime,
                'updated_at' => new DateTime
            )
        );
        DB::table('newspaper')->insert( $newspaper );

        $rss = array(
            array(
                'id_newspaper'   => 1,
                'id_tag'   => 2,
                'url'   => 'http://rss.nytimes.com/services/xml/rss/nyt/HomePage.xml',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
            ),
            array(
                'id_newspaper'   => 1,
                'id_tag'   => 1,
                'url'   => 'http://rss.nytimes.com/services/xml/rss/nyt/Sports.xml',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
            )
        );
        DB::table('rss')->insert( $rss );

    }

}