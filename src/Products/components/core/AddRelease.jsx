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
	const { loading, result, formData, changeFormValue } = useForms({
		productID,
		version: '',
		changeLog: '',
	});

	if (!isForm) {
		return (
			<Button onClick={() => setIsForm(true)}>
				{__('Add Release', 'licensehub')}
			</Button>
		);
	}

	const handleSubmit = (e) => {
		e.preventDefault();

		console.log('submit');
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
						onChange={(e) => changeFormValue('version', e.target.value)}
						autoFocus={true}
					/>
				</FormGroup>
				<FormGroup>
					<Label htmlFor="version">{__('ChangeLog', 'licensehub')}</Label>
					<Textarea
						id="changelog"
						name="changelog"
						result={result}
						onChange={(e) => changeFormValue('changeLog', e.target.value)}>
						{formData.changeLog}
					</Textarea>
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
