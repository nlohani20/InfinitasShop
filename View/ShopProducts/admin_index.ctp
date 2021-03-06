<?php
/**
 * Add some documentation for this index form.
 *
 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
 *
 * @link		  http://infinitas-cms.org/Shop
 * @package	   Shop.View.index
 * @license	   http://infinitas-cms.org/mit-license The MIT License
 * @since 0.9b1
 *
 * @author dogmatic69
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

echo $this->Form->create(false, array('action' => 'mass'));
echo $this->Infinitas->adminIndexHead($filterOptions, array(
	'add',
	'edit',
	'toggle',
	'copy',
	'delete'
));
echo $this->Filter->alphabetFilter();
?>
<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(array(
			$this->Form->checkbox('all') => array(
				'class' => 'first'
			),
			$this->Paginator->sort('ShopProduct.name', __d('shop', 'Name')) => array(
				'colspan' => 3
			),
			$this->Paginator->sort('ShopProductType.name', __d('shop', 'Type')) => array(
				'class' => 'larger'
			),
			$this->Paginator->sort('ShopBrand.name', __d('shop', 'Brand')) => array(
				'class' => 'larger'
			),
			$this->Paginator->sort('shop_product_attribute_count', __d('shop', 'Attributes')) => array(
				'class' => 'small'
			),
			$this->Paginator->sort('base_selling', __d('shop', 'Price')) => array(
				'class' => 'medium'
			),
			__d('shop', 'Markup') => array(
				'class' => 'large'
			),
			$this->Paginator->sort('total_stock', __d('shop', 'Stock')) => array(
				'class' => 'medium'
			),
			$this->Paginator->sort('modified') => array(
				'class' => 'date'
			),
			$this->Paginator->sort('active', __d('shop', 'Status')) => array(
				'class' => 'small'
			),
		));

		foreach ($shopProducts as $shopProduct) { ?>
			<tr class="parent">
				<td>
					<?php
						echo '<span class="toggle"><a href="#">+</a></span>',
						$this->Infinitas->massActionCheckBox($shopProduct);
						echo $this->Html->link($this->Design->icon('th'), array(
							'action' => 'matrix',
							$shopProduct['ShopProduct']['id']
						), array('escape' => false));
					?>&nbsp;
				</td>
				<td colspan="3">
					<?php
						echo sprintf('%s<br/>%s',
							$this->Html->image($shopProduct['ShopImage']['image_small'], array(
								'width' => 70
							)),
							$this->Html->adminQuickLink($shopProduct['ShopProduct'])
						);
					?>&nbsp;
				</td>
				<td>
					<?php
						echo $this->Html->link($shopProduct['ShopProductType']['name'], array(
							'controller' => 'shop_product_types',
							'action' => 'edit',
							$shopProduct['ShopProductType']['id']
						));
					?>&nbsp;
				</td>
				<td>
					<?php
						echo $this->Html->link($shopProduct['ShopBrand']['name'], array(
							'controller' => 'shop_brands',
							'action' => 'edit',
							$shopProduct['ShopBrand']['id']
						));
					?>&nbsp;
				</td>
				<td>
					<?php 
						echo $this->Html->link($this->Design->count($shopProduct['ShopProduct']['shop_product_attribute_count']), array(
							'controller' => 'shop_attribute_groups',
							'action' => 'product',
							$shopProduct['ShopProduct']['id']
						), array('escape' => false)); 
					?>
				</td>
				<td><?php echo $this->Shop->adminPrice($shopProduct['ShopProductVariantMaster']['ShopProductVariantPrice']); ?>&nbsp;</td>
				<td><?php echo $this->Shop->adminMarkup($shopProduct['ShopProductVariantMaster']['ShopProductVariantPrice']); ?>&nbsp;</td>
				<td><?php echo $this->Shop->stockValue($shopProduct['ShopStockValue']); ?>&nbsp;</td>
				<td><?php echo $this->Infinitas->date($shopProduct['ShopProduct']); ?></td>
				<td><?php echo $this->Shop->adminStatus($shopProduct); ?>&nbsp;</td>
			</tr>
			<tr class="details">
				<td colspan="2">
					<?php
						echo $this->Html->link($shopProduct['ShopSupplier']['name'], array(
							'controller' => 'shop_suppliers',
							'action' => 'edit',
							$shopProduct['ShopSupplier']['id']
						));
					?>&nbsp;
				</td>
				<td><?php echo __d('shop', '%d views', (int)$shopProduct['ShopProduct']['views']); ?>&nbsp;</td>
				<td>
					<?php
						echo __d('shop', '%s from %d', $shopProduct['ShopProduct']['rating'], $shopProduct['ShopProduct']['rating_count']);
					?>&nbsp;
				</td>
				<td colspan="100">
					<?php
						echo $this->element('Shop.expanded/sales', array('data' => $shopProduct));
						echo $this->element('Shop.expanded/seo', array('data' => $shopProduct));
						echo $this->element('Shop.expanded/views', array('data' => $shopProduct));
					?>
				</td>
			</tr><?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');