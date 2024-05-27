import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';
import { HeadingTwo, useForms, Button, removeQueryParameter } from '@global';
import { ProductForm } from '@products/components/functional/ProductForm';
import { AddRelease } from '../functional/AddRelease';
import { ReleaseList } from './ReleaseList';

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
		const response = await apiFetch({
			path: '/licensehub/v1/products/delete-product',
			method: 'DELETE',
			data: {
				nonce: window.lchb_products.nonce,
				id: productID,
			},
		});

		if (!response.success) console.error(response);

		removeQueryParameter('id');
		location.reload();
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
				<HeadingTwo>{__('Releases', 'licensehub')}</HeadingTwo>
				<ReleaseList productID={productID} />
				<AddRelease productID={productID} />
			</div>
			<div>
				<HeadingTwo>{__('Danger Zone', 'licensehub')}</HeadingTwo>
				<div className="flex flex-row gap-4 items-center">
					<div>
						<p className="font-bold my-0">
							{__('Delete this product', 'licensehub')}
						</p>
						<p className="my-2">
							{__(
								'Once you delete a repository, there is no going back. Please be certain.',
								'licensehub',
							)}
						</p>
					</div>
					<Button variant="danger" onClick={handleDelete} className="mt-0">
						{__('Delete Product', 'licensehub')}
					</Button>
				</div>
			</div>
		</div>
	);
};
