import { __ } from '@wordpress/i18n';
import { Button, CheckBox, FormGroup, FormStatus, useForms } from '@global';

export const SettingsForm = () => {
	const { loading, result, formData, changeFormValue, post } = useForms({
		enable_rest_api: window.lchb_settings.enable_rest_api,
	});

	return (
		<form
			onSubmit={async (e) => {
				e.preventDefault();
				await post(
					'/licensehub/v1/general-settings',
					window.lchb_settings.nonce,
				);
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
