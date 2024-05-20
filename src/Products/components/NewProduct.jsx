import { __ } from '@wordpress/i18n';
import { Button, FormGroup, HeadingTwo, Input, Label } from '@global';
import { FormStatus } from '@global/components/form/FormStatus';
import { useForms } from '@global/hooks/useForms';

export const NewProduct = () => {
	const { loading, result, formData, changeFormValue, post } = useForms({
		name: '',
		downloadLink: '',
	});

	return (
		<div>
			<HeadingTwo>{__('New Product', 'licensehub')}</HeadingTwo>
			<form
				onSubmit={async (e) => {
					e.preventDefault();

					const response = await post(
						'/licensehub/v1/new-product',
						window.lchb_products.nonce,
					);

					if (response.success) location.reload();
				}}>
				<FormGroup>
					<Label htmlFor="lchb-name">{__('Name', 'licensehub')}</Label>
					<Input
						type="text"
						id="lchb-name"
						name="lchb-name"
						value={formData.name}
						result={result}
						onChange={(e) => changeFormValue('name', e.target.value)}
					/>
				</FormGroup>
				<FormGroup>
					<Label htmlFor="lchb-download-link">
						{__('Download Link', 'licensehub')}
					</Label>
					<Input
						type="text"
						id="lchb-download-link"
						name="lchb-download-link"
						value={formData.downloadLink}
						result={result}
						onChange={(e) => changeFormValue('downloadLink', e.target.value)}
					/>
				</FormGroup>
				<FormGroup extraClass="tada-form-submit">
					<Button type="submit" loading={loading}>
						{loading
							? __('Loading...', 'licensehub')
							: __('Save Product', 'licensehub')}
					</Button>
					<FormStatus status={result} />
				</FormGroup>
			</form>
		</div>
	);
};
