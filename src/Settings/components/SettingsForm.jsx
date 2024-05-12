import { __ } from '@wordpress/i18n';
import { Button, FormGroup } from '@global';
import { FormStatus } from '@global/components/form/FormStatus';
import { useForms } from '@global/hooks/useForms';

export const SettingsForm = () => {
	const { loading, result, formData, changeFormValue, post } = useForms({});

	return (
		<form
			onSubmit={async (e) => {
				e.preventDefault();
				await post('/licensehub/v1/general-settings', lchb_settings.nonce);
			}}>
			<FormGroup>
				<Button type="submit" loading={loading}>
					{loading
						? __('Loading...', 'licensehub')
						: __('Save Settings', 'licensehub')}
				</Button>
				<FormStatus status={result} />
			</FormGroup>
		</form>
	);
};
