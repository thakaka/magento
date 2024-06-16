<?php

namespace Modules\News\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\HTTP\Client\Curl;
use Psr\Log\LoggerInterface;

class News extends Template
{
    protected $_rssUrl = 'https://vnexpress.net/rss/kinh-doanh.rss';

    protected $curl;
    protected $logger;

    public function __construct(
        Context $context,
        Curl $curl,
        LoggerInterface $logger,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->curl = $curl;
        $this->logger = $logger;
    }


    public function getNews()
    {
        $rss = simplexml_load_file("https://vnexpress.net/rss/kinh-doanh.rss");
        $items = [];
        foreach ($rss->channel->item as $item) {
            $description = (string) $item->description;
            $indexSrc = strpos($description, "src") + 4;
            $indexEndSrc = strpos($description, ">", $indexSrc + 1) - 2;
            $src = substr($description, $indexSrc, $indexEndSrc - $indexSrc + 1);
            $indexEndDes = strrpos($description, ".", $indexEndSrc + 1) - 1;
            $des = substr($description, $indexEndSrc + 12, $indexEndDes - $indexEndSrc - 12 + 2);
            $items[] = [
                'title' => (string) $item->title,
                'description' => (string) $description,
                'link' => (string) $item->link,
                'pubDate' => (string) $item->pubDate,
                'img' => $src,
                'des' => $des
            ];
        }
        return $items;
    }
}