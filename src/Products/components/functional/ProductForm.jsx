import { applyFilters } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';
import { Button, FormGroup, FormStatus, Input, Label } from '@global';

export const ProductForm = ({
	formData,
	result,
	updateFormValue,
	loading,
	handleSubmit,
	disabled,
}) => {
	/**
	 * Filter before the product form
	 *
	 * @action licensehub.start-of-product-form
	 * @param {object} formData - The formData object that holds all the form fields
	 * @param {function} updateFormValue - Function to update the formData object with your data
	 */
	const startOfProductForm = applyFilters(
		'licensehub.start-of-product-form',
		'',
		formData,
		updateFormValue,
	);

	/**
	 * Filter at the end of the product form
	 *
	 * @action licensehub.end-of-product-form
	 * @param {object} formData - The formData object that holds all the form fields
	 * @param {function} updateFormValue - Function to update the formData object with your data
	 */
	const endOfProductForm = applyFilters(
		'licensehub.end-of-product-form',
		'',
		formData,
		updateFormValue,
	);

	/**
	 * Filter after the product form
	 *
	 * @action licensehub.after-product-form
	 * @param {object} formData - The formData object that holds all the form fields
	 * @param {function} updateFormValue - Function to update the formData object with your data
	 */
	const afterProductForm = applyFilters(
		'licensehub.after-product-form',
		'',
		formData,
		updateFormValue,
	);

	return (
		<>
			<form onSubmit={handleSubmit}>
				{startOfProductForm}
				<FormGroup>
					<Label htmlFor="lchb-name">{__('Name', 'licensehub')}</Label>
					<Input
						type="text"
						id="lchb-name"
						name="lchb-name"
						value={formData.name}
						result={result}
						onChange={(e) => updateFormValue('name', e.target.value)}
						disabled={disabled}
						autoFocus={true}
					/>
				</FormGroup>
				{endOfProductForm}
				<FormGroup extraClass="tada-form-submit">
					<Button type="submit" loading={loading} disabled={disabled}>
						{loading
							? __('Loading...', 'licensehub')
							: __('Save Product', 'licensehub')}
					</Button>
					<FormStatus status={result} />
				</FormGroup>
			</form>
			{afterProductForm}
		</>
	);
};
