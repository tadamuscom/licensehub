import { __ } from '@wordpress/i18n';
import { Button, FormGroup } from '@global';
import { FormStatus } from '@global/components/form/FormStatus';
import { useForms } from '@global/hooks/useForms';
import { FluentCRM } from '@settings/components/FluentCRM';
import { Stripe } from '@settings/components/Stripe';

export const SettingsForm = () => {
	const { loading, result, formData, changeFormValue, post } = useForms({
		stripeIntegration: lchb_settings.stripe_integration === 'true',
		stripePublicKey: lchb_settings.stripe_public_key,
		stripePrivateKey: lchb_settings.stripe_private_key,
		fluentCRMIntegration: lchb_settings.fluentcrm_integration === 'true',
	});

	return (
		<form
			onSubmit={async (e) => {
				e.preventDefault();
				await post('/licensehub/v1/general-settings', lchb_settings.nonce);
			}}>
			<Stripe
				formData={formData}
				changeFormValue={changeFormValue}
				result={result}
			/>
			<FluentCRM formData={formData} changeFormValue={changeFormValue} />
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
