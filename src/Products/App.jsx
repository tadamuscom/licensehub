import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Button, Header } from '@global';
import { NewProduct } from '@products/components/NewProduct';
import { ProductList } from '@products/components/ProductList';

export const App = () => {
	const [isAddNew, setIsAddNew] = useState(false);

	return (
		<div>
			<Header
				pageTitle={__('Products', 'licensehub')}
				logoLink={lchb_products.logo}
			/>
			<Button onClick={() => setIsAddNew(true)}>
				{__('Add Product', 'licensehub')}
			</Button>
			{isAddNew ? <NewProduct /> : <ProductList />}
		</div>
	);
};
