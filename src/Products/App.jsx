import { useState } from '@wordpress/element';
import { useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Button, Header } from '@global';
import { ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { EditProduct } from '@products/components/core/EditProduct';
import { NewProduct } from '@products/components/core/NewProduct';
import { ProductList } from '@products/components/core/ProductList';

export const App = () => {
	const [isAddNew, setIsAddNew] = useState(false);
	const [isEdit, setIsEdit] = useState(false);

	useEffect(() => {
		if (window.location.search.includes('id')) setIsEdit(true);
	}, [setIsEdit]);

	if (isEdit) {
		return (
			<div className="licensehub-global">
				<Header
					pageTitle={__('Products', 'licensehub')}
					logoLink={window.lchb_products.logo}
				/>
				<EditProduct productID={window.location.search.includes('id')} />
				<ToastContainer />
			</div>
		);
	}

	return (
		<div className="licensehub-global">
			<Header
				pageTitle={__('Products', 'licensehub')}
				logoLink={window.lchb_products.logo}
			/>
			<Button onClick={() => setIsAddNew((prev) => !prev)}>
				{isAddNew
					? __('Product List', 'licensehub')
					: __('Add Product', 'licensehub')}
			</Button>
			{isAddNew ? <NewProduct setIsAddNew={setIsAddNew} /> : <ProductList />}
			<ToastContainer />
		</div>
	);
};
