<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LatestNewsController extends Controller
{
    private $rss_urls = array(
        'WSJ' => ['url' => 'https://feeds.a.dj.com/rss/RSSWorldNews.xml'],
        'CBS' => ['url' => 'https://www.cbsnews.com/latest/rss/main', 'image_tag' => 'image'],
        'BBC' => ['url' => 'http://feeds.bbci.co.uk/news/world/us_and_canada/rss.xml'],
        'NPR' => ['url' => 'https://www.npr.org/rss/rss.php?id=1001'],
        'NYT' => ['url' => 'https://rss.nytimes.com/services/xml/rss/nyt/HomePage.xml', 'image_tag' => 'media:content'],
    );
    function fetch_rss(&$feed, $url, $source_name, $image_tag = null)
    {
        $xml = simplexml_load_file($url);
        if (!$xml)
            return false;

        foreach ($xml->channel->xpath('//item') as $index => $xml_item) {
            $feed_item = false;
            $feed_item['title'] = strip_tags(trim($xml_item->title));
            $feed_item['description'] = strip_tags(trim($xml_item->description));
            $feed_item['link'] = strip_tags(trim($xml_item->link));
            $feed_item['date'] = \Carbon\Carbon::parse($xml_item->pubDate);
            $feed_item['source'] = $source_name;
            if ($image_tag) {
                if ($image_tag === 'image') {
                    $feed_item['image'] = strip_tags($xml->channel->item[$index]->$image_tag);
                } else if ($image_tag === 'media:content') {
                    $media = $xml_item->children('media', true);
                    $content = $media->content;
                    if ($content) {
                        $attributes = $content->attributes();
                        $feed_item['image'] = isset($attributes['url']) ? (string)$attributes['url'] : null;
                    }
                } else if ($image_tag === 'media:group') {
                    $feed_item['image'] = (string) $xml->item[$index]->children('media', True)->group->children('media', True)->content->attributes()['url'];
                }
            }
            $current_date = \Carbon\Carbon::now();
            if ($feed_item['date']->isSameDay($current_date)) {
                $feed[] = $feed_item;
            }
        }
        return $feed;
    }

    public function index()
    {
        if (!is_array($this->rss_urls)) {
            die('The URLs to RSS feed(s) is not provided');
        }

        $feed = false;
        foreach ($this->rss_urls as $name => $value) {
            $this->fetch_rss($feed, $value['url'], $name, $value['image_tag'] ?? null);
        }
        if (!$feed)
            die('No data to display. (Unable to retrieve XML data from provided URLs.)');

        usort($feed, function ($a, $b) {
            return \Carbon\Carbon::parse($b['date'])->diffInSeconds(\Carbon\Carbon::parse($a['date']));
        });


        return response()->json($feed);
    }
}