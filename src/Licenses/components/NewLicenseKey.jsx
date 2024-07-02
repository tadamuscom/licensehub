import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import {
	Button,
	FormGroup,
	HeadingTwo,
	Input,
	Label,
	Select,
	SelectOption,
	FormStatus,
	useForms,
} from '@global';

export const NewLicenseKey = () => {
	const { loading, result, formData, updateFormValue, post } = useForms({
		user: window.lchb_license_keys.users[0].data.ID,
		product: window.lchb_license_keys.products[0].id,
		expiresAt: '',
	});

	const products = useState(() => {
		return window.lchb_license_keys.products.map((element, index) => {
			return (
				<SelectOption id={element.id} key={index} value={element.id}>
					{element.name}
				</SelectOption>
			);
		});
	});

	const users = useState(() => {
		return window.lchb_license_keys.users.map((element, index) => {
			return (
				<SelectOption id={element.id} key={index} value={element.data.ID}>
					{element.data.user_email}
				</SelectOption>
			);
		});
	});

	const handleSubmit = async (e) => {
		e.preventDefault();

		const response = await post(
			'/licensehub/v1/licenses/new-license-key',
			window.lchb_license_keys.nonce,
		);

		if (response.success) location.reload();
	};

	return (
		<>
			<HeadingTwo>{__('New License Key', 'licensehub')}</HeadingTwo>
			<form onSubmit={handleSubmit}>
				<FormGroup>
					<Label htmlFor="lchb-user">{__('User', 'licensehub')}</Label>
					<Select
						id="lchb-user"
						name="lchb-user"
						options={users}
						value={formData.user}
						result={result}
						onChange={(e) => updateFormValue('user', e.target.value)}
					/>
				</FormGroup>
				<FormGroup>
					<Label htmlFor="lchb-product">{__('Product', 'licensehub')}</Label>
					<Select
						id="lchb-product"
						name="lchb-product"
						options={products}
						value={formData.product}
						result={result}
						onChange={(e) => updateFormValue('product', e.target.value)}
					/>
				</FormGroup>
				<FormGroup>
					<Label htmlFor="lchb-expires-at">
						{__('Expiry Date', 'licensehub')}
					</Label>
					<Input
						type="date"
						id="lchb-expires-at"
						name="lchb-expires-at"
						value={formData.expiresAt}
						result={result}
						onChange={(e) => updateFormValue('expiresAt', e.target.value)}
					/>
				</FormGroup>
				<FormGroup extraClass="tada-form-submit">
					<Button type="submit" loading={loading}>
						{loading
							? __('Loading...', 'licensehub')
							: __('Save License Key', 'licensehub')}
					</Button>
					<FormStatus status={result} />
				</FormGroup>
			</form>
		</>
	);
};
