import { __ } from '@wordpress/i18n';
import { HeadingTwo, useForms } from '@global';
import { ProductForm } from '@products/components/functional/ProductForm';

export const NewProduct = () => {
	const { loading, result, formData, changeFormValue, post } = useForms({
		name: '',
		downloadLink: '',
	});

	const handleSubmit = async (e) => {
		e.preventDefault();

		const response = await post(
			'/licensehub/v1/products/new-product',
			window.lchb_products.nonce,
		);

		if (response.success) location.reload();
	};

	return (
		<div>
			<HeadingTwo>{__('New Product', 'licensehub')}</HeadingTwo>
			<ProductForm
				loading={loading}
				result={result}
				formData={formData}
				changeFormValue={changeFormValue}
				handleSubmit={handleSubmit}
			/>
		</div>
	);
};
