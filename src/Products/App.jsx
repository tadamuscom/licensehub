import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Button, Header } from '@global';
import { ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { NewProduct } from '@products/components/NewProduct';
import { ProductList } from '@products/components/ProductList';

export const App = () => {
	const [isAddNew, setIsAddNew] = useState(false);

	return (
		<div className="licensehub-global">
			<Header
				pageTitle={__('Products', 'licensehub')}
				logoLink={lchb_products.logo}
			/>
			<Button onClick={() => setIsAddNew((prev) => !prev)}>
				{isAddNew
					? __('Product List', 'licensehub')
					: __('Add Product', 'licensehub')}
			</Button>
			{isAddNew ? <NewProduct /> : <ProductList />}
			<ToastContainer />
		</div>
	);
};
