<?php
namespace Modules\News\Plugin;

use Magento\Framework\Data\Tree\NodeFactory;

class NonCategoryLink
{
    protected $nodeFactory;

    public function __construct(
        NodeFactory $nodeFactory
    ) {
        $this->nodeFactory = $nodeFactory;
    }

    public function beforeGetHtml(
        \Magento\Theme\Block\Html\Topmenu $subject,
        $outermostClass = '',
        $childrenWrapClass = '',
        $limit = 0
    ) {

        $node = $this->nodeFactory->create(
            [
                'data' => $this->getNodeAsArray(),
                'idField' => 'id',
                'tree' => $subject->getMenu()->getTree()
            ]
        );
        $subject->getMenu()->addChild($node);
    }

    protected function getNodeAsArray()
    {
        $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $parsedUrl = parse_url($root);
        $root = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
        $url = $root . '/currency';
        return [
            'name' => __('Tin tức'),
            'id' => 'tin-tức',
            'url' => "http://electronicworld.site/news",
            'has_active' => false,
            'is_active' => false
        ];
    }

}