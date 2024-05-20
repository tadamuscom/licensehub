import { __ } from '@wordpress/i18n';
import { HeadingTwo, useForms } from '@global';
import { ProductForm } from '@products/components/functional/ProductForm';

export const EditProduct = ({ productID }) => {
	console.log(productID);

	const { loading, result, formData, changeFormValue, post } = useForms({
		name: '',
		downloadLink: '',
	});

	const handleSubmit = (e) => {
		e.preventDefault();

		post();
	};

	return (
		<div>
			<HeadingTwo>{__('Edit Product', 'licensehub')}</HeadingTwo>
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
