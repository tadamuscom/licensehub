import { __ } from '@wordpress/i18n';
import { FormGroup, Label, Input, Button, FormStatus } from '@global';
import {doAction} from '@wordpress/hooks';

export const ProductForm = ({
	formData,
	result,
	updateFormValue,
	loading,
	handleSubmit,
	disabled,
}) => {
	return (
		<form onSubmit={handleSubmit}>
			{

				/**
				 * Hook before the product form
				 *
				 * @action licensehub.start-of-product-form
				 * @param {object} formData - The formData object that holds all the form fields
				 * @param {function} updateFormValue - Function to update the formData object with your data
				 */
				doAction('licensehub.start-of-product-form', formData, updateFormValue)

			}
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
			<FormGroup extraClass="tada-form-submit">
				<Button type="submit" loading={loading} disabled={disabled}>
					{loading
						? __('Loading...', 'licensehub')
						: __('Save Product', 'licensehub')}
				</Button>
				<FormStatus status={result} />
			</FormGroup>
			{

				/**
				 * Hook after the product form
				 *
				 * @action licensehub.end-of-product-form
				 * @param {object} formData - The formData object that holds all the form fields
				 * @param {function} updateFormValue - Function to update the formData object with your data
				 */
				doAction('licensehub.end-of-product-form', formData, updateFormValue)

			}
		</form>
	);
};
