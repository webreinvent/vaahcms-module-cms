<?php namespace VaahCms\Modules\Cms\Tests\Browser;

use VaahCms\Modules\Cms\Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            $this->browse(function (Browser $browser) {
                $browser->maximize();
                $browser->visit('/admin')
                    ->assertSee('VaahCms');
            });
        });
    }
}
