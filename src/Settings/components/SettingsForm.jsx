import { __ } from '@wordpress/i18n';
import { Button, CheckBox, FormGroup } from '@global';
import { FormStatus } from '@global/components/form/FormStatus';
import { useForms } from '@global/hooks/useForms';

export const SettingsForm = () => {
	const { loading, result, formData, changeFormValue, post } = useForms({
		enable_rest_api: lchb_settings.enable_rest_api,
	});

	return (
		<form
			onSubmit={async (e) => {
				e.preventDefault();
				await post('/licensehub/v1/general-settings', lchb_settings.nonce);
			}}>
			<FormGroup>
				<CheckBox
					label={__('Enable REST API', 'licensehub')}
					checked={formData.enable_rest_api}
					onChange={(e) => changeFormValue('enable_rest_api', e.target.checked)}
				/>
			</FormGroup>
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
