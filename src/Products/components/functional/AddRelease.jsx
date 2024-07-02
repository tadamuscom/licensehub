import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import {
	Button,
	FormGroup,
	Label,
	Input,
	FormStatus,
	HeadingThree,
	useForms,
} from '@global';
import { Textarea } from '@global/index';

export const AddRelease = ({ productID }) => {
	const [isForm, setIsForm] = useState(false);
	const { loading, result, formData, updateFormValue, filePost } = useForms({
		productID,
		version: '',
		changeLog: '',
		fileUpload: '',
	});

	if (!isForm) {
		return (
			<Button onClick={() => setIsForm(true)}>
				{__('Add Release', 'licensehub')}
			</Button>
		);
	}

	const handleSubmit = async (e) => {
		e.preventDefault();

		const response = await filePost(
			'/licensehub/v1/releases/new-release',
			window.lchb_products.releases_nonce,
			window.lchb_products.ajax_url,
		);

		if (response.success) location.reload();
	};

	return (
		<>
			<HeadingThree>{__('Add Release', 'licensehub')}</HeadingThree>
			<form onSubmit={handleSubmit}>
				<FormGroup>
					<Label htmlFor="version">{__('Version', 'licensehub')}</Label>
					<Input
						type="text"
						id="version"
						name="version"
						value={formData.version}
						result={result}
						onChange={(e) => updateFormValue('version', e.target.value)}
						autoFocus={true}
					/>
				</FormGroup>
				<FormGroup>
					<Label htmlFor="version">{__('ChangeLog', 'licensehub')}</Label>
					<Textarea
						id="changelog"
						name="changelog"
						result={result}
						onChange={(e) => updateFormValue('changeLog', e.target.value)}>
						{formData.changeLog}
					</Textarea>
				</FormGroup>
				<FormGroup>
					<Label htmlFor="file-upload">{__('File Upload', 'licensehub')}</Label>
					<Input
						type="file"
						id="file-upload"
						name="file-upload"
						result={result}
						onChange={(e) => updateFormValue('fileUpload', e.target.files[0])}
					/>
				</FormGroup>
				<FormGroup extraClass="tada-form-submit">
					<Button type="submit" loading={loading}>
						{loading
							? __('Loading...', 'licensehub')
							: __('Save Release', 'licensehub')}
					</Button>
					<FormStatus status={result} />
				</FormGroup>
			</form>
		</>
	);
};
