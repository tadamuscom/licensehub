import { apiFetch } from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';
import { HeadingTwo, useForms, Button, removeQueryParameter } from '@global';
import { ProductForm } from '@products/components/functional/ProductForm';

export const EditProduct = ({ productID }) => {
	const getProductByID = (id) =>
		window.lchb_products.products.find((product) => product.id === id);

	const { loading, result, formData, changeFormValue, put } = useForms({
		name: getProductByID(productID).name,
	});

	const handleSubmit = async (e) => {
		e.preventDefault();

		const response = await put(
			'/licensehub/v1/products/update-product',
			{ id: productID, ...formData },
			window.lchb_products.nonce,
		);

		removeQueryParameter('id');

		if (response.success) location.reload();
	};

	const handleDelete = async () => {
		await apiFetch({
			path: '/licensehub/v1/delete-product',
			method: 'DELETE',
			data: {
				nonce: window.lchb_products.nonce,
				id: productID,
			},
		});
	};

	return (
		<div>
			<HeadingTwo>{__('Edit Product', 'licensehub')}</HeadingTwo>
			<div>
				<ProductForm
					loading={loading}
					result={result}
					formData={formData}
					changeFormValue={changeFormValue}
					handleSubmit={handleSubmit}
				/>
			</div>
			<div>
				<HeadingTwo>{__('Danger Zone', 'licensehub')}</HeadingTwo>
				<div className="flex flex-row gap-4 items-center">
					<div>
						<p className="font-bold my-0">
							{__('Delete this product', 'licensehub')}
						</p>
						<p>
							{__(
								'Once you delete a repository, there is no going back. Please be certain.',
								'licensehub',
							)}
						</p>
					</div>
					<div className="ml-1">
						<Button variant="danger" onClick={handleDelete}>
							{__('Delete Product', 'licensehub')}
						</Button>
					</div>
				</div>
			</div>
		</div>
	);
};
