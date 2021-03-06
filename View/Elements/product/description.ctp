<?php
$tabs = array(
	__d('shop', 'Description'),
	__d('shop', 'Specifications'),
	__d('shop', 'Reviews')
);

$contents = array(
	$shopProduct['ShopProduct']['description'],
	$shopProduct['ShopProduct']['specifications'],
	'reviews'
);

if (!empty($shopProduct['ShopProductVariant'])) {
	$tabs[] = __d('shop', 'Options');
	$contents[] = $this->element('Shop.product/option_description', array(
		'shopProduct' => $shopProduct
	));
}

if (!empty($shopProduct['ShopImagesProduct'])) {
	$tabs[] = __d('shop', 'Additional images');
	$contents[] = $this->element('Shop.product/additional_images', array(
		'shopProduct' => $shopProduct
	));
}
echo $this->Html->tag('div', $this->Design->tabs($tabs, $contents), array(
	'class' => 'product-description'
));
echo $this->Html->tag('hr');
echo $this->ModuleLoader->load('custom4');
echo $this->Html->tag('p', __d('shop', 'This product was first available on %s', CakeTime::nice($shopProduct['ShopProduct']['available'])));