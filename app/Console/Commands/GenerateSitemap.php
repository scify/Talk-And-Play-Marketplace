<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Symfony\Component\Console\Command\Command as CommandAlias;

class GenerateSitemap extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a sitemap for better SEO';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $sitemapGenerator = Sitemap::create();
        $sitemapGenerator->add(Url::create('/')->setPriority(1.0)->addImage(asset('img/tp_logo_small.png'), 'Talk & Play Marketplace image')->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
        $sitemapGenerator->add(Url::create('/login')->setPriority(0.9)->addImage(asset('img/tp_logo_small.png'), 'Talk & Play Marketplace image')->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY));
        $sitemapGenerator->add(Url::create('/register')->setPriority(0.9)->addImage(asset('img/advertisement-poster.png'), 'Talk & Play Marketplace image')->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY));
        $sitemapGenerator->add(Url::create('/how-it-works-marketplace')->setPriority(0.8)->addImage(asset('img/advertisement-poster.png'), 'Talk & Play Marketplace image')->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY));
        $sitemapGenerator->add(Url::create('/how-it-works-desktop')->setPriority(0.8)->addImage(asset('img/advertisement-poster.png'), 'Talk & Play Marketplace image')->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY));
        $sitemapGenerator->add(Url::create('/terms-of-use')->setPriority(0.2)->addImage(asset('img/advertisement-poster.png'), 'Talk & Play Marketplace image')->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY));
        $sitemapGenerator->add(Url::create('/content-guidelines')->setPriority(0.9)->addImage(asset('img/tp_logo_small.png'), 'Talk & Play Marketplace image')->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
        $sitemapGenerator->add(Url::create('/privacy-policy')->setPriority(0.2)->addImage(asset('img/advertisement-poster.png'), 'Talk & Play Marketplace image')->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY));
        $sitemapGenerator->add(Url::create('/communication-cards')->setPriority(0.9)->addImage(asset('img/advertisement-poster.png'), 'Talk & Play Marketplace image')->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
        $sitemapGenerator->add(Url::create('/game-cards')->setPriority(0.9)->addImage(asset('img/advertisement-poster.png'), 'Talk & Play Marketplace image')->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
        $sitemapGenerator->writeToFile(public_path('sitemap.xml'));
        return CommandAlias::SUCCESS;
    }
}
