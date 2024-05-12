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
} from '@global';
import { FormStatus } from '@global/components/form/FormStatus';
import { useForms } from '@global/hooks/useForms';

export const NewAPIKey = () => {
	const { loading, result, formData, changeFormValue, post } = useForms({
		user: lchb_api_keys.users[0].data.ID,
		expiresAt: '',
	});

	const [users, setUsers] = useState(() => {
		return lchb_api_keys.users.map((user, index) => {
			return (
				<SelectOption id={user.data.ID} value={user.data.ID} key={index}>
					{user.data.user_email}
				</SelectOption>
			);
		});
	});

	const handleSubmit = async (e) => {
		e.preventDefault();

		const response = await post(
			'/tadamus/lchb/v1/new-api-key',
			lchb_api_keys.nonce,
		);

		if (response.success) location.reload();
	};

	return (
		<>
			<HeadingTwo>{__('New API Key', 'licensehub')}</HeadingTwo>
			<form onSubmit={handleSubmit}>
				<FormGroup>
					<Label htmlFor="lchb-user">{__('User', 'licensehub')}</Label>
					<Select
						id="lchb-user"
						name="lchb-user"
						options={users}
						value={formData.user}
						onChange={(e) => changeFormValue('user', e.target.value)}
						result={result}
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
						onChange={(e) => changeFormValue('expiresAt', e.target.value)}
						result={result}
					/>
				</FormGroup>
				<FormGroup extraClass="tada-form-submit">
					<Button>
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
