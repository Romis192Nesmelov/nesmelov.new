<?php

namespace App\Http\Controllers;

use SimpleXMLElement;
trait SettingsTrait
{
    private SimpleXMLElement $settings;
    private array $metas = [
        'meta_description' => ['name' => 'description', 'property' => false],
        'meta_keywords' => ['name' => 'keywords', 'property' => false],
        'meta_twitter_card' => ['name' => 'twitter:card', 'property' => false],
        'meta_twitter_size' => ['name' => 'twitter:size', 'property' => false],
        'meta_twitter_creator' => ['name' => 'twitter:creator', 'property' => false],
        'meta_og_url' => ['name' => false, 'property' => 'og:url'],
        'meta_og_type' => ['name' => false, 'property' => 'og:type'],
        'meta_og_title' => ['name' => false, 'property' => 'og:title'],
        'meta_og_description' => ['name' => false, 'property' => 'og:description'],
        'meta_og_image' => ['name' => false, 'property' => 'og:image'],
        'meta_robots' => ['name' => 'robots', 'property' => false],
        'meta_googlebot' => ['name' => 'googlebot', 'property' => false],
        'meta_google_site_verification' => ['name' => 'robots', 'property' => false],
    ];

    public function __construct()
    {
        $this->settings = simplexml_load_file(env('SETTINGS_XML'));
    }

    // Seo
    public function getSeoTags(): array
    {
        $tags = ['title' => ''];
        if ($this->settings->seo->title) $tags['title'] = (string)$this->settings->seo->title;
        foreach ($this->metas as $meta => $params) {
            $tags[$meta] = (string)$this->settings->seo->$meta;
        }
        return $tags;
    }

    public function getSettings(): array
    {
        return (array)$this->settings->settings;
    }

    public function getRequisites(): array
    {
        return (array)$this->settings->requisites;
    }

    public function saveSeoTags(): void
    {
        if (request()->has('title')) $this->settings->seo->title = request()->title;
        foreach ($this->metas as $meta => $params) {
            $this->settings->seo->$meta = request()->input($meta);
        }
        $this->save();
    }

    public function saveSettings($fieldsSettings, $fieldsRequisites): void
    {
        foreach ($fieldsSettings as $name => $val) {
            $this->settings->settings->$name = $val;
        }

        foreach ($fieldsRequisites as $name => $val) {
            $this->settings->requisites->$name = $val;
        }
        $this->save();
    }

    private function save(): void
    {
        $this->settings->asXML(env('SETTINGS_XML'));
    }

}
