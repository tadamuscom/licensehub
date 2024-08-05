import { applyFilters } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';
import { Button, CheckBox, FormGroup, FormStatus, useForms } from '@global';

export const SettingsForm = () => {
	const { loading, result, formData, updateFormValue, post } = useForms({
		enable_rest_api: window.lchb_settings.enable_rest_api,
	});

	/**
	 * Filter before the settings form
	 *
	 * @action licensehub.start-of-settings-form
	 * @param {object} formData - The formData object that holds all the form fields
	 * @param {function} updateFormValue - Function to update the formData object with your data
	 * @param {object} result - Status of the form submission
	 */
	const startOfForm = applyFilters(
		'licensehub.start-of-settings-form',
		'',
		formData,
		updateFormValue,
		result,
	);

	/**
	 * Filter after the settings form
	 *
	 * @action licensehub.end-of-settings-form
	 * @param {object} formData - The formData object that holds all the form fields
	 * @param {function} updateFormValue - Function to update the formData object with your data
	 * @param {object} result - Status of the form submission
	 */
	const endOfForm = applyFilters(
		'licensehub.end-of-settings-form',
		'',
		formData,
		updateFormValue,
		result,
	);

	return (
		<form
			onSubmit={async (e) => {
				e.preventDefault();
				await post(
					'/licensehub/v1/settings/general',
					window.lchb_settings.nonce,
				);
			}}>
			{startOfForm}
			<FormGroup>
				<CheckBox
					label={__('Enable REST API', 'licensehub')}
					checked={formData.enable_rest_api}
					onChange={(e) => updateFormValue('enable_rest_api', e.target.checked)}
				/>
			</FormGroup>
			{endOfForm}
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
